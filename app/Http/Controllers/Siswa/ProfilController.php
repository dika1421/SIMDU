<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProfilController extends Controller
{
    private function getSiswa()
    {
        $user = auth()->user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        if (!$siswa) {
            $kelas = Kelas::first();
            
            // Jika tidak ada kelas, buat dummy
            if (!$kelas) {
                $kelas = (object) [
                    'id' => 1,
                    'nama' => 'XII A PEMASARAN',
                    'nama_kelas' => 'XII A PEMASARAN',
                    'jurusan' => 'Pemasaran'
                ];
            }
            
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => '232410031',
                'nisn' => '1234567890',
                'nama' => $user->name,
                'nama_lengkap' => $user->name,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-01-01',
                'kelas_id' => $kelas->id ?? null,
                'status' => 'aktif',
                'tahun_masuk' => date('Y'),
                'no_telepon' => '',
                'alamat' => '',
                'nama_ayah' => '',
                'nama_ibu' => '',
                'no_telepon_orangtua' => '',
                'pekerjaan_orangtua' => '',
                'alamat_orangtua' => '',
                'agama' => 'Islam'
            ]);
            
            // Reload with relationship
            $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        }
        
        return $siswa;
    }
    
    public function index()
    {
        try {
            $user = auth()->user();
            $siswa = $this->getSiswa();
            
            return view('siswa.profil.index', compact('user', 'siswa'));
            
        } catch (\Exception $e) {
            Log::error('Error in profil index: ' . $e->getMessage());
            
            $user = auth()->user();
            // Data dummy untuk tampilan
            $siswa = (object) [
                'id' => 1,
                'nis' => '232410031',
                'nisn' => '1234567890',
                'nama_lengkap' => $user->name ?? 'Rahmat Aditya',
                'nama' => $user->name ?? 'Rahmat Aditya',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-08-27',
                'tahun_masuk' => '2024',
                'status' => 'aktif',
                'no_telepon' => '08123456789',
                'alamat' => 'Jl. Pendidikan No. 123',
                'nama_ayah' => 'Bapak Siswa',
                'nama_ibu' => 'Ibu Siswa',
                'no_telepon_orangtua' => '08123456789',
                'pekerjaan_orangtua' => 'Wiraswasta',
                'alamat_orangtua' => 'Jl. Orang Tua No. 456',
                'agama' => 'Islam',
                'kelas' => (object) [
                    'id' => 1,
                    'nama' => 'XII A PEMASARAN',
                    'nama_kelas' => 'XII A PEMASARAN',
                    'jurusan' => 'Pemasaran'
                ],
                'kelas_id' => 1
            ];
            
            return view('siswa.profil.index', compact('user', 'siswa'))
                ->with('warning', 'Data menggunakan dummy karena terjadi kesalahan.');
        }
    }
    
    public function edit()
    {
        try {
            $user = auth()->user();
            $siswa = $this->getSiswa();
            $kelasList = Kelas::all();
            
            return view('siswa.profil.edit', compact('user', 'siswa', 'kelasList'));
            
        } catch (\Exception $e) {
            Log::error('Error in profil edit: ' . $e->getMessage());
            return redirect()->route('siswa.profil.index')
                ->with('error', 'Gagal memuat form edit: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            $siswa = $this->getSiswa();
            
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'no_telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:500',
                'jenis_kelamin' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'agama' => 'nullable|string|max:50',
                'nama_ayah' => 'nullable|string|max:255',
                'nama_ibu' => 'nullable|string|max:255',
                'no_telepon_orangtua' => 'nullable|string|max:20',
                'pekerjaan_orangtua' => 'nullable|string|max:255',
                'alamat_orangtua' => 'nullable|string|max:500',
            ]);
            
            DB::beginTransaction();
            
            // Update user
            $user->update([
                'name' => $validated['nama_lengkap']
            ]);
            
            // Update siswa
            $siswa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nama' => $validated['nama_lengkap'],
                'no_telepon' => $validated['no_telepon'] ?? $siswa->no_telepon,
                'alamat' => $validated['alamat'] ?? $siswa->alamat,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? $siswa->jenis_kelamin,
                'tempat_lahir' => $validated['tempat_lahir'] ?? $siswa->tempat_lahir,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? $siswa->tanggal_lahir,
                'agama' => $validated['agama'] ?? $siswa->agama,
                'nama_ayah' => $validated['nama_ayah'] ?? $siswa->nama_ayah,
                'nama_ibu' => $validated['nama_ibu'] ?? $siswa->nama_ibu,
                'no_telepon_orangtua' => $validated['no_telepon_orangtua'] ?? $siswa->no_telepon_orangtua,
                'pekerjaan_orangtua' => $validated['pekerjaan_orangtua'] ?? $siswa->pekerjaan_orangtua,
                'alamat_orangtua' => $validated['alamat_orangtua'] ?? $siswa->alamat_orangtua,
            ]);
            
            DB::commit();
            
            return redirect()->route('siswa.profil.index')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error update profil: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);
            
            $user = auth()->user();
            
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini salah!');
            }
            
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            return redirect()->route('siswa.profil.index')
                ->with('success', 'Password berhasil diubah!');
                
        } catch (\Exception $e) {
            Log::error('Error change password: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}