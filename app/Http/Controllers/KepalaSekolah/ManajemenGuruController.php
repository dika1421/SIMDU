<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManajemenGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Guru::with('user');
            
            // Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhere('nuptk', 'LIKE', "%{$search}%")
                      ->orWhere('jabatan', 'LIKE', "%{$search}%");
                });
            }
            
            // Filter status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $guru = $query->orderBy('nama_lengkap')->paginate(10);
            
            // Statistik
            $totalGuru = Guru::count();
            $guruAktif = Guru::where('status', 'aktif')->count();
            $guruPNS = Guru::where('status_kepegawaian', 'pns')->count();
            $guruHonorer = Guru::where('status_kepegawaian', 'honorer')->count();
            $guruKontrak = Guru::where('status_kepegawaian', 'kontrak')->count();
            
            return view('kepala-sekolah.manajemen-guru.index', compact(
                'guru', 'totalGuru', 'guruAktif', 'guruPNS', 'guruHonorer', 'guruKontrak'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kepala-sekolah.manajemen-guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ... kode store ...
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // ... kode show ...
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // ... kode edit ...
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ... kode update ...
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ... kode destroy ...
    }

    /**
     * Halaman absensi guru
     */
    public function absensi(Request $request)
    {
        try {
            $tanggal = $request->tanggal ?? Carbon::now()->toDateString();
            
            // Ambil semua guru aktif
            $guru = Guru::with('user')
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
            
            // Ambil absensi untuk tanggal tersebut
            foreach ($guru as $g) {
                $g->absensi_hari_ini = DB::table('absensi')
                    ->where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();
            }
            
            $statusList = [
                'hadir' => 'Hadir',
                'sakit' => 'Sakit',
                'izin' => 'Izin',
                'alfa' => 'Alfa',
                'terlambat' => 'Terlambat'
            ];
            
            return view('kepala-sekolah.manajemen-guru.absensi', compact('guru', 'tanggal', 'statusList'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data absensi: ' . $e->getMessage());
        }
    }

    /**
     * Simpan absensi guru
     */
    public function storeAbsensi(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'absensi' => 'required|array',
                'absensi.*.status' => 'nullable|in:hadir,sakit,izin,alfa,terlambat',
            ]);
            
            DB::beginTransaction();
            
            $savedCount = 0;
            foreach ($request->absensi as $id => $data) {
                if (isset($data['status']) && !empty($data['status'])) {
                    // Cek apakah sudah ada absensi hari ini
                    $existing = DB::table('absensi')
                        ->where('absensi_type', 'guru')
                        ->where('guru_id', $id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();
                    
                    $absensiData = [
                        'absensi_type' => 'guru',
                        'guru_id' => $id,
                        'tanggal' => $request->tanggal,
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                        'diinput_oleh' => auth()->id(),
                        'waktu_masuk' => $data['status'] === 'hadir' ? ($data['waktu_masuk'] ?? now()->toTimeString()) : null,
                        'updated_at' => now(),
                    ];
                    
                    if ($existing) {
                        DB::table('absensi')->where('id', $existing->id)->update($absensiData);
                    } else {
                        $absensiData['created_at'] = now();
                        DB::table('absensi')->insert($absensiData);
                    }
                    $savedCount++;
                }
            }
            
            DB::commit();
            
            if ($savedCount === 0) {
                return redirect()->back()->with('warning', 'Tidak ada data absensi yang dipilih');
            }
            
            return redirect()->back()->with('success', 'Absensi guru berhasil disimpan');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Rekap absensi guru
     */
    public function rekapAbsensi(Request $request)
    {
        try {
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            $guru = Guru::where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
            
            $rekap = [];
            foreach ($guru as $g) {
                $absensi = DB::table('absensi')
                    ->where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $total = $absensi->count();
                
                $rekap[] = [
                    'id' => $g->id,
                    'nama' => $g->nama_lengkap,
                    'nuptk' => $g->nuptk,
                    'nip' => $g->nip,
                    'hadir' => $hadir,
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'alfa' => $absensi->where('status', 'alfa')->count(),
                    'terlambat' => $absensi->where('status', 'terlambat')->count(),
                    'total' => $total,
                    'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                ];
            }
            
            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('kepala-sekolah.manajemen-guru.rekap-absensi', compact('rekap', 'bulan', 'tahun', 'bulanList', 'tahunList'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat rekap absensi: ' . $e->getMessage());
        }
    }
}