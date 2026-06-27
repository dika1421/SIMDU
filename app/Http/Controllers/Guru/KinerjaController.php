<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KinerjaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                Log::warning('Guru not found for user: ' . $user->id);
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru. Hubungi administrator.');
            }
            
            $tahun = $request->tahun ?? now()->year;
            $semester = $request->semester ?? 'ganjil';

            // =============================================
            // 1. STATISTIK NILAI
            // =============================================
            $nilaiQuery = Nilai::where('guru_id', $guru->id);
            
            // Cek apakah kolom status ada
            if (Schema::hasColumn('nilai', 'status')) {
                $nilaiQuery->where('status', 'published');
            }
            
            $rataNilai = $nilaiQuery->avg('nilai_akhir') ?? 0;
            $totalNilai = $nilaiQuery->count();
            
            // Distribusi nilai
            $nilaiPublished = Nilai::where('guru_id', $guru->id);
            
            if (Schema::hasColumn('nilai', 'status')) {
                $nilaiPublished = $nilaiPublished->where('status', 'published');
            }
            
            $distribusiNilai = [
                'A' => (clone $nilaiPublished)->where('nilai_akhir', '>=', 85)->count(),
                'B' => (clone $nilaiPublished)->whereBetween('nilai_akhir', [75, 84])->count(),
                'C' => (clone $nilaiPublished)->whereBetween('nilai_akhir', [60, 74])->count(),
                'D' => (clone $nilaiPublished)->whereBetween('nilai_akhir', [40, 59])->count(),
                'E' => (clone $nilaiPublished)->where('nilai_akhir', '<', 40)->count(),
            ];

            // =============================================
            // 2. STATISTIK ABSENSI - PERBAIKAN
            // =============================================
            // PERBAIKAN: Gunakan 'guru_id' langsung, bukan 'absensi_type' dan 'absensi_id'
            $absensi = Absensi::where('guru_id', $guru->id)
                ->whereYear('tanggal', $tahun)
                ->get();

            $statistikAbsensi = [
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
            ];

            $totalHari = $statistikAbsensi['hadir'] + $statistikAbsensi['sakit'] + 
                         $statistikAbsensi['izin'] + $statistikAbsensi['alfa'];
            $persenHadir = $totalHari > 0 ? round(($statistikAbsensi['hadir'] / $totalHari) * 100, 2) : 0;

            // =============================================
            // 3. ABSENSI PER BULAN (untuk chart)
            // =============================================
            $absensiPerBulan = [];
            $bulanList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            for ($i = 1; $i <= 12; $i++) {
                $absensiBulan = Absensi::where('guru_id', $guru->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $i)
                    ->get();
                
                $absensiPerBulan[] = [
                    'bulan' => $bulanList[$i - 1],
                    'hadir' => $absensiBulan->where('status', 'hadir')->count(),
                    'sakit' => $absensiBulan->where('status', 'sakit')->count(),
                    'izin' => $absensiBulan->where('status', 'izin')->count(),
                    'alfa' => $absensiBulan->where('status', 'alfa')->count(),
                    'total' => $absensiBulan->count(),
                ];
            }

            // =============================================
            // 4. STATISTIK MENGAJAR
            // =============================================
            $totalKelas = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->count();
            $totalMapel = Jadwal::where('guru_id', $guru->id)->distinct('mata_pelajaran_id')->count();
            
            // Total siswa dari semua kelas yang diajar
            $kelasIds = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->pluck('kelas_id');
            $totalSiswa = \App\Models\Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->count();

            // =============================================
            // 5. MAPEL YANG DIAJAR
            // =============================================
            $mapel = Mapel::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();

            // =============================================
            // 6. NILAI PER BULAN (untuk chart)
            // =============================================
            $nilaiPerBulan = [];
            for ($i = 1; $i <= 12; $i++) {
                $query = Nilai::where('guru_id', $guru->id)
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', $tahun);
                
                if (Schema::hasColumn('nilai', 'status')) {
                    $query->where('status', 'published');
                }
                
                $nilaiPerBulan[] = round($query->avg('nilai_akhir') ?? 0, 2);
            }

            // =============================================
            // 7. ABSENSI HARI INI
            // =============================================
            $hariIni = Carbon::today();
            $absensiHariIni = Absensi::where('guru_id', $guru->id)
                ->whereDate('tanggal', $hariIni)
                ->first();

            $statusHariIni = $absensiHariIni ? $absensiHariIni->status : 'belum_absen';
            $jamMasuk = $absensiHariIni ? $absensiHariIni->jam_masuk : null;
            $jamKeluar = $absensiHariIni ? $absensiHariIni->jam_keluar : null;

            // =============================================
            // 8. CHART 7 HARI TERAKHIR
            // =============================================
            $chartLabels = [];
            $chartDataHadir = [];
            $chartDataSakit = [];
            $chartDataIzin = [];
            $chartDataAlpha = [];

            for ($i = 6; $i >= 0; $i--) {
                $tanggal = Carbon::today()->subDays($i);
                $chartLabels[] = $tanggal->translatedFormat('d M');
                
                $absensiHari = Absensi::where('guru_id', $guru->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();
                
                if ($absensiHari) {
                    $chartDataHadir[] = $absensiHari->status == 'hadir' ? 1 : 0;
                    $chartDataSakit[] = $absensiHari->status == 'sakit' ? 1 : 0;
                    $chartDataIzin[] = $absensiHari->status == 'izin' ? 1 : 0;
                    $chartDataAlpha[] = $absensiHari->status == 'alfa' ? 1 : 0;
                } else {
                    $chartDataHadir[] = 0;
                    $chartDataSakit[] = 0;
                    $chartDataIzin[] = 0;
                    $chartDataAlpha[] = 0;
                }
            }

            return view('guru.kinerja.index', compact(
                'guru',
                'tahun',
                'semester',
                'rataNilai',
                'totalNilai',
                'distribusiNilai',
                'statistikAbsensi',
                'persenHadir',
                'mapel',
                'nilaiPerBulan',
                'totalKelas',
                'totalMapel',
                'totalSiswa',
                'absensiPerBulan',
                'statusHariIni',
                'jamMasuk',
                'jamKeluar',
                'chartLabels',
                'chartDataHadir',
                'chartDataSakit',
                'chartDataIzin',
                'chartDataAlpha'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in kinerja index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function detailMapel($mapelId, Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            $mapel = Mapel::findOrFail($mapelId);
            $tahun = $request->tahun ?? now()->year;
            $semester = $request->semester ?? 'ganjil';
            
            // Ambil nilai untuk mapel tertentu
            $query = Nilai::where('guru_id', $guru->id)
                ->where('mata_pelajaran_id', $mapelId);
            
            // Filter tahun ajaran jika ada kolom
            if (Schema::hasColumn('nilai', 'tahun_ajaran')) {
                $query->where('tahun_ajaran', $tahun . '/' . ($tahun + 1));
            }
            
            if (Schema::hasColumn('nilai', 'semester')) {
                $query->where('semester', $semester);
            }
            
            if (Schema::hasColumn('nilai', 'status')) {
                $query->where('status', 'published');
            }
            
            $nilai = $query->with('siswa')->get();
            
            $rataNilai = $nilai->avg('nilai_akhir') ?? 0;
            $nilaiTertinggi = $nilai->max('nilai_akhir') ?? 0;
            $nilaiTerendah = $nilai->min('nilai_akhir') ?? 0;
            $totalSiswa = $nilai->count();
            
            $distribusi = [
                'A' => $nilai->where('nilai_akhir', '>=', 85)->count(),
                'B' => $nilai->whereBetween('nilai_akhir', [75, 84])->count(),
                'C' => $nilai->whereBetween('nilai_akhir', [60, 74])->count(),
                'D' => $nilai->whereBetween('nilai_akhir', [40, 59])->count(),
                'E' => $nilai->where('nilai_akhir', '<', 40)->count(),
            ];
            
            return view('guru.kinerja.detail-mapel', compact(
                'mapel',
                'nilai',
                'rataNilai',
                'nilaiTertinggi',
                'nilaiTerendah',
                'distribusi',
                'tahun',
                'semester',
                'totalSiswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in kinerja detail mapel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export kinerja ke PDF/Excel
     */
    public function export(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            $tahun = $request->tahun ?? now()->year;
            
            // Data untuk export
            $data = [
                'guru' => $guru,
                'tahun' => $tahun,
                'rataNilai' => Nilai::where('guru_id', $guru->id)->avg('nilai_akhir') ?? 0,
                'totalNilai' => Nilai::where('guru_id', $guru->id)->count(),
                'totalHadir' => Absensi::where('guru_id', $guru->id)->where('status', 'hadir')->count(),
                'totalSakit' => Absensi::where('guru_id', $guru->id)->where('status', 'sakit')->count(),
                'totalIzin' => Absensi::where('guru_id', $guru->id)->where('status', 'izin')->count(),
                'totalAlpha' => Absensi::where('guru_id', $guru->id)->where('status', 'alfa')->count(),
            ];
            
            // TODO: Implement export ke PDF/Excel
            return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
            
        } catch (\Exception $e) {
            Log::error('Error in kinerja export: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}