<?php
// app/Http/Controllers/Guru/DashboardController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display guru dashboard
     */
    public function index()
    {
        try {
            // Ambil data guru yang login
            $user = Auth::user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                // Jika guru tidak ditemukan, coba cari berdasarkan user_id di tabel guru
                $guru = Guru::first(); // Ambil guru pertama sebagai fallback
            }

            // Total Kelas yang diajar
            $totalKelas = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->count();
            
            // Total Siswa dari semua kelas yang diajar
            $kelasIds = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->pluck('kelas_id');
            $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->count();
            $siswaPerKelas = $totalKelas > 0 ? round($totalSiswa / $totalKelas) : 0;

            // Total Mata Pelajaran yang diajar - PERBAIKAN: gunakan mata_pelajaran_id
            $totalMapel = Jadwal::where('guru_id', $guru->id)->distinct('mata_pelajaran_id')->count();

            // Rata-rata Nilai
            $rataNilai = Nilai::where('guru_id', $guru->id)
                ->where('status', 'published')
                ->avg('nilai_akhir') ?? 0;

            // Jadwal Hari Ini
            $hariIni = Carbon::now()->dayOfWeek;
            $jadwalHariIni = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get();

            // Absensi Hari Ini (untuk semua siswa yang diajar)
            $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');
            $tanggalHariIni = Carbon::today();

            // PERBAIKAN: Gunakan 'siswa_id' langsung, bukan 'absensi_type' dan 'absensi_id'
            $hadirHariIni = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'hadir')
                ->count();

            $sakitHariIni = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'sakit')
                ->count();

            $izinHariIni = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'izin')
                ->count();

            $alfaHariIni = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggalHariIni)
                ->where('status', 'alfa')
                ->count();

            $totalAbsensiHariIni = $hadirHariIni + $sakitHariIni + $izinHariIni + $alfaHariIni;
            $persentaseKehadiran = $totalAbsensiHariIni > 0 
                ? round(($hadirHariIni / $totalAbsensiHariIni) * 100, 2) 
                : 0;

            // Data untuk grafik nilai per kelas
            $chartLabels = [];
            $chartData = [];
            
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
            foreach ($kelasList as $kelas) {
                $chartLabels[] = $kelas->nama_kelas ?? 'Kelas ' . $kelas->id;
                $rataNilaiKelas = Nilai::where('guru_id', $guru->id)
                    ->whereHas('siswa', function($q) use ($kelas) {
                        $q->where('kelas_id', $kelas->id);
                    })
                    ->where('status', 'published')
                    ->avg('nilai_akhir') ?? 0;
                $chartData[] = round($rataNilaiKelas, 2);
            }

            // Jika tidak ada data chart, set default
            if (empty($chartLabels)) {
                $chartLabels = ['Belum Ada Data'];
                $chartData = [0];
            }

            // Statistik tambahan
            $kelasBulanIni = Jadwal::where('guru_id', $guru->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->distinct('kelas_id')
                ->count();

            $nilaiTerinput = Nilai::where('guru_id', $guru->id)
                ->where('status', 'published')
                ->count();
            
            $totalNilai = Nilai::where('guru_id', $guru->id)->count();

            // Prestasi terbaru (contoh data)
            $prestasiTerbaru = collect();

            return view('guru.dashboard', compact(
                'guru',
                'totalKelas',
                'totalSiswa',
                'siswaPerKelas',
                'totalMapel',
                'rataNilai',
                'jadwalHariIni',
                'hadirHariIni',
                'sakitHariIni',
                'izinHariIni',
                'alfaHariIni',
                'persentaseKehadiran',
                'chartLabels',
                'chartData',
                'kelasBulanIni',
                'nilaiTerinput',
                'totalNilai',
                'prestasiTerbaru'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Jika error, kirim data default
            return view('guru.dashboard', [
                'guru' => null,
                'totalKelas' => 0,
                'totalSiswa' => 0,
                'siswaPerKelas' => 0,
                'totalMapel' => 0,
                'rataNilai' => 0,
                'jadwalHariIni' => collect(),
                'hadirHariIni' => 0,
                'sakitHariIni' => 0,
                'izinHariIni' => 0,
                'alfaHariIni' => 0,
                'persentaseKehadiran' => 0,
                'chartLabels' => ['Belum Ada Data'],
                'chartData' => [0],
                'kelasBulanIni' => 0,
                'nilaiTerinput' => 0,
                'totalNilai' => 0,
                'prestasiTerbaru' => collect(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * =============================================
     * FUNGSI PROFIL GURU
     * =============================================
     */

    /**
     * Display profil guru
     */
    public function profil()
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->with('user')->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }

            // Data statistik untuk profil
            $kelasIds = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->pluck('kelas_id');
            $totalKelas = $kelasIds->count();
            $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->count();
            // PERBAIKAN: gunakan mata_pelajaran_id
            $totalMapel = Jadwal::where('guru_id', $guru->id)->distinct('mata_pelajaran_id')->count();
            
            return view('guru.profil.index', compact(
                'guru', 
                'user',
                'totalKelas',
                'totalSiswa',
                'totalMapel'
            ));
        } catch (\Exception $e) {
            Log::error('Profil Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Edit profil guru
     */
    public function editProfil()
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->with('user')->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            return view('guru.profil.edit', compact('guru', 'user'));
        } catch (\Exception $e) {
            Log::error('Edit Profil Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update profil guru
     */
    public function updateProfil(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nomor_hp' => 'nullable|string|max:15',
                'alamat' => 'nullable|string|max:500',
                'jenis_kelamin' => 'nullable|in:L,P',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update guru
            $guruData = [
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($guru->foto && file_exists(storage_path('app/public/' . $guru->foto))) {
                    unlink(storage_path('app/public/' . $guru->foto));
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/guru', $filename, 'public');
                $guruData['foto'] = $path;
            }

            $guru->update($guruData);

            return redirect()->route('guru.profil.index')
                ->with('success', '✅ Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Update Profil Error: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $user = auth()->user();

            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', '❌ Password saat ini salah!');
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('guru.profil.index')
                ->with('success', '✅ Password berhasil diubah!');

        } catch (\Exception $e) {
            Log::error('Change Password Error: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * =============================================
     * FUNGSI TAMBAHAN LAINNYA
     * =============================================
     */

    /**
     * Get calendar events for guru
     */
    public function getEvents(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return response()->json([]);
            }

            $events = [];

            // Ambil jadwal mengajar sebagai events
            $jadwal = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->get();

            foreach ($jadwal as $j) {
                // Mapping hari ke tanggal
                $hariMap = [
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                    7 => 'Sunday'
                ];
                
                $startDate = Carbon::now()->startOfWeek();
                $dayOfWeek = $hariMap[$j->hari] ?? 'Monday';
                $startDate->next($dayOfWeek);
                
                $events[] = [
                    'id' => 'jadwal_' . $j->id,
                    'title' => $j->mapel->nama_mapel . ' - ' . $j->kelas->nama_kelas,
                    'start' => $startDate->format('Y-m-d') . 'T' . $j->jam_mulai,
                    'end' => $startDate->format('Y-m-d') . 'T' . $j->jam_selesai,
                    'color' => '#3498db',
                    'allDay' => false,
                    'extendedProps' => [
                        'kelas' => $j->kelas->nama_kelas,
                        'mapel' => $j->mapel->nama_mapel,
                        'ruangan' => $j->ruangan ?? '-'
                    ]
                ];
            }

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('Get Events Error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get statistik data for dashboard (AJAX)
     */
    public function getStatistik(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return response()->json(['error' => 'Guru tidak ditemukan'], 404);
            }

            $kelasIds = Jadwal::where('guru_id', $guru->id)->distinct('kelas_id')->pluck('kelas_id');
            $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');

            // Statistik absensi bulan ini
            $bulanIni = Carbon::now()->month;
            $tahunIni = Carbon::now()->year;

            $absensiBulanIni = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->get();

            $statistik = [
                'hadir' => $absensiBulanIni->where('status', 'hadir')->count(),
                'sakit' => $absensiBulanIni->where('status', 'sakit')->count(),
                'izin' => $absensiBulanIni->where('status', 'izin')->count(),
                'alfa' => $absensiBulanIni->where('status', 'alfa')->count(),
                'total' => $absensiBulanIni->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $statistik
            ]);

        } catch (\Exception $e) {
            Log::error('Get Statistik Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}