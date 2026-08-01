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
    // ... (kode kelas sama seperti sebelumnya) ...

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
     * STORE SPP - PERBAIKAN FINAL
     */
    public function sppStore(Request $request){ 
        // DEBUG: Log semua data yang masuk
        Log::info('=== DATA SPP MASUK ===');
        Log::info($request->all());
        
        // Validasi
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|integer|exists:siswas,id',
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
            // Cek apakah siswa benar-benar ada di database
            $siswa = Siswa::find($request->siswa_id);
            if (!$siswa) {
                Log::error('Siswa dengan ID ' . $request->siswa_id . ' tidak ditemukan!');
                return redirect()->back()
                    ->with('error', 'Siswa tidak ditemukan di database!')
                    ->withInput();
            }
            
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
                'tanggal_bayar' => $tanggal
            ]); 

            Log::info('✅ SPP BERHASIL DISIMPAN:', ['id' => $spp->id, 'siswa_id' => $request->siswa_id]);

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
        $request->validate([
            'kategori' => 'required|string|in:SPP Bulanan,SPP Tahunan,SPP Semester',
            'tanggal_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:1000',
            'metode_bayar' => 'required|string',
            'status' => 'nullable|string'
        ]);
        
        try {
            $tanggal = $request->tanggal_bayar;
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));
            
            Spp::findOrFail($id)->update([
                'kategori' => $request->kategori,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $request->jumlah,
                'nominal' => $request->jumlah,
                'metode_bayar' => $request->metode_bayar,
                'status' => $request->status ?? 'lunas',
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => $tanggal
            ]); 
            
            return redirect()->route('administrasi.keuangan.spp')->with('success','SPP berhasil diupdate');
            
        } catch (\Exception $e) {
            Log::error('Error update SPP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    
    public function sppDestroy($id){ 
        try{ 
            Spp::findOrFail($id)->delete(); 
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
    // ... (kode pembayaran lain sama seperti sebelumnya) ...

    public function laporanKeuangan(Request $request){ 
        return $this->sppLaporan($request);
    }
    
    public function exportLaporan(){ 
        return $this->sppIndex(request()); 
    }
}