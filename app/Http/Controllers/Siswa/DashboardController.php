<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KalenderAkademik;
use App\Models\Absensi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Dashboard Siswa
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Ambil data siswa
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            // Jika siswa tidak ada, buat otomatis
            if (!$siswa) {
                $siswa = $this->autoCreateSiswa($user);
            }
            
            // Data yang akan dikirim ke view
            $data = $this->getDashboardData($siswa);
            
            return view('siswa.dashboard', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in siswa dashboard: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('siswa.dashboard', $this->getEmptyDashboardData($e->getMessage()));
        }
    }

    /**
     * Auto create siswa jika belum ada
     */
    private function autoCreateSiswa($user)
    {
        try {
            $kelas = Kelas::first();
            
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
                'nama' => $user->name,
                'nama_lengkap' => $user->name,
                'kelas_id' => $kelas->id,
                'status' => 'aktif',
                'tahun_masuk' => date('Y'),
            ]);
            
            Log::info('Auto created siswa for user: ' . $user->id);
            
            return $siswa;
            
        } catch (\Exception $e) {
            Log::error('Auto create siswa failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all dashboard data
     */
    private function getDashboardData($siswa)
    {
        // 1. Nilai
        $nilaiData = $this->getNilaiData($siswa);
        
        // 2. Absensi
        $absensiData = $this->getAbsensiData($siswa);
        
        // 3. Events
        $eventsMendatang = $this->getEvents();
        
        // 4. Peringkat
        $peringkat = $this->hitungPeringkat($siswa);
        
        return [
            'siswa' => $siswa,
            'nilaiTerbaru' => $nilaiData['nilaiTerbaru'],
            'rataNilai' => $nilaiData['rataNilai'],
            'absensiHariIni' => $absensiData['absensiHariIni'],
            'statistikAbsensi' => $absensiData['statistikAbsensi'],
            'persentaseKehadiran' => $absensiData['persentaseKehadiran'],
            'eventsMendatang' => $eventsMendatang,
            'peringkat' => $peringkat,
        ];
    }

    /**
     * Get nilai data
     */
    private function getNilaiData($siswa)
    {
        try {
            $nilaiTerbaru = Nilai::with(['mataPelajaran'])
                ->where('siswa_id', $siswa->id)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $rataNilai = Nilai::where('siswa_id', $siswa->id)
                ->where('status', 'published')
                ->avg('nilai_akhir') ?? 0;
            
            return [
                'nilaiTerbaru' => $nilaiTerbaru,
                'rataNilai' => $rataNilai,
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting nilai data: ' . $e->getMessage());
            return [
                'nilaiTerbaru' => collect(),
                'rataNilai' => 0,
            ];
        }
    }

    /**
     * Get absensi data
     */
    private function getAbsensiData($siswa)
    {
        try {
            // Absensi hari ini
            $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
                ->where('absensi_type', 'siswa')
                ->whereDate('tanggal', Carbon::today())
                ->first();
            
            // Statistik absensi bulan ini
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
                'terlambat' => $absensiBulanIni->where('status', 'terlambat')->count(),
            ];
            
            $totalAbsensi = array_sum($statistikAbsensi);
            $persentaseKehadiran = $totalAbsensi > 0 
                ? round(($statistikAbsensi['hadir'] / $totalAbsensi) * 100, 2) 
                : 0;
            
            return [
                'absensiHariIni' => $absensiHariIni,
                'statistikAbsensi' => $statistikAbsensi,
                'persentaseKehadiran' => $persentaseKehadiran,
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting absensi data: ' . $e->getMessage());
            return [
                'absensiHariIni' => null,
                'statistikAbsensi' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'terlambat' => 0],
                'persentaseKehadiran' => 0,
            ];
        }
    }

    /**
     * Get events
     */
    private function getEvents()
    {
        try {
            return KalenderAkademik::where('status', 'aktif')
                ->where('tanggal_mulai', '>=', Carbon::today())
                ->orderBy('tanggal_mulai', 'asc')
                ->take(5)
                ->get();
                
        } catch (\Exception $e) {
            Log::error('Error getting events: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Hitung peringkat siswa
     */
    private function hitungPeringkat($siswa)
    {
        try {
            if (!$siswa || !$siswa->kelas_id) {
                return '-';
            }

            $siswaKelas = Siswa::where('kelas_id', $siswa->kelas_id)->get();
            
            if ($siswaKelas->isEmpty()) {
                return '-';
            }

            $peringkatSiswa = [];
            foreach ($siswaKelas as $s) {
                $rata = Nilai::where('siswa_id', $s->id)
                            ->where('status', 'published')
                            ->avg('nilai_akhir') ?? 0;
                $peringkatSiswa[] = [
                    'siswa_id' => $s->id,
                    'rata' => $rata
                ];
            }

            usort($peringkatSiswa, function($a, $b) {
                return $b['rata'] <=> $a['rata'];
            });

            $posisi = 1;
            foreach ($peringkatSiswa as $index => $data) {
                if ($data['siswa_id'] == $siswa->id) {
                    $posisi = $index + 1;
                    break;
                }
            }

            return $posisi;
            
        } catch (\Exception $e) {
            Log::error('Error hitung peringkat: ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get empty dashboard data
     */
    private function getEmptyDashboardData($errorMessage = null)
    {
        return [
            'siswa' => null,
            'nilaiTerbaru' => collect(),
            'rataNilai' => 0,
            'absensiHariIni' => null,
            'statistikAbsensi' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'terlambat' => 0],
            'persentaseKehadiran' => 0,
            'eventsMendatang' => collect(),
            'peringkat' => '-',
            'error' => $errorMessage,
        ];
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
                'jenis_kelamin' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable|string',
                'tanggal_lahir' => 'nullable|date',
                'agama' => 'nullable|string',
            ]);

            // Update user
            $user->update([
                'name' => $request->nama_lengkap
            ]);

            // Update siswa
            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nama' => $request->nama_lengkap,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
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

            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }

            $user->update([
                'password' => Hash::make($request->password_baru)
            ]);

            return redirect()->back()->with('success', 'Password berhasil diubah');

        } catch (\Exception $e) {
            Log::error('Error change password siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}