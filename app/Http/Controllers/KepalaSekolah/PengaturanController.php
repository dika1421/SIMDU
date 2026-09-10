<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengaturanSekolah;

class PengaturanController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $pengaturan = PengaturanSekolah::getPengaturan();
        return view('kepala-sekolah.Pengaturan.index', compact('pengaturan'));
        //                          ^^^^^^^^^^ huruf P BESAR
    }

    /**
     * Simpan profil sekolah.
     */
    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_sekolah'   => 'required|string|max:255',
            'nuptk'          => 'nullable|string|max:50',
            'akreditasi'     => 'nullable|string|max:5',
            'kepala_sekolah' => 'nullable|string|max:255',
            'alamat'         => 'nullable|string',
            'telepon'        => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'website'        => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $pengaturan = PengaturanSekolah::getPengaturan();

        $data = $request->only([
            'nama_sekolah',
            'nuptk',
            'akreditasi',
            'kepala_sekolah',
            'alamat',
            'telepon',
            'email',
            'website',
        ]);

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo-sekolah', 'public');
        }

        $pengaturan->update($data);

        return redirect()
            ->route('kepala-sekolah.pengaturan')
            ->with('success', 'Profil sekolah berhasil disimpan.');
    }

    /**
     * Simpan pengaturan keamanan.
     */
    public function updateKeamanan(Request $request)
    {
        $request->validate([
            'masa_berlaku_password'  => 'nullable|integer|min:1|max:365',
            'batas_percobaan_login'  => 'nullable|integer|min:1|max:20',
        ]);

        $pengaturan = PengaturanSekolah::getPengaturan();

        $pengaturan->update([
            'two_factor'            => $request->has('two_factor'),
            'notifikasi_login'      => $request->has('notifikasi_login'),
            'masa_berlaku_password' => $request->input('masa_berlaku_password', 90),
            'batas_percobaan_login' => $request->input('batas_percobaan_login', 5),
        ]);

        return redirect()
            ->route('kepala-sekolah.pengaturan')
            ->with('success', 'Pengaturan keamanan berhasil disimpan.');
    }
}