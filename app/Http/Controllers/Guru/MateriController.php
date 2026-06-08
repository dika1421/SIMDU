<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        
        $materi = Materi::where('guru_id', $guru->id)
            ->with(['mapel', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('guru.materi.index', compact('materi'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        
        $kelas = Kelas::whereHas('jadwal', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->get();

        $mapel = Mapel::whereHas('jadwal', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->get();

        return view('guru.materi.create', compact('kelas', 'mapel'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'file' => 'required|file|mimes:pdf,ppt,pptx,doc,docx,mp4|max:20480',
        ]);

        $path = $request->file('file')->store('materi', 'public');

        Materi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'mapel_id' => $request->mapel_id,
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil diupload');
    }

    public function show($id)
    {
        $materi = Materi::with(['guru.user', 'mapel', 'kelas'])->findOrFail($id);
        return view('guru.materi.show', compact('materi'));
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil dihapus');
    }

    public function download($id)
    {
        $materi = Materi::findOrFail($id);
        
        if (!Storage::disk('public')->exists($materi->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($materi->file_path);
    }
}