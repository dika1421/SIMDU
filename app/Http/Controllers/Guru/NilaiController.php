<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel; // Ganti MataPelajaran menjadi Mapel
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                Log::warning('Guru not found for user: ' . $user->id);
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru. Hubungi administrator.');
            }
            
            // Ambil kelas yang diajar oleh guru
            $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();
            
            // Ambil mata pelajaran yang diajar oleh guru - gunakan Mapel
            $mapel = Mapel::whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();
            
            // Hitung statistik nilai
            $statistik = [];
            foreach ($kelas as $k) {
                foreach ($mapel as $m) {
                    $nilai = Nilai::where('guru_id', $guru->id)
                                  ->where('kelas_id', $k->id)
                                  ->where('mata_pelajaran_id', $m->id)
                                  ->where('status', 'published')
                                  ->get();
                    
                    $statistik[$k->id][$m->id] = [
                        'rata_rata' => $nilai->avg('nilai_akhir') ?? 0,
                        'jumlah_siswa' => $nilai->count(),
                        'nilai_tertinggi' => $nilai->max('nilai_akhir') ?? 0,
                        'nilai_terendah' => $nilai->min('nilai_akhir') ?? 0
                    ];
                }
            }
            
            return view('guru.nilai.index', compact('kelas', 'mapel', 'statistik'));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function input(Request $request)
    {
        try {
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
                'tahun_ajaran' => 'required',
                'semester' => 'required|in:ganjil,genap'
            ]);
            
            $siswa = Siswa::where('kelas_id', $request->kelas_id)
                          ->where('status', 'aktif')
                          ->with('user')
                          ->get();
            
            if ($siswa->isEmpty()) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Tidak ada siswa di kelas ini.');
            }
            
            $mataPelajaran = Mapel::find($request->mata_pelajaran_id); // Gunakan Mapel
            $kelas = Kelas::find($request->kelas_id);
            
            if (!$mataPelajaran) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Mata pelajaran tidak ditemukan.');
            }
            
            if (!$kelas) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Kelas tidak ditemukan.');
            }
            
            foreach ($siswa as $s) {
                $s->nilai = Nilai::where('siswa_id', $s->id)
                                 ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                                 ->where('tahun_ajaran', $request->tahun_ajaran)
                                 ->where('semester', $request->semester)
                                 ->first();
            }
            
            return view('guru.nilai.input', compact('siswa', 'mataPelajaran', 'kelas', 'request'));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai input: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function raport(Request $request)
    {
        try {
            $siswaId = $request->siswa_id;
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            
            if (!$siswaId) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Siswa tidak dipilih.');
            }
            
            $siswa = Siswa::with(['kelas', 'user'])->find($siswaId);
            
            if (!$siswa) {
                return redirect()->route('guru.nilai.index')
                               ->with('error', 'Siswa tidak ditemukan.');
            }
            
            $nilai = Nilai::with(['mataPelajaran']) // Relasi mataPelajaran akan menggunakan Mapel
                          ->where('siswa_id', $siswaId)
                          ->where('tahun_ajaran', $tahunAjaran)
                          ->where('semester', $semester)
                          ->where('status', 'published')
                          ->get();
            
            $rataRata = $nilai->avg('nilai_akhir') ?? 0;
            $totalNilai = $nilai->sum('nilai_akhir');
            $jumlahMapel = $nilai->count();
            
            return view('guru.nilai.raport', compact('siswa', 'nilai', 'tahunAjaran', 'semester', 'rataRata', 'totalNilai', 'jumlahMapel'));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai raport: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function publish(Request $request)
    {
        try {
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
                'tahun_ajaran' => 'required',
                'semester' => 'required|in:ganjil,genap'
            ]);
            
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            
            if (!$guru) {
                return back()->with('error', 'Anda tidak terdaftar sebagai guru.');
            }
            
            $updated = Nilai::where('kelas_id', $request->kelas_id)
                            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                            ->where('tahun_ajaran', $request->tahun_ajaran)
                            ->where('semester', $request->semester)
                            ->where('guru_id', $guru->id)
                            ->update(['status' => 'published', 'is_rapor' => true]);
            
            if ($updated > 0) {
                return redirect()->route('guru.nilai.index')
                               ->with('success', $updated . ' nilai berhasil dipublish ke raport.');
            } else {
                return redirect()->route('guru.nilai.index')
                               ->with('warning', 'Tidak ada nilai yang dipublish.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error in nilai publish: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')
                           ->with('error', 'Gagal mempublish nilai: ' . $e->getMessage());
        }
    }
    
    /**
     * Hitung nilai akhir berdasarkan komponen nilai
     */
    private function hitungNilaiAkhir($data)
    {
        $nilaiHarian = array_filter([
            $data['nilai_harian_1'] ?? null,
            $data['nilai_harian_2'] ?? null,
            $data['nilai_harian_3'] ?? null
        ]);
        $rataHarian = count($nilaiHarian) > 0 ? array_sum($nilaiHarian) / count($nilaiHarian) : 0;
        
        $nilaiTugas = array_filter([
            $data['nilai_tugas_1'] ?? null,
            $data['nilai_tugas_2'] ?? null
        ]);
        $rataTugas = count($nilaiTugas) > 0 ? array_sum($nilaiTugas) / count($nilaiTugas) : 0;
        
        $uts = $data['nilai_uts'] ?? 0;
        $uas = $data['nilai_uas'] ?? 0;
        $praktek = $data['nilai_praktek'] ?? 0;
        
        $bobotHarian = 0.20;
        $bobotTugas = 0.20;
        $bobotUTS = 0.30;
        $bobotUAS = 0.30;
        
        if ($praktek > 0) {
            $bobotHarian = 0.15;
            $bobotTugas = 0.15;
            $bobotUTS = 0.25;
            $bobotUAS = 0.25;
            $bobotPraktek = 0.20;
            
            $nilaiAkhir = ($rataHarian * $bobotHarian) +
                          ($rataTugas * $bobotTugas) +
                          ($uts * $bobotUTS) +
                          ($uas * $bobotUAS) +
                          ($praktek * $bobotPraktek);
        } else {
            $nilaiAkhir = ($rataHarian * $bobotHarian) +
                          ($rataTugas * $bobotTugas) +
                          ($uts * $bobotUTS) +
                          ($uas * $bobotUAS);
        }
        
        return round($nilaiAkhir, 2);
    }
}