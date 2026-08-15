<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'kelasData'
            ));

        } catch (\Exception $e) {
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
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * =============================================
     * METHOD PROFIL (DITAMBAHKAN)
     * =============================================
     */

    /**
     * Tampilkan halaman profil
     */
    public function profil()
    {
        $user = Auth::user();
        return view('administrasi.profil.index', compact('user'));
    }

    /**
     * Tampilkan halaman edit profil
     */
    public function editProfil()
    {
        $user = Auth::user();
        return view('administrasi.profil.edit', compact('user'));
    }

    /**
     * Update profil user
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()
            ->route('administrasi.profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Ganti password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}