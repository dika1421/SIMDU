<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\ArsipDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArsipController extends Controller
{
    /**
     * Daftar kategori yang sesuai dengan CHECK CONSTRAINT database.
     * Constraint: arsip_dokumen_kategori_check
     */
    private function kategoriList(): array
    {
        return [
            'surat_masuk'  => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'keputusan'    => 'Surat Keputusan',
            'laporan'      => 'Laporan',
            'notulen'      => 'Notulen',
            'sertifikat'   => 'Sertifikat',
            'ijazah'       => 'Ijazah',
            'lainnya'      => 'Lainnya',
        ];
    }

    public function index(Request $request)
    {
        try {
            $query = ArsipDokumen::with('uploader');

            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_dokumen', 'like', "%{$search}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 10);
            $arsip   = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $kategoriList = $this->kategoriList();

            $tahunList = ArsipDokumen::query()
                ->whereNotNull('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return view('administrasi.arsip.index', compact('arsip', 'kategoriList', 'tahunList'));

        } catch (\Exception $e) {
            Log::error('Error arsip index: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $kategoriList = $this->kategoriList();
        return view('administrasi.arsip.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen'    => 'required|string|max:200',
            'nomor_dokumen'   => 'nullable|string|max:100',
            'kategori'        => 'required|in:surat_masuk,surat_keluar,keputusan,laporan,notulen,sertifikat,ijazah,lainnya',
            'tanggal_dokumen' => 'nullable|date',
            'keterangan'      => 'nullable|string',
            'file'            => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName  = time() . '_' . Str::random(10) . '.' . $extension;
            $path      = $file->storeAs('arsip', $fileName, 'public');

            $nomorDokumen = $request->nomor_dokumen;
            if (empty($nomorDokumen)) {
                $nomorDokumen = 'ARS/' . date('Y') . '/' . strtoupper(Str::random(6));
            }

            ArsipDokumen::create([
                'nomor_dokumen'   => $nomorDokumen,
                'nama_dokumen'    => $request->nama_dokumen,
                'kategori'        => $request->kategori,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'file_path'       => $path,
                'keterangan'      => $request->keterangan,
                'uploaded_by'     => auth()->id(),
                'tahun'           => $request->tanggal_dokumen
                                        ? Carbon::parse($request->tanggal_dokumen)->year
                                        : Carbon::now()->year,
            ]);

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil diarsipkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengarsipkan dokumen: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengarsipkan dokumen: ' . $e->getMessage())->withInput();
        }
    }

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

    public function edit($id)
    {
        try {
            $arsip        = ArsipDokumen::findOrFail($id);
            $kategoriList = $this->kategoriList();

            $fileExists = $arsip->file_path
                && Storage::disk('public')->exists($arsip->file_path);

            return view('administrasi.arsip.edit', compact('arsip', 'kategoriList', 'fileExists'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Data dokumen tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error in Arsip edit: ' . $e->getMessage());
            return redirect()->route('administrasi.arsip.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $arsip = ArsipDokumen::findOrFail($id);

            $request->validate([
                'nama_dokumen'    => 'required|string|max:200',
                'nomor_dokumen'   => 'nullable|string|max:100',
                'kategori'        => 'required|in:surat_masuk,surat_keluar,keputusan,laporan,notulen,sertifikat,ijazah,lainnya',
                'tanggal_dokumen' => 'nullable|date',
                'keterangan'      => 'nullable|string',
                'file'            => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            ]);

            DB::beginTransaction();

            $data = [
                'nomor_dokumen'   => $request->nomor_dokumen ?: $arsip->nomor_dokumen,
                'nama_dokumen'    => $request->nama_dokumen,
                'kategori'        => $request->kategori,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'keterangan'      => $request->keterangan,
                'tahun'           => $request->tanggal_dokumen
                                        ? Carbon::parse($request->tanggal_dokumen)->year
                                        : Carbon::now()->year,
            ];

            if ($request->hasFile('file')) {
                if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                    Storage::disk('public')->delete($arsip->file_path);
                }

                $file      = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $fileName  = time() . '_' . Str::random(10) . '.' . $extension;
                $path      = $file->storeAs('arsip', $fileName, 'public');

                $data['file_path'] = $path;
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
            Log::error('Gagal update dokumen: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate dokumen: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $arsip = ArsipDokumen::findOrFail($id);
            $arsip->delete();

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil dipindahkan ke tempat sampah');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus dokumen: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $arsip = ArsipDokumen::findOrFail($id);

            if (!$arsip->file_path) {
                return back()->with('error', 'Path file tidak ditemukan di database');
            }

            if (!Storage::disk('public')->exists($arsip->file_path)) {
                return back()->with('error', 'File fisik tidak ditemukan di server');
            }

            $cleanNama = preg_replace('/[^a-zA-Z0-9]/', '_', $arsip->nama_dokumen ?? 'dokumen');
            $extension = pathinfo($arsip->file_path, PATHINFO_EXTENSION);
            $fileName  = $cleanNama . '_' . date('Y-m-d') . '.' . $extension;

            return Storage::disk('public')->download($arsip->file_path, $fileName);

        } catch (\Exception $e) {
            Log::error('Gagal download file: ' . $e->getMessage());
            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $arsip = ArsipDokumen::withTrashed()->findOrFail($id);

            if ($arsip->file_path && !Storage::disk('public')->exists($arsip->file_path)) {
                DB::rollBack();
                return back()->with('error', 'File fisik tidak ditemukan, tidak dapat direstore');
            }

            $arsip->restore();

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil direstore');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal restore dokumen: ' . $e->getMessage());
            return back()->with('error', 'Gagal restore dokumen: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $arsip = ArsipDokumen::withTrashed()->findOrFail($id);

            if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }

            $arsip->forceDelete();

            DB::commit();

            return redirect()->route('administrasi.arsip.index')
                ->with('success', 'Dokumen berhasil dihapus permanen');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus permanen dokumen: ' . $e->getMessage());
            return back()->with('error', 'Gagal hapus permanen dokumen: ' . $e->getMessage());
        }
    }

    public function trash(Request $request)
    {
        try {
            $query = ArsipDokumen::onlyTrashed()->with('uploader');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_dokumen', 'like', "%{$search}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$search}%");
                });
            }

            $arsip = $query->orderBy('deleted_at', 'desc')->paginate(10);

            return view('administrasi.arsip.trash', compact('arsip'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat trash: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data sampah: ' . $e->getMessage());
        }
    }
}