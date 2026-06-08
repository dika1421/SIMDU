<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Jurusan::query();
            
            // Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_jurusan', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%");
                });
            }
            
            // Filter status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $jurusan = $query->orderBy('nama')->paginate(10);
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            
            return view('administrasi.jurusan.index', compact('jurusan', 'statusList'));
        } catch (\Exception $e) {
            Log::error('Error in jurusanIndex: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $guruList = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap', 'asc')
                ->get();
            
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            
            return view('administrasi.jurusan.create', compact('statusList', 'guruList'));
        } catch (\Exception $e) {
            Log::error('Error in jurusanCreate: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'nullable|string|max:20|unique:jurusan,kode_jurusan',
            'nama' => 'required|string|max:100|unique:jurusan,nama',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'kepala_jurusan_id' => 'nullable|exists:gurus,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Generate kode_jurusan jika kosong
            $kodeJurusan = $request->kode_jurusan;
            if (empty($kodeJurusan)) {
                $kodeJurusan = $this->generateKodeJurusan($request->nama);
            }
            
            $jurusan = Jurusan::create([
                'kode_jurusan' => strtoupper($kodeJurusan),
                'nama' => ucwords($request->nama),
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'kepala_jurusan_id' => $request->kepala_jurusan_id
            ]);
            
            DB::commit();
            
            return redirect()->route('administrasi.jurusan.index')
                ->with('success', '✅ Jurusan ' . $jurusan->nama . ' berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in jurusanStore: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal menyimpan jurusan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $jurusan = Jurusan::with(['kelas.siswa', 'kepalaJurusan.user'])->findOrFail($id);
            $jumlahKelas = $jurusan->kelas->count();
            $jumlahSiswa = $jurusan->kelas->sum(function($kelas) {
                return $kelas->siswa->count();
            });
            
            return view('administrasi.jurusan.show', compact('jurusan', 'jumlahKelas', 'jumlahSiswa'));
        } catch (\Exception $e) {
            Log::error('Error in jurusanShow: ' . $e->getMessage());
            return back()->with('error', 'Data jurusan tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            $guruList = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap', 'asc')
                ->get();
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            
            return view('administrasi.jurusan.edit', compact('jurusan', 'statusList', 'guruList'));
        } catch (\Exception $e) {
            Log::error('Error in jurusanEdit: ' . $e->getMessage());
            return back()->with('error', 'Data jurusan tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_jurusan' => 'nullable|string|max:20|unique:jurusan,kode_jurusan,' . $id,
            'nama' => 'required|string|max:100|unique:jurusan,nama,' . $id,
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'kepala_jurusan_id' => 'nullable|exists:gurus,id'
        ]);

        try {
            DB::beginTransaction();
            
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->update([
                'kode_jurusan' => strtoupper($request->kode_jurusan),
                'nama' => ucwords($request->nama),
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'kepala_jurusan_id' => $request->kepala_jurusan_id
            ]);
            
            DB::commit();
            
            return redirect()->route('administrasi.jurusan.index')
                ->with('success', '✅ Jurusan ' . $jurusan->nama . ' berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in jurusanUpdate: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengupdate jurusan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $jurusan = Jurusan::findOrFail($id);
            
            // Cek apakah jurusan memiliki kelas
            if ($jurusan->kelas()->count() > 0) {
                throw new \Exception('Jurusan tidak dapat dihapus karena masih memiliki ' . $jurusan->kelas()->count() . ' kelas!');
            }
            
            $namaJurusan = $jurusan->nama;
            $jurusan->delete();
            
            DB::commit();
            
            return redirect()->route('administrasi.jurusan.index')
                ->with('success', '✅ Jurusan ' . $namaJurusan . ' berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in jurusanDestroy: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal menghapus jurusan: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate kode jurusan otomatis dari nama
     */
    private function generateKodeJurusan($nama)
    {
        // Ambil 3 huruf pertama dari nama
        $kode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama), 0, 3));
        
        // Cek apakah kode sudah ada
        $exists = Jurusan::where('kode_jurusan', $kode)->exists();
        if ($exists) {
            $counter = 1;
            while (Jurusan::where('kode_jurusan', $kode . $counter)->exists()) {
                $counter++;
            }
            $kode = $kode . $counter;
        }
        
        return $kode;
    }
    
    /**
     * API: Get all active jurusan for dropdown
     */
    public function getActiveJurusan(Request $request)
    {
        try {
            $query = Jurusan::where('status', 'aktif')->orderBy('nama');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_jurusan', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%");
                });
            }
            
            $jurusan = $query->get(['id', 'kode_jurusan', 'nama'])->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->kode_jurusan . ' - ' . $item->nama
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $jurusan
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getActiveJurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jurusan'
            ], 500);
        }
    }
}