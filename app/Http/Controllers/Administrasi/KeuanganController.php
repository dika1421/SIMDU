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

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Kelas::with(['waliKelas.user', 'jurusan']);
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('tingkat')) $query->where('tingkat', $request->tingkat);
            if ($request->filled('jurusan_id')) $query->where('jurusan_id', $request->jurusan_id);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_kelas', 'LIKE', "%{$search}%")
                      ->orWhere('kode_kelas', 'LIKE', "%{$search}%")
                      ->orWhere('tingkat', 'LIKE', "%{$search}%");
                });
            }
            $kelas = $query->orderBy('tingkat')->orderBy('nama')->paginate(10);            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            $tingkatList = ['X' => 'X', 'XI' => 'XI', 'XII' => 'XII', 'XIII' => 'XIII'];
            $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
            return view('administrasi.kelas.index', compact('kelas', 'statusList', 'tingkatList', 'jurusanList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasIndex: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            $tingkatList = ['X' => 'X', 'XI' => 'XI', 'XII' => 'XII', 'XIII' => 'XIII'];
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            return view('administrasi.kelas.create', compact('guru', 'jurusanList', 'tahunAjaran', 'tingkatList', 'statusList'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama',
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:255'
        ]);
        try {
            DB::beginTransaction();
            $kelas = Kelas::create([
                'nama' => $request->nama,
                'kode_kelas' => $request->kode_kelas ?? strtoupper(str_replace(' ', '', $request->nama)),
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'wali_kelas_id' => $request->wali_kelas_id,
                'kapasitas' => $request->kapasitas,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'tahun_ajaran' => $request->tahun_ajaran ?? date('Y'),
            ]);
            DB::commit();
            return redirect()->route('administrasi.kelas.index')->with('success', 'Kelas ' . $kelas->nama . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $kelas = Kelas::with(['waliKelas.user', 'jurusan', 'siswa.user'])->findOrFail($id);
        return view('administrasi.kelas.show', compact('kelas'));
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        $tingkatList = ['X'=>'X','XI'=>'XI','XII'=>'XII','XIII'=>'XIII'];
        $statusList = ['aktif'=>'Aktif','nonaktif'=>'Non Aktif'];
        return view('administrasi.kelas.edit', compact('kelas','guru','jurusanList','tahunAjaran','tingkatList','statusList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama,' . $id,
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        return redirect()->route('administrasi.kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        if($kelas->siswa()->count() > 0) return back()->with('error','Kelas masih ada siswa!');
        $kelas->delete();
        return redirect()->route('administrasi.kelas.index')->with('success','Kelas dihapus!');
    }

    public function getKelasList(Request $request)
    {
        $query = Kelas::with('jurusan')->where('status','aktif');
        if($request->filled('search')){
            $query->where('nama','LIKE',"%{$request->search}%");
        }
        return response()->json(['success'=>true,'data'=>$query->get()]);
    }

    // ===== TAMBAHAN WAJIB BIAR ERROR HILANG =====
    public function sppIndex(Request $request){ return $this->index($request); }
    public function sppCreate(){ return $this->create(); }
    public function sppStore(Request $request){ return $this->store($request); }
    public function sppShow($id){ return $this->show($id); }
    public function sppEdit($id){ return $this->edit($id); }
    public function sppUpdate(Request $request, $id){ return $this->update($request, $id); }
    public function sppDestroy($id){ return $this->destroy($id); }
    public function sppLaporan(){ return view('administrasi.keuangan.spp.laporan', ['kelas'=>Kelas::all()]); }
    public function pembayaranLainIndex(Request $request){ return $this->index($request); }
    public function pembayaranLainCreate(){ return $this->create(); }
    public function pembayaranLainStore(Request $request){ return $this->store($request); }
    public function pembayaranLainEdit($id){ return $this->edit($id); }
    public function pembayaranLainUpdate(Request $request, $id){ return $this->update($request, $id); }
    public function pembayaranLainDestroy($id){ return $this->destroy($id); }
    public function laporanKeuangan(){ return view('administrasi.kelas.index', ['kelas'=>Kelas::paginate(10),'statusList'=>['aktif'=>'Aktif'],'tingkatList'=>['X'=>'X'],'jurusanList'=>Jurusan::all()]); }
    public function exportLaporan(){ return $this->laporanKeuangan(); }
}