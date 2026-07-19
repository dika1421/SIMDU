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
     * ✅ FIX: Login dengan NUPTK (untuk Guru, Kepala Sekolah, Administrasi)
     * Mencari NUPTK langsung di tabel users
     */
    public function loginNuptk(Request $request)
    {
        $request->validate([
            'nuptk' => 'required|string',
            'password' => 'required|string',
        ]);

        $nuptk = trim($request->nuptk);
        $password = $request->password;
        
        // 🔍 LOG: Catat data yang masuk
        Log::info('🔍 LOGIN NUPTK - Step 1: Data masuk:', [
            'nuptk' => $nuptk,
            'password' => $password,
            'ip' => $request->ip(),
        ]);
        
        // Cari user berdasarkan NUPTK di tabel users
        $user = User::where('nuptk', $nuptk)->first();
        
        // 🔍 LOG: Catat hasil query
        Log::info('🔍 LOGIN NUPTK - Step 2: Hasil query:', [
            'nuptk' => $nuptk,
            'user_found' => $user ? 'YES' : 'NO',
            'user_data' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nuptk' => $user->nuptk,
            ] : null,
        ]);
        
        if (!$user) {
            Log::warning('🔍 LOGIN NUPTK - Step 3: User TIDAK ditemukan!', [
                'nuptk' => $nuptk,
            ]);
            return back()->withErrors(['nuptk' => 'NUPTK tidak ditemukan'])->withInput();
        }
        
        // 🔍 LOG: Cek password
        $passwordMatch = Hash::check($password, $user->password);
        Log::info('🔍 LOGIN NUPTK - Step 4: Password check:', [
            'password_match' => $passwordMatch ? '✅ YES' : '❌ NO',
            'password_hash_db' => $user->password,
            'expected_password' => $this->generatePasswordByRole($user, $user->role),
        ]);
        
        if ($passwordMatch) {
            Log::info('🔍 LOGIN NUPTK - Step 5: ✅ LOGIN BERHASIL!', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->role,
            ]);
            Auth::login($user, $request->has('remember'));
            session(['login_role' => $user->role]);
            return $this->redirectBasedOnRole($user);
        }
        
        $expectedPassword = $this->generatePasswordByRole($user, $user->role);
        if ($password === $expectedPassword) {
            Log::info('🔍 LOGIN NUPTK - Step 5b: Password default cocok!', [
                'user_id' => $user->id,
                'expected_password' => $expectedPassword,
            ]);
            $user->password = Hash::make($password);
            $user->save();
            Auth::login($user, $request->has('remember'));
            session(['login_role' => $user->role]);
            return $this->redirectBasedOnRole($user);
        }
        
        Log::warning('🔍 LOGIN NUPTK - Step 6: ❌ Password SALAH!', [
            'user_id' => $user->id,
            'user_name' => $user->name,
        ]);
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
        
        if (Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember_siswa'));
            session(['login_role' => 'siswa']);
            return redirect()->route('siswa.dashboard');
        }
        
        $expectedPassword = $this->generatePasswordByRole($user, 'siswa');
        if ($password === $expectedPassword) {
            $user->password = Hash::make($password);
            $user->save();
            
            Auth::login($user, $request->has('remember_siswa'));
            session(['login_role' => 'siswa']);
            return redirect()->route('siswa.dashboard');
        }
        
        return back()->withErrors(['password' => 'Password salah'])->withInput();
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
        
        // ✅ PRIORITAS 1: Cek NUPTK langsung dari tabel users
        if (!empty($user->nuptk)) {
            return $prefix . $roleNumber . substr($user->nuptk, -4);
        }
        
        // ✅ PRIORITAS 2: Cek NUPTK dari relasi guru (jika ada)
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru && !empty($guru->nuptk)) {
            return $prefix . $roleNumber . substr($guru->nuptk, -4);
        }
        
        // ✅ PRIORITAS 3: Cek NIS dari relasi siswa (jika ada)
        $siswa = Siswa::where('user_id', $user->id)->first();
        if ($siswa && !empty($siswa->nis)) {
            return $prefix . $roleNumber . substr($siswa->nis, -4);
        }
        
        // ✅ FALLBACK: Gunakan 4 digit terakhir ID
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