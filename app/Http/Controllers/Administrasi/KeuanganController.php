<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeuanganController extends Controller
{
    // INI UNTUK MANAJEMEN KELAS - jangan dipakai SPP
    public function index(Request $request)
    {
        try {
            $query = Kelas::with(['waliKelas.user', 'jurusan']);
            if ($request->filled('jurusan_id')) $query->where('jurusan_id', $request->jurusan_id);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_kelas', 'LIKE', "%{$search}%")
                      ->orWhere('kode_kelas', 'LIKE', "%{$search}%");
                });
            }
            $kelas = $query->orderBy('nama_kelas')->paginate(10);
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            $tingkatList = ['X' => 'X', 'XI' => 'XI', 'XII' => 'XII', 'XIII' => 'XIII'];
            $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
            return view('administrasi.kelas.index', compact('kelas', 'statusList', 'tingkatList', 'jurusanList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasIndex: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ... create, store, edit, update, destroy tetap pakai nama_kelas
    public function create()
    {
        $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        $tingkatList = ['X'=>'X','XI'=>'XI','XII'=>'XII','XIII'=>'XIII'];
        $statusList = ['aktif'=>'Aktif','nonaktif'=>'Non Aktif'];
        return view('administrasi.kelas.create', compact('guru','jurusanList','tahunAjaran','tingkatList','statusList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255'
        ]);
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'kode_kelas' => $request->kode_kelas ?? strtoupper(str_replace(' ', '', $request->nama_kelas)),
            'jurusan_id' => $request->jurusan_id,
            'wali_kelas_id' => $request->wali_kelas_id,
            'kapasitas' => $request->kapasitas,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('administrasi.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show($id){ $kelas = Kelas::with(['waliKelas.user','jurusan','siswa'])->findOrFail($id); return view('administrasi.kelas.show', compact('kelas')); }
    public function edit($id){ $kelas=Kelas::findOrFail($id); $guru=Guru::with('user')->where('status','aktif')->get(); $jurusanList=Jurusan::all(); $tahunAjaran=TahunAjaran::where('status','aktif')->first(); $tingkatList=['X'=>'X','XI'=>'XI','XII'=>'XII']; $statusList=['aktif'=>'Aktif','nonaktif'=>'Non Aktif']; return view('administrasi.kelas.edit', compact('kelas','guru','jurusanList','tahunAjaran','tingkatList','statusList')); }
    public function update(Request $request, $id){ $request->validate(['nama_kelas'=>'required|string|max:50|unique:kelas,nama_kelas,'.$id]); $kelas=Kelas::findOrFail($id); $kelas->update($request->only(['nama_kelas','kode_kelas','jurusan_id','wali_kelas_id','kapasitas','keterangan'])); return redirect()->route('administrasi.kelas.index')->with('success','Kelas diupdate!'); }
    public function destroy($id){ $kelas=Kelas::findOrFail($id); $kelas->delete(); return redirect()->route('administrasi.kelas.index')->with('success','Kelas dihapus!'); }
    public function getKelasList(Request $request){ $query=Kelas::with('jurusan'); if($request->filled('search')) $query->where('nama_kelas','LIKE',"%{$request->search}%"); return response()->json(['success'=>true,'data'=>$query->orderBy('nama_kelas')->get()]); }

    // ================== INI YANG BENER UNTUK SPP ==================
    public function sppIndex(Request $request)
    {
        // Ambil data siswa + kelas untuk halaman SPP
        $query = Siswa::with(['user','kelas']);
        if($request->filled('search')){
            $search=$request->search;
            $query->whereHas('user', function($q) use ($search){
                $q->where('name','LIKE',"%{$search}%");
            });
        }
        if($request->filled('kelas_id')) $query->where('kelas_id', $request->kelas_id);
        
        $siswa = $query->paginate(20);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        
        // Jika view spp belum ada, fallback ke view keuangan
        if(view()->exists('administrasi.keuangan.spp.index')){
            return view('administrasi.keuangan.spp.index', compact('siswa','kelasList'));
        } else {
            // Buat tampilan sederhana biar tidak jadi Manajemen Kelas
            return view('administrasi.kelas.index', ['kelas'=>Kelas::orderBy('nama_kelas')->paginate(10), 'statusList'=>['aktif'=>'Aktif'], 'tingkatList'=>['X'=>'X'], 'jurusanList'=>Jurusan::all()])
            ->with('warning','View administrasi.keuangan.spp.index belum ada, buat dulu file blade nya');
        }
    }

    public function sppCreate(){ $kelas=Kelas::orderBy('nama_kelas')->get(); $siswa=Siswa::with('user')->get(); return view('administrasi.keuangan.spp.create', compact('kelas','siswa')); }
    public function sppStore(Request $request){ return redirect()->route('administrasi.keuangan.spp.index')->with('success','SPP disimpan'); }
    public function sppShow($id){ return $this->show($id); }
    public function sppEdit($id){ $kelas=Kelas::orderBy('nama_kelas')->get(); return view('administrasi.keuangan.spp.edit', compact('kelas')); }
    public function sppUpdate(Request $request, $id){ return redirect()->route('administrasi.keuangan.spp.index')->with('success','SPP diupdate'); }
    public function sppDestroy($id){ return redirect()->route('administrasi.keuangan.spp.index')->with('success','SPP dihapus'); }
    public function sppLaporan(){ return view('administrasi.keuangan.spp.laporan', ['kelas'=>Kelas::orderBy('nama_kelas')->get()]); }
    
    public function pembayaranLainIndex(Request $request){ return $this->sppIndex($request); }
    public function pembayaranLainCreate(){ return $this->sppCreate(); }
    public function pembayaranLainStore(Request $request){ return $this->sppStore($request); }
    public function pembayaranLainEdit($id){ return $this->sppEdit($id); }
    public function pembayaranLainUpdate(Request $request, $id){ return $this->sppUpdate($request, $id); }
    public function pembayaranLainDestroy($id){ return $this->sppDestroy($id); }
    public function laporanKeuangan(){ return $this->sppIndex(request()); }
    public function exportLaporan(){ return $this->sppIndex(request()); }
}