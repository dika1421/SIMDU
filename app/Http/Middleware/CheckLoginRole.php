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
    
    $user = User::where('nuptk', $nuptk)->first();
    
    if (!$user) {
        return back()->with('error', 'NUPTK tidak ditemukan')->withInput();
    }
    
    if (Hash::check($password, $user->password)) {
        Auth::login($user, $request->has('remember'));
        
        // 🔥 SET SESSION LOGIN_ROLE
        session(['login_role' => $user->role]);
        
        Log::info('Login berhasil:', [
            'user_id' => $user->id,
            'role' => $user->role,
            'login_role' => session('login_role'),
        ]);
        
        return $this->redirectBasedOnRole($user);
    }
    
    // Cek password default
    $expectedPassword = $this->generatePasswordByRole($user, $user->role);
    if ($password === $expectedPassword) {
        $user->password = Hash::make($password);
        $user->save();
        
        Auth::login($user, $request->has('remember'));
        session(['login_role' => $user->role]);
        
        Log::info('Login dengan password default:', [
            'user_id' => $user->id,
            'role' => $user->role,
        ]);
        
        return $this->redirectBasedOnRole($user);
    }
    
    return back()->with('error', 'Password salah')->withInput();
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
        return back()->with('error', 'NIS tidak ditemukan')->withInput();
    }
    
    $user = $siswa->user;
    
    if (!$user) {
        return back()->with('error', 'User tidak ditemukan')->withInput();
    }
    
    if (Hash::check($password, $user->password)) {
        Auth::login($user, $request->has('remember_siswa'));
        session(['login_role' => 'siswa']);
        
        Log::info('Login siswa berhasil:', [
            'user_id' => $user->id,
            'role' => 'siswa',
        ]);
        
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
    
    return back()->with('error', 'Password salah')->withInput();
}