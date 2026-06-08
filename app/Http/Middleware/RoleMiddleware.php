<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Cek apakah role user ada di daftar roles yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak ada, abort 403
        abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . $user->role . ', Role yang diizinkan: ' . implode(', ', $roles));
    }
}