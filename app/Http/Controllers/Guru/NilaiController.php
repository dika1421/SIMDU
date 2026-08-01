<?php
// app/Http/Controllers/Guru/NilaiController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    /**
     * Dashboard nilai guru - menampilkan kelas & mapel yang diajar
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru.');
            }
            
            // 🔥 PERBAIKAN: Gunakan 'jadwal' (tanpa 's')
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();
            
            // 🔥 PERBAIKAN: Gunakan 'mata_pelajaran' (tanpa '_id')
            $mapelIds = DB::table('jadwal')
                ->where('guru_id', $guru->id)
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('mata_pelajaran');
            
            $mapel = Mapel::whereIn('id', $mapelIds)->get();
            
            // Hitung statistik nilai
            $statistik = [];
            foreach ($kelas as $k) {
                foreach ($mapel as $m) {
                    $nilai = Nilai::where('guru_id', $guru->id)
                                  ->where('kelas_id', $k->id)
                                  ->where('mapel_id', $m->id)
                                  ->where('status', 'published')
                                  ->get();
                    
                    $statistik[$k->id][$m->id] = [
                        'rata_rata' => $nilai->avg('nilai_akhir') ?? 0,
                        'jumlah_siswa' => $nilai->count(),
                        'nilai_tertinggi' => $nilai->max('nilai_akhir') ?? 0,
                        'nilai_terendah' => $nilai->min('nilai_akhir') ?? 0,
                        'total_nilai' => $nilai->sum('nilai_akhir') ?? 0
                    ];
                }
            }
            
            $tahunAjaranList = Nilai::where('guru_id', $guru->id)
                                    ->distinct()
                                    ->pluck('tahun_ajaran')
                                    ->filter()
                                    ->toArray();
            
            if (empty($tahunAjaranList)) {
                $tahunAjaranList = [date('Y') . '/' . (date('Y') + 1)];
            }
            
            $semesterList = ['ganjil', 'genap'];
            
            return view('guru.nilai.index', compact(
                'kelas', 
                'mapel', 
                'statistik', 
                'guru',
                'tahunAjaranList',
                'semesterList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Form input nilai
     */
    public function input(Request $request)
    {
        try {
            // 🔥 PERBAIKAN: Buat validasi nullable
            $request->validate([
                'kelas_id' => 'nullable|exists:kelas,id',
                'mapel_id' => 'nullable|exists:mata_pelajarans,id',
                'tahun_ajaran' => 'nullable|string',
                'semester' => 'nullable|in:ganjil,genap'
            ]);
            
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Data guru tidak ditemukan.');
            }
            
            // 🔥 PERBAIKAN: Jika tidak ada, ambil default
            $kelasId = $request->kelas_id;
            $mapelId = $request->mapel_id;
            
            if (!$kelasId) {
                $kelasPertama = Kelas::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->first();
                $kelasId = $kelasPertama ? $kelasPertama->id : null;
            }
            
            if (!$mapelId) {
                $mapelPertama = Mapel::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->first();
                $mapelId = $mapelPertama ? $mapelPertama->id : null;
            }
            
            if (!$kelasId || !$mapelId) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Silakan pilih kelas dan mata pelajaran terlebih dahulu.');
            }
            
            // 🔥 PERBAIKAN: Cek apakah guru mengajar mapel ini di kelas ini
            $isAuthorized = Jadwal::where('guru_id', $guru->id)
                                  ->where('kelas_id', $kelasId)
                                  ->where('mata_pelajaran', $mapelId)
                                  ->exists();
            
            if (!$isAuthorized) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Anda tidak memiliki akses untuk menginput nilai di kelas ini.');
            }
            
            // 🔥 PERBAIKAN: Gunakan 'nama' bukan 'nama_lengkap'
            $siswa = Siswa::where('kelas_id', $kelasId)
                          ->where('status', 'aktif')
                          ->with('user')
                          ->orderBy('nama')
                          ->get();
            
            if ($siswa->isEmpty()) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Tidak ada siswa di kelas ini.');
            }
            
            $mataPelajaran = Mapel::find($mapelId);
            $kelas = Kelas::find($kelasId);
            
            if (!$mataPelajaran) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Mata pelajaran tidak ditemukan.');
            }
            
            if (!$kelas) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Kelas tidak ditemukan.');
            }
            
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            
            // Ambil nilai yang sudah ada
            foreach ($siswa as $s) {
                $s->nilai = Nilai::where('siswa_id', $s->id)
                                 ->where('mapel_id', $mapelId)
                                 ->where('guru_id', $guru->id)
                                 ->where('tahun_ajaran', $tahunAjaran)
                                 ->where('semester', $semester)
                                 ->first();
            }
            
            return view('guru.nilai.input', compact(
                'siswa', 
                'mataPelajaran', 
                'kelas', 
                'guru', 
                'tahunAjaran', 
                'semester',
                'kelasId',
                'mapelId'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai input: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Save nilai
     */
    public function save(Request $request)
    {
        try {
            // 🔥 PERBAIKAN: Validasi nullable
            $request->validate([
                'nilai' => 'required|array',
                'nilai.*.nilai_harian_1' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_harian_2' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_harian_3' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_tugas_1' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_tugas_2' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_uts' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_uas' => 'nullable|numeric|min:0|max:100',
                'nilai.*.nilai_praktek' => 'nullable|numeric|min:0|max:100',
                'kelas_id' => 'nullable|exists:kelas,id',
                'mapel_id' => 'nullable|exists:mata_pelajarans,id',
                'tahun_ajaran' => 'nullable|string',
                'semester' => 'nullable|in:ganjil,genap'
            ]);

            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan');
            }

            // 🔥 PERBAIKAN: Ambil nilai dari berbagai kemungkinan
            $kelasId = $request->kelas_id;
            $mapelId = $request->mapel_id;
            
            if (!$kelasId || !$mapelId) {
                return redirect()->back()->with('error', 'Kelas dan Mata Pelajaran harus dipilih.');
            }

            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';

            DB::beginTransaction();

            $savedCount = 0;
            foreach ($request->nilai as $siswa_id => $nilaiData) {
                // Cek apakah ada nilai yang diisi
                $hasValue = false;
                $nilaiFields = ['nilai_harian_1', 'nilai_harian_2', 'nilai_harian_3', 'nilai_tugas_1', 'nilai_tugas_2', 'nilai_uts', 'nilai_uas', 'nilai_praktek'];
                
                foreach ($nilaiFields as $field) {
                    if (isset($nilaiData[$field]) && $nilaiData[$field] !== null && $nilaiData[$field] !== '') {
                        $hasValue = true;
                        break;
                    }
                }
                
                if (!$hasValue) {
                    continue;
                }

                // Cek apakah nilai sudah ada
                $existing = Nilai::where('siswa_id', $siswa_id)
                    ->where('mapel_id', $mapelId)
                    ->where('guru_id', $guru->id)
                    ->where('kelas_id', $kelasId)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester)
                    ->first();

                // Hitung nilai akhir dari komponen
                $nilaiAkhir = $this->hitungNilaiAkhir($nilaiData);
                $predikat = $this->getPredikat($nilaiAkhir);

                $data = [
                    'nilai_harian_1' => $nilaiData['nilai_harian_1'] ?? null,
                    'nilai_harian_2' => $nilaiData['nilai_harian_2'] ?? null,
                    'nilai_harian_3' => $nilaiData['nilai_harian_3'] ?? null,
                    'nilai_tugas_1' => $nilaiData['nilai_tugas_1'] ?? null,
                    'nilai_tugas_2' => $nilaiData['nilai_tugas_2'] ?? null,
                    'nilai_uts' => $nilaiData['nilai_uts'] ?? null,
                    'nilai_uas' => $nilaiData['nilai_uas'] ?? null,
                    'nilai_praktek' => $nilaiData['nilai_praktek'] ?? null,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $predikat,
                    'status' => 'draft',
                    'updated_at' => now()
                ];

                if ($existing) {
                    $existing->update($data);
                } else {
                    $data['siswa_id'] = $siswa_id;
                    $data['mapel_id'] = $mapelId;
                    $data['guru_id'] = $guru->id;
                    $data['kelas_id'] = $kelasId;
                    $data['tahun_ajaran'] = $tahunAjaran;
                    $data['semester'] = $semester;
                    $data['created_at'] = now();

                    Nilai::create($data);
                }
                $savedCount++;
            }

            DB::commit();

            if ($savedCount === 0) {
                return redirect()->route('guru.nilai.index')
                    ->with('warning', 'Tidak ada nilai yang disimpan. Pastikan Anda mengisi minimal satu komponen nilai.');
            }

            return redirect()->route('guru.nilai.index')
                ->with('success', $savedCount . ' nilai berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in nilai save: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
    
    /**
     * =============================================
     * FUNGSI RAPORT SISWA
     * =============================================
     */
    
    /**
     * Tampilkan halaman raport siswa
     */
    public function raport(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Anda tidak terdaftar sebagai guru.');
            }
            
            $semesterList = ['ganjil', 'genap'];
            
            // 🔥 PERBAIKAN: Gunakan 'jadwal' (tanpa 's')
            $kelasDiAjar = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();
            
            if ($kelasDiAjar->isEmpty()) {
                return view('guru.nilai.raport', [
                    'kelasDiAjar' => collect(),
                    'siswa' => collect(),
                    'mapel' => collect(),
                    'dataNilai' => [],
                    'kelas' => null,
                    'selectedKelasId' => null,
                    'guru' => $guru,
                    'tahunAjaran' => date('Y') . '/' . (date('Y') + 1),
                    'semester' => 'ganjil',
                    'tahunAjaranList' => [],
                    'semesterList' => $semesterList,
                    'rataRataSiswa' => []
                ])->with('error', 'Anda belum mengajar kelas manapun.');
            }
            
            $selectedKelasId = $request->input('kelas_id', $kelasDiAjar->first()->id);
            
            if (!$kelasDiAjar->contains('id', $selectedKelasId)) {
                $selectedKelasId = $kelasDiAjar->first()->id;
            }
            
            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');
            
            $kelas = Kelas::with(['jurusan', 'waliKelas.user'])->find($selectedKelasId);
            
            // 🔥 PERBAIKAN: Gunakan 'nama' bukan 'nama_lengkap'
            $siswa = Siswa::where('kelas_id', $selectedKelasId)
                ->where('status', 'aktif')
                ->with('user')
                ->orderBy('nama')
                ->get();
            
            // 🔥 PERBAIKAN: Gunakan 'mata_pelajaran' (tanpa '_id')
            $mapel = Mapel::whereHas('jadwal', function($query) use ($guru, $selectedKelasId) {
                $query->where('guru_id', $guru->id)
                      ->where('kelas_id', $selectedKelasId);
            })->get();
            
            if ($mapel->isEmpty()) {
                return view('guru.nilai.raport', [
                    'kelasDiAjar' => $kelasDiAjar,
                    'siswa' => $siswa,
                    'mapel' => collect(),
                    'dataNilai' => [],
                    'kelas' => $kelas,
                    'selectedKelasId' => $selectedKelasId,
                    'guru' => $guru,
                    'tahunAjaran' => $tahunAjaran,
                    'semester' => $semester,
                    'tahunAjaranList' => $this->getTahunAjaranList($guru->id),
                    'semesterList' => $semesterList,
                    'rataRataSiswa' => []
                ])->with('error', 'Anda belum mengajar mata pelajaran apapun di kelas ini.');
            }
            
            $dataNilai = [];
            
            $listNilai = Nilai::whereIn('siswa_id', $siswa->pluck('id'))
                ->whereIn('mapel_id', $mapel->pluck('id'))
                ->where('guru_id', $guru->id)
                ->where('kelas_id', $selectedKelasId)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('status', 'published')
                ->get();
            
            $nilaiGrouped = [];
            foreach ($listNilai as $nilai) {
                $nilaiGrouped[$nilai->siswa_id][$nilai->mapel_id] = $nilai;
            }
            
            foreach ($siswa as $s) {
                foreach ($mapel as $m) {
                    $nilai = $nilaiGrouped[$s->id][$m->id] ?? null;
                    
                    if ($nilai) {
                        $dataNilai[$s->id][$m->id] = [
                            'tugas' => $nilai->nilai_tugas_1 ?? 0,
                            'uts' => $nilai->nilai_uts ?? 0,
                            'uas' => $nilai->nilai_uas ?? 0,
                            'akhir' => $nilai->nilai_akhir ?? 0,
                            'predikat' => $nilai->predikat ?? '-',
                            'status_nilai' => $nilai->status ?? 'draft',
                        ];
                    } else {
                        $dataNilai[$s->id][$m->id] = [
                            'tugas' => '-',
                            'uts' => '-',
                            'uas' => '-',
                            'akhir' => '-',
                            'predikat' => '-',
                            'status_nilai' => 'belum_diisi',
                        ];
                    }
                }
            }
            
            $rataRataSiswa = [];
            foreach ($siswa as $s) {
                $total = 0;
                $count = 0;
                foreach ($mapel as $m) {
                    $nilai = $dataNilai[$s->id][$m->id] ?? null;
                    if ($nilai && $nilai['akhir'] !== '-' && $nilai['akhir'] > 0) {
                        $total += $nilai['akhir'];
                        $count++;
                    }
                }
                $rataRataSiswa[$s->id] = $count > 0 ? round($total / $count, 2) : 0;
            }
            
            $tahunAjaranList = $this->getTahunAjaranList($guru->id);
            
            return view('guru.nilai.raport', compact(
                'kelasDiAjar',
                'siswa',
                'mapel',
                'dataNilai',
                'kelas',
                'selectedKelasId',
                'guru',
                'tahunAjaran',
                'semester',
                'tahunAjaranList',
                'semesterList',
                'rataRataSiswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai raport: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Get detail raport per siswa
     */
    public function raportDetail($siswaId, Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            $siswa = Siswa::with(['kelas', 'user'])->findOrFail($siswaId);
            
            // 🔥 PERBAIKAN: Gunakan 'jadwal' (tanpa 's')
            $isAuthorized = Jadwal::where('guru_id', $guru->id)
                ->where('kelas_id', $siswa->kelas_id)
                ->exists();
            
            if (!$isAuthorized) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke raport siswa ini'], 403);
            }
            
            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');
            
            $nilaiSiswa = Nilai::with(['mapel'])
                ->where('siswa_id', $siswaId)
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('status', 'published')
                ->get();
            
            $rataRata = $nilaiSiswa->avg('nilai_akhir') ?? 0;
            $totalNilai = $nilaiSiswa->sum('nilai_akhir');
            $jumlahMapel = $nilaiSiswa->count();
            
            foreach ($nilaiSiswa as $n) {
                $grade = $this->getGrade($n->nilai_akhir);
                $n->grade = $grade['grade'];
                $n->grade_warna = $grade['warna'];
                $n->predikat_label = $this->getPredikat($n->nilai_akhir);
            }
            
            $predikatKeseluruhan = $this->getPredikat($rataRata);
            
            return response()->json([
                'siswa' => $siswa,
                'nilai' => $nilaiSiswa,
                'rataRata' => $rataRata,
                'totalNilai' => $totalNilai,
                'jumlahMapel' => $jumlahMapel,
                'predikatKeseluruhan' => $predikatKeseluruhan,
                'tahunAjaran' => $tahunAjaran,
                'semester' => $semester
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in raport detail: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Cetak raport per siswa (PDF)
     */
    public function raportCetak($siswaId, Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Anda tidak terdaftar sebagai guru.');
            }
            
            $siswa = Siswa::with(['kelas', 'user'])->findOrFail($siswaId);
            
            // 🔥 PERBAIKAN: Gunakan 'jadwal' (tanpa 's')
            $isAuthorized = Jadwal::where('guru_id', $guru->id)
                ->where('kelas_id', $siswa->kelas_id)
                ->exists();
            
            if (!$isAuthorized) {
                abort(403, 'Anda tidak memiliki akses ke raport siswa ini.');
            }
            
            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');
            
            $nilaiSiswa = Nilai::with(['mapel'])
                ->where('siswa_id', $siswaId)
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('status', 'published')
                ->get();
            
            $rataRata = $nilaiSiswa->avg('nilai_akhir') ?? 0;
            $predikatKeseluruhan = $this->getPredikat($rataRata);
            
            return view('guru.nilai.raport-cetak', compact(
                'siswa',
                'nilaiSiswa',
                'rataRata',
                'predikatKeseluruhan',
                'tahunAjaran',
                'semester',
                'guru'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in raport cetak: ' . $e->getMessage());
            return redirect()->route('guru.nilai.raport')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Publish nilai ke raport
     */
    public function publish(Request $request)
    {
        try {
            $request->validate([
                'kelas_id' => 'nullable|exists:kelas,id',
                'mapel_id' => 'nullable|exists:mata_pelajarans,id',
                'tahun_ajaran' => 'nullable|string',
                'semester' => 'nullable|in:ganjil,genap'
            ]);
            
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return back()->with('error', 'Anda tidak terdaftar sebagai guru.');
            }
            
            $kelasId = $request->kelas_id;
            $mapelId = $request->mapel_id;
            
            if (!$kelasId || !$mapelId) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Silakan pilih kelas dan mata pelajaran.');
            }
            
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            
            $draftCount = Nilai::where('kelas_id', $kelasId)
                            ->where('mapel_id', $mapelId)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->where('semester', $semester)
                            ->where('guru_id', $guru->id)
                            ->where('status', 'draft')
                            ->count();
            
            if ($draftCount === 0) {
                return redirect()->route('guru.nilai.index')
                               ->with('warning', 'Tidak ada nilai draft yang dipublish.');
            }
            
            $updated = Nilai::where('kelas_id', $kelasId)
                            ->where('mapel_id', $mapelId)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->where('semester', $semester)
                            ->where('guru_id', $guru->id)
                            ->where('status', 'draft')
                            ->update([
                                'status' => 'published',
                                'is_rapor' => true,
                                'updated_at' => now()
                            ]);
            
            if ($updated > 0) {
                return redirect()->route('guru.nilai.index')
                               ->with('success', $updated . ' nilai berhasil dipublish ke raport.');
            } else {
                return redirect()->route('guru.nilai.index')
                               ->with('warning', 'Tidak ada nilai yang dipublish.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error in nilai publish: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Gagal mempublish nilai: ' . $e->getMessage());
        }
    }
    
    /**
     * =============================================
     * FUNGSI PRIVATE (Helper)
     * =============================================
     */
    
    /**
     * Hitung nilai akhir dari komponen-komponen nilai
     */
    private function hitungNilaiAkhir($data)
    {
        // Kumpulkan nilai harian
        $nilaiHarian = array_filter([
            $data['nilai_harian_1'] ?? null,
            $data['nilai_harian_2'] ?? null,
            $data['nilai_harian_3'] ?? null
        ], function($v) {
            return $v !== null && $v !== '';
        });
        $rataHarian = count($nilaiHarian) > 0 ? array_sum($nilaiHarian) / count($nilaiHarian) : 0;
        
        // Kumpulkan nilai tugas
        $nilaiTugas = array_filter([
            $data['nilai_tugas_1'] ?? null,
            $data['nilai_tugas_2'] ?? null
        ], function($v) {
            return $v !== null && $v !== '';
        });
        $rataTugas = count($nilaiTugas) > 0 ? array_sum($nilaiTugas) / count($nilaiTugas) : 0;
        
        $uts = $data['nilai_uts'] ?? 0;
        $uas = $data['nilai_uas'] ?? 0;
        $praktek = $data['nilai_praktek'] ?? 0;
        
        // Bobot nilai
        $bobotHarian = 0.20;
        $bobotTugas = 0.20;
        $bobotUTS = 0.30;
        $bobotUAS = 0.30;
        $bobotPraktek = 0;
        
        if ($praktek > 0) {
            $bobotHarian = 0.15;
            $bobotTugas = 0.15;
            $bobotUTS = 0.25;
            $bobotUAS = 0.25;
            $bobotPraktek = 0.20;
            
            $nilaiAkhir = ($rataHarian * $bobotHarian) +
                          ($rataTugas * $bobotTugas) +
                          ($uts * $bobotUTS) +
                          ($uas * $bobotUAS) +
                          ($praktek * $bobotPraktek);
        } else {
            $nilaiAkhir = ($rataHarian * $bobotHarian) +
                          ($rataTugas * $bobotTugas) +
                          ($uts * $bobotUTS) +
                          ($uas * $bobotUAS);
        }
        
        return round($nilaiAkhir, 2);
    }
    
    /**
     * Dapatkan predikat dari nilai
     */
    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'Sangat Baik';
        elseif ($nilai >= 80) return 'Baik';
        elseif ($nilai >= 70) return 'Cukup';
        elseif ($nilai >= 60) return 'Kurang';
        else return 'Sangat Kurang';
    }
    
    /**
     * Dapatkan grade dan warna dari nilai
     */
    private function getGrade($nilai)
    {
        if ($nilai >= 90) return ['grade' => 'A', 'warna' => 'success'];
        elseif ($nilai >= 80) return ['grade' => 'B', 'warna' => 'primary'];
        elseif ($nilai >= 70) return ['grade' => 'C', 'warna' => 'warning'];
        elseif ($nilai >= 60) return ['grade' => 'D', 'warna' => 'danger'];
        else return ['grade' => 'E', 'warna' => 'dark'];
    }
    
    /**
     * Dapatkan daftar tahun ajaran yang tersedia untuk guru
     */
    private function getTahunAjaranList($guruId)
    {
        $list = Nilai::where('guru_id', $guruId)
                     ->distinct()
                     ->pluck('tahun_ajaran')
                     ->filter()
                     ->toArray();
        
        if (empty($list)) {
            $list = [date('Y') . '/' . (date('Y') + 1)];
        }
        
        return $list;
    }
}