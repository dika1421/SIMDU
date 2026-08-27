<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrasi
     */
    public function index()
    {
        try {
            // Total Siswa
            $totalSiswa = Siswa::count();
            $siswaAktif = Siswa::where('status', 'aktif')->count();

            // Total Guru
            $totalGuru = Guru::count();
            $guruPNS = Guru::where('status_kepegawaian', 'PNS')->count();

            // Pembayaran Bulan Ini
            $bulanIni = date('m');
            $tahunIni = date('Y');
            $pembayaranBulanIni = Spp::where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->where('status', 'lunas')
                ->sum('jumlah');
            $sppBulanIni = Spp::where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->count();

            // Kehadiran Hari Ini
            $tanggalHariIni = Carbon::today();
            $hadirSiswa = Absensi::whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'hadir')
                ->count();
            $sakitSiswa = Absensi::whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'sakit')
                ->count();
            $izinSiswa = Absensi::whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'izin')
                ->count();
            $alfaSiswa = Absensi::whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'alfa')
                ->count();

            $kehadiranPersen = $totalSiswa > 0 ? round(($hadirSiswa / max($totalSiswa, 1)) * 100, 2) : 0;

            // Pembayaran Terbaru
            $pembayaranTerbaru = Spp::with(['siswa.user', 'siswa.kelas'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Jadwal Hari Ini
            $hariIni = Carbon::now()->dayOfWeek;
            $jadwalHariIni = Jadwal::with(['kelas', 'guru', 'mataPelajaran'])
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->limit(10)
                ->get();

            // Data untuk grafik distribusi kelas
            $kelasData = [];
            $kelasLabels = [];
            $kelasList = Kelas::withCount('siswa')->get();
            foreach ($kelasList as $kelas) {
                $kelasLabels[] = $kelas->nama_kelas ?? 'Kelas ' . $kelas->id;
                $kelasData[] = $kelas->siswa_count;
            }

            if (empty($kelasData)) {
                $kelasLabels = ['Belum Ada Data'];
                $kelasData = [1];
            }

            // Data untuk chart kehadiran
            $chartKehadiranData = [
                $hadirSiswa,
                $sakitSiswa,
                $izinSiswa,
                $alfaSiswa
            ];

            return view('administrasi.dashboard', compact(
                'totalSiswa',
                'siswaAktif',
                'totalGuru',
                'guruPNS',
                'pembayaranBulanIni',
                'sppBulanIni',
                'kehadiranPersen',
                'hadirSiswa',
                'sakitSiswa',
                'izinSiswa',
                'alfaSiswa',
                'pembayaranTerbaru',
                'jadwalHariIni',
                'kelasLabels',
                'kelasData',
                'chartKehadiranData'
            ));

        } catch (\Exception $e) {
            Log::error('Error in administrasi dashboard: ' . $e->getMessage());
            
            return view('administrasi.dashboard', [
                'totalSiswa' => 0,
                'siswaAktif' => 0,
                'totalGuru' => 0,
                'guruPNS' => 0,
                'pembayaranBulanIni' => 0,
                'sppBulanIni' => 0,
                'kehadiranPersen' => 0,
                'hadirSiswa' => 0,
                'sakitSiswa' => 0,
                'izinSiswa' => 0,
                'alfaSiswa' => 0,
                'pembayaranTerbaru' => collect(),
                'jadwalHariIni' => collect(),
                'kelasLabels' => ['Belum Ada Data'],
                'kelasData' => [1],
                'chartKehadiranData' => [0, 0, 0, 0],
                'error' => $e->getMessage()
            ]);
        }
    }

    // =============================================
    // METHOD UNTUK PROFIL (TAMBAHKAN INI)
    // =============================================

    /**
     * Tampilkan halaman profil
     */
    public function profil()
    {
        try {
            $user = Auth::user();
            
            // Cek apakah user ada
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            return view('administrasi.profil.index', compact('user'));
            
        } catch (\Exception $e) {
            Log::error('Error in profil: ' . $e->getMessage());
            return redirect()->route('administrasi.dashboard')
                ->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman edit profil
     */
    public function editProfil()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            return view('administrasi.profil.edit', compact('user'));
            
        } catch (\Exception $e) {
            Log::error('Error in edit profil: ' . $e->getMessage());
            return redirect()->route('administrasi.profil.index')
                ->with('error', 'Gagal memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update profil user
     */
    public function updateProfil(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'no_hp' => 'nullable|string|max:15',
                'alamat' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?? $user->no_hp,
                'alamat' => $validated['alamat'] ?? $user->alamat,
            ]);

            DB::commit();

            return redirect()
                ->route('administrasi.profil.index')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error update profil: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Ganti password
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini salah!');
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil diubah!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error change password: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}