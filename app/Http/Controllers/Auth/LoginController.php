<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function loginGuru(Request $request)
    {
        $request->validate([
            'nuptk' => 'required|string',
            'password' => 'required|string',
        ]);

        $nuptk = trim($request->nuptk);
        $password = $request->password;
        
        // Cari guru
        $guru = Guru::where('nuptk', $nuptk)->first();
        
        if (!$guru) {
            return back()->withErrors(['nuptk' => 'NUPTK tidak ditemukan'])->withInput();
        }
        
        $user = $guru->user;
        
        if (!$user) {
            return back()->withErrors(['nuptk' => 'User tidak ditemukan'])->withInput();
        }
        
        // Generate expected password
        $last4Digits = substr($nuptk, -4);
        $expectedPassword = 'simdu#4' . $last4Digits;
        
        // Cek password menggunakan method validatePassword
        if ($user->validatePassword($expectedPassword)) {
            Auth::login($user, $request->has('remember'));
            
            $routes = [
                'kepala_sekolah' => 'kepala-sekolah.dashboard',
                'administrasi' => 'administrasi.dashboard',
                'guru' => 'guru.dashboard',
            ];
            
            return redirect()->route($routes[$user->role] ?? 'home');
        }
        
        // Coba dengan password yang diinput langsung
        if ($user->validatePassword($password)) {
            Auth::login($user, $request->has('remember'));
            
            $routes = [
                'kepala_sekolah' => 'kepala-sekolah.dashboard',
                'administrasi' => 'administrasi.dashboard',
                'guru' => 'guru.dashboard',
            ];
            
            return redirect()->route($routes[$user->role] ?? 'home');
        }
        
        return back()->withErrors(['password' => 'Password salah'])->withInput();
    }

    public function loginSiswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        $nis = trim($request->nis);
        $password = $request->password;
        
        // Cari siswa
        $siswa = Siswa::where('nis', $nis)->first();
        
        if (!$siswa) {
            return back()->withErrors(['nis' => 'NIS tidak ditemukan'])->withInput();
        }
        
        $user = $siswa->user;
        
        if (!$user) {
            return back()->withErrors(['nis' => 'User tidak ditemukan'])->withInput();
        }
        
        // Generate expected password
        $last4Digits = substr($nis, -4);
        $expectedPassword = 'simdu#4' . $last4Digits;
        
        // Cek password
        if ($user->validatePassword($expectedPassword)) {
            Auth::login($user, $request->has('remember_siswa'));
            return redirect()->route('siswa.dashboard');
        }
        
        if ($user->validatePassword($password)) {
            Auth::login($user, $request->has('remember_siswa'));
            return redirect()->route('siswa.dashboard');
        }
        
        return back()->withErrors(['password' => 'Password salah'])->withInput();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->validatePassword($request->password)) {
            Auth::login($user, $request->has('remember'));
            
            $routes = [
                'kepala_sekolah' => 'kepala-sekolah.dashboard',
                'administrasi' => 'administrasi.dashboard',
                'guru' => 'guru.dashboard',
                'siswa' => 'siswa.dashboard',
            ];
            
            return redirect()->route($routes[$user->role] ?? 'home');
        }
        
        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}