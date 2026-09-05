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
use App\Imports\KelasImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KelasExport;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Kelas::with(['waliKelas.user', 'jurusan', 'siswa']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('tingkat')) {
                $query->where('tingkat', $request->tingkat);
            }
            if ($request->filled('jurusan_id')) {
                $query->where('jurusan_id', $request->jurusan_id);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    // FIX: pakai nama_kelas
                    $q->where('nama_kelas', 'ILIKE', "%{$search}%")
                      ->orWhere('kode_kelas', 'ILIKE', "%{$search}%")
                      ->orWhere('tingkat', 'ILIKE', "%{$search}%");
                });
            }

            // FIX: orderBy nama -> nama_kelas
            $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->paginate(10);

            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            // FIX: Jurusan kamu kadang kolomnya nama_jurusan, bukan nama
            $jurusanList = Jurusan::orderByRaw('COALESCE(nama_jurusan, nama)')->get();

            return view('administrasi.kelas.index', compact('kelas', 'statusList', 'tingkatList', 'jurusanList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasIndex: '. $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            $jurusanList = Jurusan::orderByRaw('COALESCE(nama_jurusan, nama)')->get();
            $tahunAjaran = TahunAjaran::where('is_aktif', true)->first();            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            return view('administrasi.kelas.create', compact('guru', 'jurusanList', 'tahunAjaran', 'tingkatList', 'statusList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasCreate: '. $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            // FIX: validasi harus ke nama_kelas
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();
            // FIX: where nama -> nama_kelas
            $exists = Kelas::where('nama_kelas', $request->nama_kelas)->exists();
            if ($exists) throw new \Exception('Nama kelas sudah terdaftar!');

            $kodeKelas = $request->kode_kelas;
            if (empty($kodeKelas)) {
                $jurusan = Jurusan::find($request->jurusan_id);
                $kodeJurusan = $jurusan? ($jurusan->kode_jurusan?? 'UMUM') : 'UMUM';
                $lastKelas = Kelas::where('tingkat', $request->tingkat)->where('jurusan_id', $request->jurusan_id)->orderBy('id', 'desc')->first();
                if ($lastKelas && $lastKelas->kode_kelas && preg_match('/(\d+)$/', $lastKelas->kode_kelas, $matches)) {
                    $nextNumber = (int)$matches[1] + 1;
                    $kodeKelas = $request->tingkat. '-'. $kodeJurusan. '-'. str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                } else {
                    $kodeKelas = $request->tingkat. '-'. $kodeJurusan. '-01';
                }
            }

            $kelas = Kelas::create([
                'nama_kelas' => $request->nama_kelas, // FIX
                'kode_kelas' => $kodeKelas,
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'wali_kelas_id' => $request->wali_kelas_id,
                'kapasitas' => $request->kapasitas?? 36,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'tahun_ajaran' => $request->tahun_ajaran?? date('Y'). '/'. (date('Y') + 1),
            ]);

            DB::commit();
            return redirect()->route('administrasi.kelas.index')->with('success', '✅ Kelas '. $kelas->nama_kelas. ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in kelasStore: '. $e->getMessage());
            return back()->with('error', '❌ Gagal menyimpan kelas: '. $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $kelas = Kelas::with(['waliKelas.user', 'jurusan', 'siswa.user'])->findOrFail($id);
            $totalSiswa = $kelas->siswa->count();
            $siswaLaki = $kelas->siswa->where('jenis_kelamin', 'L')->count();
            $siswaPerempuan = $kelas->siswa->where('jenis_kelamin', 'P')->count();
            $siswaAktif = $kelas->siswa->where('status', 'aktif')->count();
            return view('administrasi.kelas.show', compact('kelas', 'totalSiswa', 'siswaLaki', 'siswaPerempuan', 'siswaAktif'));
        } catch (\Exception $e) {
            Log::error('Error in kelasShow: '. $e->getMessage());
            return back()->with('error', 'Data kelas tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $kelas = Kelas::with(['siswa', 'waliKelas'])->findOrFail($id);
            $guru = Guru::with('user')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
            $jurusanList = Jurusan::orderByRaw('COALESCE(nama_jurusan, nama)')->get();
            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            $tingkatList = ['X' => 'X (Sepuluh)', 'XI' => 'XI (Sebelas)', 'XII' => 'XII (Dua Belas)', 'XIII' => 'XIII (Tiga Belas)'];
            $statusList = ['aktif' => 'Aktif', 'nonaktif' => 'Non Aktif'];
            return view('administrasi.kelas.edit', compact('kelas', 'guru', 'jurusanList', 'tahunAjaran', 'tingkatList', 'statusList'));
        } catch (\Exception $e) {
            Log::error('Error in kelasEdit: '. $e->getMessage());
            return back()->with('error', 'Data kelas tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,'. $id,
            'tingkat' => 'required|in:X,XI,XII,XIII',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();
            $kelas = Kelas::findOrFail($id);
            $kelas->update([
                'nama_kelas' => $request->nama_kelas, // FIX
                'kode_kelas' => $request->kode_kelas?? $kelas->kode_kelas,
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'wali_kelas_id' => $request->wali_kelas_id,
                'kapasitas' => $request->kapasitas?? $kelas->kapasitas?? 36,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'tahun_ajaran' => $request->tahun_ajaran?? $kelas->tahun_ajaran?? date('Y'). '/'. (date('Y') + 1),
            ]);
            DB::commit();
            return redirect()->route('administrasi.kelas.index')->with('success', '✅ Kelas '. $kelas->nama_kelas. ' berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in kelasUpdate: '. $e->getMessage());
            return back()->with('error', '❌ Gagal mengupdate kelas: '. $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $kelas = Kelas::with(['siswa'])->findOrFail($id);
            if ($kelas->siswa()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak dapat dihapus karena masih memiliki '. $kelas->siswa()->count(). ' siswa!'], 400);
            }
            $namaKelas = $kelas->nama_kelas; // FIX
            $kelas->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kelas '. $namaKelas. ' berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus kelas: '. $e->getMessage()], 500);
        }
    }

    // ==================== IMPORT / EXPORT / TEMPLATE ====================
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt|max:5120'
        ]);

        try {
            $import = new KelasImport();
            Excel::import($import, $request->file('file'));

            $success = $import->getSuccessCount();
            $failed = $import->getFailedCount();
            $errors = $import->getErrors();

            if ($success > 0) {
                $msg = "✅ Berhasil import {$success} kelas baru!";
                if ($failed > 0) {
                    $msg.= " ({$failed} gagal / dilewati).<br><small>". implode('<br>', array_slice($errors, 0, 5)). "</small>";
                }
                return back()->with('success', $msg);
            } else {
                return back()->with('error', '❌ Gagal import. <br>'. implode('<br>', $errors));
            }
        } catch (\Exception $e) {
            Log::error('Import Kelas Error: '.$e->getMessage());
            return back()->with('error', '❌ Gagal import: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['nama_kelas', 'jurusan', 'tingkat', 'kapasitas', 'status']);
            fputcsv($handle, ['X A PEMASARAN', 'PEMASARAN', 'X', '40', 'aktif']);
            fputcsv($handle, ['X B PEMASARAN', 'PEMASARAN', 'X', '40', 'aktif']);
            fclose($handle);
        };
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_kelas.csv"',
        ]);
    }

    public function export()
    {
        try {
            if (class_exists(\App\Exports\KelasExport::class)) {
                return Excel::download(new KelasExport, 'export_kelas_'.date('Ymd').'.xlsx');
            }

            // FIX: orderBy nama -> nama_kelas
            $kelas = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama_kelas')->get();
            $callback = function() use ($kelas) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['nama_kelas', 'kode_kelas', 'tingkat', 'jurusan', 'kapasitas', 'status', 'jumlah_siswa']);
                foreach ($kelas as $k) {
                    fputcsv($handle, [
                        $k->nama_kelas,
                        $k->kode_kelas,
                        $k->tingkat,
                        $k->jurusan->nama_jurusan?? $k->jurusan->nama?? '-',
                        $k->kapasitas,
                        $k->status,
                        $k->siswa->count()
                    ]);
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="export_kelas_'.date('Ymd').'.csv"',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: '.$e->getMessage());
        }
    }

    public function getKelasList(Request $request)
    {
        try {
            $query = Kelas::with('jurusan')->where('status', 'aktif');
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_kelas', 'ILIKE', "%{$search}%")->orWhere('kode_kelas', 'ILIKE', "%{$search}%");
                });
            }
            if ($request->filled('tingkat')) $query->where('tingkat', $request->tingkat);
            if ($request->filled('jurusan_id')) $query->where('jurusan_id', $request->jurusan_id);

            $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->tingkat. ' '. $item->nama_kelas. ($item->jurusan? ' ('.($item->jurusan->nama_jurusan?? $item->jurusan->nama).')' : ''),
                    'tingkat' => $item->tingkat,
                    'nama' => $item->nama_kelas,
                    'nama_kelas' => $item->nama_kelas,
                    'kode_kelas' => $item->kode_kelas,
                ];
            });
            return response()->json(['success' => true, 'data' => $kelas]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getJurusanList(Request $request)
    {
        try {
            $query = Jurusan::query();
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'ILIKE', "%{$search}%")
                      ->orWhere('nama_jurusan', 'ILIKE', "%{$search}%")
                      ->orWhere('kode_jurusan', 'ILIKE', "%{$search}%");
                });
            }
            $jurusan = $query->orderByRaw('COALESCE(nama_jurusan, nama)')->get(['id', 'nama', 'nama_jurusan', 'kode_jurusan'])->map(function($item) {
                $nama = $item->nama_jurusan?? $item->nama;
                return ['id' => $item->id, 'nama' => $nama, 'nama_jurusan' => $nama, 'kode_jurusan' => $item->kode_jurusan, 'text' => $item->kode_jurusan. ' - '. $nama];
            });
            return response()->json(['success' => true, 'data' => $jurusan]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getWaliKelasList(Request $request)
    {
        try {
            $query = Guru::with('user')->where('status', 'aktif');
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'ILIKE', "%{$search}%")->orWhere('nip', 'ILIKE', "%{$search}%");
                });
            }
            $guru = $query->orderBy('nama_lengkap')->get()->map(function($item) {
                return ['id' => $item->id, 'text' => $item->nama_lengkap. ' ('. ($item->user->email?? '-'). ')', 'nama' => $item->nama_lengkap];
            });
            return response()->json(['success' => true, 'data' => $guru]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}