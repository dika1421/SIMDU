<?php
// app/Http/Controllers/Siswa/PembayaranController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    private function getSiswa()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return null;
            }
            
            $siswa = Siswa::where('user_id', $user->id)->first();
            
            if (!$siswa) {
                $kelas = Kelas::first();
                
                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'nis' => 'SIS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'nisn' => 'NSN' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $user->name,
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2008-01-01',
                    'alamat' => $user->alamat ?? '-',
                    'no_telepon' => $user->no_telepon ?? '-',
                    'agama' => 'Islam',
                    'kelas_id' => $kelas ? $kelas->id : null,
                    'status' => 'aktif',
                    'tahun_masuk' => date('Y')
                ]);
                
                Log::info('Auto created siswa for user: ' . $user->id);
            }
            
            return $siswa;
            
        } catch (\Exception $e) {
            Log::error('Error in getSiswa: ' . $e->getMessage());
            return null;
        }
    }

    public function index(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $pembayaran = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $totalNominal = $pembayaran->sum('nominal');
            $totalDibayar = $pembayaran->sum('jumlah_dibayar');
            $totalSisa = $pembayaran->sum('sisa');
            
            $statistikStatus = [
                'lunas' => $pembayaran->where('status', 'lunas')->count(),
                'belum_lunas' => $pembayaran->where('status', 'belum_lunas')->count(),
                'pending' => $pembayaran->where('status', 'pending')->count(),
            ];
            
            $byJenis = [];
            $grouped = $pembayaran->groupBy('jenis_pembayaran');
            
            foreach ($grouped as $key => $items) {
                if (empty($key)) continue;
                
                $byJenis[$key] = [
                    'label' => $this->getJenisLabel($key),
                    'nominal' => $items->sum('nominal'),
                    'dibayar' => $items->sum('jumlah_dibayar'),
                    'sisa' => $items->sum('sisa'),
                    'count' => $items->count(),
                    'persentase' => $items->sum('nominal') > 0 
                        ? round(($items->sum('jumlah_dibayar') / $items->sum('nominal')) * 100, 2) 
                        : 0
                ];
            }
            
            $persentase = $totalNominal > 0 
                ? round(($totalDibayar / $totalNominal) * 100, 2) 
                : 0;
            
            $chartData = [
                'labels' => array_column($byJenis, 'label'),
                'nominal' => array_column($byJenis, 'nominal'),
                'dibayar' => array_column($byJenis, 'dibayar'),
                'sisa' => array_column($byJenis, 'sisa'),
            ];
            
            $jenisList = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->whereNotNull('jenis_pembayaran')
                ->distinct()
                ->pluck('jenis_pembayaran')
                ->toArray();
            
            $jenisOptions = [];
            foreach ($jenisList as $jenis) {
                $jenisOptions[$jenis] = $this->getJenisLabel($jenis);
            }
            
            return view('siswa.pembayaran.index', compact(
                'siswa', 'pembayaran', 'totalNominal', 'totalDibayar', 
                'totalSisa', 'statistikStatus', 'byJenis', 'persentase',
                'chartData', 'jenisOptions', 'jenisList'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in pembayaran index: ' . $e->getMessage());
            
            return view('siswa.pembayaran.index', [
                'siswa' => $siswa ?? null,
                'pembayaran' => collect([]),
                'totalNominal' => 0,
                'totalDibayar' => 0,
                'totalSisa' => 0,
                'statistikStatus' => ['lunas' => 0, 'belum_lunas' => 0, 'pending' => 0],
                'byJenis' => [],
                'persentase' => 0,
                'chartData' => ['labels' => [], 'nominal' => [], 'dibayar' => [], 'sisa' => []],
                'jenisOptions' => [],
                'jenisList' => []
            ]);
        }
    }
    
    public function show($id)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $pembayaran = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->findOrFail($id);
            
            return view('siswa.pembayaran.show', compact('pembayaran', 'siswa'));
            
        } catch (\Exception $e) {
            Log::error('Error in pembayaran show: ' . $e->getMessage());
            return redirect()->route('siswa.pembayaran.index')
                ->with('error', 'Data pembayaran tidak ditemukan');
        }
    }
    
    public function riwayat(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $jenis = $request->jenis;
            $status = $request->status;
            $search = $request->search;
            
            $query = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan');
            
            if ($jenis && $jenis != 'semua') {
                $query->where('jenis_pembayaran', $jenis);
            }
            
            if ($status && $status != 'semua') {
                $query->where('status', $status);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('no_transaksi', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }
            
            $pembayaran = $query->orderBy('tanggal', 'desc')
                ->paginate(15)
                ->appends(request()->query());
            
            $jenisList = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->whereNotNull('jenis_pembayaran')
                ->distinct()
                ->pluck('jenis_pembayaran')
                ->toArray();
            
            $jenisOptions = [];
            foreach ($jenisList as $j) {
                $jenisOptions[$j] = $this->getJenisLabel($j);
            }
            
            $statusOptions = [
                'lunas' => 'Lunas',
                'belum_lunas' => 'Belum Lunas',
                'pending' => 'Pending',
            ];
            
            return view('siswa.pembayaran.riwayat', compact(
                'pembayaran', 'jenisOptions', 'statusOptions',
                'jenis', 'status', 'search', 'siswa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in pembayaran riwayat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat riwayat pembayaran: ' . $e->getMessage());
        }
    }
    
    public function cetakStruk($id)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $pembayaran = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->findOrFail($id);
            
            return view('siswa.pembayaran.cetak-struk', compact('pembayaran', 'siswa'));
            
        } catch (\Exception $e) {
            Log::error('Error in cetak struk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencetak struk');
        }
    }
    
    /**
     * Tagihan Tahunan - TANPA strtotime!
     */
    public function tagihanTahunan(Request $request)
    {
        try {
            $siswa = $this->getSiswa();
            
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
            }
            
            $tahun = $request->tahun ?? date('Y');
            
            $tagihan = Keuangan::where('siswa_id', $siswa->id)
                ->where('tipe', 'pemasukan')
                ->whereYear('tanggal', $tahun)
                ->get();
            
            $totalNominal = $tagihan->sum('nominal');
            $totalDibayar = $tagihan->sum('jumlah_dibayar');
            $totalSisa = $tagihan->sum('sisa');
            
            // 🔥 BYPASS: Data per bulan diisi 0 (tidak ada filter bulan)
            $perBulan = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $perBulan[$bulan] = [
                    'bulan' => $this->getNamaBulan($bulan),
                    'nominal' => 0,
                    'dibayar' => 0,
                    'sisa' => 0,
                    'count' => 0
                ];
            }
            
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('siswa.pembayaran.tagihan-tahunan', compact(
                'siswa', 'perBulan', 'tahun', 'tahunList',
                'totalNominal', 'totalDibayar', 'totalSisa'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in tagihan tahunan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat tagihan tahunan: ' . $e->getMessage());
        }
    }
    
    private function getJenisLabel($jenis)
    {
        $labels = [
            'spp' => 'SPP',
            'uang_bangunan' => 'Uang Bangunan',
            'uang_kegiatan' => 'Uang Kegiatan',
            'uang_seragam' => 'Uang Seragam',
            'uang_buku' => 'Uang Buku',
            'pendaftaran' => 'Pendaftaran',
            'uts' => 'UTS',
            'uas' => 'UAS',
            'prakerin' => 'Prakerin',
            'wisuda' => 'Wisuda',
            'lainnya' => 'Lainnya',
        ];
        
        return $labels[$jenis] ?? ucfirst(str_replace('_', ' ', $jenis));
    }
    
    private function getNamaBulan($bulan)
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanList[$bulan] ?? '-';
    }
}