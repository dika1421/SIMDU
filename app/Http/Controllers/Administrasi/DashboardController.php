<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Absensi;
use App\Models\Jadwal;
// use App\Models\Nilai; // 🔥 COMMENT DULU JIKA ERROR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
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

            // 🔥 Data untuk grafik distribusi kelas
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
}