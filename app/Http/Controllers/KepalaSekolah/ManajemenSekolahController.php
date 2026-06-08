<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\StrukturOrganisasi;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenSekolahController extends Controller
{
    /**
     * ==================== STRUKTUR ORGANISASI ====================
     */
    public function struktur()
    {
        // PERBAIKI: Gunakan relasi yang benar
        $struktur = StrukturOrganisasi::with(['guru.user', 'parent'])
            ->orderBy('urutan')
            ->get();
        
        // PERBAIKI: Ambil guru dengan user
        $guru = Guru::with('user')->get();
        
        return view('kepala-sekolah.manajemen-sekolah.struktur-organisasi', compact('struktur', 'guru'));
    }

    public function strukturStore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'urutan' => 'required|integer',
            'guru_id' => 'nullable|exists:gurus,id',
            'parent_id' => 'nullable|exists:struktur_organisasi,id',
        ]);

        StrukturOrganisasi::create($request->all());

        return redirect()->route('kepala-sekolah.manajemen.struktur')
            ->with('success', 'Struktur organisasi berhasil ditambahkan');
    }

    public function strukturUpdate(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'urutan' => 'required|integer',
            'guru_id' => 'nullable|exists:gurus,id',
            'parent_id' => 'nullable|exists:struktur_organisasi,id',
        ]);

        $struktur->update($request->all());

        return redirect()->route('kepala-sekolah.manajemen.struktur')
            ->with('success', 'Struktur organisasi berhasil diupdate');
    }

    public function strukturDestroy($id)
    {
        StrukturOrganisasi::findOrFail($id)->delete();

        return redirect()->route('kepala-sekolah.manajemen.struktur')
            ->with('success', 'Struktur organisasi berhasil dihapus');
    }

    /**
     * ==================== JURUSAN ====================
     */
    public function jurusan()
    {
        // PERBAIKI: Gunakan relasi yang benar
        $jurusan = Jurusan::with('kepalaJurusan.user')->get();
        $guru = Guru::with('user')->get();
        
        return view('kepala-sekolah.manajemen-sekolah.jurusan', compact('jurusan', 'guru'));
    }

    public function jurusanStore(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan',
            'nama' => 'required',
            'kepala_jurusan_id' => 'nullable|exists:gurus,id',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('kepala-sekolah.manajemen.jurusan')
            ->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function jurusanUpdate(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        $request->validate([
            'kode' => 'required|unique:jurusan,kode,' . $id,
            'nama' => 'required',
            'kepala_jurusan_id' => 'nullable|exists:gurus,id',
        ]);

        $jurusan->update($request->all());

        return redirect()->route('kepala-sekolah.manajemen.jurusan')
            ->with('success', 'Jurusan berhasil diupdate');
    }

    public function jurusanDestroy($id)
    {
        Jurusan::findOrFail($id)->delete();

        return redirect()->route('kepala-sekolah.manajemen.jurusan')
            ->with('success', 'Jurusan berhasil dihapus');
    }

    /**
     * ==================== TAHUN AJARAN ====================
     */
    public function tahunAjaran()
    {
        $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')->get();
        return view('kepala-sekolah.manajemen-sekolah.tahun-ajaran', compact('tahunAjaran'));
    }

    public function tahunAjaranStore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'semester' => 'required|in:ganjil,genap',
        ]);

        // Jika set sebagai aktif, nonaktifkan yang lain
        if ($request->has('is_aktif') && $request->is_aktif) {
            TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        TahunAjaran::create($request->all());

        return redirect()->route('kepala-sekolah.manajemen.tahun-ajaran')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function tahunAjaranSetAktif($id)
    {
        // Nonaktifkan semua
        TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        
        // Aktifkan yang dipilih
        TahunAjaran::findOrFail($id)->update(['is_aktif' => true]);

        return redirect()->route('kepala-sekolah.manajemen.tahun-ajaran')
            ->with('success', 'Tahun ajaran aktif telah diubah');
    }

    /**
     * ==================== KELAS ====================
     */
    public function kelas()
    {
        // PERBAIKI: Gunakan relasi yang benar
        $kelas = Kelas::with(['jurusan', 'waliKelas.user'])->get();
        $jurusan = Jurusan::all();
        $guru = Guru::with('user')->get();
        
        return view('kepala-sekolah.manajemen-sekolah.kelas', compact('kelas', 'jurusan', 'guru'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'kode_kelas' => 'required|unique:kelas',
            'jurusan_id' => 'required|exists:jurusan,id',
            'tingkat' => 'required|integer|between:10,13',
            'kapasitas' => 'required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tahun_ajaran' => 'required',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kepala-sekolah.manajemen.kelas')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $request->validate([
            'nama_kelas' => 'required',
            'kode_kelas' => 'required|unique:kelas,kode_kelas,' . $id,
            'jurusan_id' => 'required|exists:jurusan,id',
            'tingkat' => 'required|integer|between:10,13',
            'kapasitas' => 'required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tahun_ajaran' => 'required',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kepala-sekolah.manajemen.kelas')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function kelasDestroy($id)
    {
        Kelas::findOrFail($id)->delete();

        return redirect()->route('kepala-sekolah.manajemen.kelas')
            ->with('success', 'Kelas berhasil dihapus');
    }
}