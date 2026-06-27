<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KalenderAkademik;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// PERBAIKAN: Import model Absensi dengan namespace yang benar
use App\Models\Absensi;

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
            
            // Nilai terbaru (dengan pengecekan model Nilai)
            $nilaiTerbaru = collect();
            $rataNilai = 0;
            
            if (class_exists('App\Models\Nilai')) {
                $nilaiTerbaru = Nilai::with(['mataPelajaran'])
                    ->where('siswa_id', $siswa->id)
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                
                $rataNilai = Nilai::where('siswa_id', $siswa->id)
                    ->where('status', 'published')
                    ->avg('nilai_akhir') ?? 0;
            }
            
            // PERBAIKAN: Query absensi yang benar sesuai struktur tabel
            $absensiHariIni = null;
            $absensiBulanIni = collect();
            $statistikAbsensi = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
            ];
            $persentaseKehadiran = 0;
            
            // Cek apakah model Absensi ada
            if (class_exists('App\Models\Absensi')) {
                // Absensi hari ini - PERBAIKAN: menggunakan siswa_id, bukan absensi_id
                $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
                    ->where('absensi_type', 'siswa')
                    ->whereDate('tanggal', Carbon::today())
                    ->first();
                
                // Statistik absensi bulan ini - PERBAIKAN: menggunakan siswa_id
                $absensiBulanIni = Absensi::where('siswa_id', $siswa->id)
                    ->where('absensi_type', 'siswa')
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->get();
                
                $statistikAbsensi = [
                    'hadir' => $absensiBulanIni->where('status', 'hadir')->count(),
                    'sakit' => $absensiBulanIni->where('status', 'sakit')->count(),
                    'izin' => $absensiBulanIni->where('status', 'izin')->count(),
                    'alpha' => $absensiBulanIni->where('status', 'alpha')->count(),
                ];
                
                $totalAbsensi = array_sum($statistikAbsensi);
                $persentaseKehadiran = $totalAbsensi > 0 
                    ? round(($statistikAbsensi['hadir'] / $totalAbsensi) * 100, 2) 
                    : 0;
            } else {
                Log::warning('Model Absensi tidak ditemukan');
            }
            
            // Event mendatang
            $eventsMendatang = collect();
            if (class_exists('App\Models\KalenderAkademik')) {
                $eventsMendatang = KalenderAkademik::where('status', 'aktif')
                    ->where('tanggal_mulai', '>=', Carbon::today())
                    ->orderBy('tanggal_mulai', 'asc')
                    ->take(5)
                    ->get();
            }
            
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
            
            // Return view dengan data kosong
            return view('siswa.dashboard', [
                'siswa' => null,
                'nilaiTerbaru' => collect(),
                'rataNilai' => 0,
                'absensiHariIni' => null,
                'statistikAbsensi' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0],
                'persentaseKehadiran' => 0,
                'eventsMendatang' => collect(),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get profil siswa
     */
    public function profil()
    {
        try {
            $user = auth()->user();
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            return view('siswa.profil', compact('siswa', 'user'));
            
        } catch (\Exception $e) {
            Log::error('Error in siswa profil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat profil');
        }
    }
    
    /**
     * Update profil siswa
     */
    public function updateProfil(Request $request)
    {
        try {
            $user = auth()->user();
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'no_telepon' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
            ]);
            
            // Update user name
            $user->update([
                'name' => $request->nama_lengkap
            ]);
            
            // Update siswa
            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
            ]);
            
            return redirect()->back()->with('success', 'Profil berhasil diupdate');
            
        } catch (\Exception $e) {
            Log::error('Error update profil siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update profil: ' . $e->getMessage());
        }
    }
    
    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password_lama' => 'required',
                'password_baru' => 'required|min:6|confirmed',
            ]);
            
            $user = auth()->user();
            
            if (!\Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }
            
            $user->update([
                'password' => \Hash::make($request->password_baru)
            ]);
            
            return redirect()->back()->with('success', 'Password berhasil diubah');
            
        } catch (\Exception $e) {
            Log::error('Error change password siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}