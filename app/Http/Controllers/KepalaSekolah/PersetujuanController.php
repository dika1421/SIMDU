<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PersetujuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan dengan filter
     */
    public function index(Request $request)
    {
        try {
            $query = Pengajuan::with('pengaju');

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan tipe
            if ($request->filled('tipe')) {
                $query->where('tipe', $request->tipe);
            }

            // Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('pengaju', function($sub) use ($search) {
                          $sub->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $pengajuan = $query->orderBy('created_at', 'desc')->paginate(20);

            // Statistik
            $total = Pengajuan::count();
            $menunggu = Pengajuan::where('status', 'menunggu')->count();
            $disetujui = Pengajuan::where('status', 'disetujui')->count();
            $ditolak = Pengajuan::where('status', 'ditolak')->count();
            $revisi = Pengajuan::where('status', 'revisi')->count();

            return view('kepala-sekolah.persetujuan.index', compact(
                'pengajuan',
                'total',
                'menunggu',
                'disetujui',
                'ditolak',
                'revisi'
            ));

        } catch (\Exception $e) {
            Log::error('Persetujuan Index Error: ' . $e->getMessage());
            
            return view('kepala-sekolah.persetujuan.index', [
                'pengajuan' => collect(),
                'total' => 0,
                'menunggu' => 0,
                'disetujui' => 0,
                'ditolak' => 0,
                'revisi' => 0,
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru
     */
    public function create()
    {
        try {
            $pengajuList = User::where('role', '!=', 'kepala_sekolah')
                ->orderBy('name')
                ->get();

            return view('kepala-sekolah.persetujuan.create', compact('pengajuList'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Create Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan pengajuan baru ke database
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pengaju_id' => 'required|exists:users,id',
                'judul' => 'required|string|max:255',
                'tipe' => 'required|string|in:anggaran,izin,proyek,lainnya',
                'deskripsi' => 'required|string|min:10',
                'jumlah_anggaran' => 'nullable|numeric|min:0',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $lampiranPath = $file->storeAs('uploads/pengajuan', $filename, 'public');
            }

            $pengajuan = Pengajuan::create([
                'pengaju_id' => $request->pengaju_id,
                'judul' => $request->judul,
                'tipe' => $request->tipe,
                'deskripsi' => $request->deskripsi,
                'jumlah_anggaran' => $request->jumlah_anggaran ?? 0,
                'lampiran' => $lampiranPath,
                'status' => 'menunggu',
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil dibuat dan menunggu persetujuan.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail pengajuan
     */
    public function show($id)
    {
        try {
            $pengajuan = Pengajuan::with(['pengaju', 'penyetuju'])->findOrFail($id);
            return view('kepala-sekolah.persetujuan.show', compact('pengajuan'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Show Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Menampilkan form edit pengajuan
     */
    public function edit($id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'menunggu') {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $pengajuan->status . '" tidak dapat diedit.');
            }

            $pengajuList = User::where('role', '!=', 'kepala_sekolah')
                ->orderBy('name')
                ->get();

            return view('kepala-sekolah.persetujuan.edit', compact('pengajuan', 'pengajuList'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Edit Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Mengupdate data pengajuan
     */
    public function update(Request $request, $id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'menunggu') {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $pengajuan->status . '" tidak dapat diupdate.');
            }

            $validator = Validator::make($request->all(), [
                'pengaju_id' => 'required|exists:users,id',
                'judul' => 'required|string|max:255',
                'tipe' => 'required|string|in:anggaran,izin,proyek,lainnya',
                'deskripsi' => 'required|string|min:10',
                'jumlah_anggaran' => 'nullable|numeric|min:0',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $lampiranPath = $pengajuan->lampiran;
            if ($request->hasFile('lampiran')) {
                if ($pengajuan->lampiran && file_exists(storage_path('app/public/' . $pengajuan->lampiran))) {
                    unlink(storage_path('app/public/' . $pengajuan->lampiran));
                }
                
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $lampiranPath = $file->storeAs('uploads/pengajuan', $filename, 'public');
            }

            $pengajuan->update([
                'pengaju_id' => $request->pengaju_id,
                'judul' => $request->judul,
                'tipe' => $request->tipe,
                'deskripsi' => $request->deskripsi,
                'jumlah_anggaran' => $request->jumlah_anggaran ?? 0,
                'lampiran' => $lampiranPath,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil diupdate.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus pengajuan
     */
    public function destroy($id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if (!in_array($pengajuan->status, ['menunggu', 'ditolak'])) {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $pengajuan->status . '" tidak dapat dihapus.');
            }

            if ($pengajuan->lampiran && file_exists(storage_path('app/public/' . $pengajuan->lampiran))) {
                unlink(storage_path('app/public/' . $pengajuan->lampiran));
            }

            $pengajuan->delete();

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '🗑️ Pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Destroy Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Menyetujui pengajuan
     */
    public function approve($id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'menunggu') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $pengajuan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
                'tanggal_disetujui' => now(),
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil disetujui');

        } catch (\Exception $e) {
            Log::error('Persetujuan Approve Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Menolak pengajuan
     */
    public function reject(Request $request, $id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'menunggu') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $request->validate([
                'catatan' => 'required|string|max:1000',
            ]);

            $pengajuan->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'disetujui_oleh' => Auth::id(),
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '❌ Pengajuan ditolak');

        } catch (\Exception $e) {
            Log::error('Persetujuan Reject Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }

    /**
     * Mengembalikan pengajuan untuk revisi
     */
    public function revise(Request $request, $id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            
            if ($pengajuan->status !== 'menunggu') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $request->validate([
                'catatan' => 'required|string|max:1000',
            ]);

            $pengajuan->update([
                'status' => 'revisi',
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '🔄 Pengajuan dikembalikan untuk revisi');

        } catch (\Exception $e) {
            Log::error('Persetujuan Revise Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard statistik persetujuan
     */
    public function dashboard()
    {
        try {
            $statistik = [
                'menunggu' => Pengajuan::where('status', 'menunggu')->count(),
                'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
                'revisi' => Pengajuan::where('status', 'revisi')->count(),
                'total' => Pengajuan::count(),
                'total_anggaran' => Pengajuan::where('tipe', 'anggaran')
                    ->where('status', 'disetujui')
                    ->sum('jumlah_anggaran'),
            ];

            $recent = Pengajuan::with('pengaju')
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('kepala-sekolah.persetujuan.dashboard', compact('statistik', 'recent'));

        } catch (\Exception $e) {
            Log::error('Persetujuan Dashboard Error: ' . $e->getMessage());
            
            return view('kepala-sekolah.persetujuan.dashboard', [
                'statistik' => [
                    'menunggu' => 0,
                    'disetujui' => 0,
                    'ditolak' => 0,
                    'revisi' => 0,
                    'total' => 0,
                    'total_anggaran' => 0,
                ],
                'recent' => collect(),
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 TAMBAHKAN METHOD PRINT (jika digunakan)
     */
    public function print($id)
    {
        try {
            $pengajuan = Pengajuan::with(['pengaju', 'penyetuju'])->findOrFail($id);
            return view('kepala-sekolah.persetujuan.print', compact('pengajuan'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Print Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * 🔥 TAMBAHKAN METHOD BULK APPROVE
     */
    public function bulkApprove(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:pengajuan,id',
            ]);

            $updated = Pengajuan::whereIn('id', $request->ids)
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'disetujui',
                    'disetujui_oleh' => Auth::id(),
                    'tanggal_disetujui' => now(),
                ]);

            return redirect()->back()
                ->with('success', "✅ {$updated} pengajuan berhasil disetujui.");

        } catch (\Exception $e) {
            Log::error('Persetujuan BulkApprove Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 TAMBAHKAN METHOD BULK REJECT
     */
    public function bulkReject(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:pengajuan,id',
            ]);

            $updated = Pengajuan::whereIn('id', $request->ids)
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'ditolak',
                    'disetujui_oleh' => Auth::id(),
                ]);

            return redirect()->back()
                ->with('success', "❌ {$updated} pengajuan berhasil ditolak.");

        } catch (\Exception $e) {
            Log::error('Persetujuan BulkReject Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 TAMBAHKAN METHOD UNTUK CEK DATA (Opsional)
     */
    public function getStatistik()
    {
        try {
            $statistik = [
                'menunggu' => Pengajuan::where('status', 'menunggu')->count(),
                'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
                'revisi' => Pengajuan::where('status', 'revisi')->count(),
                'total' => Pengajuan::count(),
            ];

            return response()->json($statistik);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}