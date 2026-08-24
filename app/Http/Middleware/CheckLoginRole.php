<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoginRole
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil user yang login
        $user = Auth::user();

        // Cek apakah session login_role ada
        if (!session()->has('login_role')) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        // Ambil role dari session
        $loginRole = session('login_role');

        // Cek apakah role user sesuai dengan session
        if ($user->role !== $loginRole) {
            // Logout dan redirect ke login
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error', 'Akses ditolak. Role tidak sesuai.');
        }

        return $next($request);
    }
}