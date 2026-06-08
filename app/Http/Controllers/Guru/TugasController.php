<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        
        $tugas = Tugas::where('guru_id', $guru->id)
            ->with(['mapel', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('guru.tugas.index', compact('tugas'));
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

        return view('guru.tugas.create', compact('kelas', 'mapel'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_deadline' => 'required|date|after:today',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mapel_id,
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'tanggal_diberikan' => now(),
            'tanggal_deadline' => $request->tanggal_deadline,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('tugas', 'public');
        }

        Tugas::create($data);

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil dibuat');
    }

    public function show($id)
    {
        $tugas = Tugas::with(['guru.user', 'mapel', 'kelas'])->findOrFail($id);
        
        $pengumpulan = PengumpulanTugas::where('tugas_id', $id)
            ->with('siswa.user')
            ->get();

        $siswa = Siswa::where('kelas_id', $tugas->kelas_id)
            ->where('status', 'aktif')
            ->with('user')
            ->get();

        return view('guru.tugas.show', compact('tugas', 'pengumpulan', 'siswa'));
    }

    public function nilai(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($id);
        
        $pengumpulan->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil diberikan');
    }
}