<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSholat;
use App\Models\JadwalSholat;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiSholatController extends Controller
{
    // ==================== DASHBOARD ====================
    public function index(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $role = $request->get('role', 'siswa');
            
            // Statistik
            if ($role == 'siswa') {
                $totalUsers = Siswa::where('status', 'aktif')->count();
            } else {
                $totalUsers = Guru::where('status', 'aktif')->count();
            }
            
            $tepatWaktu = AbsensiSholat::where('role', $role)->whereDate('tanggal', $tanggal)->where('status', 'tepat_waktu')->count();
            $terlambat = AbsensiSholat::where('role', $role)->whereDate('tanggal', $tanggal)->where('status', 'terlambat')->count();
            $tidakHadir = AbsensiSholat::where('role', $role)->whereDate('tanggal', $tanggal)->where('status', 'tidak_hadir')->count();
            $izin = AbsensiSholat::where('role', $role)->whereDate('tanggal', $tanggal)->where('status', 'izin')->count();
            
            $statistik = [
                'totalUsers' => $totalUsers,
                'tepatWaktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'tidakHadir' => $tidakHadir,
                'izin' => $izin,
            ];
            
            // Data absensi per sholat
            $sholatList = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            $absensi = [];
            foreach ($sholatList as $sholat) {
                $absensi[$sholat] = AbsensiSholat::where('role', $role)
                    ->whereDate('tanggal', $tanggal)
                    ->where('sholat', $sholat)
                    ->with('user')
                    ->get();
            }
            
            // Jadwal sholat
            $jadwal = JadwalSholat::where('tanggal', $tanggal)->first();
            if (!$jadwal) {
                $jadwal = (object) ['subuh' => '04:30', 'dzuhur' => '12:00', 'ashar' => '15:30', 'maghrib' => '18:00', 'isya' => '19:30'];
            }
            
            return view('administrasi.sholat.sholat', compact('tanggal', 'role', 'statistik', 'absensi', 'jadwal', 'sholatList'));
        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function dashboard(Request $request)
    {
        return $this->index($request);
    }
    
    // ==================== INPUT MANUAL SISWA ====================
    public function siswa(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $kelasId = $request->get('kelas_id');
            $search = $request->get('search');
            
            $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');
            
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $siswa = $query->get();
            
            foreach ($siswa as $s) {
                $absensiData = AbsensiSholat::where('role', 'siswa')
                    ->where('user_id', $s->id)
                    ->whereDate('tanggal', $tanggal)
                    ->get();
                
                $s->absensi = $absensiData->isEmpty() ? collect() : $absensiData->keyBy('sholat');
            }
            
            $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
            $sholatList = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            $statusList = [
                'tepat_waktu' => 'Tepat Waktu',
                'terlambat' => 'Terlambat',
                'tidak_hadir' => 'Tidak Hadir',
                'izin' => 'Izin'
            ];
            
            return view('administrasi.sholat.sholat-siswa', compact('siswa', 'kelasList', 'kelasId', 'tanggal', 'sholatList', 'statusList', 'search'));
        } catch (\Exception $e) {
            Log::error('Siswa Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // ==================== INPUT MANUAL GURU ====================
    public function guru(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $search = $request->get('search');
            
            $query = Guru::with('user')->where('status', 'aktif');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nip', 'LIKE', "%{$search}%")
                      ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $guru = $query->get();
            
            foreach ($guru as $g) {
                $absensiData = AbsensiSholat::where('role', 'guru')
                    ->where('user_id', $g->id)
                    ->whereDate('tanggal', $tanggal)
                    ->get();
                
                $g->absensi = $absensiData->isEmpty() ? collect() : $absensiData->keyBy('sholat');
            }
            
            $sholatList = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            $statusList = [
                'tepat_waktu' => 'Tepat Waktu',
                'terlambat' => 'Terlambat',
                'tidak_hadir' => 'Tidak Hadir',
                'izin' => 'Izin'
            ];
            
            return view('administrasi.sholat.sholat-guru', compact('guru', 'tanggal', 'sholatList', 'statusList', 'search'));
        } catch (\Exception $e) {
            Log::error('Guru Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // ==================== REKAP SISWA ====================
    public function rekapSiswa(Request $request)
    {
        try {
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));
            $kelasId = $request->get('kelas_id');
            $search = $request->get('search');
            
            $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');
            
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $siswa = $query->get();
            
            foreach ($siswa as $s) {
                $absensi = AbsensiSholat::where('role', 'siswa')
                    ->where('user_id', $s->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();
                    
                $s->absensi = $absensi;
                $s->total_hadir = $absensi->whereIn('status', ['tepat_waktu', 'terlambat'])->count();
                $s->total_tepat_waktu = $absensi->where('status', 'tepat_waktu')->count();
                $s->total_terlambat = $absensi->where('status', 'terlambat')->count();
                $s->total_izin = $absensi->where('status', 'izin')->count();
                $s->total_tidak_hadir = $absensi->where('status', 'tidak_hadir')->count();
                $s->persentase = $s->total_hadir > 0 ? round(($s->total_hadir / 25) * 100, 2) : 0;
            }
            
            $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
            
            return view('administrasi.sholat.rekap-sholat-siswa', compact('siswa', 'kelasList', 'kelasId', 'bulan', 'tahun', 'search'));
        } catch (\Exception $e) {
            Log::error('Rekap Siswa Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // ==================== REKAP GURU ====================
    public function rekapGuru(Request $request)
    {
        try {
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));
            $search = $request->get('search');
            
            $query = Guru::with('user')->where('status', 'aktif');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nip', 'LIKE', "%{$search}%")
                      ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $guru = $query->get();
            
            foreach ($guru as $g) {
                $absensi = AbsensiSholat::where('role', 'guru')
                    ->where('user_id', $g->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();
                    
                $g->absensi = $absensi;
                $g->total_hadir = $absensi->whereIn('status', ['tepat_waktu', 'terlambat'])->count();
                $g->total_tepat_waktu = $absensi->where('status', 'tepat_waktu')->count();
                $g->total_terlambat = $absensi->where('status', 'terlambat')->count();
                $g->total_izin = $absensi->where('status', 'izin')->count();
                $g->total_tidak_hadir = $absensi->where('status', 'tidak_hadir')->count();
                $g->persentase = $g->total_hadir > 0 ? round(($g->total_hadir / 25) * 100, 2) : 0;
            }
            
            return view('administrasi.sholat.rekap-sholat-guru', compact('guru', 'bulan', 'tahun', 'search'));
        } catch (\Exception $e) {
            Log::error('Rekap Guru Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // ==================== SCAN RFID ====================
    public function scan()
    {
        return view('administrasi.sholat.scan');
    }
    
    public function scanStore(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'user_id' => 'required|integer',
            'sholat' => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
        ]);
        
        try {
            DB::beginTransaction();
            
            $tanggal = date('Y-m-d');
            $waktuAbsen = Carbon::now();
            
            $existing = AbsensiSholat::where('role', $request->role)
                ->where('user_id', $request->user_id)
                ->where('tanggal', $tanggal)
                ->where('sholat', $request->sholat)
                ->first();
                
            if ($existing) {
                return response()->json(['success' => false, 'message' => 'Sudah absen untuk sholat ' . $request->sholat . ' hari ini'], 422);
            }
            
            $jadwal = JadwalSholat::where('tanggal', $tanggal)->first();
            $waktuJadwal = $jadwal ? $jadwal->{$request->sholat} : null;
            
            if (!$waktuJadwal) {
                $status = 'tepat_waktu';
            } else {
                $jadwalCarbon = Carbon::parse($waktuJadwal);
                $selisih = $waktuAbsen->diffInMinutes($jadwalCarbon, false);
                if ($selisih <= 0) {
                    $status = 'tepat_waktu';
                } elseif ($selisih <= 15) {
                    $status = 'terlambat';
                } else {
                    $status = 'tidak_hadir';
                }
            }
            
            AbsensiSholat::create([
                'role' => $request->role,
                'user_id' => $request->user_id,
                'tanggal' => $tanggal,
                'sholat' => $request->sholat,
                'status' => $status,
                'waktu_absen' => $waktuAbsen,
                'keterangan' => $request->keterangan,
            ]);
            
            DB::commit();
            
            $statusText = [
                'tepat_waktu' => 'Tepat Waktu',
                'terlambat' => 'Terlambat',
                'tidak_hadir' => 'Tidak Hadir'
            ];
            
            return response()->json(['success' => true, 'message' => '✅ Absensi berhasil! Status: ' . ($statusText[$status] ?? $status)]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // ==================== GET USER BY RFID CARD ====================
    public function getUserByCard(Request $request)
    {
        try {
            $cardNumber = $request->get('card_number');
            
            if (!$cardNumber) {
                return response()->json(['success' => false, 'message' => 'Nomor kartu tidak ditemukan']);
            }
            
            // Cari di tabel siswa
            $siswa = Siswa::with('user')->where('rfid_card', $cardNumber)->where('status', 'aktif')->first();
            if ($siswa) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $siswa->id,
                        'role' => 'siswa',
                        'name' => $siswa->user->name ?? $siswa->nama,
                        'nip_nis' => $siswa->nis ?? '-',
                        'kelas' => $siswa->kelas->nama_kelas ?? '-'
                    ]
                ]);
            }
            
            // Cari di tabel guru
            $guru = Guru::with('user')->where('rfid_card', $cardNumber)->where('status', 'aktif')->first();
            if ($guru) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $guru->id,
                        'role' => 'guru',
                        'name' => $guru->user->name ?? $guru->nama_lengkap,
                        'nip_nis' => $guru->nip ?? '-',
                        'kelas' => '-'
                    ]
                ]);
            }
            
            return response()->json(['success' => false, 'message' => 'Kartu tidak terdaftar di sistem']);
            
        } catch (\Exception $e) {
            Log::error('GetUserByCard Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    // ==================== STORE MANUAL ====================
    public function manualStore(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->absensi as $userId => $sholats) {
                foreach ($sholats as $sholat => $data) {
                    if (isset($data['status']) && !empty($data['status'])) {
                        AbsensiSholat::updateOrCreate(
                            [
                                'role' => $request->role,
                                'user_id' => $userId,
                                'tanggal' => $request->tanggal,
                                'sholat' => $sholat,
                            ],
                            [
                                'status' => $data['status'],
                                'waktu_absen' => isset($data['waktu_absen']) && $data['waktu_absen'] 
                                    ? Carbon::parse($request->tanggal . ' ' . $data['waktu_absen']) 
                                    : null,
                                'keterangan' => $data['keterangan'] ?? null,
                            ]
                        );
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', '✅ Absensi berhasil disimpan!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal menyimpan: ' . $e->getMessage());
        }
    }
    
    // ==================== API ====================
    public function getData(Request $request)
    {
        try {
            $role = $request->get('role', 'siswa');
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            
            if ($role == 'siswa') {
                $users = Siswa::with('user')->where('status', 'aktif')->get();
            } else {
                $users = Guru::with('user')->where('status', 'aktif')->get();
            }
            
            $absensi = AbsensiSholat::where('role', $role)
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->groupBy('user_id');
            
            $data = [];
            foreach ($users as $user) {
                $userAbsensi = $absensi->get($user->id, collect());
                $data[] = [
                    'id' => $user->id,
                    'nama' => $role == 'siswa' ? ($user->user->name ?? $user->nama) : ($user->user->name ?? $user->nama_lengkap),
                    'absensi' => $userAbsensi->map(function($item) {
                        return [
                            'sholat' => $item->sholat,
                            'status' => $item->status,
                            'waktu' => $item->waktu_absen ? date('H:i', strtotime($item->waktu_absen)) : null
                        ];
                    })->values()
                ];
            }
            
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getUsers(Request $request)
    {
        try {
            $role = $request->get('role');
            
            if ($role == 'siswa') {
                $users = Siswa::with('user')->where('status', 'aktif')->get()->map(function($siswa) {
                    return ['id' => $siswa->id, 'name' => $siswa->user->name ?? $siswa->nama];
                });
            } else {
                $users = Guru::with('user')->where('status', 'aktif')->get()->map(function($guru) {
                    return ['id' => $guru->id, 'name' => $guru->user->name ?? $guru->nama_lengkap];
                });
            }
            
            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function exportSiswa(Request $request)
    {
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
    
    public function exportGuru(Request $request)
    {
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
}