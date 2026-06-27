<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login dengan email (untuk semua role)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
        }
        
        if ($this->validatePassword($user, $request->password)) {
            Auth::login($user, $request->has('remember'));
            session()->forget('login_role');
            return $this->redirectBasedOnRole($user);
        }
        
        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    /**
     * Login dengan NUPTK (untuk Guru, Kepala Sekolah, Administrasi)
     */
    public function loginNuptk(Request $request)
    {
        $request->validate([
            'nuptk' => 'required|string',
            'password' => 'required|string',
        ]);

        $nuptk = trim($request->nuptk);
        $password = $request->password;
        
        // Cari guru berdasarkan NUPTK
        $guru = Guru::where('nuptk', $nuptk)->first();
        
        if (!$guru) {
            return back()->withErrors(['nuptk' => 'NUPTK tidak ditemukan'])->withInput();
        }
        
        $user = $guru->user;
        
        if (!$user) {
            return back()->withErrors(['nuptk' => 'User tidak ditemukan'])->withInput();
        }
        
        // Validasi password berdasarkan role
        if ($this->validateNuptkPassword($user, $password)) {
            Auth::login($user, $request->has('remember'));
            
            // Simpan role yang digunakan untuk login ke session
            session(['login_role' => $user->role]);
            
            return $this->redirectBasedOnRole($user);
        }
        
        return back()->withErrors(['password' => 'Password salah'])->withInput();
    }

    /**
     * Login khusus Guru (legacy - untuk kompatibilitas)
     */
    public function loginGuru(Request $request)
    {
        return $this->loginNuptk($request);
    }

    /**
     * Login dengan NIS (untuk Siswa)
     */
    public function loginSiswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        $nis = trim($request->nis);
        $password = $request->password;
        
        $siswa = Siswa::where('nis', $nis)->first();
        
        if (!$siswa) {
            return back()->withErrors(['nis' => 'NIS tidak ditemukan'])->withInput();
        }
        
        $user = $siswa->user;
        
        if (!$user) {
            return back()->withErrors(['nis' => 'User tidak ditemukan'])->withInput();
        }
        
        // Validasi password untuk siswa
        if ($this->validateSiswaPassword($user, $password)) {
            Auth::login($user, $request->has('remember_siswa'));
            session(['login_role' => 'siswa']);
            return redirect()->route('siswa.dashboard');
        }
        
        return back()->withErrors(['password' => 'Password salah'])->withInput();
    }

    /**
     * Validasi password untuk login NUPTK
     */
    private function validateNuptkPassword($user, $password)
    {
        // 1. Cek dengan password hash (jika sudah di-hash)
        if (Hash::check($password, $user->password)) {
            return true;
        }
        
        // 2. Cek dengan MD5 (untuk kompatibilitas)
        if (md5($password) === $user->password) {
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        
        // 3. Cek dengan password default berdasarkan role
        $expectedPassword = $this->generatePasswordByRole($user, $user->role);
        
        if ($password === $expectedPassword) {
            // Hash password untuk next login
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        
        return false;
    }

    /**
     * Validasi password untuk login Siswa
     */
    private function validateSiswaPassword($user, $password)
    {
        // 1. Cek dengan password hash
        if (Hash::check($password, $user->password)) {
            return true;
        }
        
        // 2. Cek dengan MD5
        if (md5($password) === $user->password) {
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        
        // 3. Cek dengan password default siswa
        $expectedPassword = $this->generatePasswordByRole($user, 'siswa');
        
        if ($password === $expectedPassword) {
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        
        return false;
    }

    /**
     * Validasi password umum (untuk login email)
     */
    private function validatePassword($user, $password)
    {
        if (Hash::check($password, $user->password)) {
            return true;
        }
        
        if (md5($password) === $user->password) {
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        
        $expectedPassword = $this->generatePasswordByRole($user, $user->role);
        
        if ($password === $expectedPassword || Hash::check($expectedPassword, $user->password)) {
            return true;
        }
        
        return false;
    }

    /**
     * Generate password default berdasarkan role
     */
    private function generatePasswordByRole($user, $role)
    {
        $prefix = 'simdu#';
        $roleNumber = $this->getRoleNumber($role);
        
        // Untuk user dengan NUPTK (Guru, Kepala Sekolah, Administrasi)
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru && $guru->nuptk) {
            return $prefix . $roleNumber . substr($guru->nuptk, -4);
        }
        
        // Untuk user dengan NIS (Siswa)
        $siswa = Siswa::where('user_id', $user->id)->first();
        if ($siswa && $siswa->nis) {
            return $prefix . $roleNumber . substr($siswa->nis, -4);
        }
        
        // Fallback menggunakan ID
        return $prefix . $roleNumber . substr((string)$user->id, -4);
    }

    /**
     * Get role number for password generation
     */
    private function getRoleNumber($role)
    {
        $roles = [
            'kepala_sekolah' => '1',
            'administrasi' => '2',
            'guru' => '3',
            'siswa' => '4',
        ];
        
        return $roles[$role] ?? '0';
    }

    /**
     * Redirect berdasarkan role user
     */
    private function redirectBasedOnRole($user)
    {
        $routes = [
            'kepala_sekolah' => 'kepala-sekolah.dashboard',
            'administrasi' => 'administrasi.dashboard',
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
        ];
        
        $route = $routes[$user->role] ?? 'home';
        
        if (!route_exists($route)) {
            Log::warning('Route not found: ' . $route . ' for role: ' . $user->role);
            return redirect()->route('home');
        }
        
        return redirect()->route($route);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        session()->forget('login_role');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}

// Helper function untuk cek route exists
if (!function_exists('route_exists')) {
    function route_exists($name)
    {
        try {
            return app('router')->has($name);
        } catch (\Exception $e) {
            return false;
        }
    }
}