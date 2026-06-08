<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    private function getSiswa()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            $kelas = \App\Models\Kelas::first();
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => 'SIS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'nisn' => 'NSN' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'nama_lengkap' => $user->name,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-01-01',
                'kelas_id' => $kelas ? $kelas->id : null,
                'status' => 'aktif',
                'tahun_masuk' => date('Y')
            ]);
        }
        
        return $siswa;
    }
    
    public function index()
    {
        $user = auth()->user();
        $siswa = $this->getSiswa();
        
        return view('siswa.profil.index', compact('user', 'siswa'));
    }
    
    public function edit()
    {
        $user = auth()->user();
        $siswa = $this->getSiswa();
        
        return view('siswa.profil.edit', compact('user', 'siswa'));
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        $siswa = $this->getSiswa();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telepon_orangtua' => 'nullable|string|max:20',
            'pekerjaan_orangtua' => 'nullable|string|max:255',
            'alamat_orangtua' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->nama_lengkap,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat
            ]);
            
            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_telepon_orangtua' => $request->no_telepon_orangtua,
                'pekerjaan_orangtua' => $request->pekerjaan_orangtua,
                'alamat_orangtua' => $request->alamat_orangtua
            ]);
            
            DB::commit();
            
            return redirect()->route('siswa.profil.index')
                ->with('success', 'Profil berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }
        
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->route('siswa.profil.index')
            ->with('success', 'Password berhasil diubah');
    }
}