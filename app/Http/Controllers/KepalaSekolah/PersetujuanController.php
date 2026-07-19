<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('kepala-sekolah.persetujuan.index', compact('pengajuan'));
    }

    /**
     * Menampilkan detail pengajuan
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with('pengaju')->findOrFail($id);
        return view('kepala-sekolah.persetujuan.show', compact('pengajuan'));
    }

    /**
     * Menyetujui pengajuan
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Validasi: hanya yang status 'menunggu' yang bisa disetujui
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan,
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now(),
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
        
        // Validasi: hanya yang status 'menunggu' yang bisa ditolak
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
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
    }
}