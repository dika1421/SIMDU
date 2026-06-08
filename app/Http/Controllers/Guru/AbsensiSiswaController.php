<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Jadwal;  // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiSiswaController extends Controller
{
    /**
     * Display dashboard absensi siswa
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru. Hubungi administrator.');
            }

            // Ambil kelas yang diajar oleh guru (gunakan relasi jadwal)
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->orderBy('nama')->get();

            // Jika tidak ada kelas, ambil semua kelas
            if ($kelas->isEmpty()) {
                $kelas = Kelas::orderBy('nama')->get();
            }

            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $kelasId = $request->get('kelas_id');
            $mataPelajaranId = $request->get('mata_pelajaran_id');
            $search = $request->get('search');

            // Ambil mata pelajaran yang diajar oleh guru di kelas tertentu
            $mataPelajaranList = collect();
            if ($kelasId) {
                $mataPelajaranList = DB::table('mata_pelajarans as mp')
                    ->select('mp.id', 'mp.nama_mapel as nama')
                    ->join('jadwals as j', 'mp.id', '=', 'j.mata_pelajaran_id')
                    ->where('j.guru_id', $guru->id)
                    ->where('j.kelas_id', $kelasId)
                    ->whereNull('mp.deleted_at')
                    ->whereNull('j.deleted_at')
                    ->distinct()
                    ->orderBy('mp.nama_mapel', 'asc')
                    ->get();
            }

            $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');

            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'LIKE', "%{$search}%")
                      ->orWhere('nisn', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            $siswa = $query->get();

            // Ambil absensi untuk tanggal ini berdasarkan mata pelajaran
            foreach ($siswa as $s) {
                $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                    ->where('absensi_id', $s->id)
                    ->whereDate('tanggal', $tanggal)
                    ->when($mataPelajaranId, function($q) use ($mataPelajaranId) {
                        $q->where('mata_pelajaran_id', $mataPelajaranId);
                    })
                    ->first();
                
                $s->status_absensi = $absensi ? $absensi->status : null;
                $s->waktu_absensi = $absensi ? $absensi->waktu_masuk : null;
                $s->keterangan_absensi = $absensi ? $absensi->keterangan : null;
            }

            // Statistik
            $totalSiswa = $siswa->count();
            $hadir = $siswa->where('status_absensi', 'hadir')->count();
            $sakit = $siswa->where('status_absensi', 'sakit')->count();
            $izin = $siswa->where('status_absensi', 'izin')->count();
            $alfa = $siswa->where('status_absensi', 'alfa')->count();
            $terlambat = $siswa->where('status_absensi', 'terlambat')->count();
            $belumAbsen = $totalSiswa - ($hadir + $sakit + $izin + $alfa + $terlambat);

            $statusList = [
                'hadir' => 'Hadir',
                'sakit' => 'Sakit',
                'izin' => 'Izin',
                'alfa' => 'Alfa',
                'terlambat' => 'Terlambat'
            ];

            return view('guru.absensi-siswa.index', compact('siswa', 'kelas', 'kelasId', 'tanggal', 'statusList', 'search', 
                'totalSiswa', 'hadir', 'sakit', 'izin', 'alfa', 'terlambat', 'belumAbsen', 'mataPelajaranList', 'mataPelajaranId'));
                
        } catch (\Exception $e) {
            Log::error('Absensi Index Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store manual absensi
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'nullable|exists:kelas,id',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajarans,id',
            'absensi' => 'required|array',
        ]);
        
        try {
            DB::beginTransaction();
            
            $savedCount = 0;
            foreach ($request->absensi as $siswaId => $data) {
                if (isset($data['status']) && !empty($data['status'])) {
                    Absensi::updateOrCreate(
                        [
                            'absensi_type' => 'App\\Models\\Siswa',
                            'absensi_id' => $siswaId,
                            'tanggal' => $request->tanggal,
                            'mata_pelajaran_id' => $request->mata_pelajaran_id,
                        ],
                        [
                            'status' => $data['status'],
                            'waktu_masuk' => isset($data['waktu_absen']) && $data['waktu_absen'] 
                                ? Carbon::parse($request->tanggal . ' ' . $data['waktu_absen']) 
                                : Carbon::now(),
                            'keterangan' => $data['keterangan'] ?? null,
                            'diinput_oleh' => auth()->id(),
                        ]
                    );
                    $savedCount++;
                }
            }
            
            DB::commit();
            
            if ($savedCount === 0) {
                return redirect()->back()->with('warning', 'Tidak ada data absensi yang disimpan.');
            }
            
            return redirect()->back()->with('success', "✅ {$savedCount} absensi berhasil disimpan!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Absensi Error: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Scan RFID
     */
    public function scan(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $mataPelajaranId = $request->get('mata_pelajaran_id');
        return view('guru.absensi-siswa.scan', compact('kelasId', 'mataPelajaranId'));
    }

    /**
     * Get siswa by RFID card number
     */
    public function getSiswaByCard(Request $request)
    {
        try {
            $cardNumber = $request->get('card_number');
            
            if (!$cardNumber) {
                return response()->json(['success' => false, 'message' => 'Nomor kartu tidak ditemukan']);
            }
            
            $siswa = Siswa::with(['user', 'kelas'])
                ->where('rfid_card', $cardNumber)
                ->where('status', 'aktif')
                ->first();
                
            if ($siswa) {
                $mataPelajaranId = $request->get('mata_pelajaran_id');
                
                $sudahAbsen = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                    ->where('absensi_id', $siswa->id)
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->when($mataPelajaranId, function($q) use ($mataPelajaranId) {
                        $q->where('mata_pelajaran_id', $mataPelajaranId);
                    })
                    ->exists();
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $siswa->id,
                        'nis' => $siswa->nis,
                        'nama' => $siswa->user->name ?? $siswa->nis,
                        'kelas' => $siswa->kelas->nama ?? '-',
                        'sudah_absen' => $sudahAbsen
                    ]
                ]);
            }
            
            return response()->json(['success' => false, 'message' => 'Kartu tidak terdaftar untuk siswa']);
            
        } catch (\Exception $e) {
            Log::error('Get Siswa By Card Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process scan RFID absensi
     */
    public function scanStore(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|integer|exists:siswa,id',
            'status' => 'required|in:hadir,sakit,izin,alfa,terlambat',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajarans,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            $tanggal = date('Y-m-d');
            $waktuAbsen = Carbon::now();
            $mataPelajaranId = $request->get('mata_pelajaran_id');
            
            $siswa = Siswa::with('user')->find($request->siswa_id);
            if (!$siswa) {
                return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
            }
            
            $existing = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $request->siswa_id)
                ->whereDate('tanggal', $tanggal)
                ->when($mataPelajaranId, function($q) use ($mataPelajaranId) {
                    $q->where('mata_pelajaran_id', $mataPelajaranId);
                })
                ->first();
                
            if ($existing) {
                $existing->update([
                    'status' => $request->status,
                    'waktu_masuk' => $waktuAbsen,
                    'keterangan' => $request->keterangan,
                    'diinput_oleh' => auth()->id(),
                ]);
                $message = '✅ Absensi berhasil diupdate!';
                $isUpdate = true;
            } else {
                Absensi::create([
                    'absensi_type' => 'App\\Models\\Siswa',
                    'absensi_id' => $request->siswa_id,
                    'tanggal' => $tanggal,
                    'status' => $request->status,
                    'waktu_masuk' => $waktuAbsen,
                    'keterangan' => $request->keterangan,
                    'mata_pelajaran_id' => $mataPelajaranId,
                    'diinput_oleh' => auth()->id(),
                ]);
                $message = '✅ Absensi berhasil disimpan!';
                $isUpdate = false;
            }
            
            DB::commit();
            
            $statusText = [
                'hadir' => 'Hadir',
                'sakit' => 'Sakit',
                'izin' => 'Izin',
                'alfa' => 'Alfa',
                'terlambat' => 'Terlambat'
            ];
            
            return response()->json([
                'success' => true, 
                'message' => $message,
                'data' => [
                    'nama' => $siswa->user->name ?? $siswa->nis,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas->nama ?? '-',
                    'status' => $statusText[$request->status] ?? $request->status,
                    'waktu' => $waktuAbsen->format('H:i:s'),
                    'is_update' => $isUpdate
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Scan Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get mata pelajaran by kelas untuk dropdown (AJAX)
     */
    public function getMataPelajaranByKelas(Request $request)
    {
        try {
            $kelasId = $request->get('kelas_id');
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Guru tidak ditemukan',
                    'data' => []
                ], 404);
            }
            
            // Gunakan query builder langsung
            $mataPelajaran = DB::table('mata_pelajarans as mp')
                ->select('mp.id', 'mp.nama_mapel as nama')
                ->join('jadwals as j', 'mp.id', '=', 'j.mata_pelajaran_id')
                ->where('j.guru_id', $guru->id)
                ->where('j.kelas_id', $kelasId)
                ->whereNull('mp.deleted_at')
                ->whereNull('j.deleted_at')
                ->distinct()
                ->orderBy('mp.nama_mapel', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $mataPelajaran,
                'count' => $mataPelajaran->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Get Mata Pelajaran Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Riwayat absensi siswa
     */
    public function riwayat(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru. Hubungi administrator.');
            }

            // Ambil kelas yang diajar oleh guru (gunakan relasi jadwal)
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->orderBy('nama')->get();

            // Jika tidak ada kelas, ambil semua kelas
            if ($kelas->isEmpty()) {
                $kelas = Kelas::orderBy('nama')->get();
            }

            $kelasId = $request->get('kelas_id');
            $mataPelajaranId = $request->get('mata_pelajaran_id');
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));
            $search = $request->get('search');

            // Ambil mata pelajaran yang diajar oleh guru
            $mataPelajaranList = DB::table('mata_pelajarans as mp')
                ->select('mp.id', 'mp.nama_mapel as nama')
                ->join('jadwals as j', 'mp.id', '=', 'j.mata_pelajaran_id')
                ->where('j.guru_id', $guru->id)
                ->when($kelasId, function($q) use ($kelasId) {
                    $q->where('j.kelas_id', $kelasId);
                })
                ->whereNull('mp.deleted_at')
                ->whereNull('j.deleted_at')
                ->distinct()
                ->orderBy('mp.nama_mapel', 'asc')
                ->get();

            // Query siswa
            $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');
            
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'LIKE', "%{$search}%")
                      ->orWhere('nisn', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $siswa = $query->get();
            
            // Hitung statistik per siswa berdasarkan mata pelajaran
            $statistikSiswa = [];
            foreach ($siswa as $s) {
                $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                    ->where('absensi_id', $s->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->when($mataPelajaranId, function($q) use ($mataPelajaranId) {
                        $q->where('mata_pelajaran_id', $mataPelajaranId);
                    })
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $sakit = $absensi->where('status', 'sakit')->count();
                $izin = $absensi->where('status', 'izin')->count();
                $alfa = $absensi->where('status', 'alfa')->count();
                $terlambat = $absensi->where('status', 'terlambat')->count();
                $total = $absensi->count();
                $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
                
                $statistikSiswa[$s->id] = [
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alfa' => $alfa,
                    'terlambat' => $terlambat,
                    'total' => $total,
                    'persentase' => $persentase
                ];
            }
            
            // Data untuk dropdown bulan
            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            // Data untuk dropdown tahun
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('guru.absensi-siswa.riwayat', compact('kelas', 'siswa', 'statistikSiswa', 
                'kelasId', 'mataPelajaranId', 'mataPelajaranList', 'bulan', 'tahun', 'bulanList', 'tahunList', 'search'));
            
        } catch (\Exception $e) {
            Log::error('Riwayat Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan absensi per kelas
     */
    public function laporan(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru. Hubungi administrator.');
            }

            // Ambil kelas yang diajar oleh guru (gunakan relasi jadwal)
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->orderBy('nama')->get();

            // Jika tidak ada jadwal, ambil semua kelas
            if ($kelas->isEmpty()) {
                $kelas = Kelas::orderBy('nama')->get();
            }

            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));
            $kelasId = $request->get('kelas_id');
            $mataPelajaranId = $request->get('mata_pelajaran_id');
            
            $mataPelajaranList = DB::table('mata_pelajarans as mp')
                ->select('mp.id', 'mp.nama_mapel as nama')
                ->join('jadwals as j', 'mp.id', '=', 'j.mata_pelajaran_id')
                ->where('j.guru_id', $guru->id)
                ->when($kelasId, function($q) use ($kelasId) {
                    $q->where('j.kelas_id', $kelasId);
                })
                ->whereNull('mp.deleted_at')
                ->whereNull('j.deleted_at')
                ->distinct()
                ->orderBy('mp.nama_mapel', 'asc')
                ->get();
            
            $siswa = collect();
            $statistik = [];
            $rekapKelas = [
                'total_hadir' => 0,
                'total_sakit' => 0,
                'total_izin' => 0,
                'total_alfa' => 0,
                'total_terlambat' => 0,
                'total_siswa' => 0
            ];
            
            if ($kelasId) {
                $siswa = Siswa::with(['user', 'kelas'])
                    ->where('kelas_id', $kelasId)
                    ->where('status', 'aktif')
                    ->get();
                
                $rekapKelas['total_siswa'] = $siswa->count();
                
                foreach ($siswa as $s) {
                    $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                        ->where('absensi_id', $s->id)
                        ->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan)
                        ->when($mataPelajaranId, function($q) use ($mataPelajaranId) {
                            $q->where('mata_pelajaran_id', $mataPelajaranId);
                        })
                        ->get();
                    
                    $hadir = $absensi->where('status', 'hadir')->count();
                    $sakit = $absensi->where('status', 'sakit')->count();
                    $izin = $absensi->where('status', 'izin')->count();
                    $alfa = $absensi->where('status', 'alfa')->count();
                    $terlambat = $absensi->where('status', 'terlambat')->count();
                    $total = $absensi->count();
                    
                    $rekapKelas['total_hadir'] += $hadir;
                    $rekapKelas['total_sakit'] += $sakit;
                    $rekapKelas['total_izin'] += $izin;
                    $rekapKelas['total_alfa'] += $alfa;
                    $rekapKelas['total_terlambat'] += $terlambat;
                    
                    $statistik[$s->id] = [
                        'siswa' => $s,
                        'hadir' => $hadir,
                        'sakit' => $sakit,
                        'izin' => $izin,
                        'alfa' => $alfa,
                        'terlambat' => $terlambat,
                        'total' => $total,
                        'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                    ];
                }
            }
            
            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('guru.absensi-siswa.laporan', compact('kelas', 'statistik', 'rekapKelas', 
                'bulan', 'tahun', 'kelasId', 'mataPelajaranId', 'mataPelajaranList', 'bulanList', 'tahunList'));
            
        } catch (\Exception $e) {
            Log::error('Laporan Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Rekap absensi per kelas
     */
    public function rekap(Request $request)
    {
        return $this->laporan($request);
    }

    /**
     * Export absensi ke Excel
     */
    public function export(Request $request)
    {
        try {
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));
            $kelasId = $request->get('kelas_id');
            $mataPelajaranId = $request->get('mata_pelajaran_id');
            
            // TODO: Implement export to Excel
            return back()->with('info', 'Fitur export sedang dalam pengembangan');
            
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengexport laporan');
        }
    }
}