<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class GuruController extends Controller
{
    private function parseTanggalIndo($tanggal)
    {
        if (empty($tanggal)) return null;
        $tanggal = trim((string)$tanggal);
        if ($tanggal === '') return null;

        if (is_numeric($tanggal) && (float)$tanggal > 30000) {
            try {
                return ExcelDate::excelToDateTimeObject($tanggal)->format('Y-m-d');
            } catch (\Exception $e) {}
        }
        if (preg_match('/^\d{4}$/', $tanggal)) {
            return $tanggal. '-01-01';
        }

        $map = [
            'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
            'april' => 'April', 'mei' => 'May', 'juni' => 'June', 'juli' => 'July',
            'agustus' => 'August', 'september' => 'September', 'oktober' => 'October',
            'nopember' => 'November', 'november' => 'November', 'desember' => 'December',
            'ags' => 'August', 'agu' => 'August', 'agt' => 'August',
            'jan' => 'January', 'feb' => 'February', 'mar' => 'March', 'apr' => 'April',
            'jun' => 'June', 'jul' => 'July', 'sep' => 'September', 'sept' => 'September',
            'okt' => 'October', 'nop' => 'November', 'nov' => 'November',
            'des' => 'December', 'desm' => 'December', 'dec' => 'December',
        ];

        // urutkan dari kata terpanjang biar gak tabrakan
        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        $tglEnglish = $tanggal;
        foreach ($map as $indo => $eng) {
            $tglEnglish = preg_replace('/\b'. preg_quote($indo, '/'). '\b/i', $eng, $tglEnglish);
        }

        try {
            return Carbon::parse($tglEnglish)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('d M Y', $tglEnglish)->format('Y-m-d');
            } catch (\Exception $e2) {
                try {
                    return Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
                } catch (\Exception $e3) {
                    Log::warning("Gagal parse tanggal final: {$tanggal} -> {$tglEnglish}");
                    return null;
                }
            }
        }
    }

    private function parseTanggalIndoSafe($tanggal) {
        try {
            $parsed = $this->parseTanggalIndo($tanggal);
            return $parsed? Carbon::parse($parsed) : null;
        } catch (\Exception $e) { return null; }
    }

    public function index(Request $request)
    {
        try {
            $query = Guru::with(['user', 'mataPelajaran']);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nuptk', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhere('status_kepegawaian', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($user) use ($search) {
                          $user->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }
            $guru = $query->orderBy('created_at', 'desc')->paginate(10);
            $guruLaki = Guru::where('jenis_kelamin', 'L')->count();
            $guruPerempuan = Guru::where('jenis_kelamin', 'P')->count();
            $rataUsia = Guru::whereNotNull('tanggal_lahir')->get()
                ->map(function($g) {
                    $carbon = $this->parseTanggalIndoSafe($g->tanggal_lahir);
                    return $carbon? $carbon->age : null;
                })->filter()->avg();
            $rataUsia = round($rataUsia?? 0);
            return view('administrasi.guru.index', compact('guru', 'guruLaki', 'guruPerempuan', 'rataUsia'));
        } catch (\Exception $e) {
            Log::error('Error in Guru Index: '. $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function create()
    {
        $mataPelajaran = Mapel::where('status', 'aktif')->orderBy('nama_mapel')->get();
        return view('administrasi.guru.create', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'nuptk' => 'nullable|string|max:20|unique:gurus,nuptk',
            'jabatan' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'jurusan_pendidikan' => 'required|string|max:100',
            'tanggal_masuk' => 'required|string',
            'agama' => 'required|string|max:20',
            'nip' => 'nullable|string|max:50|unique:gurus,nip',
            'nama_universitas' => 'nullable|string|max:200',
            'tahun_lulus' => 'nullable|integer|min:1980|max:'. date('Y'),
            'tmt' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajarans,id',
        ]);

        try {
            DB::beginTransaction();
            $nip = $request->nip?: $this->generateNIP($request->tanggal_lahir, $this->getNextGuruId());
            $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $request->nama_guru)). '@guru.sch.id';
            $email = $this->generateUniqueEmail($email);
            $user = User::create(['name' => $request->nama_guru,'email' => $email,'password' => Hash::make('password123'),'role' => 'guru','status' => 'aktif']);
            $guru = Guru::create([
                'user_id' => $user->id,'nip' => $nip,'nuptk' => $request->nuptk,
                'nama_lengkap' => $request->nama_guru,'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $this->parseTanggalIndo($request->tanggal_lahir),
                'alamat' => $request->alamat_lengkap,'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->nama_universitas,'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $this->parseTanggalIndo($request->tanggal_masuk),
                'tmt' => $this->parseTanggalIndo($request->tmt),
                'status_kepegawaian' => $request->jabatan,'agama' => $request->agama,'status' => 'aktif'
            ]);
            if ($request->has('mata_pelajaran') &&!empty($request->mata_pelajaran)) {
                $guru->mataPelajaran()->sync($request->mata_pelajaran);
            }
            DB::commit();
            return redirect()->route('administrasi.guru.index')->with('success', "✅ Guru {$guru->nama_lengkap} berhasil ditambahkan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal menyimpan: '. $e->getMessage())->withInput();
        }
    }

    public function show($id){ try { $guru = Guru::with(['user','mataPelajaran','kelasWali'])->findOrFail($id); return view('administrasi.guru.show', compact('guru')); } catch (\Exception $e) { return back()->with('error','Data guru tidak ditemukan'); } }
    public function edit($id){ try { $guru = Guru::with(['user','mataPelajaran'])->findOrFail($id); $mataPelajaran = Mapel::where('status','aktif')->orderBy('nama_mapel')->get(); $selectedMapel = $guru->mataPelajaran->pluck('id')->toArray(); return view('administrasi.guru.edit', compact('guru','mataPelajaran','selectedMapel')); } catch (\Exception $e) { return back()->with('error','Data guru tidak ditemukan'); } }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255','jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100','tanggal_lahir' => 'required|string','alamat' => 'required|string',
            'nuptk' => 'nullable|string|max:20|unique:gurus,nuptk,'.$id,'jabatan' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50','jurusan_pendidikan' => 'required|string|max:100',
            'tanggal_masuk' => 'required|string','agama' => 'required|string|max:20',
            'nip' => 'required|string|max:50|unique:gurus,nip,'.$id,'nama_universitas' => 'nullable|string|max:200',
            'tahun_lulus' => 'nullable|integer|min:1980|max:'.date('Y'),'tmt' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20','status' => 'nullable|string|in:aktif,nonaktif',
            'mata_pelajaran' => 'nullable|array','mata_pelajaran.*' => 'exists:mata_pelajarans,id',
        ]);
        try {
            DB::beginTransaction();
            $guru = Guru::findOrFail($id);
            $guru->update([
                'nama_lengkap' => $request->nama_lengkap,'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,'tanggal_lahir' => $this->parseTanggalIndo($request->tanggal_lahir),
                'alamat' => $request->alamat,'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,'jurusan_pendidikan' => $request->jurusan_pendidikan,
                'universitas' => $request->nama_universitas,'tahun_lulus' => $request->tahun_lulus,
                'tmt_masuk' => $this->parseTanggalIndo($request->tanggal_masuk),
                'tmt' => $this->parseTanggalIndo($request->tmt),
                'status_kepegawaian' => $request->jabatan,'agama' => $request->agama,
                'nuptk' => $request->nuptk,'nip' => $request->nip,'status' => $request->status?? 'aktif'
            ]);
            if ($guru->user) $guru->user->update(['name' => $request->nama_lengkap]);
            $guru->mataPelajaran()->sync($request->has('mata_pelajaran')? $request->mata_pelajaran : []);
            DB::commit();
            return redirect()->route('administrasi.guru.index')->with('success', "✅ Guru {$guru->nama_lengkap} berhasil diupdate!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal mengupdate: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $guru = Guru::with(['user','mataPelajaran'])->findOrFail($id);
            $guru->mataPelajaran()->detach();
            Kelas::where('wali_kelas_id',$guru->id)->update(['wali_kelas_id'=>null]);
            if ($guru->user) $guru->user->delete();
            $guru->delete();
            DB::commit();
            return redirect()->route('administrasi.guru.index')->with('success', "✅ Guru {$guru->nama_lengkap} berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal menghapus guru: '.$e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Guru::with(['user','mataPelajaran']);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(fn($q)=>$q->where('nama_lengkap','LIKE',"%{$search}%")->orWhere('nuptk','LIKE',"%{$search}%")->orWhere('nip','LIKE',"%{$search}%"));
            }
            $guruList = $query->orderBy('created_at','desc')->get();
            $headers = ['NO','NAMA GURU','JK','TEMPAT LAHIR','TANGGAL LAHIR','ALAMAT','NUPTK','NIP','JABATAN','MATA PELAJARAN','PENDIDIKAN TERAKHIR','JURUSAN','UNIVERSITAS','TAHUN LULUS','TMT MASUK','TMT DARUL ULUM','AGAMA','NO TELEPON','STATUS'];
            $self = $this;
            $callback = function() use ($headers,$guruList,$self) {
                $handle = fopen('php://output','w');
                fwrite($handle,"\xEF\xBB\xBF");
                fputcsv($handle,$headers);
                $no=1;
                foreach ($guruList as $g) {
                    fputcsv($handle,[
                        $no++,$g->nama_lengkap,$g->jenis_kelamin,$g->tempat_lahir,
                        $g->tanggal_lahir?($self->parseTanggalIndo($g->tanggal_lahir)??$g->tanggal_lahir):'',
                        $g->alamat,$g->nuptk,$g->nip,$g->status_kepegawaian,
                        $g->mataPelajaran->pluck('nama_mapel')->implode(', '),
                        $g->pendidikan_terakhir,$g->jurusan_pendidikan,$g->universitas,$g->tahun_lulus,
                        $g->tmt_masuk?($self->parseTanggalIndo($g->tmt_masuk)??$g->tmt_masuk):'',
                        $g->tmt?($self->parseTanggalIndo($g->tmt)??$g->tmt):'',
                        $g->agama,$g->no_telepon,$g->status
                    ]);
                }
                fclose($handle);
            };
            return response()->streamDownload($callback,'data_guru_'.Carbon::now()->format('Y-m-d_His').'.csv',['Content-Type'=>'text/csv; charset=UTF-8']);
        } catch (\Exception $e) {
            return redirect()->route('administrasi.guru.index')->with('error','❌ Gagal export data: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = ['NO','NAMA GURU','JK','TEMPAT TANGGAL LAHIR','ALAMAT LENGKAP','NUPTK','JABATAN','NAMA UNIVERSITAS','JURUSAN','TAHUN LULUS','TMT SMK DARUL ULUM','MATA PELAJARAN (pisahkan dengan koma)'];
        $data = [[1,'Ahmad Sudrajat','L','Jakarta, 15 Mei 1980','Jl. Pendidikan No. 123','1234567890123456','Guru Matematika','UNJ','Pendidikan Matematika','2005','2010-07-01','Matematika, Fisika']];
        $callback = function() use ($headers,$data){$h=fopen('php://output','w');fwrite($h,"\xEF\xBB\xBF");fputcsv($h,$headers);foreach($data as $r)fputcsv($h,$r);fclose($h);};
        return response()->streamDownload($callback,'template_guru.csv',['Content-Type'=>'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $request->validate(['file'=>'required|file|mimes:csv,txt|max:2048']);
        try {
            $file=$request->file('file');$successCount=0;$failedCount=0;$errors=[];
            $handle=fopen($file->getPathname(),'r');$headers=fgetcsv($handle,0,',');
            DB::beginTransaction();$rowNumber=1;
            while(($row=fgetcsv($handle,0,','))!==false){
                $rowNumber++;if(count(array_filter($row))<4)continue;
                $namaGuru=trim($row[1]??'');$jk=trim($row[2]??'');$tempatTanggalLahir=trim($row[3]??'');
                $alamat=trim($row[4]??'');$nuptk=trim(preg_replace('/\s+/','',$row[5]??''));$jabatan=trim($row[6]??'');
                $universitas=trim($row[7]??'');$jurusan=trim($row[8]??'');$tahunLulus=trim($row[9]??'');$tmt=trim($row[10]??'');$mapelList=trim($row[11]??'');
                if(empty($namaGuru)){ $failedCount++; $errors[]="Baris {$rowNumber}: NAMA kosong"; continue; }
                if(empty($jk)||!in_array(strtoupper($jk),['L','P'])){ $failedCount++; $errors[]="Baris {$rowNumber}: JK salah"; continue; }
                if(empty($jabatan)){ $failedCount++; $errors[]="Baris {$rowNumber}: JABATAN kosong"; continue; }
                $tempatLahir='';$tglRaw='';
                if(!empty($tempatTanggalLahir)&&strpos($tempatTanggalLahir,',')!==false){$p=explode(',',$tempatTanggalLahir,2);$tempatLahir=trim($p[0]);$tglRaw=trim($p[1]);}else{$tempatLahir=$tempatTanggalLahir;}
                $tanggalLahir=$this->parseTanggalIndo($tglRaw);$tmtParsed=$this->parseTanggalIndo($tmt);
                if(!empty($nuptk)&&Guru::where('nuptk',$nuptk)->exists()){ $failedCount++; $errors[]="Baris {$rowNumber}: NUPTK duplikat"; continue; }
                $nip='NIP'.date('Ymd').str_pad($rowNumber,4,'0',STR_PAD_LEFT);
                while(Guru::where('nip',$nip)->exists()) $nip='NIP'.date('Ymd').str_pad($rowNumber.rand(1,99),4,'0',STR_PAD_LEFT);
                $email=strtolower(preg_replace('/[^a-zA-Z0-9]/','.',$namaGuru)).'@guru.sch.id';$email=$this->generateUniqueEmail($email);
                $user=User::create(['name'=>$namaGuru,'email'=>$email,'password'=>Hash::make('password123'),'role'=>'guru','status'=>'aktif']);
                $guru=Guru::create(['user_id'=>$user->id,'nip'=>$nip,'nuptk'=>!empty($nuptk)?$nuptk:null,'nama_lengkap'=>$namaGuru,'jenis_kelamin'=>strtoupper($jk),'tempat_lahir'=>$tempatLahir,'tanggal_lahir'=>$tanggalLahir,'alamat'=>$alamat,'pendidikan_terakhir'=>$this->getPendidikanTerakhir($tahunLulus),'jurusan_pendidikan'=>!empty($jurusan)?$jurusan:'Pendidikan','universitas'=>!empty($universitas)?$universitas:null,'tahun_lulus'=>!empty($tahunLulus)&&is_numeric($tahunLulus)?(int)$tahunLulus:null,'tmt_masuk'=>$tmtParsed??date('Y-m-d'),'tmt'=>$tmtParsed,'status_kepegawaian'=>$jabatan,'agama'=>'Islam','status'=>'aktif']);
                if(!empty($mapelList)){$names=array_map('trim',explode(',',$mapelList));$ids=[];foreach($names as $nm){$m=Mapel::where('nama_mapel','LIKE',"%{$nm}%")->first();if($m)$ids[]=$m->id;}if(!empty($ids))$guru->mataPelajaran()->sync($ids);}
                $successCount++;
            }
            fclose($handle);DB::commit();
            $msg="✅ Import selesai! {$successCount} berhasil.";
            if($failedCount>0){$msg="⚠ Import: {$successCount} berhasil, {$failedCount} gagal.";return redirect()->route('administrasi.guru.index')->with('warning',$msg)->with('import_errors',$errors);}
            return redirect()->route('administrasi.guru.index')->with('success',$msg);
        } catch (\Exception $e) {
            if(isset($handle)&&is_resource($handle))fclose($handle);
            DB::rollBack();
            Log::error('Error Import: '.$e->getMessage());
            return redirect()->route('administrasi.guru.index')->with('error','❌ Gagal import: '.$e->getMessage());
        }
    }

    private function getNextGuruId(){ $last=Guru::orderBy('id','desc')->first(); return ($last?$last->id:0)+1; }
    private function generateUniqueEmail($email){ $ori=$email;$c=1;while(User::where('email',$email)->exists()){$p=strpos($ori,'@');$email=$p!==false?substr($ori,0,$p).$c.substr($ori,$p):$ori.$c;$c++;}return $email; }
    private function generateNIP($tgl,$row){ return 'NIP'.date('Ymd').str_pad($row,4,'0',STR_PAD_LEFT); }
    private function getPendidikanTerakhir($th){ if(empty($th))return 'S1';$t=(int)$th;if($t>=2020)return 'S2';if($t>=2010)return 'S1';if($t>=2000)return 'S1';if($t>=1990)return 'D4';if($t>=1980)return 'D3';return 'SMA'; }
}