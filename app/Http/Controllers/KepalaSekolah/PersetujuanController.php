<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;  // PASTIKAN USE STATEMENT INI ADA
use App\Models\User;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('pengaju');  // <-- SUDAH BENAR

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('kepala-sekolah.persetujuan.index', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('pengaju')->findOrFail($id);
        return view('kepala-sekolah.persetujuan.show', compact('pengajuan'));
    }

    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        $request->validate([
            'catatan' => 'nullable',
        ]);

        $pengajuan->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan,
            'disetujui_oleh' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', 'Pengajuan berhasil disetujui');
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        $request->validate([
            'catatan' => 'required',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan,
            'disetujui_oleh' => auth()->id(),
        ]);

        return redirect()->route('kepala-sekolah.persetujuan.index')
            ->with('success', 'Pengajuan ditolak');
    }

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