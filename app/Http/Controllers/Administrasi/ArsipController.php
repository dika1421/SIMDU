<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\ArsipDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = ArsipDokumen::with('uploader');

            // Filter kategori
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter tahun (use year of tanggal_dokumen if `tahun` column not present)
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_dokumen', $request->tahun);
            }

            // Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('kode_arsip', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $arsip = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Data untuk filter dropdown
            $kategoriList = [
                'surat_keputusan' => 'Surat Keputusan',
                'laporan_bulanan' => 'Laporan Bulanan',
                'sertifikat' => 'Sertifikat',
                'dokumen_siswa' => 'Dokumen Siswa',
                'dokumen_guru' => 'Dokumen Guru',
                'akreditasi' => 'Akreditasi',
                'kurikulum' => 'Kurikulum',
                'keuangan' => 'Keuangan'
            ];

            $tahunList = ArsipDokumen::selectRaw("EXTRACT(YEAR FROM tanggal_dokumen) AS tahun")
                ->whereNotNull('tanggal_dokumen')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return view('administrasi.arsip.index', compact('arsip', 'kategoriList', 'tahunList'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriList = [
            'surat_keputusan' => 'Surat Keputusan',
            'laporan_bulanan' => 'Laporan Bulanan',
            'sertifikat' => 'Sertifikat',
            'dokumen_siswa' => 'Dokumen Siswa',
            'dokumen_guru' => 'Dokumen Guru',
            'akreditasi' => 'Akreditasi',
            'kurikulum' => 'Kurikulum',
            'keuangan' => 'Keuangan'
        ];
        return view('administrasi.arsip.create', compact('kategoriList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kode_arsip' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tanggal_dokumen' => 'nullable|date',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Upload file
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $path = $file->storeAs('arsip', $fileName, 'public');

            // Generate kode_arsip jika kosong
            $kodeArsip = $request->kode_arsip;
            if (empty($kodeArsip)) {
                $kodeArsip = 'ARS/' . date('Y') . '/' . Str::upper(Str::random(6));
            }

            $arsip = ArsipDokumen::create([
                'kode_arsip' => $kodeArsip,
                'judul' => $request->judul,
                'jenis_dokumen' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'tipe_file' => $file->getClientMimeType(),
                'ukuran_file' => $file->getSize(),
                'kategori' => $request->kategori,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'tahun' => $request->tanggal_dokumen ? Carbon::parse($request->tanggal_dokumen)->year : Carbon::now()->year,
                'uploaded_by' => auth()->id(),
                'status' => 'aktif'
            ]);

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil diarsipkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengarsipkan dokumen: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $arsip = ArsipDokumen::with('uploader')->findOrFail($id);
            return view('administrasi.arsip.show', compact('arsip'));
        } catch (\Exception $e) {
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Dokumen tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            // Cari data arsip
            $arsip = ArsipDokumen::findOrFail($id);
            
            // Siapkan kategori list
            $kategoriList = [
                'surat_keputusan' => 'Surat Keputusan',
                'laporan_bulanan' => 'Laporan Bulanan',
                'sertifikat' => 'Sertifikat',
                'dokumen_siswa' => 'Dokumen Siswa',
                'dokumen_guru' => 'Dokumen Guru',
                'akreditasi' => 'Akreditasi',
                'kurikulum' => 'Kurikulum',
                'keuangan' => 'Keuangan'
            ];
            
            // Cek apakah file fisik ada
            $fileExists = false;
            if ($arsip->path_file && Storage::disk('public')->exists($arsip->path_file)) {
                $fileExists = true;
            }
            
            return view('administrasi.arsip.edit', compact('arsip', 'kategoriList', 'fileExists'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Data dokumen tidak ditemukan');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in Arsip edit: ' . $e->getMessage());
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $arsip = ArsipDokumen::findOrFail($id);

            $request->validate([
                'judul' => 'required|string|max:255',
                'kode_arsip' => 'nullable|string|max:100',
                'kategori' => 'required|string|max:100',
                'tanggal_dokumen' => 'nullable|date',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
                'deskripsi' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Prepare update data
            $data = [
                'kode_arsip' => $request->kode_arsip ?: ('ARS/' . date('Y') . '/' . Str::upper(Str::random(6))),
                'judul' => $request->judul,
                'jenis_dokumen' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'tahun' => $request->tanggal_dokumen ? Carbon::parse($request->tanggal_dokumen)->year : Carbon::now()->year,
            ];

            // Jika upload file baru
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($arsip->path_file && Storage::disk('public')->exists($arsip->path_file)) {
                    Storage::disk('public')->delete($arsip->path_file);
                }
                
                // Upload file baru
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(10) . '.' . $extension;
                $path = $file->storeAs('arsip', $fileName, 'public');
                
                $data['nama_file'] = $file->getClientOriginalName();
                $data['path_file'] = $path;
                $data['tipe_file'] = $file->getClientMimeType();
                $data['ukuran_file'] = $file->getSize();
            }

            $arsip->update($data);

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil diupdate');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Data dokumen tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate dokumen: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $arsip = ArsipDokumen::findOrFail($id);
            
            // Soft delete - file tidak dihapus dulu, hanya record
            $arsip->delete();

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil dipindahkan ke tempat sampah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download file arsip.
     */
    public function download($id)
    {
        try {
            $arsip = ArsipDokumen::findOrFail($id);
            
            // Cek apakah file ada
            if (!$arsip->path_file) {
                return back()->with('error', 'Path file tidak ditemukan di database');
            }
            
            if (!Storage::disk('public')->exists($arsip->path_file)) {
                return back()->with('error', 'File fisik tidak ditemukan di server');
            }

            // Bersihkan nama file untuk download
            $cleanJudul = preg_replace('/[^a-zA-Z0-9]/', '_', $arsip->judul);
            $extension = pathinfo($arsip->path_file, PATHINFO_EXTENSION);
            $fileName = $cleanJudul . '_' . date('Y-m-d') . '.' . $extension;
            
            return Storage::disk('public')->download($arsip->path_file, $fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft deleted record.
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();
            
            $arsip = ArsipDokumen::withTrashed()->findOrFail($id);
            
            // Cek apakah file masih ada
            if ($arsip->path_file && !Storage::disk('public')->exists($arsip->path_file)) {
                DB::rollBack();
                return back()->with('error', 'File fisik tidak ditemukan, tidak dapat direstore');
            }
            
            $arsip->restore();
            
            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil direstore');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal restore dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Force delete (permanent delete with file).
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $arsip = ArsipDokumen::withTrashed()->findOrFail($id);
            
            // Hapus file dari storage
            if ($arsip->path_file && Storage::disk('public')->exists($arsip->path_file)) {
                Storage::disk('public')->delete($arsip->path_file);
            }
            
            // Hapus permanent dari database
            $arsip->forceDelete();

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil dihapus permanen');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus permanen dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Display trashed (deleted) records.
     */
    public function trash(Request $request)
    {
        try {
            $query = ArsipDokumen::onlyTrashed()->with('uploader');

            // Filter pencarian di trash
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('kode_arsip', 'like', "%{$search}%");
                });
            }

            $arsip = $query->orderBy('deleted_at', 'desc')->paginate(10);
            
            return view('administrasi.arsip.trash', compact('arsip'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data sampah: ' . $e->getMessage());
        }
    }
}