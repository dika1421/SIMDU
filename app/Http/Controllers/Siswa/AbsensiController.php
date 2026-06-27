<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Get siswa data for current logged in user
     */
    private function getSiswa()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                Log::warning('No authenticated user found');
                return null;
            }
            
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                $kelas = Kelas::first();
                
                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'nis' => 'SIS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'nisn' => 'NSN' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $user->name,
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2008-01-01',
                    'alamat' => $user->alamat ?? '-',
                    'no_telepon' => $user->no_telepon ?? '-',
                    'agama' => 'Islam',
                    'kelas_id' => $kelas ? $kelas->id : null,
                    'status' => 'aktif',
                    'tahun_masuk' => date('Y')
                ]);
                
                Log::info('Auto created siswa for user: ' . $user->id);
            }
            
            return $siswa;
            
        } catch (\Exception $e) {
            Log::error('Error in getSiswa: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Display absensi siswa (dashboard)
     */
    public function index(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // PERBAIKAN: Gunakan siswa_id, bukan absensi_id
            $absensi = Absensi::where('siswa_id', $siswa->id)
                ->where('absensi_type', 'siswa')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc')
                ->get();
            
            // Hitung statistik
            $statistik = [
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alpha' => $absensi->where('status', 'alpha')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'total' => $absensi->count(),
            ];
            
            $statistik['persentase'] = $statistik['total'] > 0 
                ? round(($statistik['hadir'] / $statistik['total']) * 100, 2) 
                : 0;
            
            // Data untuk grafik per minggu
            $mingguan = $this->getKehadiranMingguan($siswa->id, $bulan, $tahun);
            
            // Data keterlambatan
            $terlambatList = $absensi->where('status', 'terlambat')
                ->map(function($item) {
                    return [
                        'tanggal' => Carbon::parse($item->tanggal)->format('d/m/Y'),
                        'hari' => Carbon::parse($item->tanggal)->translatedFormat('l'),
                        'waktu' => $item->waktu_masuk ? Carbon::parse($item->waktu_masuk)->format('H:i') : '-',
                    ];
                });
            
            // Cek absensi hari ini
            $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
                ->where('absensi_type', 'siswa')
                ->whereDate('tanggal', Carbon::today())
                ->first();
            
            // Data untuk filter
            $bulanList = $this->getBulanList();
            $tahunList = $this->getTahunList($siswa->id);
            
            return view('siswa.absensi.index', compact(
                'absensi', 'statistik', 'mingguan', 'terlambatList',
                'bulan', 'tahun', 'bulanList', 'tahunList', 'absensiHariIni', 'siswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Display riwayat absensi siswa
     */
    public function riwayat(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // PERBAIKAN: Gunakan siswa_id, bukan absensi_id
            $absensi = Absensi::where('siswa_id', $siswa->id)
                ->where('absensi_type', 'siswa')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'desc')
                ->paginate(15);
            
            // Rekap per bulan
            $rekapBulanan = [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alpha' => $absensi->where('status', 'alpha')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'total' => $absensi->total(),
                'persentase' => $absensi->total() > 0 
                    ? round(($absensi->where('status', 'hadir')->count() / $absensi->total()) * 100, 2) 
                    : 0
            ];
            
            // Data untuk filter
            $bulanList = $this->getBulanList();
            $tahunList = $this->getTahunList($siswa->id);
            
            return view('siswa.absensi.riwayat', compact(
                'absensi', 'rekapBulanan',
                'bulan', 'tahun', 'bulanList', 'tahunList', 'siswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi riwayat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Display rekap absensi
     */
    public function rekap(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // Rekap per bulan dalam setahun
            $rekapPerBulan = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $absensi = Absensi::where('siswa_id', $siswa->id)
                    ->where('absensi_type', 'siswa')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $rekapPerBulan[$bulan] = [
                    'bulan' => $this->getBulanList()[$bulan],
                    'hadir' => $absensi->where('status', 'hadir')->count(),
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'alpha' => $absensi->where('status', 'alpha')->count(),
                    'terlambat' => $absensi->where('status', 'terlambat')->count(),
                    'total' => $absensi->count(),
                ];
            }
            
            $tahunList = $this->getTahunList($siswa->id);
            
            return view('siswa.absensi.rekap', compact('rekapPerBulan', 'tahun', 'tahunList', 'siswa'));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi rekap: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export absensi ke CSV
     */
    public function export(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            $absensi = Absensi::where('siswa_id', $siswa->id)
                ->where('absensi_type', 'siswa')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc')
                ->get();
            
            $filename = "absensi_{$siswa->nis}_{$tahun}_{$bulan}.csv";
            $handle = fopen('php://temp', 'w');
            
            // Header CSV
            fputcsv($handle, ['No', 'Tanggal', 'Hari', 'Waktu Masuk', 'Waktu Keluar', 'Status', 'Keterangan']);
            
            // Data CSV
            foreach ($absensi as $index => $a) {
                fputcsv($handle, [
                    $index + 1,
                    Carbon::parse($a->tanggal)->format('d/m/Y'),
                    Carbon::parse($a->tanggal)->translatedFormat('l'),
                    $a->waktu_masuk ? Carbon::parse($a->waktu_masuk)->format('H:i') : '-',
                    $a->waktu_keluar ? Carbon::parse($a->waktu_keluar)->format('H:i') : '-',
                    ucfirst($a->status),
                    $a->keterangan ?? '-'
                ]);
            }
            
            rewind($handle);
            $csvContent = stream_get_contents($handle);
            fclose($handle);
            
            return response($csvContent, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
                
        } catch (\Exception $e) {
            Log::error('Error in absensi export: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
    
    /**
     * Get weekly attendance data for chart
     */
    private function getKehadiranMingguan($siswaId, $bulan, $tahun)
    {
        $data = [];
        $startDate = Carbon::create($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $currentWeek = $startDate->copy()->startOfWeek();
        $weekNumber = 1;
        
        while ($currentWeek <= $endDate) {
            $weekEnd = $currentWeek->copy()->endOfWeek();
            
            $absensi = Absensi::where('siswa_id', $siswaId)
                ->where('absensi_type', 'siswa')
                ->whereBetween('tanggal', [$currentWeek, $weekEnd])
                ->get();
            
            $data[] = [
                'minggu' => 'Minggu ' . $weekNumber,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alpha' => $absensi->where('status', 'alpha')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
            ];
            
            $currentWeek->addWeek();
            $weekNumber++;
        }
        
        return $data;
    }
    
    /**
     * Get list of months
     */
    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
    
    /**
     * Get list of available years for this student
     */
    private function getTahunList($siswaId)
    {
        $tahunAbsensi = Absensi::where('siswa_id', $siswaId)
            ->where('absensi_type', 'siswa')
            ->select(DB::raw('DISTINCT EXTRACT(YEAR FROM tanggal) as tahun'))
            ->pluck('tahun')
            ->toArray();
        
        $currentYear = date('Y');
        $years = range($currentYear - 2, $currentYear + 1);
        
        // Merge with existing years
        $allYears = array_unique(array_merge($years, $tahunAbsensi));
        sort($allYears);
        
        return $allYears;
    }
}