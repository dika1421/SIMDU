<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    private function getSiswa()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return null;
            }
            
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                $kelas = \App\Models\Kelas::first();
                
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
    
    public function index(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // Ambil data absensi
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswa->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc')
                ->get();
            
            // Hitung statistik
            $statistik = [
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
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
                        'waktu' => $item->waktu_masuk ? Carbon::parse($item->waktu_masuk)->format('H:i') : '-',
                    ];
                });
            
            // Data untuk filter
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('siswa.absensi.index', compact(
                'absensi', 'statistik', 'mingguan', 'terlambatList',
                'bulan', 'tahun', 'bulanList', 'tahunList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function riwayat(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // Ambil data absensi
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswa->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'desc')
                ->get();
            
            // Rekap per bulan
            $rekapBulanan = [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'total' => $absensi->count(),
                'persentase' => $absensi->count() > 0 
                    ? round(($absensi->where('status', 'hadir')->count() / $absensi->count()) * 100, 2) 
                    : 0
            ];
            
            // Data statistik per hari
            $statistikHarian = [];
            foreach ($absensi as $a) {
                $statistikHarian[] = [
                    'tanggal' => Carbon::parse($a->tanggal)->format('d/m/Y'),
                    'hari' => Carbon::parse($a->tanggal)->translatedFormat('l'),
                    'status' => $a->status,
                    'waktu_masuk' => $a->waktu_masuk ? Carbon::parse($a->waktu_masuk)->format('H:i') : '-',
                    'keterangan' => $a->keterangan ?? '-'
                ];
            }
            
            // Data untuk filter
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('siswa.absensi.riwayat', compact(
                'absensi', 'statistikHarian', 'rekapBulanan',
                'bulan', 'tahun', 'bulanList', 'tahunList', 'siswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi riwayat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    private function getKehadiranMingguan($siswaId, $bulan, $tahun)
    {
        $data = [];
        $startDate = Carbon::create($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $currentWeek = $startDate->copy()->startOfWeek();
        
        while ($currentWeek <= $endDate) {
            $weekEnd = $currentWeek->copy()->endOfWeek();
            $weekNumber = $currentWeek->weekOfMonth;
            
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswaId)
                ->whereBetween('tanggal', [$currentWeek, $weekEnd])
                ->get();
            
            $data[] = [
                'minggu' => 'Minggu ' . $weekNumber,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'alfa' => $absensi->where('status', 'alfa')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
            ];
            
            $currentWeek->addWeek();
        }
        
        return $data;
    }
    
    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
}