<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\KalenderAkademik;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            // CEK: Pastikan user tidak null
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // CEK: Ambil data siswa, jika tidak ada buat otomatis
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                // Auto create data siswa
                $kelas = Kelas::first();
                
                // Jika belum ada kelas, buat kelas default
                if (!$kelas) {
                    $kelas = Kelas::create([
                        'nama' => 'X IPA 1',
                        'tingkat' => 'X',
                        'kapasitas' => 32
                    ]);
                    Log::info('Auto created default kelas');
                }
                
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
                    'kelas_id' => $kelas->id,
                    'status' => 'aktif',
                    'tahun_masuk' => date('Y')
                ]);
                
                Log::info('Auto created siswa for user: ' . $user->id);
            }
            
            // Nilai terbaru
            $nilaiTerbaru = Nilai::with(['mataPelajaran'])
                ->where('siswa_id', $siswa->id)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $rataNilai = Nilai::where('siswa_id', $siswa->id)
                ->where('status', 'published')
                ->avg('nilai_akhir') ?? 0;
            
            // Absensi hari ini
            $absensiHariIni = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswa->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();
            
            // Statistik absensi bulan ini
            $absensiBulanIni = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswa->id)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->get();
            
            $statistikAbsensi = [
                'hadir' => $absensiBulanIni->where('status', 'hadir')->count(),
                'sakit' => $absensiBulanIni->where('status', 'sakit')->count(),
                'izin' => $absensiBulanIni->where('status', 'izin')->count(),
                'alfa' => $absensiBulanIni->where('status', 'alfa')->count(),
            ];
            
            $totalAbsensi = array_sum($statistikAbsensi);
            $persentaseKehadiran = $totalAbsensi > 0 
                ? round(($statistikAbsensi['hadir'] / $totalAbsensi) * 100, 2) 
                : 0;
            
            // Event mendatang
            $eventsMendatang = KalenderAkademik::where('status', 'aktif')
                ->where('tanggal_mulai', '>=', Carbon::today())
                ->orderBy('tanggal_mulai', 'asc')
                ->take(5)
                ->get();
            
            return view('siswa.dashboard', compact(
                'siswa', 
                'nilaiTerbaru', 
                'rataNilai', 
                'absensiHariIni',
                'statistikAbsensi', 
                'persentaseKehadiran', 
                'eventsMendatang'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in siswa dashboard: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}