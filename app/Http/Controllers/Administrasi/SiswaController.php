<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Load dengan relasi yang diperlukan
            $query = Siswa::with(['user', 'kelas.jurusan']);

            // Filter by kelas
            if ($request->filled('kelas')) {
                $query->where('kelas_id', $request->kelas);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search by NIS, NISN, or name
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap', 'like', "%{$search}%")
                      ->orWhereHas('user', function($u) use ($search) {
                          $u->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Pagination dengan 10 data per halaman
            $siswa = $query->orderBy('created_at', 'desc')->paginate(10);
            
            // Tambahkan query string ke pagination
            $siswa->appends($request->query());
            
            $kelas = Kelas::with('jurusan')->get();

            return view('administrasi.siswa.index', compact('siswa', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Error in siswa index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::with('jurusan')->orderBy('nama')->get();
        return view('administrasi.siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|string|unique:siswas,nis',
            'nisn' => 'nullable|string|unique:siswas,nisn',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telp_ortu' => 'nullable|string|max:15',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'kelas_id' => 'nullable|exists:kelas,id',
            'tahun_masuk' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'password' => 'nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            // Generate password (default menggunakan NIS jika tidak diisi)
            $password = $request->filled('password') ? $request->password : $request->nis;

            // Buat user
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'siswa',
                'no_telepon' => $request->no_telp_ortu,
                'status' => 'aktif'
            ]);

            // Buat data siswa
            $siswaData = [
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'kelas_id' => $request->kelas_id,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_telepon_orangtua' => $request->no_telp_ortu,
                'pekerjaan_orangtua' => $request->pekerjaan_ortu,
                'tahun_masuk' => $request->tahun_masuk ?? date('Y'),
                'status' => 'aktif'
            ];

            // Tambahkan agama jika kolom tersedia
            if (Schema::hasColumn('siswas', 'agama')) {
                $siswaData['agama'] = $request->agama;
            }

            Siswa::create($siswaData);

            DB::commit();

            return redirect()->route('administrasi.siswa.index')
                ->with('success', 'Siswa berhasil ditambahkan!<br>Email: ' . $request->email . '<br>Password: ' . $password);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in siswa store: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambah siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::with([
                'user', 
                'kelas.jurusan',
                'spp'
            ])->findOrFail($id);
            
            return view('administrasi.siswa.show', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error in siswa show: ' . $e->getMessage());
            return redirect()->route('administrasi.siswa.index')
                ->with('error', 'Data siswa tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $siswa = Siswa::with('user')->findOrFail($id);
            $kelas = Kelas::with('jurusan')->orderBy('nama')->get();
            return view('administrasi.siswa.edit', compact('siswa', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Error in siswa edit: ' . $e->getMessage());
            return redirect()->route('administrasi.siswa.index')
                ->with('error', 'Data siswa tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'agama' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:kelas,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telp_ortu' => 'nullable|string|max:15',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif,lulus,dropout',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            if ($siswa->user) {
                $userUpdate = [
                    'name' => $request->nama_lengkap,
                    'no_telepon' => $request->no_telp_ortu
                ];
                
                if ($request->filled('password')) {
                    $userUpdate['password'] = Hash::make($request->password);
                }
                
                $siswa->user->update($userUpdate);
            }

            // Update siswa
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'kelas_id' => $request->kelas_id,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_telepon_orangtua' => $request->no_telp_ortu,
                'pekerjaan_orangtua' => $request->pekerjaan_ortu,
                'status' => $request->status
            ];

            if (Schema::hasColumn('siswas', 'agama')) {
                $updateData['agama'] = $request->agama;
            }

            if (Schema::hasColumn('siswas', 'no_telepon')) {
                $updateData['no_telepon'] = $request->no_telepon;
            }

            $siswa->update($updateData);

            DB::commit();

            $message = 'Data siswa berhasil diupdate!';
            if ($request->filled('password')) {
                $message .= ' Password telah diubah.';
            }

            return redirect()->route('administrasi.siswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in siswa update: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengupdate siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $siswa = Siswa::findOrFail($id);
            
            if ($siswa->user) {
                $siswa->user->delete();
            }
            
            $siswa->delete();

            DB::commit();

            return redirect()->route('administrasi.siswa.index')
                ->with('success', 'Data siswa berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in siswa destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Process student mutation to another class.
     */
    public function mutasi(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'kelas_tujuan' => 'required|exists:kelas,id',
            'alasan' => 'required|string|min:5'
        ]);

        try {
            DB::beginTransaction();

            $kelasLama = $siswa->kelas_id;
            
            $siswa->update(['kelas_id' => $request->kelas_tujuan]);
            
            DB::commit();

            return redirect()->route('administrasi.siswa.show', $siswa->id)
                ->with('success', 'Mutasi siswa berhasil diproses dari kelas ' . ($kelasLama ?? 'tidak ada') . ' ke kelas baru');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in siswa mutasi: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memproses mutasi: ' . $e->getMessage());
        }
    }

    /**
     * Reset student password to NIS.
     */
    public function resetPassword($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            
            if (!$siswa->user) {
                return redirect()->route('administrasi.siswa.index')
                    ->with('error', 'User tidak ditemukan untuk siswa ini');
            }
            
            $newPassword = $siswa->nis;
            $siswa->user->update([
                'password' => Hash::make($newPassword)
            ]);
            
            return redirect()->route('administrasi.siswa.index')
                ->with('success', 'Password untuk siswa ' . $siswa->nama_lengkap . ' telah direset menjadi NIS: ' . $newPassword);
            
        } catch (\Exception $e) {
            Log::error('Error in resetPassword: ' . $e->getMessage());
            return redirect()->route('administrasi.siswa.index')
                ->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    /**
     * Import data siswa from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $successCount = 0;
            $failedCount = 0;
            $errors = [];
            
            // Log file info
            Log::info('Import file: ' . $file->getClientOriginalName());
            Log::info('File size: ' . $file->getSize() . ' bytes');
            
            $handle = fopen($file->getPathname(), 'r');
            if ($handle === false) {
                throw new \Exception('Tidak dapat membaca file.');
            }
            
            DB::beginTransaction();
            
            $rowNumber = 0;
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $rowNumber++;
                
                // Log setiap baris untuk debugging
                Log::info("Row {$rowNumber}: " . json_encode($row));
                
                // Skip baris kosong
                if (count(array_filter($row)) < 2) {
                    Log::info("Row {$rowNumber}: Skipped - kurang dari 2 kolom terisi");
                    continue;
                }
                
                // Baris pertama adalah header, skip
                if ($rowNumber == 1) {
                    Log::info("Row {$rowNumber}: Header detected - " . json_encode($row));
                    continue;
                }
                
                // Mapping data - sesuaikan dengan urutan kolom
                // Format: NO,NISN,NIS,NAMA SISWA,JENIS KELAMIN
                $no = trim($row[0] ?? '');
                $nisn = trim($row[1] ?? '');
                $nis = trim($row[2] ?? '');
                $nama = trim($row[3] ?? '');
                $jenisKelamin = trim($row[4] ?? '');
                
                Log::info("Row {$rowNumber}: Data - NO: {$no}, NISN: {$nisn}, NIS: {$nis}, NAMA: {$nama}, JK: {$jenisKelamin}");
                
                // Validasi NIS (wajib)
                if (empty($nis)) {
                    $failedCount++;
                    $errors[] = "Baris {$rowNumber}: NIS tidak boleh kosong";
                    Log::warning("Row {$rowNumber}: NIS kosong");
                    continue;
                }
                
                // Validasi nama (wajib)
                if (empty($nama)) {
                    $failedCount++;
                    $errors[] = "Baris {$rowNumber}: NAMA SISWA tidak boleh kosong";
                    Log::warning("Row {$rowNumber}: NAMA kosong");
                    continue;
                }
                
                // Validasi jenis kelamin
                if (!empty($jenisKelamin)) {
                    $jenisKelamin = strtoupper($jenisKelamin);
                    if (!in_array($jenisKelamin, ['L', 'P'])) {
                        $jenisKelamin = 'L';
                    }
                } else {
                    $jenisKelamin = 'L';
                }
                
                // Cek apakah NIS sudah ada
                if (Siswa::where('nis', $nis)->exists()) {
                    $failedCount++;
                    $errors[] = "Baris {$rowNumber}: NIS '{$nis}' sudah terdaftar";
                    Log::warning("Row {$rowNumber}: NIS '{$nis}' sudah terdaftar");
                    continue;
                }
                
                // Cek apakah NISN sudah ada (jika diisi)
                if (!empty($nisn) && Siswa::where('nisn', $nisn)->exists()) {
                    $failedCount++;
                    $errors[] = "Baris {$rowNumber}: NISN '{$nisn}' sudah terdaftar";
                    Log::warning("Row {$rowNumber}: NISN '{$nisn}' sudah terdaftar");
                    continue;
                }
                
                try {
                    // Generate email dari nama
                    $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $nama)) . '@siswa.sch.id';
                    $email = $this->generateUniqueEmail($email);
                    
                    Log::info("Row {$rowNumber}: Generating email: {$email}");
                    
                    // Buat user
                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make($nis),
                        'role' => 'siswa',
                        'status' => 'aktif'
                    ]);
                    
                    Log::info("Row {$rowNumber}: User created with ID: {$user->id}");
                    
                    // Buat siswa
                    $siswa = Siswa::create([
                        'user_id' => $user->id,
                        'nisn' => !empty($nisn) ? $nisn : null,
                        'nis' => $nis,
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => $jenisKelamin,
                        'status' => 'aktif'
                    ]);
                    
                    Log::info("Row {$rowNumber}: Siswa created with ID: {$siswa->id}");
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error("Row {$rowNumber}: Error - " . $e->getMessage());
                }
            }
            
            fclose($handle);
            DB::commit();
            
            Log::info("Import completed - Success: {$successCount}, Failed: {$failedCount}");
            
            if ($successCount == 0 && $failedCount == 0) {
                return redirect()->route('administrasi.siswa.index')
                    ->with('warning', 'Tidak ada data yang diproses. Pastikan file CSV memiliki format yang benar.');
            }
            
            $message = "✅ Import selesai! {$successCount} data berhasil ditambahkan.";
            if ($failedCount > 0) {
                $message = "⚠️ Import selesai: {$successCount} berhasil, {$failedCount} gagal.";
                return redirect()->route('administrasi.siswa.index')
                    ->with('warning', $message)
                    ->with('import_errors', $errors);
            }
            
            return redirect()->route('administrasi.siswa.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            DB::rollBack();
            Log::error('Error in Siswa Import: ' . $e->getMessage());
            return redirect()->route('administrasi.siswa.index')
                ->with('error', '❌ Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique email
     */
    private function generateUniqueEmail($email)
    {
        $original = $email;
        $counter = 1;
        
        while (User::where('email', $email)->exists()) {
            $atPos = strpos($original, '@');
            if ($atPos !== false) {
                $name = substr($original, 0, $atPos);
                $domain = substr($original, $atPos);
                $email = $name . $counter . $domain;
            } else {
                $email = $original . $counter;
            }
            $counter++;
        }
        
        return $email;
    }

    /**
     * Download template CSV untuk siswa
     */
    public function downloadTemplate()
    {
        try {
            $headers = ['NO', 'NISN', 'NIS', 'NAMA SISWA', 'JENIS KELAMIN'];
            
            $data = [
                [1, '3093655826', '2526027', 'Abdul Rahman Al Hafiz', 'L'],
                [2, '0099965845', '2526029', 'Achmad Guntur Anggara', 'L'],
                [3, '0096626125', '2526021', 'Ahmad Muhari Wijaya', 'L'],
                [4, '', '2526033', 'Akhdan Rifa', 'L'],
                [5, '3090984673', '2526073', 'Anisa Fitri Ayu', 'P'],
            ];
            
            $callback = function() use ($headers, $data) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers);
                foreach ($data as $row) {
                    fputcsv($handle, $row);
                }
                fclose($handle);
            };
            
            return response()->streamDownload($callback, 'template_siswa.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in downloadTemplate: ' . $e->getMessage());
            return redirect()->route('administrasi.siswa.index')
                ->with('error', '❌ Gagal download template: ' . $e->getMessage());
        }
    }

    /**
     * Export siswa data to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Siswa::with(['kelas.jurusan']);
            
            if ($request->filled('kelas')) {
                $query->where('kelas_id', $request->kelas);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $siswa = $query->orderBy('nama_lengkap')->get();
            
            $headers = [
                'NO', 'NISN', 'NIS', 'NAMA SISWA', 'JENIS KELAMIN',
                'TEMPAT LAHIR', 'TANGGAL LAHIR', 'ALAMAT', 'AGAMA',
                'NAMA AYAH', 'NAMA IBU', 'NO TELP ORTU', 'PEKERJAAN ORTU',
                'KELAS', 'TAHUN MASUK', 'STATUS'
            ];
            
            $callback = function() use ($headers, $siswa) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers);
                
                $no = 1;
                foreach ($siswa as $s) {
                    fputcsv($handle, [
                        $no++,
                        $s->nisn ?? '-',
                        $s->nis ?? '-',
                        $s->nama_lengkap,
                        $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                        $s->tempat_lahir ?? '-',
                        $s->tanggal_lahir ? Carbon::parse($s->tanggal_lahir)->format('Y-m-d') : '-',
                        $s->alamat ?? '-',
                        $s->agama ?? '-',
                        $s->nama_ayah ?? '-',
                        $s->nama_ibu ?? '-',
                        $s->no_telepon_orangtua ?? '-',
                        $s->pekerjaan_orangtua ?? '-',
                        $s->kelas->nama ?? '-',
                        $s->tahun_masuk ?? '-',
                        $s->status ?? 'aktif'
                    ]);
                }
                fclose($handle);
            };
            
            return response()->streamDownload($callback, 'data_siswa_' . date('Y-m-d') . '.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in siswa export: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}