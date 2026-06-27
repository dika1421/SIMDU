<?php
// app/Http/Middleware/CheckLoginRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Ambil role dari session (jika ada), atau gunakan role user
        $loginRole = session('login_role', $user->role);
        
        // Jika user adalah kepala_sekolah dan ingin mengakses guru, cek session
        // Jika tidak ada session login_role, gunakan role user
        if ($user->role === 'kepala_sekolah' && !session()->has('login_role')) {
            // Default ke kepala_sekolah jika tidak ada session
            $loginRole = 'kepala_sekolah';
        }
        
        // Log untuk debugging
        Log::info('CheckLoginRole:', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'login_role' => $loginRole,
            'allowed_roles' => $roles,
            'session' => session()->all()
        ]);
        
        // Cek apakah role yang digunakan untuk login diizinkan
        if (!in_array($loginRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . $loginRole . ', Role yang diizinkan: ' . implode(', ', $roles));
        }
        
        return $next($request);
    }
}