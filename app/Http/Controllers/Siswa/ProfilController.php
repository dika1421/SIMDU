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
            
            // Buat data dummy untuk testing
            $siswa = new Siswa();
            $siswa->user_id = $user->id;
            $siswa->nis = '232410031';
            $siswa->nisn = '1234567890';
            $siswa->nama = $user->name;
            $siswa->nama_lengkap = $user->name;
            $siswa->jenis_kelamin = 'L';
            $siswa->tempat_lahir = 'Jakarta';
            $siswa->tanggal_lahir = '2008-08-27';
            $siswa->kelas_id = $kelas->id ?? null;
            $siswa->status = 'aktif';
            $siswa->tahun_masuk = date('Y');
            $siswa->no_telepon = '08123456789';
            $siswa->alamat = 'Jl. Pendidikan No. 123';
            $siswa->nama_ayah = 'Bapak Siswa';
            $siswa->nama_ibu = 'Ibu Siswa';
            $siswa->no_telepon_orangtua = '08123456789';
            $siswa->pekerjaan_orangtua = 'Wiraswasta';
            $siswa->alamat_orangtua = 'Jl. Orang Tua No. 456';
            $siswa->agama = 'Islam';
            $siswa->save();
            
            // Reload dengan relasi
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
            
            // Data dummy jika error
            $siswa = new \stdClass();
            $siswa->id = 1;
            $siswa->nis = '232410031';
            $siswa->nisn = '1234567890';
            $siswa->nama_lengkap = $user->name ?? 'Rahmat Aditya';
            $siswa->nama = $user->name ?? 'Rahmat Aditya';
            $siswa->jenis_kelamin = 'Laki-laki';
            $siswa->tempat_lahir = 'Jakarta';
            $siswa->tanggal_lahir = '2008-08-27';
            $siswa->tahun_masuk = '2024';
            $siswa->status = 'aktif';
            $siswa->no_telepon = '08123456789';
            $siswa->alamat = 'Jl. Pendidikan No. 123';
            $siswa->nama_ayah = 'Bapak Siswa';
            $siswa->nama_ibu = 'Ibu Siswa';
            $siswa->no_telepon_orangtua = '08123456789';
            $siswa->pekerjaan_orangtua = 'Wiraswasta';
            $siswa->alamat_orangtua = 'Jl. Orang Tua No. 456';
            $siswa->agama = 'Islam';
            $siswa->kelas = new \stdClass();
            $siswa->kelas->id = 1;
            $siswa->kelas->nama = 'XII A PEMASARAN';
            $siswa->kelas->nama_kelas = 'XII A PEMASARAN';
            $siswa->kelas->jurusan = 'Pemasaran';
            $siswa->kelas_id = 1;
            
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
            
            // Update siswa - PERBAIKAN: hanya field yang ada di database
            $updateData = [
                'nama_lengkap' => $validated['nama_lengkap'],
                'nama' => $validated['nama_lengkap'],
                'no_telepon' => $validated['no_telepon'] ?? $siswa->no_telepon,
                'alamat' => $validated['alamat'] ?? $siswa->alamat,
                'nama_ayah' => $validated['nama_ayah'] ?? $siswa->nama_ayah,
                'nama_ibu' => $validated['nama_ibu'] ?? $siswa->nama_ibu,
                'no_telepon_orangtua' => $validated['no_telepon_orangtua'] ?? $siswa->no_telepon_orangtua,
                'pekerjaan_orangtua' => $validated['pekerjaan_orangtua'] ?? $siswa->pekerjaan_orangtua,
                'alamat_orangtua' => $validated['alamat_orangtua'] ?? $siswa->alamat_orangtua,
            ];
            
            // Hanya update jika field ada di database
            if (isset($siswa->jenis_kelamin)) {
                $updateData['jenis_kelamin'] = $validated['jenis_kelamin'] ?? $siswa->jenis_kelamin;
            }
            if (isset($siswa->tempat_lahir)) {
                $updateData['tempat_lahir'] = $validated['tempat_lahir'] ?? $siswa->tempat_lahir;
            }
            if (isset($siswa->tanggal_lahir)) {
                $updateData['tanggal_lahir'] = $validated['tanggal_lahir'] ?? $siswa->tanggal_lahir;
            }
            if (isset($siswa->agama)) {
                $updateData['agama'] = $validated['agama'] ?? $siswa->agama;
            }
            
            $siswa->update($updateData);
            
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