<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $loginRole = session('login_role');

        // Log untuk debugging
        Log::info('CheckLoginRole:', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'login_role' => $loginRole,
            'required_roles' => $roles,
            'url' => $request->fullUrl(),
        ]);

        // Jika tidak ada session login_role, set dari user role
        if (empty($loginRole)) {
            $loginRole = $user->role;
            session(['login_role' => $loginRole]);
            Log::info('Session login_role tidak ada, di-set ke:', ['login_role' => $loginRole]);
        }

        // Cek apakah role yang digunakan untuk login diizinkan
        if (!in_array($loginRole, $roles)) {
            Log::warning('Akses ditolak:', [
                'user_id' => $user->id,
                'login_role' => $loginRole,
                'required_roles' => $roles,
            ]);
            
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . $loginRole . ', Role yang diizinkan: ' . implode(', ', $roles));
        }

        // Cek apakah user memiliki role yang sesuai dengan login_role
        if ($user->role !== $loginRole) {
            Log::warning('Role mismatch:', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'login_role' => $loginRole,
            ]);
        }

        return $next($request);
    }
}