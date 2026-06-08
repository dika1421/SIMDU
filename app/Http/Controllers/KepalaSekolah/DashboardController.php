<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Prestasi;
use App\Models\Keuangan;
use App\Models\Pengajuan;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\KalenderAkademik;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STATISTIK SISWA ====================
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::where('status', 'aktif')->count();
        $pertumbuhanSiswa = $this->getPertumbuhan('siswa');
        
        // ==================== STATISTIK GURU ====================
        $totalGuru = Guru::count();
        $guruAktif = Guru::where('status', 'aktif')->count();
        $guruPNS = Guru::where('status_kepegawaian', 'pns')->count();
        $guruHonorer = Guru::where('status_kepegawaian', 'honorer')->count();
        $pertumbuhanGuru = $this->getPertumbuhan('guru');
        
        // ==================== KEHADIRAN HARI INI ====================
        $today = Carbon::now()->toDateString();
        
        // PERBAIKAN: Menggunakan siswa_id dan DB::table untuk menghindari polymorphic
        $hadirSiswa = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->where('absensi_type', 'siswa')
            ->where('status', 'hadir')
            ->count();
        
        $sakitSiswa = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->where('absensi_type', 'siswa')
            ->where('status', 'sakit')
            ->count();
        
        $izinSiswa = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->where('absensi_type', 'siswa')
            ->where('status', 'izin')
            ->count();
        
        $alfaSiswa = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->where('absensi_type', 'siswa')
            ->where('status', 'alfa')
            ->count();
        
        $totalKehadiranHariIni = $hadirSiswa + $sakitSiswa + $izinSiswa + $alfaSiswa;
        $kehadiranHariIni = $totalSiswa > 0 ? round(($hadirSiswa / max($totalSiswa, 1)) * 100, 2) : 0;
        
        // ==================== KEHADIRAN BULAN INI ====================
        $hadirBulanIni = DB::table('absensi')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('absensi_type', 'siswa')
            ->where('status', 'hadir')
            ->count();
            
        $sakitBulanIni = DB::table('absensi')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('absensi_type', 'siswa')
            ->where('status', 'sakit')
            ->count();
            
        $izinBulanIni = DB::table('absensi')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('absensi_type', 'siswa')
            ->where('status', 'izin')
            ->count();
            
        $alfaBulanIni = DB::table('absensi')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('absensi_type', 'siswa')
            ->where('status', 'alfa')
            ->count();
        
        // ==================== KEUANGAN ====================
        $pemasukanBulanIni = Keuangan::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('tipe', 'pemasukan')
            ->sum('jumlah') ?? 0;
        
        $pengeluaranBulanIni = Keuangan::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->where('tipe', 'pengeluaran')
            ->sum('jumlah') ?? 0;
        
        $totalKeuangan = $pemasukanBulanIni - $pengeluaranBulanIni;
        $pertumbuhanKeuangan = $this->getPertumbuhanKeuangan();
        
        // ==================== PRESTASI ====================
        $prestasiTahunIni = Prestasi::where('tahun', Carbon::now()->year)->count();
        $prestasiBulanIni = Prestasi::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $prestasiBulanLalu = Prestasi::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Prestasi Terbaru
        $prestasiTerbaru = Prestasi::with('siswa.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($p) {
                $p->nama_siswa = $p->siswa->user->name ?? $p->siswa->nama_lengkap ?? '-';
                return $p;
            });
        
        // ==================== INFORMASI SEKOLAH ====================
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        $tahunAjaranAktif = $tahunAjaranAktif ? $tahunAjaranAktif->nama : '-';
        
        // ==================== STATISTIK PER KELAS ====================
        $statistikKelas = Kelas::with(['jurusan', 'waliKelas', 'siswa'])
            ->withCount('siswa')
            ->get()
            ->map(function($kelas) {
                $rataNilai = Nilai::where('kelas_id', $kelas->id)
                    ->where('status', 'published')
                    ->avg('nilai_akhir') ?? 0;
                
                // PERBAIKAN: Menggunakan siswa_id langsung
                $siswaIds = $kelas->siswa->pluck('id')->toArray();
                
                $totalAbsensi = DB::table('absensi')
                    ->where('absensi_type', 'siswa')
                    ->whereIn('siswa_id', $siswaIds)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->count();
                
                $hadirAbsensi = DB::table('absensi')
                    ->where('absensi_type', 'siswa')
                    ->whereIn('siswa_id', $siswaIds)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->where('status', 'hadir')
                    ->count();
                
                $kelas->rata_nilai = round($rataNilai, 2);
                $kelas->persentase_kehadiran = $totalAbsensi > 0 ? round(($hadirAbsensi / $totalAbsensi) * 100, 2) : 0;
                $kelas->jumlah_siswa = $kelas->siswa_count;
                
                return $kelas;
            });
        
        // ==================== SEBARAN SISWA PER KELAS ====================
        $siswaPerKelas = Siswa::select('kelas_id', DB::raw('count(*) as total'))
            ->where('status', 'aktif')
            ->whereNotNull('kelas_id')
            ->groupBy('kelas_id')
            ->with('kelas')
            ->get();
        
        // ==================== PENGAJUAN MENUNGGU ====================
        $pengajuanMenunggu = Pengajuan::with('pengaju')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // ==================== AKTIVITAS TERBARU ====================
        $aktivitasTerbaru = $this->getAktivitasTerbaru();
        
        // ==================== AGENDA MENDATANG ====================
        $agendaMendatang = KalenderAkademik::where('tanggal_mulai', '>=', Carbon::now())
            ->where('status', 'aktif')
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get()
            ->map(function($agenda) {
                $warnaMap = [
                    'libur' => 'warning',
                    'ujian' => 'danger',
                    'acara' => 'success',
                    'rapat' => 'primary',
                    'pendaftaran' => 'info',
                    'lainnya' => 'secondary'
                ];
                $agenda->warna = $warnaMap[$agenda->jenis] ?? 'primary';
                $agenda->warna_badge = $warnaMap[$agenda->jenis] ?? 'primary';
                return $agenda;
            });
        
        // ==================== GRAFIK PERKEMBANGAN ====================
        $chartSiswaLabels = [];
        $chartSiswaData = [];
        $chartGuruLabels = [];
        $chartGuruData = [];
        $chartKeuanganLabels = [];
        $chartKeuanganData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $chartSiswaLabels[] = $bulan->translatedFormat('M');
            $chartGuruLabels[] = $bulan->translatedFormat('M');
            $chartKeuanganLabels[] = $bulan->translatedFormat('M');
            
            $chartSiswaData[] = Siswa::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            
            $chartGuruData[] = Guru::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            
            $chartKeuanganData[] = Keuangan::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->where('tipe', 'pemasukan')
                ->sum('jumlah') / 1000000 ?? 0;
        }
        
        // ==================== GRAFIK KEHADIRAN MINGGUAN ====================
        $kehadiranMingguan = $this->getKehadiranMingguan();
        
        // ==================== NOTIFIKASI ====================
        $notifikasi = Pengajuan::where('status', 'pending')->count();
        $kegiatanHariIni = KalenderAkademik::whereDate('tanggal_mulai', Carbon::now()->toDateString())
            ->where('status', 'aktif')
            ->count();
        $persetujuanMenunggu = $notifikasi;
        
        return view('kepala-sekolah.dashboard', compact(
            'totalSiswa',
            'siswaAktif',
            'pertumbuhanSiswa',
            'totalGuru',
            'guruAktif',
            'guruPNS',
            'guruHonorer',
            'pertumbuhanGuru',
            'hadirSiswa',
            'sakitSiswa',
            'izinSiswa',
            'alfaSiswa',
            'kehadiranHariIni',
            'totalKehadiranHariIni',
            'hadirBulanIni',
            'sakitBulanIni',
            'izinBulanIni',
            'alfaBulanIni',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'totalKeuangan',
            'pertumbuhanKeuangan',
            'prestasiTahunIni',
            'prestasiBulanIni',
            'prestasiBulanLalu',
            'prestasiTerbaru',
            'totalKelas',
            'totalJurusan',
            'tahunAjaranAktif',
            'statistikKelas',
            'siswaPerKelas',
            'pengajuanMenunggu',
            'aktivitasTerbaru',
            'agendaMendatang',
            'kehadiranMingguan',
            'chartSiswaLabels',
            'chartSiswaData',
            'chartGuruLabels',
            'chartGuruData',
            'chartKeuanganLabels',
            'chartKeuanganData',
            'notifikasi',
            'kegiatanHariIni',
            'persetujuanMenunggu'
        ));
    }
    
    /**
     * Hitung pertumbuhan data bulanan
     */
    private function getPertumbuhan($type)
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $bulanLalu = Carbon::now()->subMonth()->month;
        $tahunLalu = Carbon::now()->subMonth()->year;
        
        if ($type == 'siswa') {
            $totalBulanIni = Siswa::whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $bulanIni)
                ->count();
            $totalBulanLalu = Siswa::whereYear('created_at', $tahunLalu)
                ->whereMonth('created_at', $bulanLalu)
                ->count();
        } elseif ($type == 'guru') {
            $totalBulanIni = Guru::whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $bulanIni)
                ->count();
            $totalBulanLalu = Guru::whereYear('created_at', $tahunLalu)
                ->whereMonth('created_at', $bulanLalu)
                ->count();
        } else {
            return 0;
        }
        
        if ($totalBulanLalu > 0) {
            return round((($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100, 1);
        }
        
        return $totalBulanIni > 0 ? 100 : 0;
    }
    
    /**
     * Hitung pertumbuhan keuangan
     */
    private function getPertumbuhanKeuangan()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $bulanLalu = Carbon::now()->subMonth()->month;
        $tahunLalu = Carbon::now()->subMonth()->year;
        
        $totalBulanIni = Keuangan::whereYear('tanggal', $tahunIni)
            ->whereMonth('tanggal', $bulanIni)
            ->where('tipe', 'pemasukan')
            ->sum('jumlah') ?? 0;
            
        $totalBulanLalu = Keuangan::whereYear('tanggal', $tahunLalu)
            ->whereMonth('tanggal', $bulanLalu)
            ->where('tipe', 'pemasukan')
            ->sum('jumlah') ?? 0;
        
        if ($totalBulanLalu > 0) {
            return round((($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100, 1);
        }
        
        return $totalBulanIni > 0 ? 100 : 0;
    }
    
    /**
     * Get aktivitas terbaru
     */
    private function getAktivitasTerbaru()
    {
        $aktivitas = collect();
        
        $siswaBaru = Siswa::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($s) {
                return (object)[
                    'deskripsi' => "Siswa baru: {$s->user->name} ({$s->nis})",
                    'created_at' => $s->created_at,
                    'icon' => 'user-plus',
                    'warna' => 'success'
                ];
            });
        
        $guruBaru = Guru::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($g) {
                return (object)[
                    'deskripsi' => "Guru baru: {$g->user->name} ({$g->nip})",
                    'created_at' => $g->created_at,
                    'icon' => 'chalkboard-user',
                    'warna' => 'primary'
                ];
            });
        
        $pengajuanBaru = Pengajuan::with('pengaju')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($p) {
                return (object)[
                    'deskripsi' => "Pengajuan baru dari: {$p->pengaju->name} - {$p->judul}",
                    'created_at' => $p->created_at,
                    'icon' => 'file-alt',
                    'warna' => 'warning'
                ];
            });
        
        $aktivitas = $siswaBaru->concat($guruBaru)->concat($pengajuanBaru);
        $aktivitas = $aktivitas->sortByDesc('created_at')->take(10);
        
        return $aktivitas;
    }
    
    /**
     * Get data kehadiran mingguan
     */
    private function getKehadiranMingguan()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data[] = [
                'tanggal' => $date->toDateString(),
                'hari' => $date->translatedFormat('l'),
                'siswa' => DB::table('absensi')
                    ->whereDate('tanggal', $date->toDateString())
                    ->where('absensi_type', 'siswa')
                    ->where('status', 'hadir')
                    ->count(),
                'guru' => DB::table('absensi')
                    ->whereDate('tanggal', $date->toDateString())
                    ->where('absensi_type', 'guru')
                    ->where('status', 'hadir')
                    ->count(),
            ];
        }
        return $data;
    }
}