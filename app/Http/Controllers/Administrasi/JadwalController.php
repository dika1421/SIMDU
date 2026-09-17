<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\RuangKelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class JadwalController extends Controller
{
    /**
     * Daftar Mata Pelajaran Lengkap (TANPA DUPLIKASI)
     */
    private function getDaftarMapel()
    {
        $mapelArray = [
            ['id' => 1, 'nama' => 'Pendidikan Agama dan Budi Pekerti (PAI)', 'kode' => 'PAI', 'kelompok' => 'A'],
            ['id' => 2, 'nama' => 'Praktik Ibadah (PAI Mulok)', 'kode' => 'PRAK-IBADAH', 'kelompok' => 'A'],
            ['id' => 3, 'nama' => 'PPKn', 'kode' => 'PPKN', 'kelompok' => 'A'],
            ['id' => 4, 'nama' => 'Bahasa Indonesia', 'kode' => 'BIN', 'kelompok' => 'A'],
            ['id' => 5, 'nama' => 'Bahasa Inggris', 'kode' => 'BING', 'kelompok' => 'A'],
            ['id' => 6, 'nama' => 'Bahasa Arab', 'kode' => 'BAR', 'kelompok' => 'A'],
            ['id' => 7, 'nama' => 'Bahasa Sunda', 'kode' => 'BSU', 'kelompok' => 'A'],
            ['id' => 8, 'nama' => 'Matematika', 'kode' => 'MTK', 'kelompok' => 'A'],
            ['id' => 9, 'nama' => 'Sejarah', 'kode' => 'SJRH', 'kelompok' => 'A'],
            ['id' => 10, 'nama' => 'Seni Budaya', 'kode' => 'SB', 'kelompok' => 'A'],
            ['id' => 11, 'nama' => 'Penjaskes (PJOK)', 'kode' => 'PJOK', 'kelompok' => 'A'],
            ['id' => 12, 'nama' => 'Informatika', 'kode' => 'INF', 'kelompok' => 'A'],
            ['id' => 13, 'nama' => 'Projek IPAS', 'kode' => 'PJ-IPAS', 'kelompok' => 'A'],
            ['id' => 14, 'nama' => 'Agama Mulok', 'kode' => 'AGM-MULOK', 'kelompok' => 'B'],
            ['id' => 15, 'nama' => 'Produk Kreatif dan Kewirausahaan (PKK)', 'kode' => 'PKK', 'kelompok' => 'C'],
            ['id' => 16, 'nama' => 'Administrasi Transaksi', 'kode' => 'ADM-TRANS', 'kelompok' => 'C'],
            ['id' => 17, 'nama' => 'Pengemasan dan Pendistribusian Produk', 'kode' => 'PENGEM-PROD', 'kelompok' => 'C'],
            ['id' => 18, 'nama' => 'Bisnis Online', 'kode' => 'BISNIS-ON', 'kelompok' => 'C'],
            ['id' => 19, 'nama' => 'Komunikasi Bisnis', 'kode' => 'KOM-BIS', 'kelompok' => 'C'],
            ['id' => 20, 'nama' => 'Strategi Marketing Visual Merchandising', 'kode' => 'SMVM', 'kelompok' => 'C'],
            ['id' => 21, 'nama' => 'Customer Service', 'kode' => 'CS', 'kelompok' => 'C'],
            ['id' => 22, 'nama' => 'Marketing', 'kode' => 'MRKT', 'kelompok' => 'C'],
            ['id' => 23, 'nama' => 'Desain Grafis', 'kode' => 'DESGRAF', 'kelompok' => 'C'],
            ['id' => 24, 'nama' => 'Pengelolaan Bisnis Ritel', 'kode' => 'PBR', 'kelompok' => 'C'],
            ['id' => 25, 'nama' => 'Produk Pastry dan Bakery (Elemen 2)', 'kode' => 'PASTRY', 'kelompok' => 'C'],
            ['id' => 26, 'nama' => 'Produk Cake dan Kue Indo (Elemen 1)', 'kode' => 'CAKE', 'kelompok' => 'C'],
            ['id' => 27, 'nama' => 'Penyajian Makanan (Elemen 5)', 'kode' => 'PENY-MAKAN-5', 'kelompok' => 'C'],
            ['id' => 28, 'nama' => 'Penyajian Makanan (Elemen 3-4)', 'kode' => 'PENY-MAKAN-34', 'kelompok' => 'C'],
            ['id' => 29, 'nama' => 'Sanitasi Higiene K3', 'kode' => 'SHK3', 'kelompok' => 'C'],
            ['id' => 30, 'nama' => 'Boga Dasar (Elemen 2 dan 6)', 'kode' => 'BOGA-DASAR', 'kelompok' => 'C'],
            ['id' => 31, 'nama' => 'Food Product', 'kode' => 'FP', 'kelompok' => 'C'],
            ['id' => 32, 'nama' => 'Elemen 1, 3 dan 4 (BDP)', 'kode' => 'ELEMEN-134', 'kelompok' => 'C'],
            ['id' => 33, 'nama' => 'Elemen 1 dan 2 (BDP)', 'kode' => 'ELEMEN-12', 'kelompok' => 'C'],
            ['id' => 34, 'nama' => 'Elemen 3 dan 6 (BDP)', 'kode' => 'ELEMEN-36', 'kelompok' => 'C'],
            ['id' => 35, 'nama' => 'Elemen 4 (BDP)', 'kode' => 'ELEMEN-4', 'kelompok' => 'C'],
            ['id' => 36, 'nama' => 'Elemen 5 dan 7 (BDP)', 'kode' => 'ELEMEN-57', 'kelompok' => 'C'],
            ['id' => 37, 'nama' => 'Elemen 8 Pemasaran', 'kode' => 'ELEMEN-8', 'kelompok' => 'C'],
        ];

        $result = collect();
        foreach ($mapelArray as $item) {
            $result->push((object) $item);
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Jadwal::with(['kelas', 'kelas.jurusan', 'mapel', 'guru', 'guru.user']);

            if ($request->filled('hari')) {
                $query->where('hari', $request->hari);
            }

            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }

            $query->orderByRaw("
                CASE
                    WHEN LOWER(hari) = 'senin' THEN 1
                    WHEN LOWER(hari) = 'selasa' THEN 2
                    WHEN LOWER(hari) = 'rabu' THEN 3
                    WHEN LOWER(hari) = 'kamis' THEN 4
                    WHEN LOWER(hari) = 'jumat' THEN 5
                    WHEN LOWER(hari) = 'sabtu' THEN 6
                    ELSE 7
                END
            ");
            $query->orderBy('jam_mulai', 'asc');

            $jadwal = $query->get();

            $kelas = Kelas::with('jurusan')->get();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

            return view('administrasi.jadwal.index', [
                'jadwal' => $jadwal,
                'kelas' => $kelas,
                'hariList' => $hariList,
                'selectedHari' => $request->hari,
                'selectedKelasId' => $request->kelas_id
            ]);

        } catch (\Exception $e) {
            Log::error('Error in jadwal index: ' . $e->getMessage());
            $kelas = Kelas::with('jurusan')->get();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $jadwal = collect();

            return view('administrasi.jadwal.index', [
                'jadwal' => $jadwal,
                'kelas' => $kelas,
                'hariList' => $hariList,
                'selectedHari' => $request->hari,
                'selectedKelasId' => $request->kelas_id
            ])->with('error', 'Gagal memuat data jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $kelas = Kelas::with('jurusan')->get();

            $mapel = $this->getDaftarMapel();

            if (class_exists('App\Models\Mapel')) {
                try {
                    $dbMapel = Mapel::orderBy('nama_mapel')->get();
                    if ($dbMapel->isNotEmpty()) {
                        $existingNames = $mapel->pluck('nama')->map(function($item) {
                            return strtolower(preg_replace('/^[a-zA-Z]\.\s*/', '', $item));
                        })->toArray();

                        foreach ($dbMapel as $m) {
                            $cleanName = strtolower(preg_replace('/^[a-zA-Z]\.\s*/', '', $m->nama_mapel));
                            if (!in_array($cleanName, $existingNames)) {
                                $mapel->push((object) [
                                    'id' => $m->id,
                                    'nama' => $m->nama_mapel,
                                    'kode' => $m->kode_mapel ?? 'MAPEL' . $m->id,
                                    'kelompok' => $m->kelompok ?? 'C'
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error ambil mapel dari Model: ' . $e->getMessage());
                }
            }

            $mapelKelompokA = $mapel->filter(fn($item) => $item->kelompok == 'A');
            $mapelKelompokB = $mapel->filter(fn($item) => $item->kelompok == 'B');
            $mapelKelompokC = $mapel->filter(fn($item) => $item->kelompok == 'C');

            $guru = Guru::with('user')->get();
            $ruang = RuangKelas::all();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $tahunAjaranList = TahunAjaran::all();
            $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
            $semesterList = collect([
                (object) ['id' => 'ganjil', 'nama' => 'Ganjil'],
                (object) ['id' => 'genap', 'nama' => 'Genap'],
            ]);
            $bulan = date('n');
            $semesterAktif = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';

            return view('administrasi.jadwal.create', compact(
                'kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC',
                'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 
                'semesterList', 'semesterAktif'
            ));
        } catch (\Exception $e) {
            Log::error('Error in jadwal create: ' . $e->getMessage());

            $kelas = Kelas::all();
            $mapel = $this->getDaftarMapel();
            $mapelKelompokA = $mapel->filter(fn($i) => $i->kelompok == 'A');
            $mapelKelompokB = $mapel->filter(fn($i) => $i->kelompok == 'B');
            $mapelKelompokC = $mapel->filter(fn($i) => $i->kelompok == 'C');
            $guru = collect();
            $ruang = collect();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $tahunAjaranList = collect();
            $tahunAjaranAktif = null;
            $semesterList = collect();
            $semesterAktif = 'ganjil';

            return view('administrasi.jadwal.create', compact(
                'kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC',
                'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 
                'semesterList', 'semesterAktif'
            ))->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Data yang diterima:', $request->all());

            $validated = $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mapel_id' => 'required',
                'guru_id' => 'required|exists:gurus,id',
                'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'ruang' => 'required|string|max:50',
                'tahun_ajaran' => 'nullable|string|max:20',
                'semester' => 'nullable|string|max:10',
            ]);

            $tahunAjaran = $request->tahun_ajaran;
            if (empty($tahunAjaran)) {
                $tahunAktif = TahunAjaran::where('is_aktif', true)->first();
                $tahunAjaran = $tahunAktif ? $tahunAktif->nama_tahun : date('Y') . '/' . (date('Y') + 1);
            }

            $semester = $request->semester;
            if (empty($semester)) {
                $bulan = date('n');
                $semester = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';
            }

            if ($request->jam_mulai >= $request->jam_selesai) {
                return back()->with('error', 'Jam selesai harus lebih besar dari jam mulai')->withInput();
            }

            $bentrok = Jadwal::where('hari', $request->hari)
                ->where('ruangan', $request->ruang)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($bentrok) {
                return back()->with('error', 'Jadwal bentrok! Ruangan sudah digunakan pada jam tersebut.')->withInput();
            }

            $guruBentrok = Jadwal::where('hari', $request->hari)
                ->where('guru_id', $request->guru_id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($guruBentrok) {
                return back()->with('error', 'Jadwal bentrok! Guru sudah mengajar pada jam tersebut.')->withInput();
            }

            $kelasBentrok = Jadwal::where('hari', $request->hari)
                ->where('kelas_id', $request->kelas_id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($kelasBentrok) {
                return back()->with('error', 'Jadwal bentrok! Kelas sudah memiliki jadwal pada jam tersebut.')->withInput();
            }

            $jadwal = Jadwal::create([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'guru_id' => $request->guru_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruangan' => $request->ruang,
                'tahun_ajaran' => $tahunAjaran,
                'semester' => $semester,
                'status' => 'aktif',
            ]);

            Log::info('Jadwal berhasil ditambahkan: ID ' . $jadwal->id);
            return redirect()->route('administrasi.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error in jadwal store: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $jadwal = Jadwal::with(['kelas', 'kelas.jurusan', 'mapel', 'guru', 'guru.user'])->findOrFail($id);
            return view('administrasi.jadwal.show', compact('jadwal'));
        } catch (\Exception $e) {
            Log::error('Error in jadwal show: ' . $e->getMessage());
            return redirect()->route('administrasi.jadwal.index')
                ->with('error', 'Data jadwal tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $kelas = Kelas::with('jurusan')->get();
            $mapel = $this->getDaftarMapel();

            if (class_exists('App\Models\Mapel')) {
                try {
                    $dbMapel = Mapel::orderBy('nama_mapel')->get();
                    if ($dbMapel->isNotEmpty()) {
                        $existingNames = $mapel->pluck('nama')->map(function($item) {
                            return strtolower(preg_replace('/^[a-zA-Z]\.\s*/', '', $item));
                        })->toArray();

                        foreach ($dbMapel as $m) {
                            $cleanName = strtolower(preg_replace('/^[a-zA-Z]\.\s*/', '', $m->nama_mapel));
                            if (!in_array($cleanName, $existingNames)) {
                                $mapel->push((object) [
                                    'id' => $m->id,
                                    'nama' => $m->nama_mapel,
                                    'kode' => $m->kode_mapel ?? 'MAPEL' . $m->id,
                                    'kelompok' => $m->kelompok ?? 'C'
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('EDIT - Error ambil mapel dari Model: ' . $e->getMessage());
                }
            }

            $mapelKelompokA = $mapel->filter(fn($i) => $i->kelompok == 'A');
            $mapelKelompokB = $mapel->filter(fn($i) => $i->kelompok == 'B');
            $mapelKelompokC = $mapel->filter(fn($i) => $i->kelompok == 'C');

            $guru = Guru::with('user')->get();
            $ruang = RuangKelas::all();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $tahunAjaranList = TahunAjaran::all();
            $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
            $semesterList = collect([
                (object) ['id' => 'ganjil', 'nama' => 'Ganjil'],
                (object) ['id' => 'genap', 'nama' => 'Genap'],
            ]);
            $bulan = date('n');
            $semesterAktif = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';

            return view('administrasi.jadwal.edit', compact(
                'jadwal', 'kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC',
                'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 
                'semesterList', 'semesterAktif'
            ));
        } catch (\Exception $e) {
            Log::error('Error in jadwal edit: ' . $e->getMessage());
            return redirect()->route('administrasi.jadwal.index')
                ->with('error', 'Data jadwal tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mapel_id' => 'required',
                'guru_id' => 'required|exists:gurus,id',
                'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'ruang' => 'required|string|max:50',
                'tahun_ajaran' => 'nullable|string|max:20',
                'semester' => 'nullable|string|max:10',
            ]);

            if ($request->jam_mulai >= $request->jam_selesai) {
                return back()->with('error', 'Jam selesai harus lebih besar dari jam mulai')->withInput();
            }

            $tahunAjaran = $request->tahun_ajaran;
            if (empty($tahunAjaran)) {
                $tahunAktif = TahunAjaran::where('is_aktif', true)->first();
                $tahunAjaran = $tahunAktif ? $tahunAktif->nama_tahun : date('Y') . '/' . (date('Y') + 1);
            }

            $semester = $request->semester;
            if (empty($semester)) {
                $bulan = date('n');
                $semester = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';
            }

            $bentrok = Jadwal::where('hari', $request->hari)
                ->where('ruangan', $request->ruang)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('id', '!=', $id)
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($bentrok) {
                return back()->with('error', 'Jadwal bentrok! Ruangan sudah digunakan pada jam tersebut.')->withInput();
            }

            $guruBentrok = Jadwal::where('hari', $request->hari)
                ->where('guru_id', $request->guru_id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('id', '!=', $id)
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($guruBentrok) {
                return back()->with('error', 'Jadwal bentrok! Guru sudah mengajar pada jam tersebut.')->withInput();
            }

            $jadwal->update([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'guru_id' => $request->guru_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruangan' => $request->ruang,
                'tahun_ajaran' => $tahunAjaran,
                'semester' => $semester,
            ]);

            return redirect()->route('administrasi.jadwal.index')
                ->with('success', 'Jadwal berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error in jadwal update: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            return redirect()->route('administrasi.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in jadwal destroy: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Tampilan kalender jadwal.
     */
    public function kalender()
    {
        try {
            $jadwal = Jadwal::with(['kelas', 'mapel', 'guru.user'])->get();
            $daftarMapel = $this->getDaftarMapel();

            $events = [];
            $warna = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#2c3e50'];
            $dayMap = [
                'senin' => 1, 'selasa' => 2, 'rabu' => 3,
                'kamis' => 4, 'jumat' => 5, 'sabtu' => 6,
            ];

            foreach ($jadwal as $j) {
                $mapelNama = '';
                if ($j->mapel) {
                    $mapelNama = $j->mapel->nama_mapel ?? $j->mapel->nama ?? 'Mapel';
                } else {
                    $found = $daftarMapel->firstWhere('id', $j->mapel_id);
                    $mapelNama = $found ? $found->nama : 'Mapel ' . $j->mapel_id;
                }

                $kelasNama = $j->kelas->nama_kelas ?? $j->kelas->nama ?? 'Kelas ?';
                $guruNama = $j->guru->user->name ?? $j->guru->nama_lengkap ?? '-';

                $events[] = [
                    'title' => $mapelNama . ' - ' . $kelasNama,
                    'daysOfWeek' => [$dayMap[$j->hari] ?? 1],
                    'startTime' => $j->jam_mulai,
                    'endTime' => $j->jam_selesai,
                    'color' => $warna[($j->kelas_id ?? 0) % count($warna)],
                    'description' => 'Guru: ' . $guruNama . ', Ruangan: ' . ($j->ruangan ?? '-'),
                ];
            }

            return view('administrasi.jadwal.kalender', compact('events'));
            
        } catch (\Exception $e) {
            Log::error('Error in jadwal kalender: ' . $e->getMessage());
            $events = [];
            return view('administrasi.jadwal.kalender', compact('events'))
                ->with('error', 'Gagal memuat kalender: ' . $e->getMessage());
        }
    }

    /**
     * Export jadwal
     */
    public function export(Request $request)
    {
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }

    /**
     * Copy jadwal
     */
    public function copy(Request $request)
    {
        return back()->with('info', 'Fitur copy jadwal sedang dalam pengembangan');
    }

    /**
     * Check jadwal conflict (untuk AJAX)
     */
    public function checkConflict(Request $request)
    {
        try {
            $kelasId = $request->kelas_id;
            $guruId = $request->guru_id;
            $hari = $request->hari;
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;
            $ruang = $request->ruangan;
            $excludeId = $request->exclude_id;
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';

            $hasConflict = false;
            $message = '';

            $queryKelas = Jadwal::where('hari', $hari)
                ->where('kelas_id', $kelasId)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester);
            if ($excludeId) $queryKelas->where('id', '!=', $excludeId);
            $conflictKelas = $queryKelas->where(function($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai);
            })->exists();

            if ($conflictKelas) {
                $hasConflict = true;
                $message = 'Kelas sudah memiliki jadwal di waktu yang sama.';
            }

            if (!$hasConflict && $guruId) {
                $queryGuru = Jadwal::where('hari', $hari)
                    ->where('guru_id', $guruId)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester);
                if ($excludeId) $queryGuru->where('id', '!=', $excludeId);
                $conflictGuru = $queryGuru->where(function($q) use ($jamMulai, $jamSelesai) {
                    $q->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai);
                })->exists();

                if ($conflictGuru) {
                    $hasConflict = true;
                    $message = 'Guru sudah memiliki jadwal mengajar di waktu yang sama.';
                }
            }

            if (!$hasConflict && $ruang) {
                $queryRuang = Jadwal::where('hari', $hari)
                    ->where('ruangan', $ruang)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester);
                if ($excludeId) $queryRuang->where('id', '!=', $excludeId);
                $conflictRuang = $queryRuang->where(function($q) use ($jamMulai, $jamSelesai) {
                    $q->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai);
                })->exists();

                if ($conflictRuang) {
                    $hasConflict = true;
                    $message = 'Ruangan sudah digunakan untuk jadwal lain di waktu yang sama.';
                }
            }

            return response()->json(['hasConflict' => $hasConflict, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Error checking conflict: ' . $e->getMessage());
            return response()->json(['hasConflict' => false, 'message' => 'Error checking conflict']);
        }
    }

    /**
     * ============================================================
     * 🔥 IMPORT JADWAL DARI CSV
     * Handle BOM + extra comma + auto-create mapel
     * ============================================================
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120'
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['csv', 'txt'])) {
                $rows = array_map('str_getcsv', file($file->getRealPath()));
            } else {
                return back()->with('error', 'Untuk file Excel (.xlsx/.xls), silakan Save As → CSV terlebih dahulu.');
            }

            if (empty($rows) || count($rows) < 2) {
                return back()->with('error', 'File kosong atau tidak ada data.');
            }

            // ==========================================
            // HANDLE HEADER + BOM
            // ==========================================
            $header = array_map('strtolower', array_map('trim', $rows[0]));

            if (isset($header[0])) {
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
                $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
                $header[0] = trim($header[0]);
            }

            $header = array_filter($header, function ($h) {
                return !empty($h) && $h !== '';
            });

            array_shift($rows);

            // Deteksi kolom tabel
            $kolomMapel = Schema::getColumnListing('mata_pelajarans');
            $kolomGuru  = Schema::getColumnListing('gurus');
            $kolomKelas = Schema::getColumnListing('kelas');

            Log::info('=== IMPORT JADWAL DIMULAI ===');
            Log::info('Header CSV (clean): ' . json_encode($header));

            $imported = 0;
            $skipped = 0;
            $newMapelCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                if (empty(array_filter($row))) {
                    continue;
                }

                // Potong row sesuai jumlah header
                $row = array_slice($row, 0, count($header));
                $data = array_combine($header, array_pad($row, count($header), null));

                // Trim semua value
                $data = array_map(function ($v) {
                    return is_string($v) ? trim($v) : $v;
                }, $data);

                Log::info("Baris {$rowNum}:", $data);

                if (empty($data['hari']) || empty($data['jam_mulai']) || empty($data['jam_selesai'])) {
                    $errors[] = "Baris {$rowNum}: hari/jam_mulai/jam_selesai wajib diisi";
                    $skipped++;
                    continue;
                }

                // ==========================================
                // CARI KELAS
                // ==========================================
                $kelasId = null;
                if (!empty($data['kelas'])) {
                    $q = Kelas::query();
                    if (in_array('nama_kelas', $kolomKelas)) {
                        $q->where('nama_kelas', $data['kelas']);
                    }
                    if (in_array('nama', $kolomKelas)) {
                        $q->orWhere('nama', $data['kelas']);
                    }
                    $kelas = $q->first();
                    $kelasId = $kelas->id ?? null;
                    Log::info("Kelas '{$data['kelas']}' → " . ($kelasId ?? 'NULL'));
                }

                // ==========================================
                // CARI MAPEL — AUTO-CREATE
                // ==========================================
                $mapelId = null;
                if (!empty($data['mata_pelajaran'])) {
                    $q = Mapel::query();
                    if (in_array('nama_mapel', $kolomMapel)) {
                        $q->where('nama_mapel', $data['mata_pelajaran']);
                    }
                    if (in_array('nama', $kolomMapel)) {
                        $q->orWhere('nama', $data['mata_pelajaran']);
                    }
                    $mapel = $q->first();

                    // ✅ AUTO-CREATE kalau mapel tidak ada
                    if (!$mapel) {
                        try {
                            $mapel = Mapel::create([
                                'nama_mapel' => $data['mata_pelajaran'],
                                'kode_mapel' => 'MAPEL-' . time() . rand(100, 999),
                                'kelompok' => 'C',
                            ]);
                            $newMapelCount++;
                            Log::info("🆕 Mapel BARU: '{$data['mata_pelajaran']}' → ID {$mapel->id}");
                        } catch (\Exception $e) {
                            Log::error("Gagal buat mapel '{$data['mata_pelajaran']}': " . $e->getMessage());
                        }
                    }

                    $mapelId = $mapel ? $mapel->id : null;
                    Log::info("Mapel '{$data['mata_pelajaran']}' → " . ($mapelId ?? 'NULL'));
                }

                // ==========================================
                // CARI GURU
                // ==========================================
                $guruId = null;
                if (!empty($data['guru'])) {
                    $q = Guru::query();
                    if (in_array('nama_lengkap', $kolomGuru)) {
                        $q->where('nama_lengkap', $data['guru']);
                    }
                    if (in_array('nama', $kolomGuru)) {
                        $q->orWhere('nama', $data['guru']);
                    }
                    $q->orWhereHas('user', function ($u) use ($data) {
                        $u->where('name', $data['guru']);
                    });
                    $guru = $q->first();
                    $guruId = $guru->id ?? null;
                    Log::info("Guru '{$data['guru']}' → " . ($guruId ?? 'NULL'));
                }

                // Skip kalau ada yang tidak ditemukan
                if (!$kelasId || !$mapelId || !$guruId) {
                    $missed = [];
                    if (!$kelasId) $missed[] = "kelas '{$data['kelas']}'";
                    if (!$mapelId) $missed[] = "mapel '{$data['mata_pelajaran']}'";
                    if (!$guruId) $missed[] = "guru '{$data['guru']}'";
                    $errors[] = "Baris {$rowNum}: " . implode(', ', $missed) . " tidak ditemukan";
                    $skipped++;
                    Log::warning("SKIP Baris {$rowNum}: " . implode(', ', $missed));
                    continue;
                }

                // ==========================================
                // INSERT / UPDATE
                // ==========================================
                Jadwal::updateOrCreate(
                    [
                        'hari' => strtolower($data['hari']),
                        'jam_mulai' => $data['jam_mulai'],
                        'kelas_id' => $kelasId,
                        'guru_id' => $guruId,
                        'tahun_ajaran' => $data['tahun_ajaran'] ?? date('Y') . '/' . (date('Y') + 1),
                        'semester' => strtolower($data['semester'] ?? 'ganjil'),
                    ],
                    [
                        'jam_selesai' => $data['jam_selesai'],
                        'mapel_id' => $mapelId,
                        'ruangan' => $data['ruangan'] ?? null,
                        'status' => 'aktif',
                    ]
                );

                $imported++;
                Log::info("✅ Baris {$rowNum} BERHASIL di-import");
            }

            DB::commit();

            Log::info("=== IMPORT SELESAI: {$imported} berhasil, {$skipped} di-skip, {$newMapelCount} mapel baru ===");

            $message = "✅ {$imported} jadwal berhasil diimport.";
            if ($newMapelCount > 0) {
                $message .= " 🆕 {$newMapelCount} mapel baru dibuat.";
            }
            if ($skipped > 0) {
                $message .= " ⚠️ {$skipped} baris di-skip.";
            }

            return redirect()->route('administrasi.jadwal.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import jadwal error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * ============================================================
     * DOWNLOAD TEMPLATE IMPORT JADWAL
     * ============================================================
     */
    public function downloadTemplate()
    {
        $headers = [
            'hari', 'jam_mulai', 'jam_selesai', 'kelas',
            'mata_pelajaran', 'guru', 'ruangan', 'tahun_ajaran', 'semester',
        ];

        $samples = [
            ['Senin', '07:00', '08:30', 'X A PEMASARAN', 'Matematika', "Aceng Ma'sum, S.Pd", 'R-101', '2025/2026', 'ganjil'],
            ['Senin', '08:30', '10:00', 'X A PEMASARAN', 'Bahasa Indonesia', 'Siti Hamimah, S.Ag', 'R-101', '2025/2026', 'ganjil'],
        ];

        $filename = 'template-import-jadwal-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        foreach ($samples as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}