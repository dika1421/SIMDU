<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ManajemenGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Guru::with(['user', 'jabatan']);
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhere('nuptk', 'LIKE', "%{$search}%")
                      ->orWhere('jabatan', 'LIKE', "%{$search}%");
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $guru = $query->orderBy('nama_lengkap')->paginate(10);
            
            $totalGuru = Guru::count();
            $guruAktif = Guru::where('status', 'aktif')->count();
            $guruPNS = Guru::where('status_kepegawaian', 'pns')->count();
            $guruHonorer = Guru::where('status_kepegawaian', 'honorer')->count();
            $guruKontrak = Guru::where('status_kepegawaian', 'kontrak')->count();
            
            return view('kepala-sekolah.manajemen-guru.index', compact(
                'guru', 'totalGuru', 'guruAktif', 'guruPNS', 'guruHonorer', 'guruKontrak'
            ));
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru index: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $jabatan = Jabatan::all();
            
            if ($jabatan->isEmpty()) {
                $defaultJabatan = [
                    ['nama' => 'Guru Mapel', 'keterangan' => 'Guru Mata Pelajaran'],
                    ['nama' => 'Wali Kelas', 'keterangan' => 'Wali Kelas'],
                    ['nama' => 'Kepala Program', 'keterangan' => 'Kepala Program Keahlian'],
                    ['nama' => 'Guru BK', 'keterangan' => 'Guru Bimbingan Konseling'],
                    ['nama' => 'Staf TU', 'keterangan' => 'Staf Tata Usaha'],
                    ['nama' => 'Kepala Sekolah', 'keterangan' => 'Kepala Sekolah'],
                    ['nama' => 'Wakil Kepala Sekolah', 'keterangan' => 'Wakil Kepala Sekolah'],
                ];
                
                foreach ($defaultJabatan as $data) {
                    Jabatan::create($data);
                }
                
                $jabatan = Jabatan::all();
            }
            
            return view('kepala-sekolah.manajemen-guru.create', compact('jabatan'));
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru create: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('error', 'Gagal memuat form tambah guru: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nip' => 'required|string|max:50|unique:gurus,nip',
                'nuptk' => 'nullable|string|max:50|unique:gurus,nuptk',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_telepon' => 'nullable|string|max:20',
                'pendidikan_terakhir' => 'required|string|max:100',
                'jurusan_pendidikan' => 'required|string|max:100',
                'universitas' => 'nullable|string|max:100',
                'tahun_lulus' => 'nullable|string|max:10',
                'tmt_masuk' => 'required|date',
                'status_kepegawaian' => 'required|string|max:50',
                'golongan' => 'nullable|string|max:20',
                'mata_pelajaran_utama' => 'nullable|string|max:100',
                'keahlian_khusus' => 'nullable|string|max:100',
                'jabatan_id' => 'nullable|exists:jabatans,id',
                'status' => 'nullable|in:aktif,nonaktif',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $fotoPath = $foto->storeAs('guru/foto', $fotoName, 'public');
            }

            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'foto' => $fotoPath,
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'nuptk' => $request->nuptk,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->universitas,
                'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $request->tmt_masuk,
                'status_kepegawaian' => $request->status_kepegawaian,
                'golongan' => $request->golongan,
                'mata_pelajaran_utama' => $request->mata_pelajaran_utama,
                'keahlian_khusus' => $request->keahlian_khusus,
                'jabatan_id' => $request->jabatan_id,
                'status' => $request->status ?? 'aktif',
            ]);

            DB::commit();

            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('success', 'Guru berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in manajemen guru store: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan guru: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $guru = Guru::with(['user', 'jabatan'])->findOrFail($id);
            
            return view('kepala-sekolah.manajemen-guru.show', compact('guru'));
            
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru show: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('error', 'Data guru tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $guru = Guru::with(['user', 'jabatan'])->findOrFail($id);
            $jabatan = Jabatan::all();
            
            return view('kepala-sekolah.manajemen-guru.edit', compact('guru', 'jabatan'));
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru edit: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('error', 'Data guru tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $guru = Guru::with('user')->findOrFail($id);
            
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . ($guru->user_id ?? 0),
                'nip' => 'required|string|max:50|unique:gurus,nip,' . $id,
                'nuptk' => 'nullable|string|max:50|unique:gurus,nuptk,' . $id,
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_telepon' => 'nullable|string|max:20',
                'pendidikan_terakhir' => 'required|string|max:100',
                'jurusan_pendidikan' => 'required|string|max:100',
                'universitas' => 'nullable|string|max:100',
                'tahun_lulus' => 'nullable|string|max:10',
                'tmt_masuk' => 'required|date',
                'status_kepegawaian' => 'required|string|max:50',
                'golongan' => 'nullable|string|max:20',
                'mata_pelajaran_utama' => 'nullable|string|max:100',
                'keahlian_khusus' => 'nullable|string|max:100',
                'jabatan_id' => 'nullable|exists:jabatans,id',
                'status' => 'nullable|in:aktif,nonaktif',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            DB::beginTransaction();

            $fotoPath = $guru->user->foto ?? null;
            if ($request->hasFile('foto')) {
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $fotoPath = $foto->storeAs('guru/foto', $fotoName, 'public');
            }

            if ($guru->user) {
                $guru->user->update([
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,
                    'foto' => $fotoPath,
                ]);
            }

            $guru->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'nuptk' => $request->nuptk,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->universitas,
                'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $request->tmt_masuk,
                'status_kepegawaian' => $request->status_kepegawaian,
                'golongan' => $request->golongan,
                'mata_pelajaran_utama' => $request->mata_pelajaran_utama,
                'keahlian_khusus' => $request->keahlian_khusus,
                'jabatan_id' => $request->jabatan_id,
                'status' => $request->status ?? 'aktif',
            ]);

            DB::commit();

            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('success', 'Data guru berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in manajemen guru update: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate guru: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $guru = Guru::with('user')->findOrFail($id);
            
            DB::beginTransaction();
            
            if ($guru->user && $guru->user->foto) {
                if (Storage::disk('public')->exists($guru->user->foto)) {
                    Storage::disk('public')->delete($guru->user->foto);
                }
            }
            
            if ($guru->user) {
                $guru->user->delete();
            }
            
            $guru->delete();
            
            DB::commit();

            return redirect()->route('kepala-sekolah.manajemen-guru.index')
                ->with('success', 'Guru berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in manajemen guru destroy: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Rekap Absensi Guru - FIXED
     */
    public function absensi(Request $request)
    {
        try {
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            // Ambil semua guru aktif
            $guru = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
            
            // Jika tidak ada guru
            if ($guru->isEmpty()) {
                $bulanList = $this->getBulanList();
                $tahunList = range(date('Y') - 2, date('Y') + 1);
                
                return view('kepala-sekolah.manajemen-guru.absensi', [
                    'rekapData' => [],
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'bulanList' => $bulanList,
                    'tahunList' => $tahunList,
                    'statistik' => [
                        'total_guru' => 0,
                        'total_hadir' => 0,
                        'total_sakit' => 0,
                        'total_izin' => 0,
                        'total_alfa' => 0,
                        'total_terlambat' => 0,
                        'total_absensi' => 0,
                        'rata_persentase' => 0
                    ],
                    'error' => 'Belum ada data guru'
                ]);
            }
            
            // Proses rekap per guru - QUERY MANUAL (TANPA SCOPE)
            $rekapData = [];
            foreach ($guru as $g) {
                // Query manual untuk absensi
                $absensi = Absensi::where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $total = $absensi->count();
                
                $rekapData[] = [
                    'id' => $g->id,
                    'nama' => $g->nama_lengkap ?? $g->user->name ?? '-',
                    'nip' => $g->nip ?? '-',
                    'nuptk' => $g->nuptk ?? '-',
                    'hadir' => $hadir,
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'alfa' => $absensi->where('status', 'alfa')->count(),
                    'terlambat' => $absensi->where('status', 'terlambat')->count(),
                    'total' => $total,
                    'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                ];
            }
            
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            // Statistik ringkasan
            $statistik = [
                'total_guru' => count($rekapData),
                'total_hadir' => array_sum(array_column($rekapData, 'hadir')),
                'total_sakit' => array_sum(array_column($rekapData, 'sakit')),
                'total_izin' => array_sum(array_column($rekapData, 'izin')),
                'total_alfa' => array_sum(array_column($rekapData, 'alfa')),
                'total_terlambat' => array_sum(array_column($rekapData, 'terlambat')),
                'total_absensi' => array_sum(array_column($rekapData, 'total')),
                'rata_persentase' => count($rekapData) > 0 ? round(array_sum(array_column($rekapData, 'persentase')) / count($rekapData), 2) : 0
            ];
            
            // DEBUG: Log untuk memastikan data ada
            \Log::info('=== REKAP ABSENSI ===');
            \Log::info('Bulan: ' . $bulan . ', Tahun: ' . $tahun);
            \Log::info('Total guru: ' . $guru->count());
            \Log::info('Total rekapData: ' . count($rekapData));
            \Log::info('Total absensi: ' . $statistik['total_absensi']);
            
            return view('kepala-sekolah.manajemen-guru.absensi', compact(
                'rekapData', 'bulan', 'tahun', 'bulanList', 'tahunList', 'statistik'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru absensi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('kepala-sekolah.manajemen-guru.absensi', [
                'rekapData' => [],
                'bulan' => $request->bulan ?? date('m'),
                'tahun' => $request->tahun ?? date('Y'),
                'bulanList' => $bulanList,
                'tahunList' => $tahunList,
                'statistik' => [
                    'total_guru' => 0,
                    'total_hadir' => 0,
                    'total_sakit' => 0,
                    'total_izin' => 0,
                    'total_alfa' => 0,
                    'total_terlambat' => 0,
                    'total_absensi' => 0,
                    'rata_persentase' => 0
                ],
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Simpan absensi guru
     */
    public function storeAbsensi(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'absensi' => 'required|array',
                'absensi.*.status' => 'nullable|in:hadir,sakit,izin,alfa,terlambat',
            ]);
            
            DB::beginTransaction();
            
            $savedCount = 0;
            foreach ($request->absensi as $id => $data) {
                if (isset($data['status']) && !empty($data['status'])) {
                    $guru = Guru::find($id);
                    if (!$guru) {
                        continue;
                    }
                    
                    $existing = Absensi::where('absensi_type', 'guru')
                        ->where('guru_id', $id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();
                    
                    if ($existing) {
                        $existing->update([
                            'status' => $data['status'],
                            'keterangan' => $data['keterangan'] ?? null,
                            'waktu_masuk' => $data['status'] === 'hadir' ? ($data['waktu_masuk'] ?? now()->toTimeString()) : null,
                            'waktu_keluar' => $data['waktu_keluar'] ?? null,
                        ]);
                    } else {
                        Absensi::create([
                            'absensi_type' => 'guru',
                            'guru_id' => $id,
                            'tanggal' => $request->tanggal,
                            'status' => $data['status'],
                            'keterangan' => $data['keterangan'] ?? null,
                            'waktu_masuk' => $data['status'] === 'hadir' ? ($data['waktu_masuk'] ?? now()->toTimeString()) : null,
                            'waktu_keluar' => $data['waktu_keluar'] ?? null,
                            'diinput_oleh' => auth()->id(),
                        ]);
                    }
                    $savedCount++;
                }
            }
            
            DB::commit();
            
            if ($savedCount === 0) {
                return redirect()->back()->with('warning', 'Tidak ada data absensi yang dipilih');
            }
            
            return redirect()->back()->with('success', 'Absensi guru berhasil disimpan untuk ' . $savedCount . ' guru');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in manajemen guru storeAbsensi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Reset password guru
     */
    public function resetPassword($id)
    {
        try {
            $guru = Guru::with('user')->findOrFail($id);
            
            if (!$guru->user) {
                return back()->with('error', 'User account tidak ditemukan');
            }
            
            $guru->user->update([
                'password' => Hash::make('password123')
            ]);
            
            return back()->with('success', 'Password berhasil direset ke: password123');
            
        } catch (\Exception $e) {
            Log::error('Error in manajemen guru resetPassword: ' . $e->getMessage());
            return back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    /**
     * Get list of months
     */
    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
}