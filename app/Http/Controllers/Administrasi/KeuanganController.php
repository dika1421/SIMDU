<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\PembayaranLain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeuanganController extends Controller
{
    // ================== KELAS (TETAP) ==================
    public function index(Request $request)
    {
        try {
            $query = Kelas::with(['waliKelas.user', 'jurusan']);
            if ($request->filled('jurusan_id')) $query->where('jurusan_id', $request->jurusan_id);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_kelas', 'LIKE', "%{$search}%")->orWhere('kode_kelas', 'LIKE', "%{$search}%");
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

    public function create(){ $guru=Guru::with('user')->where('status','aktif')->get(); $jurusanList=Jurusan::orderBy('kode_jurusan')->get(); $tahunAjaran=TahunAjaran::where('status','aktif')->first(); $tingkatList=['X'=>'X','XI'=>'XI','XII'=>'XII','XIII'=>'XIII']; $statusList=['aktif'=>'Aktif','nonaktif'=>'Non Aktif']; return view('administrasi.kelas.create', compact('guru','jurusanList','tahunAjaran','tingkatList','statusList')); }
    public function store(Request $request){ $request->validate(['nama_kelas'=>'required|string|max:50|unique:kelas,nama_kelas','jurusan_id'=>'nullable|exists:jurusan,id','wali_kelas_id'=>'nullable|exists:gurus,id','kapasitas'=>'nullable|integer|min:1|max:100']); Kelas::create(['nama_kelas'=>$request->nama_kelas,'kode_kelas'=>$request->kode_kelas ?? strtoupper(str_replace(' ','',$request->nama_kelas)),'jurusan_id'=>$request->jurusan_id,'wali_kelas_id'=>$request->wali_kelas_id,'kapasitas'=>$request->kapasitas,'keterangan'=>$request->keterangan]); return redirect()->route('administrasi.kelas.index')->with('success','Kelas berhasil ditambahkan!'); }
    public function show($id){ $kelas=Kelas::with(['waliKelas.user','jurusan','siswa'])->findOrFail($id); return view('administrasi.kelas.show', compact('kelas')); }
    public function edit($id){ $kelas=Kelas::findOrFail($id); $guru=Guru::with('user')->where('status','aktif')->get(); $jurusanList=Jurusan::all(); $tahunAjaran=TahunAjaran::where('status','aktif')->first(); $tingkatList=['X'=>'X','XI'=>'XI','XII'=>'XII']; $statusList=['aktif'=>'Aktif','nonaktif'=>'Non Aktif']; return view('administrasi.kelas.edit', compact('kelas','guru','jurusanList','tahunAjaran','tingkatList','statusList')); }
    public function update(Request $request, $id){ $request->validate(['nama_kelas'=>'required|string|max:50|unique:kelas,nama_kelas,'.$id]); $kelas=Kelas::findOrFail($id); $kelas->update($request->only(['nama_kelas','kode_kelas','jurusan_id','wali_kelas_id','kapasitas','keterangan'])); return redirect()->route('administrasi.kelas.index')->with('success','Kelas diupdate!'); }
    public function destroy($id){ $kelas=Kelas::findOrFail($id); $kelas->delete(); return redirect()->route('administrasi.kelas.index')->with('success','Kelas dihapus!'); }
    public function getKelasList(Request $request){ $query=Kelas::with('jurusan'); if($request->filled('search')) $query->where('nama_kelas','LIKE',"%{$request->search}%"); return response()->json(['success'=>true,'data'=>$query->orderBy('nama_kelas')->get()]); }

    // ================== SPP ==================
    public function sppIndex(Request $request)
    {
        $bulanList = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $tahunList = range(date('Y')-2, date('Y')+1);
        $statusList = ['lunas'=>'Lunas','belum_bayar'=>'Belum Bayar','terlambat'=>'Terlambat'];
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $kelasList = $kelas;
        $bulan = $request->bulan; 
        $tahun = $request->tahun;
        try {
            $query = Spp::with(['siswa.user','siswa.kelas']);
            if($request->filled('bulan')) $query->where('bulan', $request->bulan);
            if($request->filled('tahun')) $query->where('tahun', $request->tahun);
            if($request->filled('kelas')) $query->whereHas('siswa', fn($q)=>$q->where('kelas_id',$request->kelas));
            if($request->filled('status')) $query->where('status', $request->status);
            $spp = $query->orderBy('created_at','desc')->paginate(15);
        } catch (\Exception $e) {
            Log::error('sppIndex: '.$e->getMessage());
            $spp = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, ['path'=>request()->url()]);
        }
        return view('administrasi.keuangan.spp.index', compact('spp','kelas','kelasList','bulanList','tahunList','statusList','bulan','tahun'));
    }

    public function sppCreate(){ 
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelas = $kelasList;
        $siswa=Siswa::with('user','kelas')->where('status','aktif')->orderBy('nama')->get(); // FIX: nama
        $bulanList=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; 
        $tahunList=range(date('Y')-2, date('Y')+1); 
        return view('administrasi.keuangan.spp.create', compact('kelas','kelasList','siswa','bulanList','tahunList')); 
    }

    // FIX: Semua orderBy nama_lengkap -> nama, dan mapping nama_lengkap -> nama
    public function getSiswaByKelas(Request $request){ 
        try {
            $siswa=Siswa::with(['user','kelas','kelas.waliKelas.user'])
                ->where('kelas_id',$request->kelas_id)
                ->where('status','aktif')
                ->orderBy('nama')->get() // FIX
                ->map(function($s){
                    return [
                        'id'=>$s->id,
                        'nama'=>$s->user->name ?? $s->nama ?? '-', // FIX
                        'nis'=>$s->nis,
                        'kelas_id'=>$s->kelas_id,
                        'kelas_nama'=>$s->kelas->nama_kelas ?? '-',
                        'wali_kelas'=>$s->kelas->waliKelas->user->name ?? $s->kelas->waliKelas->nama_lengkap ?? '-'
                    ];
                });
            return response()->json(['success'=>true,'data'=>$siswa]); 
        } catch(\Exception $e){
            return response()->json(['success'=>false,'message'=>$e->getMessage(), 'data'=>[]]);
        }
    }

    public function cariSiswa(Request $request){ 
        $request->validate(['nis'=>'required']); 
        $siswa=Siswa::with(['user','kelas.waliKelas.user'])->where('nis',$request->nis)->where('status','aktif')->first(); 
        if(!$siswa) return response()->json(['success'=>false,'message'=>'Siswa dengan NIS '.$request->nis.' tidak ditemukan']); 
        return response()->json(['success'=>true,'data'=>[
            'id'=>$siswa->id,
            'nama'=>$siswa->user->name ?? $siswa->nama ?? '-', // FIX
            'nis'=>$siswa->nis,
            'kelas_id'=>$siswa->kelas_id,
            'kelas_nama'=>$siswa->kelas->nama_kelas ?? '-', 
            'wali_kelas'=>$siswa->kelas->waliKelas->user->name ?? $siswa->kelas->waliKelas->nama_lengkap ?? '-'
        ]]); 
    }

    public function cariSiswaByKelas(Request $request){
        $siswa = Siswa::with(['user','kelas','kelas.waliKelas.user'])
            ->where('kelas_id', $request->kelas_id)
            ->where('status','aktif')
            ->orderBy('nama')->get() // FIX
            ->map(function($s){
                return [
                    'id'=>$s->id,
                    'nama'=>$s->user->name ?? $s->nama ?? '-', // FIX
                    'nis'=>$s->nis,
                    'kelas_id'=>$s->kelas_id,
                    'kelas_nama'=>$s->kelas->nama_kelas ?? '-',
                    'wali_kelas'=>$s->kelas->waliKelas->user->name ?? '-'
                ];
            });
        return response()->json(['success'=>true,'data'=>$siswa]);
    }

    public function sppStore(Request $request){ 
        $request->validate([
            'siswa_id'=>'required|exists:siswas,id', // FIX: siswas bukan siswa
            'bulan'=>'required',
            'tahun'=>'nullable',
            'jumlah'=>'required|numeric|min:1000',
            'metode_bayar'=>'required'
        ]); 
        Spp::create([
            'siswa_id'=>$request->siswa_id,
            'bulan'=>$request->bulan,
            'tahun'=>$request->tahun ?? date('Y'),
            'jumlah'=>$request->jumlah,
            'nominal'=>$request->jumlah,
            'status'=>'lunas',
            'metode_bayar'=>$request->metode_bayar,
            'keterangan'=>$request->keterangan,
            'tanggal_bayar'=>$request->tanggal_bayar ?? now()
        ]); 
        return redirect()->route('administrasi.keuangan.spp')->with('success','SPP berhasil disimpan'); 
    }

    public function sppShow($id){ return redirect()->route('administrasi.keuangan.spp'); }
    public function sppEdit($id){ $kelas=Kelas::orderBy('nama_kelas')->get(); $kelasList=$kelas; $spp=Spp::findOrFail($id); $bulanList=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; $tahunList=range(date('Y')-2, date('Y')+1); return view('administrasi.keuangan.spp.edit', compact('kelas','kelasList','spp','bulanList','tahunList')); }
    public function sppUpdate(Request $request, $id){ Spp::findOrFail($id)->update($request->all()); return redirect()->route('administrasi.keuangan.spp')->with('success','SPP diupdate'); }
    public function sppDestroy($id){ try{ Spp::findOrFail($id)->delete(); }catch(\Exception $e){} return redirect()->route('administrasi.keuangan.spp')->with('success','SPP dihapus'); }
    
    public function sppLaporan(Request $request){ 
        $bulan = (int)($request->bulan ?? date('n'));
        $tahun = (int)($request->tahun ?? date('Y'));
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $kelasList = $kelas;
        try{
            $data = Spp::with(['siswa.user','siswa.kelas'])->where('bulan',$bulan)->where('tahun',$tahun)->get();
            $total = $data->sum('jumlah');
            $lunas = $data->where('status','lunas')->count();
            $belum = Siswa::where('status','aktif')->count() - $lunas;
            if($belum<0) $belum=0;
        }catch(\Exception $e){
            $data = collect([]); $total=0; $lunas=0; $belum=0;
        }
        return view('administrasi.keuangan.spp.laporan', compact('kelas','kelasList','data','total','lunas','belum','bulan','tahun')); 
    }

    // ================== PEMBAYARAN LAIN ==================
    public function pembayaranLainIndex(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $kelasList = $kelas;
        $jenisList=['Uang Gedung'=>'Uang Gedung','Uang Seragam'=>'Uang Seragam','Uang Buku'=>'Uang Buku','Uang Kegiatan'=>'Uang Kegiatan','Daftar Ulang'=>'Daftar Ulang','Lainnya'=>'Lainnya'];
        try {
            $query = PembayaranLain::with(['siswa.user','siswa.kelas']);
            if($request->filled('kelas')) $query->whereHas('siswa', fn($q)=>$q->where('kelas_id',$request->kelas));
            if($request->filled('jenis')) $query->where(function($q) use ($request){ $q->where('jenis_pembayaran',$request->jenis)->orWhere('kategori_pembayaran',$request->jenis); });
            if($request->filled('status')) $query->where('status', $request->status);
            $pembayaranLain = $query->orderBy('created_at','desc')->paginate(15);
            $pembayaran = $pembayaranLain;
        } catch (\Exception $e) {
            Log::error('pembayaranLainIndex: '.$e->getMessage());
            $pembayaranLain = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, ['path'=>request()->url()]);
            $pembayaran = $pembayaranLain;
        }
        return view('administrasi.keuangan.pembayaran-lain.index', compact('pembayaranLain','pembayaran','kelas','kelasList','jenisList'));
    }

    public function pembayaranLainCreate(){ 
        $kelas=Kelas::orderBy('nama_kelas')->get(); 
        $kelasList=$kelas;
        $siswa=Siswa::with('user','kelas')->where('status','aktif')->orderBy('nama')->get(); // FIX
        $jenisList=['Uang Gedung'=>'Uang Gedung','Uang Seragam'=>'Uang Seragam','Uang Buku'=>'Uang Buku','Uang Kegiatan'=>'Uang Kegiatan','Daftar Ulang'=>'Daftar Ulang','Lainnya'=>'Lainnya']; 
        return view('administrasi.keuangan.pembayaran-lain.create', compact('kelas','kelasList','siswa','jenisList')); 
    }

    public function pembayaranLainStore(Request $request){ 
        $request->validate([
            'siswa_id'=>'required|exists:siswas,id', // FIX
            'kategori_pembayaran'=>'required|string',
            'jumlah'=>'required|numeric|min:1000',
            'metode_bayar'=>'required',
            'tanggal_bayar'=>'required|date'
        ]); 
        PembayaranLain::create([
            'siswa_id'=>$request->siswa_id,
            'jenis_pembayaran'=>$request->kategori_pembayaran,
            'kategori_pembayaran'=>$request->kategori_pembayaran,
            'kategori'=>$request->kategori_pembayaran,
            'jumlah'=>$request->jumlah,
            'metode_bayar'=>$request->metode_bayar,
            'metode'=>$request->metode_bayar,
            'status'=>'lunas',
            'keterangan'=>$request->keterangan,
            'tanggal_bayar'=>$request->tanggal_bayar
        ]); 
        return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success','Pembayaran Lain berhasil disimpan'); 
    }

    public function pembayaranLainEdit($id){ 
        $kelas=Kelas::orderBy('nama_kelas')->get(); 
        $kelasList=$kelas;
        $pembayaranLain=PembayaranLain::findOrFail($id); 
        $jenisList=['Uang Gedung'=>'Uang Gedung','Uang Seragam'=>'Uang Seragam','Uang Buku'=>'Uang Buku','Uang Kegiatan'=>'Uang Kegiatan','Daftar Ulang'=>'Daftar Ulang','Lainnya'=>'Lainnya']; 
        return view('administrasi.keuangan.pembayaran-lain.edit', compact('kelas','kelasList','pembayaranLain','jenisList')); 
    }
    public function pembayaranLainUpdate(Request $request, $id){ 
        $data = $request->all();
        if($request->filled('kategori_pembayaran')){
            $data['jenis_pembayaran']=$request->kategori_pembayaran;
            $data['kategori']=$request->kategori_pembayaran;
            $data['kategori_pembayaran']=$request->kategori_pembayaran;
        }
        PembayaranLain::findOrFail($id)->update($data); 
        return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success','Pembayaran Lain diupdate'); 
    }
    public function pembayaranLainDestroy($id){ try{ PembayaranLain::findOrFail($id)->delete(); }catch(\Exception $e){} return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success','Pembayaran Lain dihapus'); }

    public function laporanKeuangan(Request $request){ 
        return $this->sppLaporan($request);
    }
    public function exportLaporan(){ return $this->sppIndex(request()); }
}