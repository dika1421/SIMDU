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
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    // ================== KELAS ==================
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

    public function create(){ 
        $guru=Guru::with('user')->where('status','aktif')->get(); 
        $jurusanList=Jurusan::orderBy('kode_jurusan')->get(); 
        $tahunAjaran=TahunAjaran::where('status','aktif')->first(); 
        $tingkatList=['X'=>'X','XI'=>'XI','XII'=>'XII','XIII'=>'XIII']; 
        $statusList=['aktif'=>'Aktif','nonaktif'=>'Non Aktif']; 
        return view('administrasi.kelas.create', compact('guru','jurusanList','tahunAjaran','tingkatList','statusList')); 
    }
    
    public function store(Request $request){ 
        $request->validate(['nama_kelas'=>'required|string|max:50|unique:kelas,nama_kelas','jurusan_id'=>'nullable|exists:jurusan,id','wali_kelas_id'=>'nullable|exists:gurus,id','kapasitas'=>'nullable|integer|min:1|max:100']); 
        Kelas::create(['nama_kelas'=>$request->nama_kelas,'kode_kelas'=>$request->kode_kelas ?? strtoupper(str_replace(' ','',$request->nama_kelas)),'jurusan_id'=>$request->jurusan_id,'wali_kelas_id'=>$request->wali_kelas_id,'kapasitas'=>$request->kapasitas,'keterangan'=>$request->keterangan]); 
        return redirect()->route('administrasi.kelas.index')->with('success','Kelas berhasil ditambahkan!'); 
    }
    
    public function show($id){ 
        $kelas=Kelas::with(['waliKelas.user','jurusan','siswa'])->findOrFail($id); 
        return view('administrasi.kelas.show', compact('kelas')); 
    }
    
    public function edit($id){ 
        $kelas=Kelas::findOrFail($id); 
        $guru=Guru::with('user')->where('status','aktif')->get(); 
        $jurusanList=Jurusan::all(); 
        $tahunAjaran=TahunAjaran::where('status','aktif')->first(); 
        $tingkatList=['X'=>'X','XI'=>'XI','XII'=>'XII']; 
        $statusList=['aktif'=>'Aktif','nonaktif'=>'Non Aktif']; 
        return view('administrasi.kelas.edit', compact('kelas','guru','jurusanList','tahunAjaran','tingkatList','statusList')); 
    }
    
    public function update(Request $request, $id){ 
        $request->validate(['nama_kelas'=>'required|string|max:50|unique:kelas,nama_kelas,'.$id]); 
        $kelas=Kelas::findOrFail($id); 
        $kelas->update($request->only(['nama_kelas','kode_kelas','jurusan_id','wali_kelas_id','kapasitas','keterangan'])); 
        return redirect()->route('administrasi.kelas.index')->with('success','Kelas diupdate!'); 
    }
    
    public function destroy($id){ 
        $kelas=Kelas::findOrFail($id); 
        $kelas->delete(); 
        return redirect()->route('administrasi.kelas.index')->with('success','Kelas dihapus!'); 
    }
    
    public function getKelasList(Request $request){ 
        $query=Kelas::with('jurusan'); 
        if($request->filled('search')) $query->where('nama_kelas','LIKE',"%{$request->search}%"); 
        return response()->json(['success'=>true,'data'=>$query->orderBy('nama_kelas')->get()]); 
    }

    // ================== SPP ==================
    public function sppIndex(Request $request)
    {
        $bulanList = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $tahunList = range(date('Y')-2, date('Y')+1);
        $statusList = ['lunas'=>'Lunas','belum_bayar'=>'Belum Bayar','terlambat'=>'Terlambat'];
        $kategoriList = ['SPP Bulanan'=>'SPP Bulanan', 'SPP Tahunan'=>'SPP Tahunan', 'SPP Semester'=>'SPP Semester'];
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $kelasList = $kelas;
        $bulan = $request->bulan; 
        $tahun = $request->tahun;
        
        try {
            $query = Spp::with(['siswa.user', 'siswa.kelas']);
            
            if($request->filled('bulan')) $query->where('bulan', $request->bulan);
            if($request->filled('tahun')) $query->where('tahun', $request->tahun);
            if($request->filled('kelas')) $query->whereHas('siswa', fn($q)=>$q->where('kelas_id',$request->kelas));
            if($request->filled('status')) $query->where('status', $request->status);
            if($request->filled('kategori')) $query->where('kategori', $request->kategori);
            
            $spp = $query->orderBy('created_at','desc')->paginate(15);
            
        } catch (\Exception $e) {
            Log::error('sppIndex: '.$e->getMessage());
            $spp = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, ['path'=>request()->url()]);
        }
        
        return view('administrasi.keuangan.spp.index', compact('spp','kelas','kelasList','bulanList','tahunList','statusList','kategoriList','bulan','tahun'));
    }

    public function sppCreate(){ 
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelas = $kelasList;
        $siswa = Siswa::with('user','kelas')->where('status','aktif')->orderBy('nama')->get(); 
        $kategoriList = ['SPP Bulanan'=>'SPP Bulanan', 'SPP Tahunan'=>'SPP Tahunan', 'SPP Semester'=>'SPP Semester'];
        return view('administrasi.keuangan.spp.create', compact('kelas','kelasList','siswa','kategoriList')); 
    }

    public function getSiswaByKelas(Request $request){ 
        try {
            if(!$request->kelas_id) return response()->json(['success'=>true,'data'=>[]]);
            $siswa = Siswa::with(['user','kelas'])
                ->where('kelas_id',$request->kelas_id)
                ->where('status','aktif')
                ->orderBy('nama')->get()
                ->map(function($s){
                    return [
                        'id'=>$s->id,
                        'nama'=>$s->nama ?? $s->user->name ?? '-',
                        'nis'=>$s->nis,
                        'kelas_id'=>$s->kelas_id,
                        'kelas_nama'=>$s->kelas->nama_kelas ?? '-',
                        'wali_kelas'=>$s->kelas->waliKelas->user->name ?? '-'
                    ];
                });
            return response()->json(['success'=>true,'data'=>$siswa]); 
        } catch(\Exception $e){
            Log::error('getSiswaByKelas: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>$e->getMessage(),'data'=>[]]);
        }
    }

    public function cariSiswa(Request $request){ 
        $request->validate(['nis'=>'required']); 
        $siswa = Siswa::with(['user','kelas.waliKelas.user'])->where('nis',$request->nis)->where('status','aktif')->first(); 
        if(!$siswa) return response()->json(['success'=>false,'message'=>'Siswa dengan NIS '.$request->nis.' tidak ditemukan']); 
        return response()->json(['success'=>true,'data'=>[
            'id'=>$siswa->id,
            'nama'=>$siswa->nama ?? $siswa->user->name ?? '-',
            'nis'=>$siswa->nis,
            'kelas_id'=>$siswa->kelas_id,
            'kelas_nama'=>$siswa->kelas->nama_kelas ?? '-', 
            'wali_kelas'=>$siswa->kelas->waliKelas->user->name ?? '-'
        ]]); 
    }

    public function cariSiswaByKelas(Request $request){
        return $this->getSiswaByKelas($request);
    }

    /**
     * STORE SPP
     */
    public function sppStore(Request $request){ 
        Log::info('=== DATA SPP MASUK ===', $request->all());
        
        // Cek siswa
        $siswaId = $request->siswa_id;
        if (!$siswaId) {
            return redirect()->back()
                ->with('error', 'Silakan pilih siswa terlebih dahulu!')
                ->withInput();
        }
        
        $siswa = Siswa::find($siswaId);
        if (!$siswa) {
            $availableIds = Siswa::limit(10)->pluck('id')->toArray();
            return redirect()->back()
                ->with('error', 'Siswa ID ' . $siswaId . ' tidak ditemukan! ID yang tersedia: ' . implode(', ', $availableIds))
                ->withInput();
        }
        
        // Validasi
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|integer|min:1',
            'kategori' => 'required|string|in:SPP Bulanan,SPP Tahunan,SPP Semester',
            'tanggal_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:1000',
            'metode_bayar' => 'required|string',
            'status' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            Log::error('VALIDASI SPP GAGAL:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
        }
        
        try {
            $tanggal = $request->tanggal_bayar;
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));
            
            $spp = Spp::create([
                'siswa_id' => $request->siswa_id,
                'kategori' => $request->kategori,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $request->jumlah,
                'nominal' => $request->jumlah,
                'status' => $request->status ?? 'lunas',
                'metode_bayar' => $request->metode_bayar,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => $tanggal,
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal . ' +7 days'))
            ]); 

            Log::info('✅ SPP BERHASIL DISIMPAN!', ['id' => $spp->id]);

            return redirect()->route('administrasi.keuangan.spp')->with('success', 'SPP berhasil disimpan!');
            
        } catch (\Exception $e) {
            Log::error('❌ ERROR SPP STORE: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function sppShow($id){ 
        return redirect()->route('administrasi.keuangan.spp'); 
    }
    
    public function sppEdit($id){ 
        $kelas = Kelas::orderBy('nama_kelas')->get(); 
        $kelasList = $kelas; 
        $spp = Spp::findOrFail($id); 
        $bulanList = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; 
        $tahunList = range(date('Y')-2, date('Y')+1);
        $kategoriList = ['SPP Bulanan'=>'SPP Bulanan', 'SPP Tahunan'=>'SPP Tahunan', 'SPP Semester'=>'SPP Semester'];
        return view('administrasi.keuangan.spp.edit', compact('kelas','kelasList','spp','bulanList','tahunList','kategoriList')); 
    }
    
    public function sppUpdate(Request $request, $id){ 
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|in:SPP Bulanan,SPP Tahunan,SPP Semester',
            'tanggal_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:1000',
            'metode_bayar' => 'required|string',
            'status' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
        }
        
        try {
            $tanggal = $request->tanggal_bayar;
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));
            
            $spp = Spp::findOrFail($id);
            $spp->update([
                'kategori' => $request->kategori,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $request->jumlah,
                'nominal' => $request->jumlah,
                'metode_bayar' => $request->metode_bayar,
                'status' => $request->status ?? 'lunas',
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => $tanggal,
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal . ' +7 days'))
            ]); 
            
            return redirect()->route('administrasi.keuangan.spp')->with('success','SPP berhasil diupdate');
            
        } catch (\Exception $e) {
            Log::error('Error update SPP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    
    public function sppDestroy($id){ 
        try{ 
            $spp = Spp::findOrFail($id); 
            $spp->delete(); 
            return redirect()->route('administrasi.keuangan.spp')->with('success','SPP berhasil dihapus');
        } catch(\Exception $e){
            Log::error('Error delete SPP: ' . $e->getMessage());
            return redirect()->route('administrasi.keuangan.spp')->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
    
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
        $kelas = Kelas::orderBy('nama_kelas')->get(); 
        $kelasList = $kelas;
        $siswa = Siswa::with('user','kelas')->where('status','aktif')->orderBy('nama')->get();
        $jenisList = ['Uang Gedung'=>'Uang Gedung','Uang Seragam'=>'Uang Seragam','Uang Buku'=>'Uang Buku','Uang Kegiatan'=>'Uang Kegiatan','Daftar Ulang'=>'Daftar Ulang','Lainnya'=>'Lainnya']; 
        return view('administrasi.keuangan.pembayaran-lain.create', compact('kelas','kelasList','siswa','jenisList')); 
    }

    public function pembayaranLainStore(Request $request){ 
        Log::info('=== DATA PEMBAYARAN LAIN MASUK ===', $request->all());
        
        // Cek siswa
        $siswaId = $request->siswa_id;
        if (!$siswaId) {
            return redirect()->back()
                ->with('error', 'Silakan pilih siswa terlebih dahulu!')
                ->withInput();
        }
        
        $siswa = Siswa::find($siswaId);
        if (!$siswa) {
            $availableIds = Siswa::limit(10)->pluck('id')->toArray();
            return redirect()->back()
                ->with('error', 'Siswa ID ' . $siswaId . ' tidak ditemukan! ID yang tersedia: ' . implode(', ', $availableIds))
                ->withInput();
        }
        
        // Validasi
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|integer|min:1',
            'kategori_pembayaran' => 'required|string',
            'jumlah' => 'required|numeric|min:1000',
            'metode_bayar' => 'required|string',
            'tanggal_bayar' => 'required|date'
        ]);
        
        if ($validator->fails()) {
            Log::error('VALIDASI PEMBAYARAN LAIN GAGAL:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
        }
        
        try {
            $pembayaran = PembayaranLain::create([
                'siswa_id' => $request->siswa_id,
                'jenis_pembayaran' => $request->kategori_pembayaran,
                'kategori_pembayaran' => $request->kategori_pembayaran,
                'jumlah' => $request->jumlah,
                'metode_bayar' => $request->metode_bayar,
                'status' => $request->status ?? 'lunas',
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => $request->tanggal_bayar
            ]); 
            
            Log::info('✅ PEMBAYARAN LAIN BERHASIL DISIMPAN!', ['id' => $pembayaran->id]);
            
            return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success', 'Pembayaran Lain berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('❌ ERROR PEMBAYARAN LAIN STORE: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function pembayaranLainEdit($id){ 
        $kelas = Kelas::orderBy('nama_kelas')->get(); 
        $kelasList = $kelas;
        $pembayaranLain = PembayaranLain::findOrFail($id); 
        $jenisList = ['Uang Gedung'=>'Uang Gedung','Uang Seragam'=>'Uang Seragam','Uang Buku'=>'Uang Buku','Uang Kegiatan'=>'Uang Kegiatan','Daftar Ulang'=>'Daftar Ulang','Lainnya'=>'Lainnya']; 
        return view('administrasi.keuangan.pembayaran-lain.edit', compact('kelas','kelasList','pembayaranLain','jenisList')); 
    }
    
    public function pembayaranLainUpdate(Request $request, $id){ 
        try {
            $data = $request->all();
            if($request->filled('kategori_pembayaran')){
                $data['jenis_pembayaran'] = $request->kategori_pembayaran;
                $data['kategori_pembayaran'] = $request->kategori_pembayaran;
            }
            $pembayaran = PembayaranLain::findOrFail($id);
            $pembayaran->update($data); 
            return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success','Pembayaran Lain berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error pembayaranLainUpdate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    
    public function pembayaranLainDestroy($id){ 
        try{ 
            $pembayaran = PembayaranLain::findOrFail($id);
            $pembayaran->delete(); 
            return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('success','Pembayaran Lain berhasil dihapus');
        } catch(\Exception $e){
            Log::error('Error pembayaranLainDestroy: ' . $e->getMessage());
            return redirect()->route('administrasi.keuangan.pembayaran-lain.index')->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function laporanKeuangan(Request $request){ 
        return $this->sppLaporan($request);
    }
    
    public function exportLaporan(){ 
        return $this->sppIndex(request()); 
    }
}