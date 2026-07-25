<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class GuruController extends Controller
{
    // ============ HELPER TANGGAL INDONESIA (KUNCINYA) ============
    private function parseTanggalIndo($tanggal)
    {
        if (empty($tanggal)) return null;

        // Jika dari Excel berupa angka serial
        if (is_numeric($tanggal)) {
            try {
                return ExcelDate::excelToDateTimeObject($tanggal)->format('Y-m-d');
            } catch (\Exception $e) {}
        }

        $tanggal = trim($tanggal);

        // Mapping bulan Indonesia -> Inggris
        $bulanIndo = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
            'april' => 'April', 'mei' => 'May', 'juni' => 'June',
            'juli' => 'July', 'agustus' => 'August', 'september' => 'September',
            'oktober' => 'October', 'november' => 'November', 'desember' => 'December',
        ];

        $tglEnglish = str_ireplace(array_keys($bulanIndo), array_values($bulanIndo), $tanggal);

        try {
            return Carbon::parse($tglEnglish)->format('Y-m-d');
        } catch (\Exception $e) {
            // Coba format d/m/Y manual
            try {
                return Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
            } catch (\Exception $e2) {
                Log::warning("Gagal parse tanggal: {$tanggal} -> {$e->getMessage()}");
                return null;
            }
        }
    }

    private function parseTanggalIndoSafe($tanggal) {
        // Untuk hitung umur di index, biar gak error kalau tanggal rusak
        try {
            $parsed = $this->parseTanggalIndo($tanggal);
            return $parsed? Carbon::parse($parsed) : null;
        } catch (\Exception $e) { return null; }
    }

    public function index(Request $request)
    {
        try {
            $query = Guru::with(['user', 'mataPelajaran']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nuptk', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhere('status_kepegawaian', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }

            $guru = $query->orderBy('created_at', 'desc')->paginate(10);

            $guruLaki = Guru::where('jenis_kelamin', 'L')->count();
            $guruPerempuan = Guru::where('jenis_kelamin', 'P')->count();

            // FIX: Rata usia tahan error tanggal Indo
            $rataUsia = Guru::whereNotNull('tanggal_lahir')->get()
                ->map(function($g) {
                    $carbon = $this->parseTanggalIndoSafe($g->tanggal_lahir);
                    return $carbon? $carbon->age : null;
                })
                ->filter()
                ->avg();
            $rataUsia = round($rataUsia?? 0);

            return view('administrasi.guru.index', compact('guru', 'guruLaki', 'guruPerempuan', 'rataUsia'));
        } catch (\Exception $e) {
            Log::error('Error in Guru Index: '. $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function create()
    {
        $mataPelajaran = Mapel::where('status', 'aktif')->orderBy('nama_mapel')->get();
        return view('administrasi.guru.create', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|string', // FIX: jangan pakai date, biar 15 Agustus 1973 lolos
            'alamat_lengkap' => 'required|string',
            'nuptk' => 'nullable|string|max:20|unique:gurus,nuptk',
            'jabatan' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'jurusan_pendidikan' => 'required|string|max:100',
            'tanggal_masuk' => 'required|string',
            'agama' => 'required|string|max:20',
            'nip' => 'nullable|string|max:50|unique:gurus,nip',
            'nama_universitas' => 'nullable|string|max:200',
            'tahun_lulus' => 'nullable|integer|min:1980|max:'. date('Y'),
            'tmt' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajarans,id',
        ]);

        try {
            DB::beginTransaction();

            $nip = $request->nip;
            if (empty($nip)) {
                $nip = $this->generateNIP($request->tanggal_lahir, $this->getNextGuruId());
            }

            $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $request->nama_guru)). '@guru.sch.id';
            $email = $this->generateUniqueEmail($email);

            $user = User::create([
                'name' => $request->nama_guru,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'status' => 'aktif'
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nuptk' => $request->nuptk,
                'nama_lengkap' => $request->nama_guru,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $this->parseTanggalIndo($request->tanggal_lahir),
                'alamat' => $request->alamat_lengkap,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->nama_universitas,
                'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $this->parseTanggalIndo($request->tanggal_masuk),
                'tmt' => $this->parseTanggalIndo($request->tmt),
                'status_kepegawaian' => $request->jabatan,
                'agama' => $request->agama,
                'status' => 'aktif'
            ]);

            if ($request->has('mata_pelajaran') &&!empty($request->mata_pelajaran)) {
                $guru->mataPelajaran()->sync($request->mata_pelajaran);
            }

            DB::commit();
            $mapelCount = $request->has('mata_pelajaran')? count($request->mata_pelajaran) : 0;
            $message = "✅ Guru {$guru->nama_lengkap} berhasil ditambahkan!";
            if ($mapelCount > 0) $message.= "<br>📚 Mengampu {$mapelCount} mata pelajaran.";

            return redirect()->route('administrasi.guru.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Guru Store: '. $e->getMessage());
            return back()->with('error', '❌ Gagal menyimpan: '. $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $guru = Guru::with(['user', 'mataPelajaran', 'kelasWali'])->findOrFail($id);
            return view('administrasi.guru.show', compact('guru'));
        } catch (\Exception $e) {
            Log::error('Error in Guru Show: '. $e->getMessage());
            return back()->with('error', 'Data guru tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $guru = Guru::with(['user', 'mataPelajaran'])->findOrFail($id);
            $mataPelajaran = Mapel::where('status', 'aktif')->orderBy('nama_mapel')->get();
            $selectedMapel = $guru->mataPelajaran->pluck('id')->toArray();
            return view('administrasi.guru.edit', compact('guru', 'mataPelajaran', 'selectedMapel'));
        } catch (\Exception $e) {
            Log::error('Error in Guru Edit: '. $e->getMessage());
            return back()->with('error', 'Data guru tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|string',
            'alamat' => 'required|string',
            'nuptk' => 'nullable|string|max:20|unique:gurus,nuptk,'. $id,
            'jabatan' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'jurusan_pendidikan' => 'required|string|max:100',
            'tanggal_masuk' => 'required|string',
            'agama' => 'required|string|max:20',
            'nip' => 'required|string|max:50|unique:gurus,nip,'. $id,
            'nama_universitas' => 'nullable|string|max:200',
            'tahun_lulus' => 'nullable|integer|min:1980|max:'. date('Y'),
            'tmt' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:aktif,nonaktif',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajarans,id',
        ]);

        try {
            DB::beginTransaction();
            $guru = Guru::findOrFail($id);

            $guru->update([
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $this->parseTanggalIndo($request->tanggal_lahir),
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->nama_universitas,
                'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $this->parseTanggalIndo($request->tanggal_masuk),
                'tmt' => $this->parseTanggalIndo($request->tmt),
                'status_kepegawaian' => $request->jabatan,
                'agama' => $request->agama,
                'nuptk' => $request->nuptk,
                'nip' => $request->nip,
                'status' => $request->status?? 'aktif'
            ]);

            if ($guru->user) {
                $guru->user->update(['name' => $request->nama_lengkap]);
            }

            if ($request->has('mata_pelajaran')) {
                $guru->mataPelajaran()->sync($request->mata_pelajaran);
            } else {
                $guru->mataPelajaran()->sync([]);
            }

            DB::commit();
            $mapelCount = $request->has('mata_pelajaran')? count($request->mata_pelajaran) : 0;
            $message = "✅ Guru {$guru->nama_lengkap} berhasil diupdate!";
            if ($mapelCount > 0) $message.= "<br>📚 Mengampu {$mapelCount} mata pelajaran.";

            return redirect()->route('administrasi.guru.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Guru Update: '. $e->getMessage());
            return back()->with('error', '❌ Gagal mengupdate: '. $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $guru = Guru::with(['user', 'mataPelajaran'])->findOrFail($id);
            $guru->mataPelajaran()->detach();
            Kelas::where('wali_kelas_id', $guru->id)->update(['wali_kelas_id' => null]);
            if ($guru->user) $guru->user->delete();
            $guru->delete();
            DB::commit();
            return redirect()->route('administrasi.guru.index')->with('success', "✅ Guru {$guru->nama_lengkap} berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Guru Destroy: '. $e->getMessage());
            return back()->with('error', '❌ Gagal menghapus guru: '. $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Guru::with(['user', 'mataPelajaran']);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nuptk', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%");
                });
            }
            $guru = $query->orderBy('created_at', 'desc')->get();
            $headers = ['NO', 'NAMA GURU', 'JK', 'TEMPAT LAHIR', 'TANGGAL LAHIR','ALAMAT', 'NUPTK', 'NIP', 'JABATAN', 'MATA PELAJARAN','PENDIDIKAN TERAKHIR', 'JURUSAN', 'UNIVERSITAS', 'TAHUN LULUS','TMT MASUK', 'TMT DARUL ULUM', 'AGAMA', 'NO TELEPON', 'STATUS'];

            $callback = function() use ($headers, $guru) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers);
                $no = 1;
                foreach ($guru as $g) {
                    $mataPelajaran = $g->mataPelajaran->pluck('nama_mapel')->implode(', ');
                    fputcsv($handle, [
                        $no++,$g->nama_lengkap,$g->jenis_kelamin,$g->tempat_lahir,
                        $g->tanggal_lahir? ($this->parseTanggalIndo($g->tanggal_lahir)?? $g->tanggal_lahir) : '',
                        $g->alamat,$g->nuptk,$g->nip,$g->status_kepegawaian,$mataPelajaran,
                        $g->pendidikan_terakhir,$g->jurusan_pendidikan,$g->universitas,$g->tahun_lulus,
                        $g->tmt_masuk? ($this->parseTanggalIndo($g->tmt_masuk)?? $g->tmt_masuk) : '',
                        $g->tmt? ($this->parseTanggalIndo($g->tmt)?? $g->tmt) : '',
                        $g->agama,$g->no_telepon,$g->status
                    ]);
                }
                fclose($handle);
            };
            $filename = 'data_guru_'. Carbon::now()->format('Y-m-d_His'). '.csv';
            return response()->streamDownload($callback, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'. $filename. '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Guru Export: '. $e->getMessage());
            return redirect()->route('administrasi.guru.index')->with('error', '❌ Gagal export data: '. $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            $headers = ['NO', 'NAMA GURU', 'JK', 'TEMPAT TANGGAL LAHIR','ALAMAT LENGKAP', 'NUPTK', 'JABATAN', 'NAMA UNIVERSITAS','JURUSAN', 'TAHUN LULUS', 'TMT SMK DARUL ULUM','MATA PELAJARAN (pisahkan dengan koma)'];
            $data = [
                [1, 'Ahmad Sudrajat', 'L', 'Jakarta, 15 Mei 1980', 'Jl. Pendidikan No. 123, Jakarta', '1234567890123456', 'Guru Matematika', 'Universitas Negeri Jakarta', 'Pendidikan Matematika', '2005', '2010-07-01', 'Matematika, Fisika'],
                [2, 'Siti Nurhaliza', 'P', 'Bandung, 20 Agustus 1985', 'Jl. Guru No. 45, Bandung', '2345678901234567', 'Guru Bahasa Indonesia', 'Universitas Pendidikan Indonesia', 'Pendidikan Bahasa Indonesia', '2008', '2012-07-15', 'Bahasa Indonesia, PKN']
            ];
            $callback = function() use ($headers, $data) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers);
                foreach ($data as $row) fputcsv($handle, $row);
                fclose($handle);
            };
            return response()->streamDownload($callback, 'template_guru.csv', ['Content-Type' => 'text/csv; charset=UTF-8',]);
        } catch (\Exception $e) {
            Log::error('Error in downloadTemplate: '. $e->getMessage());
            return redirect()->route('administrasi.guru.index')->with('error', '❌ Gagal download template: '. $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);
        try {
            $file = $request->file('file');
            $successCount = 0; $failedCount = 0; $errors = [];
            $handle = fopen($file->getPathname(), 'r');
            if ($handle === false) throw new \Exception('Tidak dapat membaca file.');
            $headers = fgetcsv($handle, 0, ',');
            DB::beginTransaction();
            $rowNumber = 1;
            while (($row = fgetcsv($handle, 0, ','))!== false) {
                $rowNumber++;
                if (count(array_filter($row)) < 4) continue;

                $namaGuru = trim($row[1]?? '');
                $jk = trim($row[2]?? '');
                $tempatTanggalLahir = trim($row[3]?? '');
                $alamat = trim($row[4]?? '');
                $nuptk = trim(preg_replace('/\s+/', '', $row[5]?? ''));
                $jabatan = trim($row[6]?? '');
                $universitas = trim($row[7]?? '');
                $jurusan = trim($row[8]?? '');
                $tahunLulus = trim($row[9]?? '');
                $tmt = trim($row[10]?? '');
                $mataPelajaranList = trim($row[11]?? '');

                if (empty($namaGuru)) { $failedCount++; $errors[] = "Baris {$rowNumber}: NAMA GURU kosong."; continue; }
                if (empty($jk) ||!in_array(strtoupper($jk), ['L', 'P'])) { $failedCount++; $errors[] = "Baris {$rowNumber}: JK harus L/P"; continue; }
                if (empty($jabatan)) { $failedCount++; $errors[] = "Baris {$rowNumber}: JABATAN kosong."; continue; }

                $tempatLahir = ''; $tanggalLahirRaw = '';
                if (!empty($tempatTanggalLahir)) {
                    if (strpos($tempatTanggalLahir, ',')!== false) {
                        $parts = explode(',', $tempatTanggalLahir, 2);
                        $tempatLahir = trim($parts[0]);
                        $tanggalLahirRaw = trim($parts[1]);
                    } else {
                        $tempatLahir = $tempatTanggalLahir;
                    }
                }
                $tanggalLahir = $this->parseTanggalIndo($tanggalLahirRaw);
                $tmtParsed = $this->parseTanggalIndo($tmt);

                if (!empty($nuptk) && Guru::where('nuptk', $nuptk)->exists()) {
                    $failedCount++; $errors[] = "Baris {$rowNumber}: NUPTK '{$nuptk}' sudah ada."; continue;
                }

                $nip = 'NIP'. date('Ymd'). str_pad($rowNumber, 4, '0', STR_PAD_LEFT);
                while (Guru::where('nip', $nip)->exists()) {
                    $nip = 'NIP'. date('Ymd'). str_pad($rowNumber. rand(1, 99), 4, '0', STR_PAD_LEFT);
                }
                $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $namaGuru)). '@guru.sch.id';
                $email = $this->generateUniqueEmail($email);

                $user = User::create([
                    'name' => $namaGuru, 'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'guru', 'status' => 'aktif'
                ]);

                $guru = Guru::create([
                    'user_id' => $user->id, 'nip' => $nip,
                    'nuptk' =>!empty($nuptk)? $nuptk : null,
                    'nama_lengkap' => $namaGuru,
                    'jenis_kelamin' => strtoupper($jk),
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir, // FIX: sudah di-parse Indonesia
                    'alamat' => $alamat,
                    'pendidikan_terakhir' => $this->getPendidikanTerakhir($tahunLulus),
                    'jurusan_pendidikan' =>!empty($jurusan)? $jurusan : 'Pendidikan',
                    'universitas' =>!empty($universitas)? $universitas : null,
                    'tahun_lulus' =>!empty($tahunLulus) && is_numeric($tahunLulus)? (int)$tahunLulus : null,
                    'tmt_masuk' => $tmtParsed?? date('Y-m-d'),
                    'tmt' => $tmtParsed,
                    'status_kepegawaian' => $jabatan,
                    'agama' => 'Islam', 'status' => 'aktif'
                ]);

                if (!empty($mataPelajaranList)) {
                    $mapelNames = array_map('trim', explode(',', $mataPelajaranList));
                    $mapelIds = [];
                    foreach ($mapelNames as $mapelName) {
                        $mapel = Mapel::where('nama_mapel', 'LIKE', "%{$mapelName}%")->first();
                        if ($mapel) $mapelIds[] = $mapel->id;
                        else $errors[] = "Baris {$rowNumber}: Mapel '{$mapelName}' tidak ditemukan.";
                    }
                    if (!empty($mapelIds)) $guru->mataPelajaran()->sync($mapelIds);
                }
                $successCount++;
            }
            fclose($handle);
            DB::commit();
            $message = "✅ Import selesai! {$successCount} berhasil.";
            if ($failedCount > 0) {
                $message = "⚠ Import: {$successCount} berhasil, {$failedCount} gagal.";
                return redirect()->route('administrasi.guru.index')->with('warning', $message)->with('import_errors', $errors);
            }
            return redirect()->route('administrasi.guru.index')->with('success', $message);
        } catch (\Exception $e) {
            if (isset($handle) && is_resource($handle)) fclose($handle);
            DB::rollBack();
            Log::error('Error in Guru Import: '. $e->getMessage());
            return redirect()->route('administrasi.guru.index')->with('error', '❌ Gagal import: '. $e->getMessage());
        }
    }

    private function getNextGuruId() { $lastGuru = Guru::orderBy('id', 'desc')->first(); return ($lastGuru? $lastGuru->id : 0) + 1; }
    private function generateUniqueEmail($email) {
        $original = $email; $counter = 1;
        while (User::where('email', $email)->exists()) {
            $atPos = strpos($original, '@');
            if ($atPos!== false) {
                $name = substr($original, 0, $atPos); $domain = substr($original, $atPos);
                $email = $name. $counter. $domain;
            } else $email = $original. $counter;
            $counter++;
        }
        return $email;
    }
    private function generateNIP($tanggalLahir, $rowNumber) { return 'NIP'. date('Ymd'). str_pad($rowNumber, 4, '0', STR_PAD_LEFT); }
    private function getPendidikanTerakhir($tahunLulus) {
        if (empty($tahunLulus)) return 'S1';
        $tahun = (int)$tahunLulus;
        if ($tahun >= 2020) return 'S2'; if ($tahun >= 2010) return 'S1';
        if ($tahun >= 2000) return 'S1'; if ($tahun >= 1990) return 'D4';
        if ($tahun >= 1980) return 'D3'; return 'SMA';
    }
}