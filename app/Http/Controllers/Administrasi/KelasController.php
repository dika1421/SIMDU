<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Kelas::with(['waliKelas.user', 'jurusan', 'siswa']);
            
            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter berdasarkan tingkat
            if ($request->filled('tingkat')) {
                $query->where('tingkat', $request->tingkat);
            }
            
            // Filter berdasarkan jurusan
            if ($request->filled('jurusan_id')) {
                $query->where('jurusan_id', $request->jurusan_id);
            }
            
            // Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('kode_kelas', 'LIKE', "%{$search}%")
                      ->orWhere('tingkat', 'LIKE', "%{$search}%");
                });
            }
            
            $kelas = $query->orderBy('tingkat')
                ->orderBy('nama')
                ->paginate(10);
            
            // Data untuk filter
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            $jurusanList = Jurusan::orderBy('nama')->get(); // Menggunakan kolom 'nama'
            
            return view('administrasi.kelas.index', compact('kelas', 'statusList', 'tingkatList', 'jurusanList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasIndex: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $guru = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
            
            $jurusanList = Jurusan::orderBy('nama')->get();
            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            
            return view('administrasi.kelas.create', compact('guru', 'jurusanList', 'tahunAjaran', 'tingkatList', 'statusList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasCreate: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama',
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();
            
            // Cek apakah nama kelas sudah ada
            $exists = Kelas::where('nama', $request->nama_kelas)->exists();
            if ($exists) {
                throw new \Exception('Nama kelas sudah terdaftar!');
            }
            
            // Generate kode kelas jika kosong
            $kodeKelas = $request->kode_kelas;
            if (empty($kodeKelas)) {
                $tingkat = $request->tingkat;
                $jurusan = Jurusan::find($request->jurusan_id);
                $kodeJurusan = $jurusan ? $jurusan->kode_jurusan : 'UMUM';
                
                $lastKelas = Kelas::where('tingkat', $tingkat)
                    ->where('jurusan_id', $request->jurusan_id)
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($lastKelas && preg_match('/(\d+)$/', $lastKelas->kode_kelas, $matches)) {
                    $nextNumber = (int)$matches[1] + 1;
                    $kodeKelas = $tingkat . '-' . $kodeJurusan . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                } else {
                    $kodeKelas = $tingkat . '-' . $kodeJurusan . '-01';
                }
            }
            
            $kelas = Kelas::create([
                'nama' => $request->nama_kelas,
                'kode_kelas' => $kodeKelas,
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'wali_kelas_id' => $request->wali_kelas_id,
                'kapasitas' => $request->kapasitas ?? 36,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'tahun_ajaran' => $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1),
            ]);
            
            DB::commit();
            
            return redirect()->route('administrasi.kelas.index')
                ->with('success', '✅ Kelas ' . $kelas->nama . ' berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in kelasStore: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal menyimpan kelas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $kelas = Kelas::with(['waliKelas.user', 'jurusan', 'siswa.user'])->findOrFail($id);
            
            // Statistik siswa
            $totalSiswa = $kelas->siswa->count();
            $siswaLaki = $kelas->siswa->where('jenis_kelamin', 'L')->count();
            $siswaPerempuan = $kelas->siswa->where('jenis_kelamin', 'P')->count();
            $siswaAktif = $kelas->siswa->where('status', 'aktif')->count();
            
            return view('administrasi.kelas.show', compact('kelas', 'totalSiswa', 'siswaLaki', 'siswaPerempuan', 'siswaAktif'));
        } catch (\Exception $e) {
            Log::error('Error in kelasShow: ' . $e->getMessage());
            return back()->with('error', 'Data kelas tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $kelas = Kelas::with(['siswa', 'waliKelas'])->findOrFail($id);
            
            $guru = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
            
            $jurusanList = Jurusan::orderBy('nama')->get();
            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            
            return view('administrasi.kelas.edit', compact('kelas', 'guru', 'jurusanList', 'tahunAjaran', 'tingkatList', 'statusList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasEdit: ' . $e->getMessage());
            return back()->with('error', 'Data kelas tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama,' . $id,
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();
            
            $kelas = Kelas::findOrFail($id);
            
            $kelas->update([
                'nama' => $request->nama_kelas,
                'kode_kelas' => $request->kode_kelas ?? $kelas->kode_kelas,
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'wali_kelas_id' => $request->wali_kelas_id,
                'kapasitas' => $request->kapasitas ?? $kelas->kapasitas ?? 36,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'tahun_ajaran' => $request->tahun_ajaran ?? $kelas->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1),
            ]);
            
            DB::commit();
            
            return redirect()->route('administrasi.kelas.index')
                ->with('success', '✅ Kelas ' . $kelas->nama . ' berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in kelasUpdate: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengupdate kelas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $kelas = Kelas::with(['siswa'])->findOrFail($id);
            
            // Cek apakah kelas memiliki siswa
            $siswaCount = $kelas->siswa()->count();
            if ($siswaCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak dapat dihapus karena masih memiliki ' . $siswaCount . ' siswa! Silahkan pindahkan atau nonaktifkan siswa terlebih dahulu.'
                ], 400);
            }
            
            // Cek apakah kelas memiliki jadwal (jika relasi ada)
            if (method_exists($kelas, 'jadwal')) {
                $jadwalCount = $kelas->jadwal()->count();
                if ($jadwalCount > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kelas tidak dapat dihapus karena masih memiliki ' . $jadwalCount . ' jadwal pelajaran! Silahkan hapus jadwal terlebih dahulu.'
                    ], 400);
                }
            }
            
            $namaKelas = $kelas->nama;
            $kelas->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas ' . $namaKelas . ' berhasil dihapus!'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in kelasDestroy: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan daftar kelas (untuk select2)
     */
    public function getKelasList(Request $request)
    {
        try {
            $query = Kelas::with('jurusan')->where('status', 'aktif');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('kode_kelas', 'LIKE', "%{$search}%")
                      ->orWhere('tingkat', 'LIKE', "%{$search}%");
                });
            }
            
            // Filter berdasarkan tingkat
            if ($request->filled('tingkat')) {
                $query->where('tingkat', $request->tingkat);
            }
            
            // Filter berdasarkan jurusan
            if ($request->filled('jurusan_id')) {
                $query->where('jurusan_id', $request->jurusan_id);
            }
            
            $kelas = $query->orderBy('tingkat')
                ->orderBy('nama')
                ->get()
                ->map(function($item) {
                    $displayName = $item->tingkat . ' ' . $item->nama;
                    if ($item->jurusan) {
                        $displayName .= ' (' . $item->jurusan->nama . ')';
                    }
                    return [
                        'id' => $item->id,
                        'text' => $displayName,
                        'tingkat' => $item->tingkat,
                        'nama' => $item->nama,
                        'kode_kelas' => $item->kode_kelas,
                        'jurusan' => $item->jurusan ? $item->jurusan->nama : null,
                        'jurusan_id' => $item->jurusan_id,
                        'kapasitas' => $item->kapasitas,
                        'siswa_count' => $item->siswa->count()
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $kelas,
                'total' => $kelas->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getKelasList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get jurusan list for dropdown (API)
     */
    public function getJurusanList(Request $request)
    {
        try {
            $query = Jurusan::orderBy('nama');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('kode_jurusan', 'LIKE', "%{$search}%");
                });
            }
            
            $jurusan = $query->get(['id', 'nama', 'kode_jurusan']);
            
            // Tambahkan aksesoris nama_jurusan untuk kompatibilitas
            $jurusan = $jurusan->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'nama_jurusan' => $item->nama, // Untuk kompatibilitas dengan view
                    'kode_jurusan' => $item->kode_jurusan,
                    'text' => $item->kode_jurusan . ' - ' . $item->nama
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $jurusan
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getJurusanList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get wali kelas list for dropdown (API)
     */
    public function getWaliKelasList(Request $request)
    {
        try {
            $query = Guru::with('user')
                ->where('status', 'aktif');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $guru = $query->orderBy('nama_lengkap')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->nama_lengkap . ' (' . ($item->user->email ?? '-') . ')',
                        'nama' => $item->nama_lengkap,
                        'nip' => $item->nip,
                        'email' => $item->user->email ?? '-'
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $guru
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getWaliKelasList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}