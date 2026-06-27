<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    /**
     * Laporan Absensi
     */
    public function absensi(Request $request)
    {
        try {
            $bulan = $request->bulan ?? now()->month;
            $tahun = $request->tahun ?? now()->year;

            // ==================== REKAP ABSENSI SISWA ====================
            $rekapSiswa = Kelas::with('jurusan')
                ->withCount('siswa')
                ->get();

            foreach ($rekapSiswa as $kelas) {
                $siswaIds = $kelas->siswa->pluck('id')->toArray();
                
                if (empty($siswaIds)) {
                    $kelas->hadir = 0;
                    $kelas->sakit = 0;
                    $kelas->izin = 0;
                    $kelas->alfa = 0;
                    $kelas->terlambat = 0;
                    continue;
                }
                
                $absensi = Absensi::where('absensi_type', 'siswa')
                    ->whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();

                $kelas->hadir = $absensi->where('status', 'hadir')->count();
                $kelas->sakit = $absensi->where('status', 'sakit')->count();
                $kelas->izin = $absensi->where('status', 'izin')->count();
                $kelas->alfa = $absensi->where('status', 'alfa')->count();
                $kelas->terlambat = $absensi->where('status', 'terlambat')->count();
                $kelas->total_absensi = $absensi->count();
            }

            // ==================== REKAP ABSENSI GURU ====================
            $rekapGuru = Guru::with('user')->where('status', 'aktif')->get();
            foreach ($rekapGuru as $guru) {
                $absensi = Absensi::where('absensi_type', 'guru')
                    ->where('guru_id', $guru->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();

                $guru->hadir = $absensi->where('status', 'hadir')->count();
                $guru->sakit = $absensi->where('status', 'sakit')->count();
                $guru->izin = $absensi->where('status', 'izin')->count();
                $guru->alfa = $absensi->where('status', 'alfa')->count();
                $guru->terlambat = $absensi->where('status', 'terlambat')->count();
                $guru->total_absensi = $absensi->count();
                
                $total = $guru->hadir + $guru->sakit + $guru->izin + $guru->alfa + $guru->terlambat;
                $guru->persentase = $total > 0 ? round(($guru->hadir / $total) * 100, 2) : 0;
            }

            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $tahunList = range(date('Y') - 2, date('Y') + 1);

            $statistik = [
                'total_siswa' => Siswa::where('status', 'aktif')->count(),
                'total_guru' => Guru::where('status', 'aktif')->count(),
                'total_kelas' => Kelas::count(),
            ];

            return view('kepala-sekolah.laporan.absensi', compact(
                'rekapSiswa', 'rekapGuru', 'bulan', 'tahun', 'bulanList', 'tahunList', 'statistik'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in laporan absensi: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat laporan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Kinerja Guru
     */
    public function kinerjaGuru(Request $request)
    {
        try {
            $tahun = $request->tahun ?? now()->year;
            $semester = $request->semester ?? 'ganjil';
            $tahunAjaran = $tahun . '/' . ($tahun + 1);

            // Ambil semua guru aktif
            $guru = Guru::with(['user'])
                ->where('status', 'aktif')
                ->get();

            foreach ($guru as $g) {
                // === RATA-RATA NILAI ===
                $rataNilai = Nilai::where('guru_id', $g->id)
                    ->where('status', 'published')
                    ->avg('nilai_akhir') ?? 0;
                $g->rataNilai = round($rataNilai, 2);

                // === JUMLAH MAPEL YANG DIAJAR ===
                // Gunakan relasi mapel dari model
                try {
                    $g->jumlahMapel = $g->mapel->count() ?? 0;
                } catch (\Exception $e) {
                    // Jika relasi mapel tidak ada, hitung dari jadwal
                    $g->jumlahMapel = DB::table('jadwals')
                        ->where('guru_id', $g->id)
                        ->distinct('mapel_id')
                        ->count('mapel_id');
                }

                // === JUMLAH KELAS YANG DIAJAR ===
                $g->jumlahKelas = DB::table('jadwals')
                    ->where('guru_id', $g->id)
                    ->distinct('kelas_id')
                    ->count('kelas_id');

                // === KEHADIRAN GURU ===
                $hadir = Absensi::where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereYear('tanggal', $tahun)
                    ->where('status', 'hadir')
                    ->count();
                
                $sakit = Absensi::where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereYear('tanggal', $tahun)
                    ->where('status', 'sakit')
                    ->count();
                
                $izin = Absensi::where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereYear('tanggal', $tahun)
                    ->where('status', 'izin')
                    ->count();
                
                $totalHadir = $hadir + $sakit + $izin;
                $totalHariKerja = 240; // Asumsi hari kerja setahun
                $g->kehadiran = $totalHariKerja > 0 ? round(($totalHadir / $totalHariKerja) * 100, 2) : 0;
                $g->hadir = $hadir;
                $g->sakit = $sakit;
                $g->izin = $izin;

                // === JUMLAH SISWA YANG DIAJAR ===
                $kelasIds = DB::table('jadwals')
                    ->where('guru_id', $g->id)
                    ->distinct('kelas_id')
                    ->pluck('kelas_id');
                
                if ($kelasIds->count() > 0) {
                    $g->jumlahSiswa = Siswa::whereIn('kelas_id', $kelasIds)
                        ->where('status', 'aktif')
                        ->count();
                } else {
                    $g->jumlahSiswa = 0;
                }

                // === NILAI KINERJA ===
                $g->nilaiKinerja = round(
                    ($g->rataNilai * 0.4) + 
                    ($g->kehadiran * 0.3) + 
                    (min($g->jumlahMapel * 10, 100) * 0.3), 
                    2
                );
                
                // === PREDIKAT KINERJA ===
                if ($g->nilaiKinerja >= 85) {
                    $g->predikat = 'Sangat Baik';
                    $g->warna = 'success';
                } elseif ($g->nilaiKinerja >= 75) {
                    $g->predikat = 'Baik';
                    $g->warna = 'primary';
                } elseif ($g->nilaiKinerja >= 60) {
                    $g->predikat = 'Cukup';
                    $g->warna = 'warning';
                } else {
                    $g->predikat = 'Kurang';
                    $g->warna = 'danger';
                }
            }

            // Sort guru berdasarkan nilai kinerja (descending)
            $guru = $guru->sortByDesc('nilaiKinerja');

            $semesterList = ['ganjil', 'genap'];
            $tahunList = range(date('Y') - 2, date('Y') + 1);

            return view('kepala-sekolah.laporan.kinerja-guru', compact(
                'guru', 'tahun', 'semester', 'tahunAjaran', 'semesterList', 'tahunList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in kinerja guru: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Gagal memuat laporan kinerja guru: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Statistik Siswa
     */
    public function statistikSiswa(Request $request)
    {
        try {
            $tahun = $request->tahun ?? now()->year;
            $semester = $request->semester ?? 'ganjil';

            // Sebaran siswa per kelas
            $perKelas = Kelas::with('jurusan')
                ->withCount('siswa')
                ->get();

            // Sebaran siswa per jenis kelamin
            $perJk = Siswa::select('jenis_kelamin', DB::raw('count(*) as total'))
                ->groupBy('jenis_kelamin')
                ->get();

            // Sebaran siswa per agama
            $perAgama = collect([]);
            if (Schema::hasColumn('siswas', 'agama')) {
                $perAgama = Siswa::select('agama', DB::raw('count(*) as total'))
                    ->groupBy('agama')
                    ->get();
            }

            // Sebaran siswa per status
            $perStatus = Siswa::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();

            // Tingkat kelulusan per tahun
            $kelulusan = Siswa::select(
                    DB::raw('tahun_masuk'),
                    DB::raw('SUM(CASE WHEN status = \'lulus\' THEN 1 ELSE 0 END) as lulus'),
                    DB::raw('SUM(CASE WHEN status = \'aktif\' THEN 1 ELSE 0 END) as aktif'),
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('tahun_masuk')
                ->orderBy('tahun_masuk', 'desc')
                ->get();

            foreach ($kelulusan as $k) {
                $k->persentase = $k->total > 0 ? round(($k->lulus / $k->total) * 100, 2) : 0;
            }

            // Total statistik
            $totalSiswa = Siswa::count();
            $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
            $totalSiswaLulus = Siswa::where('status', 'lulus')->count();
            $totalSiswaPindah = Siswa::where('status', 'pindah')->count();

            $statistik = [
                'total' => $totalSiswa,
                'aktif' => $totalSiswaAktif,
                'lulus' => $totalSiswaLulus,
                'pindah' => $totalSiswaPindah,
            ];

            $semesterList = ['ganjil', 'genap'];
            $tahunList = range(date('Y') - 2, date('Y') + 1);

            return view('kepala-sekolah.laporan.statistik-siswa', compact(
                'perKelas', 'perJk', 'perAgama', 'perStatus', 'kelulusan', 
                'statistik', 'tahun', 'semester', 'semesterList', 'tahunList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in statistik siswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat statistik siswa: ' . $e->getMessage());
        }
    }
    
    /**
     * Export laporan absensi ke Excel
     */
    public function exportAbsensi(Request $request)
    {
        try {
            $bulan = $request->bulan ?? now()->month;
            $tahun = $request->tahun ?? now()->year;
            
            // Logika export bisa ditambahkan di sini
            return redirect()->back()->with('info', 'Fitur export laporan sedang dalam pengembangan');
            
        } catch (\Exception $e) {
            Log::error('Error in export absensi: ' . $e->getMessage());
            return back()->with('error', 'Gagal export laporan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export laporan kinerja guru ke Excel
     */
    public function exportKinerja(Request $request)
    {
        try {
            $tahun = $request->tahun ?? now()->year;
            $semester = $request->semester ?? 'ganjil';
            
            // Logika export bisa ditambahkan di sini
            return redirect()->back()->with('info', 'Fitur export laporan sedang dalam pengembangan');
            
        } catch (\Exception $e) {
            Log::error('Error in export kinerja: ' . $e->getMessage());
            return back()->with('error', 'Gagal export laporan: ' . $e->getMessage());
        }
    }
}