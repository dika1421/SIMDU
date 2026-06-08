<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * Halaman input absensi siswa
     */
    public function siswa(Request $request)
    {
        try {
            $kelas = Kelas::with('jurusan')->get();
            $tanggal = $request->tanggal ?? Carbon::now()->toDateString();
            $kelas_id = $request->kelas_id;
            
            $query = Siswa::with('user', 'kelas')->where('status', 'aktif');
            
            if ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            }
            
            $siswa = $query->orderBy('nama_lengkap')->get();
            
            // PERBAIKAN: Query langsung menggunakan DB::table
            foreach ($siswa as $s) {
                $s->absensi_hari_ini = DB::table('absensi')
                    ->where('absensi_type', 'siswa')
                    ->where('siswa_id', $s->id)
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
            
            return view('administrasi.absensi.siswa', compact('siswa', 'kelas', 'tanggal', 'kelas_id', 'statusList'));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Halaman input absensi guru
     */
    public function guru(Request $request)
    {
        try {
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            $tanggal = $request->tanggal ?? Carbon::now()->toDateString();
            
            // PERBAIKAN: Query langsung menggunakan DB::table
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
            
            return view('administrasi.absensi.guru', compact('guru', 'tanggal', 'statusList'));
            
        } catch (\Exception $e) {
            Log::error('Error in absensi guru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Simpan absensi siswa
     */
    public function storeSiswa(Request $request)
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
                    // Cari siswa untuk memastikan ID valid
                    $siswa = Siswa::find($id);
                    if (!$siswa) {
                        continue;
                    }
                    
                    // Cek apakah sudah ada absensi hari ini
                    $existing = DB::table('absensi')
                        ->where('absensi_type', 'siswa')
                        ->where('siswa_id', $id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();
                    
                    $absensiData = [
                        'absensi_type' => 'siswa',
                        'siswa_id' => $id,
                        'guru_id' => null,
                        'tanggal' => $request->tanggal,
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                        'diinput_oleh' => auth()->id(),
                        'waktu_masuk' => $data['status'] === 'hadir' ? ($data['waktu_masuk'] ?? now()->toTimeString()) : null,
                        'waktu_keluar' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    if ($existing) {
                        DB::table('absensi')
                            ->where('id', $existing->id)
                            ->update($absensiData);
                    } else {
                        DB::table('absensi')->insert($absensiData);
                    }
                    $savedCount++;
                }
            }
            
            DB::commit();
            
            if ($savedCount === 0) {
                return redirect()->back()->with('warning', 'Tidak ada data absensi yang dipilih');
            }
            
            return redirect()->back()->with('success', 'Absensi siswa berhasil disimpan');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in storeSiswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }
    
    /**
     * Simpan absensi guru
     */
    public function storeGuru(Request $request)
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
                    // Cari guru untuk memastikan ID valid
                    $guru = Guru::find($id);
                    if (!$guru) {
                        continue;
                    }
                    
                    // Cek apakah sudah ada absensi hari ini
                    $existing = DB::table('absensi')
                        ->where('absensi_type', 'guru')
                        ->where('guru_id', $id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();
                    
                    $absensiData = [
                        'absensi_type' => 'guru',
                        'guru_id' => $id,
                        'siswa_id' => null,
                        'tanggal' => $request->tanggal,
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                        'diinput_oleh' => auth()->id(),
                        'waktu_masuk' => $data['status'] === 'hadir' ? ($data['waktu_masuk'] ?? now()->toTimeString()) : null,
                        'waktu_keluar' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    if ($existing) {
                        DB::table('absensi')
                            ->where('id', $existing->id)
                            ->update($absensiData);
                    } else {
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
            Log::error('Error in storeGuru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }
    
    /**
     * Rekap absensi siswa - PERBAIKAN UTAMA
     */
    public function rekapSiswa(Request $request)
    {
        try {
            $kelas = Kelas::with('jurusan')->get();
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            $kelas_id = $request->kelas_id;
            
            $query = Siswa::with('user', 'kelas')->where('status', 'aktif');
            
            if ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            }
            
            $siswa = $query->orderBy('nama_lengkap')->get();
            
            // PERBAIKAN: Menggunakan DB::table untuk menghindari relasi polymorphic
            $statistik = [];
            foreach ($siswa as $s) {
                $absensi = DB::table('absensi')
                    ->where('absensi_type', 'siswa')
                    ->where('siswa_id', $s->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $total = $absensi->count();
                
                $statistik[$s->id] = [
                    'hadir' => $hadir,
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'alfa' => $absensi->where('status', 'alfa')->count(),
                    'terlambat' => $absensi->where('status', 'terlambat')->count(),
                    'total' => $total,
                    'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                ];
            }
            
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('administrasi.absensi.rekap-siswa', compact('siswa', 'kelas', 'statistik', 'bulan', 'tahun', 'kelas_id', 'bulanList', 'tahunList'));
            
        } catch (\Exception $e) {
            Log::error('Error in rekapSiswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Rekap absensi guru - PERBAIKAN UTAMA
     */
    public function rekapGuru(Request $request)
    {
        try {
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            $rekap = [];
            foreach ($guru as $g) {
                // PERBAIKAN: Menggunakan DB::table untuk menghindari relasi polymorphic
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
                    'nip' => $g->nip ?? '-',
                    'nuptk' => $g->nuptk ?? '-',
                    'nama' => $g->nama_lengkap,
                    'mapel' => $g->mata_pelajaran_utama ?? '-',
                    'hadir' => $hadir,
                    'sakit' => $absensi->where('status', 'sakit')->count(),
                    'izin' => $absensi->where('status', 'izin')->count(),
                    'alfa' => $absensi->where('status', 'alfa')->count(),
                    'terlambat' => $absensi->where('status', 'terlambat')->count(),
                    'total' => $total,
                    'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                ];
            }
            
            $bulanList = $this->getBulanList();
            $tahunList = range(date('Y') - 2, date('Y') + 1);
            
            return view('administrasi.absensi.rekap-guru', compact('rekap', 'bulan', 'tahun', 'bulanList', 'tahunList'));
            
        } catch (\Exception $e) {
            Log::error('Error in rekapGuru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export absensi siswa ke CSV
     */
    public function exportSiswa(Request $request)
    {
        try {
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            $kelas_id = $request->kelas_id;
            
            $query = Siswa::with('user', 'kelas')->where('status', 'aktif');
            
            if ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            }
            
            $siswa = $query->orderBy('nama_lengkap')->get();
            
            $data = [];
            $no = 1;
            foreach ($siswa as $s) {
                $absensi = DB::table('absensi')
                    ->where('absensi_type', 'siswa')
                    ->where('siswa_id', $s->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $total = $absensi->count();
                
                $data[] = [
                    'NO' => $no++,
                    'NIS' => $s->nis ?? '-',
                    'NAMA SISWA' => $s->nama_lengkap,
                    'KELAS' => $s->kelas->nama ?? '-',
                    'HADIR' => $hadir,
                    'SAKIT' => $absensi->where('status', 'sakit')->count(),
                    'IZIN' => $absensi->where('status', 'izin')->count(),
                    'ALFA' => $absensi->where('status', 'alfa')->count(),
                    'TERLAMBAT' => $absensi->where('status', 'terlambat')->count(),
                    'TOTAL' => $total,
                    'PERSENTASE' => $total > 0 ? round(($hadir / $total) * 100, 2) . '%' : '0%'
                ];
            }
            
            $filename = 'rekap_absensi_siswa_' . $tahun . '_' . $bulan . '.csv';
            $handle = fopen('php://temp', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            
            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]));
            }
            
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
            
            return response($content, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in exportSiswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
    
    /**
     * Export absensi guru ke CSV
     */
    public function exportGuru(Request $request)
    {
        try {
            $bulan = $request->bulan ?? Carbon::now()->month;
            $tahun = $request->tahun ?? Carbon::now()->year;
            
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            
            $data = [];
            $no = 1;
            foreach ($guru as $g) {
                $absensi = DB::table('absensi')
                    ->where('absensi_type', 'guru')
                    ->where('guru_id', $g->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();
                
                $hadir = $absensi->where('status', 'hadir')->count();
                $total = $absensi->count();
                
                $data[] = [
                    'NO' => $no++,
                    'NUPTK' => $g->nuptk ?? '-',
                    'NIP' => $g->nip ?? '-',
                    'NAMA GURU' => $g->nama_lengkap,
                    'MATA PELAJARAN' => $g->mata_pelajaran_utama ?? '-',
                    'HADIR' => $hadir,
                    'SAKIT' => $absensi->where('status', 'sakit')->count(),
                    'IZIN' => $absensi->where('status', 'izin')->count(),
                    'ALFA' => $absensi->where('status', 'alfa')->count(),
                    'TERLAMBAT' => $absensi->where('status', 'terlambat')->count(),
                    'TOTAL' => $total,
                    'PERSENTASE' => $total > 0 ? round(($hadir / $total) * 100, 2) . '%' : '0%'
                ];
            }
            
            $filename = 'rekap_absensi_guru_' . $tahun . '_' . $bulan . '.csv';
            $handle = fopen('php://temp', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            
            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]));
            }
            
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
            
            return response($content, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in exportGuru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
    
    /**
     * Get list of months
     */
    private function getBulanList()
    {
        return [
            1 => 'Januari', 
            2 => 'Februari', 
            3 => 'Maret', 
            4 => 'April',
            5 => 'Mei', 
            6 => 'Juni', 
            7 => 'Juli', 
            8 => 'Agustus',
            9 => 'September', 
            10 => 'Oktober', 
            11 => 'November', 
            12 => 'Desember'
        ];
    }
}