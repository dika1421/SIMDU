<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoginRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!session()->has('login_role')) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        $loginRole = session('login_role');

        if ($user->role !== $loginRole) {
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error', 'Akses ditolak. Role tidak sesuai.');
        }

        return $next($request);
    }
}