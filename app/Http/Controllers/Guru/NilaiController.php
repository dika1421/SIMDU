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
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Anda tidak terdaftar sebagai guru.');
            }

            // =============================================
            // 🔥 AMBIL KELAS: Prioritas dari jadwal, fallback ke semua kelas
            // =============================================
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->orderBy('nama_kelas')->get();

            // Fallback: kalau guru belum punya jadwal, ambil semua kelas
            if ($kelas->isEmpty()) {
                $kelas = Kelas::orderBy('nama_kelas')->get();
            }

            // =============================================
            // 🔥 AMBIL MAPEL: Prioritas dari jadwal, fallback ke semua mapel
            // =============================================
            $mapelIds = DB::table('jadwal')
                ->where('guru_id', $guru->id)
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('mata_pelajaran');

            $mapel = Mapel::whereIn('id', $mapelIds)->orderBy('nama_mapel')->get();

            // Fallback: kalau guru belum punya mapel, ambil semua mapel
            if ($mapel->isEmpty()) {
                $mapel = Mapel::orderBy('nama_mapel')->get();
            }

            // =============================================
            // 🔥 STATISTIK: Hitung dari semua nilai (draft + published)
            // =============================================
            $statistik = [];
            foreach ($kelas as $k) {
                $statistik[$k->id] = [];
                foreach ($mapel as $m) {
                    $nilai = Nilai::where('kelas_id', $k->id)
                                  ->where('mapel_id', $m->id)
                                  ->get();

                    $jumlahSiswa = $nilai->count();
                    $rataRata = $jumlahSiswa > 0 ? $nilai->avg('nilai_akhir') : 0;
                    $tertinggi = $jumlahSiswa > 0 ? $nilai->max('nilai_akhir') : 0;
                    $terendah = $jumlahSiswa > 0 ? $nilai->min('nilai_akhir') : 0;

                    $statistik[$k->id][$m->id] = [
                        'rata_rata' => round($rataRata, 2),
                        'jumlah_siswa' => $jumlahSiswa,
                        'nilai_tertinggi' => round($tertinggi, 2),
                        'nilai_terendah' => round($terendah, 2),
                        'total_nilai' => round($nilai->sum('nilai_akhir'), 2),
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
            Log::error($e->getTraceAsString());

            return view('guru.nilai.index', [
                'kelas' => collect(),
                'mapel' => collect(),
                'statistik' => [],
                'guru' => null,
                'tahunAjaranList' => [date('Y') . '/' . (date('Y') + 1)],
                'semesterList' => ['ganjil', 'genap'],
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form input nilai
     */
    public function input(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Data guru tidak ditemukan.');
            }

            // =============================================
            // 🔥 AUTO-PILIH KELAS & MAPEL
            // Prioritas: request → session → pertama dari DB
            // =============================================
            $kelasId = $request->kelas_id ?? session('nilai_kelas_id');
            $mapelId = $request->mapel_id ?? session('nilai_mapel_id');

            // Auto-pilih kelas pertama kalau belum ada
            if (!$kelasId) {
                $kelasPertama = Kelas::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->first();

                if (!$kelasPertama) {
                    $kelasPertama = Kelas::first();
                }

                $kelasId = $kelasPertama ? $kelasPertama->id : null;
            }

            // Auto-pilih mapel pertama kalau belum ada
            if (!$mapelId) {
                $mapelPertama = Mapel::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->first();

                if (!$mapelPertama) {
                    $mapelPertama = Mapel::first();
                }

                $mapelId = $mapelPertama ? $mapelPertama->id : null;
            }

            // Simpan ke session
            session(['nilai_kelas_id' => $kelasId, 'nilai_mapel_id' => $mapelId]);

            // Kalau masih kosong, redirect ke index dengan info
            if (!$kelasId || !$mapelId) {
                return redirect()->route('guru.nilai.index')
                    ->with('info', 'Belum ada data kelas atau mata pelajaran. Silakan hubungi administrator untuk menambah data.');
            }

            // Cek otorisasi: guru mengajar mapel ini di kelas ini
            $isAuthorized = Jadwal::where('guru_id', $guru->id)
                                  ->where('kelas_id', $kelasId)
                                  ->where('mata_pelajaran', $mapelId)
                                  ->exists();

            // Kalau tidak ada jadwal sama sekali, izinkan (fallback mode)
            $hasAnyJadwal = Jadwal::where('guru_id', $guru->id)->exists();

            if (!$isAuthorized && $hasAnyJadwal) {
                return redirect()->route('guru.nilai.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menginput nilai di kelas ini.');
            }

            $siswa = Siswa::where('kelas_id', $kelasId)
                          ->where('status', 'aktif')
                          ->with('user')
                          ->orderBy('nama')
                          ->get();

            if ($siswa->isEmpty()) {
                return redirect()->route('guru.nilai.index')
                    ->with('error', 'Tidak ada siswa aktif di kelas ini.');
            }

            $mataPelajaran = Mapel::find($mapelId);
            $kelas = Kelas::find($kelasId);

            if (!$mataPelajaran || !$kelas) {
                return redirect()->route('guru.nilai.index')
                    ->with('error', 'Data kelas atau mata pelajaran tidak ditemukan.');
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
            Log::error($e->getTraceAsString());

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

            $kelasId = $request->kelas_id ?? session('nilai_kelas_id');
            $mapelId = $request->mapel_id ?? session('nilai_mapel_id');

            if (!$kelasId || !$mapelId) {
                return redirect()->back()->with('error', 'Kelas dan Mata Pelajaran harus dipilih.');
            }

            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';

            DB::beginTransaction();

            $savedCount = 0;
            foreach ($request->nilai as $siswa_id => $nilaiData) {
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

                $existing = Nilai::where('siswa_id', $siswa_id)
                    ->where('mapel_id', $mapelId)
                    ->where('guru_id', $guru->id)
                    ->where('kelas_id', $kelasId)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester)
                    ->first();

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

    public function raport(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Anda tidak terdaftar sebagai guru.');
            }

            $semesterList = ['ganjil', 'genap'];

            $kelasDiAjar = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->orderBy('nama_kelas')->get();

            // Fallback: kalau tidak ada jadwal, ambil semua kelas
            if ($kelasDiAjar->isEmpty()) {
                $kelasDiAjar = Kelas::orderBy('nama_kelas')->get();
            }

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
                ])->with('info', 'Belum ada data kelas. Silakan hubungi administrator.');
            }

            $selectedKelasId = $request->input('kelas_id', $kelasDiAjar->first()->id);

            if (!$kelasDiAjar->contains('id', $selectedKelasId)) {
                $selectedKelasId = $kelasDiAjar->first()->id;
            }

            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');

            $kelas = Kelas::with(['jurusan', 'waliKelas.user'])->find($selectedKelasId);

            $siswa = Siswa::where('kelas_id', $selectedKelasId)
                ->where('status', 'aktif')
                ->with('user')
                ->orderBy('nama')
                ->get();

            $mapel = Mapel::whereHas('jadwal', function($query) use ($guru, $selectedKelasId) {
                $query->where('guru_id', $guru->id)
                      ->where('kelas_id', $selectedKelasId);
            })->get();

            // Fallback: kalau tidak ada jadwal, ambil semua mapel
            if ($mapel->isEmpty()) {
                $mapel = Mapel::orderBy('nama_mapel')->get();
            }

            if ($mapel->isEmpty() || $siswa->isEmpty()) {
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
                ]);
            }

            $dataNilai = [];

            $listNilai = Nilai::whereIn('siswa_id', $siswa->pluck('id'))
                ->whereIn('mapel_id', $mapel->pluck('id'))
                ->where('guru_id', $guru->id)
                ->where('kelas_id', $selectedKelasId)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
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

    public function raportDetail($siswaId, Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $siswa = Siswa::with(['kelas', 'user'])->findOrFail($siswaId);

            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');

            $nilaiSiswa = Nilai::with(['mapel'])
                ->where('siswa_id', $siswaId)
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
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

    public function raportCetak($siswaId, Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Anda tidak terdaftar sebagai guru.');
            }

            $siswa = Siswa::with(['kelas', 'user'])->findOrFail($siswaId);

            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $semester = $request->input('semester', 'ganjil');

            $nilaiSiswa = Nilai::with(['mapel'])
                ->where('siswa_id', $siswaId)
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
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

            $kelasId = $request->kelas_id ?? session('nilai_kelas_id');
            $mapelId = $request->mapel_id ?? session('nilai_mapel_id');

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

            return redirect()->route('guru.nilai.index')
                ->with('success', $updated . ' nilai berhasil dipublish ke raport.');

        } catch (\Exception $e) {
            Log::error('Error in nilai publish: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                ->with('error', 'Gagal mempublish nilai: ' . $e->getMessage());
        }
    }

    /**
     * =============================================
     * HELPER FUNCTIONS
     * =============================================
     */

    private function hitungNilaiAkhir($data)
    {
        $nilaiHarian = array_filter([
            $data['nilai_harian_1'] ?? null,
            $data['nilai_harian_2'] ?? null,
            $data['nilai_harian_3'] ?? null
        ], function($v) {
            return $v !== null && $v !== '';
        });
        $rataHarian = count($nilaiHarian) > 0 ? array_sum($nilaiHarian) / count($nilaiHarian) : 0;

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

        if ($praktek > 0) {
            $nilaiAkhir = ($rataHarian * 0.15) + ($rataTugas * 0.15) + ($uts * 0.25) + ($uas * 0.25) + ($praktek * 0.20);
        } else {
            $nilaiAkhir = ($rataHarian * 0.20) + ($rataTugas * 0.20) + ($uts * 0.30) + ($uas * 0.30);
        }

        return round($nilaiAkhir, 2);
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'Sangat Baik';
        elseif ($nilai >= 80) return 'Baik';
        elseif ($nilai >= 70) return 'Cukup';
        elseif ($nilai >= 60) return 'Kurang';
        else return 'Sangat Kurang';
    }

    private function getGrade($nilai)
    {
        if ($nilai >= 90) return ['grade' => 'A', 'warna' => 'success'];
        elseif ($nilai >= 80) return ['grade' => 'B', 'warna' => 'primary'];
        elseif ($nilai >= 70) return ['grade' => 'C', 'warna' => 'warning'];
        elseif ($nilai >= 60) return ['grade' => 'D', 'warna' => 'danger'];
        else return ['grade' => 'E', 'warna' => 'dark'];
    }

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