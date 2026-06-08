<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            $kurikulum = $request->kurikulum ?? 'K13';
            
            // Ambil nilai
            $nilai = Nilai::with(['mataPelajaran', 'guru.user'])
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('kurikulum', $kurikulum)
                ->where('status', 'published')
                ->get();
            
            // Hitung statistik
            $statistik = [
                'rata_rata' => $nilai->avg('nilai_akhir') ?? 0,
                'nilai_tertinggi' => $nilai->max('nilai_akhir') ?? 0,
                'nilai_terendah' => $nilai->min('nilai_akhir') ?? 0,
                'jumlah_mapel' => $nilai->count(),
                'mapel_lulus' => $nilai->where('nilai_akhir', '>=', 75)->count(),
                'mapel_tidak_lulus' => $nilai->where('nilai_akhir', '<', 75)->count(),
            ];
            
            // BUAT VARIABLE mapelNilai UNTUK GRAFIK
            $mapelNilai = $nilai->map(function($item) {
                return [
                    'mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                    'nilai' => $item->nilai_akhir,
                    'kkm' => $item->mataPelajaran->kkm ?? 75
                ];
            });
            
            // Data untuk filter
            $tahunAjaranList = $this->getTahunAjaranList();
            $semesterList = ['ganjil', 'genap'];
            $kurikulumList = ['K13', 'Kurikulum Merdeka'];
            
            // TAMBAHKAN VARIABEL SISWA KE COMPACT
            return view('siswa.nilai.index', compact(
                'siswa',          // <-- TAMBAHKAN INI
                'nilai', 
                'statistik', 
                'mapelNilai',
                'tahunAjaran', 
                'semester', 
                'kurikulum', 
                'tahunAjaranList', 
                'semesterList', 
                'kurikulumList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function raport(Request $request)
    {
        try {
            $user = auth()->user();
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $tahunAjaran = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
            $semester = $request->semester ?? 'ganjil';
            
            $nilai = Nilai::with(['mataPelajaran', 'guru.user'])
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->where('status', 'published')
                ->get();
            
            $rataRata = $nilai->avg('nilai_akhir') ?? 0;
            
            return view('siswa.nilai.raport', compact('siswa', 'nilai', 'tahunAjaran', 'semester', 'rataRata'));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai raport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    private function getTahunAjaranList()
    {
        $list = [];
        for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++) {
            $list[] = $i . '/' . ($i + 1);
        }
        return $list;
    }
}