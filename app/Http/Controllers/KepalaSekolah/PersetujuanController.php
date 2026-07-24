<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PersetujuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan dengan filter
     */
    public function index(Request $request)
    {
        $query = Pengajuan::with('pengaju');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        }

        // Pencarian berdasarkan nama pengaju atau judul pengajuan
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
        
        // Data untuk filter dropdown
        $statuses = ['menunggu', 'disetujui', 'ditolak', 'revisi'];
        $tipes = ['anggaran', 'izin', 'proyek', 'lainnya'];

        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.index', compact('pengajuan', 'statuses', 'tipes'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru
     */
    public function create()
    {
        // Ambil daftar pengaju (user) untuk dropdown
        $pengajuList = User::where('role', '!=', 'kepala_sekolah')
            ->orderBy('name')
            ->get();

        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.create', compact('pengajuList'));
    }

    /**
     * Menyimpan pengajuan baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pengaju_id' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:anggaran,izin,proyek,lainnya',
            'deskripsi' => 'required|string|min:10',
            'jumlah_anggaran' => 'nullable|numeric|min:0',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'prioritas' => 'nullable|in:rendah,sedang,tinggi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Proses upload file lampiran
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $lampiranPath = $file->storeAs('uploads/pengajuan', $filename, 'public');
        }

        // Simpan data pengajuan
        $pengajuan = Pengajuan::create([
            'pengaju_id' => $request->pengaju_id,
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
            'jumlah_anggaran' => $request->jumlah_anggaran ?? 0,
            'lampiran' => $lampiranPath,
            'prioritas' => $request->prioritas ?? 'sedang',
            'status' => 'menunggu',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', '✅ Pengajuan berhasil dibuat dan menunggu persetujuan.');
    }

    /**
     * Menampilkan detail pengajuan
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['pengaju', 'disetujuiOleh'])->findOrFail($id);
        
        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.show', compact('pengajuan'));
    }

    /**
     * Menampilkan form edit pengajuan
     */
    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Validasi: hanya pengajuan dengan status 'menunggu' yang bisa diedit
        if ($pengajuan->status !== 'menunggu') {
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', '⚠️ Pengajuan dengan status "' . $pengajuan->status . '" tidak dapat diedit.');
        }

        $pengajuList = User::where('role', '!=', 'kepala_sekolah')
            ->orderBy('name')
            ->get();

        $statuses = ['menunggu', 'disetujui', 'ditolak', 'revisi'];
        $tipes = ['anggaran', 'izin', 'proyek', 'lainnya'];

        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.edit', compact('pengajuan', 'pengajuList', 'statuses', 'tipes'));
    }

    /**
     * Mengupdate data pengajuan
     */
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Validasi: hanya pengajuan dengan status 'menunggu' yang bisa diupdate
        if ($pengajuan->status !== 'menunggu') {
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', '⚠️ Pengajuan dengan status "' . $pengajuan->status . '" tidak dapat diupdate.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'pengaju_id' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:anggaran,izin,proyek,lainnya',
            'deskripsi' => 'required|string|min:10',
            'jumlah_anggaran' => 'nullable|numeric|min:0',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'prioritas' => 'nullable|in:rendah,sedang,tinggi',
            'status' => 'nullable|in:menunggu,disetujui,ditolak,revisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Proses upload file lampiran baru jika ada
        $lampiranPath = $pengajuan->lampiran;
        if ($request->hasFile('lampiran')) {
            if ($pengajuan->lampiran && file_exists(storage_path('app/public/' . $pengajuan->lampiran))) {
                unlink(storage_path('app/public/' . $pengajuan->lampiran));
            }
            
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $lampiranPath = $file->storeAs('uploads/pengajuan', $filename, 'public');
        }

        // Update data pengajuan
        $pengajuan->update([
            'pengaju_id' => $request->pengaju_id,
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
            'jumlah_anggaran' => $request->jumlah_anggaran ?? 0,
            'lampiran' => $lampiranPath,
            'prioritas' => $request->prioritas ?? 'sedang',
            'status' => $request->status ?? 'menunggu',
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', '✅ Pengajuan berhasil diupdate.');
    }

    /**
     * Menghapus pengajuan
     */
    public function destroy($id)
    {
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
    }

    /**
     * Menyetujui pengajuan
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan,
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', '✅ Pengajuan berhasil disetujui');
    }

    /**
     * Menolak pengajuan
     */
    public function reject(Request $request, $id)
    {
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
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', '❌ Pengajuan ditolak');
    }

    /**
     * Mengembalikan pengajuan untuk revisi
     */
    public function revise(Request $request, $id)
    {
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
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', '🔄 Pengajuan dikembalikan untuk revisi');
    }

    /**
     * Dashboard statistik persetujuan
     */
    public function dashboard()
    {
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

        $perTipe = Pengajuan::selectRaw('tipe, status, count(*) as total')
            ->groupBy('tipe', 'status')
            ->get();

        $recent = Pengajuan::with('pengaju')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $perBulan = Pengajuan::selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun, status, count(*) as total')
            ->groupBy('tahun', 'bulan', 'status')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(12)
            ->get();

        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.dashboard', compact(
            'statistik', 
            'recent', 
            'perTipe', 
            'perBulan'
        ));
    }

    /**
     * Export data pengajuan ke Excel/PDF (opsional)
     */
    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    /**
     * Mencetak detail pengajuan (opsional)
     */
    public function print($id)
    {
        $pengajuan = Pengajuan::with(['pengaju', 'disetujuiOleh'])->findOrFail($id);
        
        // 🔥 UBAH: menggunakan huruf besar 'Persetujuan'
        return view('kepala-sekolah.Persetujuan.print', compact('pengajuan'));
    }

    /**
     * Bulk approve pengajuan
     */
    public function bulkApprove(Request $request)
    {
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
                'updated_by' => Auth::id(),
            ]);

        return redirect()->back()
            ->with('success', "✅ {$updated} pengajuan berhasil disetujui.");
    }

    /**
     * Bulk reject pengajuan
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pengajuan,id',
            'catatan' => 'required|string|max:1000',
        ]);

        $updated = Pengajuan::whereIn('id', $request->ids)
            ->where('status', 'menunggu')
            ->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'disetujui_oleh' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

        return redirect()->back()
            ->with('success', "❌ {$updated} pengajuan berhasil ditolak.");
    }
}