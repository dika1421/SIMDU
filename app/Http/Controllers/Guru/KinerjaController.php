<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

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

            // Statistik Nilai
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

            // Statistik Absensi
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Guru')
                ->where('absensi_id', $guru->id)
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

            // Mapel yang diajar
            $mapel = Mapel::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();

            // Grafik nilai per bulan
            $nilaiPerBulan = [];
            for ($i = 1; $i <= 12; $i++) {
                $query = Nilai::where('guru_id', $guru->id)
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', $tahun);
                
                if (Schema::hasColumn('nilai', 'status')) {
                    $query->where('status', 'published');
                }
                
                $nilaiPerBulan[] = $query->avg('nilai_akhir') ?? 0;
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
                'nilaiPerBulan'
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
                ->where('mata_pelajaran_id', $mapelId)
                ->where('tahun_ajaran', $tahun . '/' . ($tahun + 1))
                ->where('semester', $semester);
            
            if (Schema::hasColumn('nilai', 'status')) {
                $query->where('status', 'published');
            }
            
            $nilai = $query->with('siswa')->get();
            
            $rataNilai = $nilai->avg('nilai_akhir') ?? 0;
            $nilaiTertinggi = $nilai->max('nilai_akhir') ?? 0;
            $nilaiTerendah = $nilai->min('nilai_akhir') ?? 0;
            
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
                'semester'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in kinerja detail mapel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}