<?php
// app/Http/Middleware/CheckLoginRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoginRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek login_role dari session
        if (!session()->has('login_role')) {
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        $loginRole = session('login_role');

        // Cek apakah user memiliki role yang sesuai
        if (!$user->hasRole($loginRole)) {
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error', 'Akses ditolak. Role tidak sesuai.');
        }

        // Jika ada parameter roles yang di-pass, cek juga
        if (!empty($roles)) {
            $hasAccess = false;
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}