<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Super Admin memiliki akses semua
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $next($request);
        }

        // Cek apakah user memiliki salah satu permission yang diminta
        foreach ($permissions as $permission) {
            // Permission bisa berupa kombinasi dengan pipe (|)
            $permList = explode('|', $permission);
            foreach ($permList as $perm) {
                if (method_exists($user, 'hasPermission') && $user->hasPermission($perm)) {
                    return $next($request);
                }
            }
        }

        // Jika tidak memiliki permission
        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}