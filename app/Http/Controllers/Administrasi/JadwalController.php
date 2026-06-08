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

class JadwalController extends Controller
{
    /**
     * Daftar Mata Pelajaran Lengkap (TANPA DUPLIKASI)
     */
    private function getDaftarMapel()
    {
        // Menggunakan collection dengan key id unik untuk menghindari duplikasi
        $mapelArray = [
            // Kelompok A (Umum/Wajib)
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
            
            // Kelompok B (Muatan Lokal/Keahlian)
            ['id' => 14, 'nama' => 'Agama Mulok', 'kode' => 'AGM-MULOK', 'kelompok' => 'B'],
            
            // Kelompok C (Kejuruan/Produktif)
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
        
        // Konversi ke collection of objects
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
            
            // AMBIL DATA MATA PELAJARAN dari daftar lengkap
            $mapel = $this->getDaftarMapel();
            
            // Coba juga ambil dari database jika ada (untuk menambah data dari DB)
            if (class_exists('App\Models\Mapel')) {
                try {
                    $dbMapel = Mapel::orderBy('nama_mapel')->get();
                    if ($dbMapel->isNotEmpty()) {
                        // Gabungkan dengan daftar yang sudah ada, tanpa duplikasi
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
                    Log::info('Mengambil mapel dari Model Mapel, jumlah: ' . $mapel->count());
                } catch (\Exception $e) {
                    Log::error('Error ambil mapel dari Model: ' . $e->getMessage());
                }
            }
            
            // Kelompokkan mapel berdasarkan kelompok untuk tampilan yang lebih rapi
            $mapelKelompokA = $mapel->filter(function($item) {
                return $item->kelompok == 'A';
            });
            $mapelKelompokB = $mapel->filter(function($item) {
                return $item->kelompok == 'B';
            });
            $mapelKelompokC = $mapel->filter(function($item) {
                return $item->kelompok == 'C';
            });

            Log::info('Final data mapel yang dikirim ke view - Total: ' . $mapel->count() . 
                      ', Kel A: ' . $mapelKelompokA->count() . 
                      ', Kel B: ' . $mapelKelompokB->count() . 
                      ', Kel C: ' . $mapelKelompokC->count());

            $guru = Guru::with('user')->get();
            $ruang = RuangKelas::all();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $tahunAjaranList = TahunAjaran::all();
            $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
            $semesterList = collect([
                (object) ['id' => 'ganjil', 'nama' => 'Ganjil'],
                (object) ['id' => 'genap', 'nama' => 'Genap'],
            ]);
            $bulan = date('n');
            $semesterAktif = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';

            return view('administrasi.jadwal.create', compact('kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC', 
                            'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 'semesterList', 'semesterAktif'));
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
            
            return view('administrasi.jadwal.create', compact('kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC',
                            'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 'semesterList', 'semesterAktif'))
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
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
                'guru_id' => 'required',
                'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'ruang' => 'required|string|max:50',
                'tahun_ajaran' => 'nullable|string|max:20',
                'semester' => 'nullable|string|max:10',
            ]);

            $tahunAjaran = $request->tahun_ajaran;
            if (empty($tahunAjaran)) {
                $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
                $tahunAjaran = $tahunAktif ? $tahunAktif->nama : date('Y') . '/' . (date('Y') + 1);
            }

            $semester = $request->semester;
            if (empty($semester)) {
                $bulan = date('n');
                $semester = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';
            }

            // Validasi jam
            if ($request->jam_mulai >= $request->jam_selesai) {
                return back()->with('error', 'Jam selesai harus lebih besar dari jam mulai')->withInput();
            }

            // Cek bentrok ruangan
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

            // Cek bentrok guru
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

            // Cek bentrok kelas
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

            // Buat jadwal
            $jadwal = Jadwal::create([
                'kelas_id' => $request->kelas_id,
                'mata_pelajaran_id' => $request->mapel_id,
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $kelas = Kelas::with('jurusan')->get();
            
            // AMBIL DATA MATA PELAJARAN dari daftar lengkap
            $mapel = $this->getDaftarMapel();
            
            // Coba juga ambil dari database jika ada
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
                    Log::info('EDIT - Mengambil mapel dari Model Mapel, jumlah: ' . $mapel->count());
                } catch (\Exception $e) {
                    Log::error('EDIT - Error ambil mapel dari Model: ' . $e->getMessage());
                }
            }
            
            // Kelompokkan mapel berdasarkan kelompok
            $mapelKelompokA = $mapel->filter(fn($i) => $i->kelompok == 'A');
            $mapelKelompokB = $mapel->filter(fn($i) => $i->kelompok == 'B');
            $mapelKelompokC = $mapel->filter(fn($i) => $i->kelompok == 'C');

            Log::info('EDIT - Final data mapel yang dikirim ke view: ' . $mapel->count() . ' data');

            $guru = Guru::with('user')->get();
            $ruang = RuangKelas::all();
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $tahunAjaranList = TahunAjaran::all();
            $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
            $semesterList = collect([
                (object) ['id' => 'ganjil', 'nama' => 'Ganjil'],
                (object) ['id' => 'genap', 'nama' => 'Genap'],
            ]);
            $bulan = date('n');
            $semesterAktif = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';

            return view('administrasi.jadwal.edit', compact(
                'jadwal', 'kelas', 'mapel', 'mapelKelompokA', 'mapelKelompokB', 'mapelKelompokC',
                'guru', 'ruang', 'hariList', 'tahunAjaranList', 'tahunAjaranAktif', 'semesterList', 'semesterAktif'
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
                'guru_id' => 'required',
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
                $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
                $tahunAjaran = $tahunAktif ? $tahunAktif->nama : date('Y') . '/' . (date('Y') + 1);
            }

            $semester = $request->semester;
            if (empty($semester)) {
                $bulan = date('n');
                $semester = ($bulan >= 1 && $bulan <= 6) ? 'genap' : 'ganjil';
            }

            // Cek bentrok ruangan
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

            // Cek bentrok guru
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
                'mata_pelajaran_id' => $request->mapel_id,
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
            $warna = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#1abc9c'];
            $dayMap = [
                'senin' => 1, 'selasa' => 2, 'rabu' => 3,
                'kamis' => 4, 'jumat' => 5, 'sabtu' => 6,
            ];
            
            foreach ($jadwal as $j) {
                $mapelNama = '';
                if ($j->mapel) {
                    $mapelNama = $j->mapel->nama_mapel ?? $j->mapel->nama ?? 'Mapel';
                } else {
                    // Cari dari daftar mapel
                    $found = $daftarMapel->firstWhere('id', $j->mata_pelajaran_id);
                    $mapelNama = $found ? $found->nama : 'Mapel ' . $j->mata_pelajaran_id;
                }
                
                $events[] = [
                    'title' => $mapelNama . ' - ' . ($j->kelas->nama ?? 'Kelas ?'),
                    'daysOfWeek' => [$dayMap[$j->hari] ?? 1],
                    'startTime' => $j->jam_mulai,
                    'endTime' => $j->jam_selesai,
                    'color' => $warna[($j->kelas_id ?? 0) % count($warna)],
                    'description' => 'Guru: ' . ($j->guru->user->name ?? $j->guru->nama_lengkap ?? '-') . ', Ruangan: ' . ($j->ruangan ?? '-'),
                ];
            }

            return view('administrasi.jadwal.kalender', compact('events'));
        } catch (\Exception $e) {
            Log::error('Error in jadwal kalender: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat kalender: ' . $e->getMessage());
        }
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
            $ruang = $request->ruang;
            $excludeId = $request->exclude_id;
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            
            $hasConflict = false;
            $message = '';
            
            // Cek bentrok kelas
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
            
            // Cek bentrok guru
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
            
            // Cek bentrok ruangan
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
}