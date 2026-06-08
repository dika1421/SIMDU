<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIMDU</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 12px;
        }
        
        .role-btn {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #666;
        }
        
        .role-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }
        
        .role-btn i {
            margin-right: 8px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 1;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10;
            background: transparent;
            border: none;
            font-size: 1rem;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .form-control {
            padding-left: 45px;
            padding-right: 45px;
            height: 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
            outline: none;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 50px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        .alert-danger {
            background: #fff2f0;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .remember-me label {
            color: #666;
            cursor: pointer;
            font-size: 14px;
        }
        
        .info-text {
            font-size: 12px;
            color: #999;
            margin-top: 8px;
            text-align: center;
        }
        
        .password-hint {
            background: #f0f7ff;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .password-hint code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .demo-accounts {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .demo-accounts h6 {
            color: #666;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .demo-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .demo-item strong {
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        
        .demo-item span {
            font-size: 11px;
            color: #999;
            display: block;
        }
        
        .demo-item small {
            display: block;
            font-size: 10px;
            color: #27ae60;
            margin-top: 5px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h3>🔐 SIMDU</h3>
            <p>Silakan login untuk mengakses sistem</p>
        </div>
        
        <!-- Role Selector -->
        <div class="role-selector">
            <button type="button" class="role-btn active" data-role="guru" onclick="setRole('guru')">
                <i class="fas fa-chalkboard-user"></i> Guru / Staf
            </button>
            <button type="button" class="role-btn" data-role="siswa" onclick="setRole('siswa')">
                <i class="fas fa-user-graduate"></i> Siswa
            </button>
        </div>
        
        <!-- Tampilkan error jika ada -->
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif
        
        <!-- Form Login untuk Guru/Staf -->
        <form id="loginFormGuru" method="POST" action="{{ route('login.guru') }}" style="display: block;">
            @csrf
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-id-card me-1"></i> 
                    NUPTK (Username)
                </label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" 
                           class="form-control" 
                           name="nuptk" 
                           id="nuptk"
                           placeholder="Masukkan NUPTK (16 digit)"
                           value="{{ old('nuptk') }}"
                           autofocus
                           maxlength="20">
                </div>
                <div class="info-text">
                    Masukkan NUPTK yang terdaftar (contoh: 195875365530062)
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           id="passwordGuru"
                           placeholder="Masukkan password">
                    <button type="button" class="password-toggle" onclick="togglePassword('passwordGuru', this)">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="password-hint" id="guruHint">
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#4</code> + <strong>4 digit terakhir NUPTK</strong>
                    <br>Contoh: Jika NUPTK = <strong>195875365530062</strong>, password = <strong>simdu#40062</strong>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Login sebagai Guru / Staf
            </button>
        </form>
        
        <!-- Form Login untuk Siswa -->
        <form id="loginFormSiswa" method="POST" action="{{ route('login.siswa') }}" style="display: none;">
            @csrf
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-id-badge me-1"></i> NIS (Username)
                </label>
                <div class="input-group">
                    <i class="fas fa-user-graduate input-icon"></i>
                    <input type="text" 
                           class="form-control" 
                           name="nis" 
                           id="nis"
                           placeholder="Masukkan NIS"
                           value="{{ old('nis') }}">
                </div>
                <div class="info-text">
                    Masukkan NIS (Nomor Induk Siswa) yang terdaftar
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           id="passwordSiswa"
                           placeholder="Masukkan password">
                    <button type="button" class="password-toggle" onclick="togglePassword('passwordSiswa', this)">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="password-hint" id="siswaHint">
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#4</code> + <strong>4 digit terakhir NIS</strong>
                    <br>Contoh: Jika NIS = <strong>1234567890</strong>, password = <strong>simdu#47890</strong>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember_siswa" id="remember_siswa" {{ old('remember_siswa') ? 'checked' : '' }}>
                <label for="remember_siswa">Ingat saya</label>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Login sebagai Siswa
            </button>
        </form>
        
        <!-- Informasi Akun Demo -->
        <div class="demo-accounts">
            <h6>🔑 Informasi Login</h6>
            <div class="demo-grid">
                <div class="demo-item">
                    <strong>👨‍🏫 Guru (Kepala Sekolah)</strong>
                    NUPTK: 195875365530062
                    <span>Password: simdu#40062</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🏫 Guru (WAKABIDKUR)</strong>
                    NUPTK: 623775065320013
                    <span>Password: simdu#4013</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🏫 Guru (KESISWAAN)</strong>
                    NUPTK: 405276066030003
                    <span>Password: simdu#4003</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🎓 Siswa (Contoh)</strong>
                    NIS: 1234567890
                    <span>Password: simdu#47890</span>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                &copy; {{ date('Y') }} Sistem Informasi Sekolah
            </small>
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility Function
        function togglePassword(inputId, buttonElement) {
            const passwordInput = document.getElementById(inputId);
            const icon = buttonElement.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function setRole(role) {
            const formGuru = document.getElementById('loginFormGuru');
            const formSiswa = document.getElementById('loginFormSiswa');
            const roleBtns = document.querySelectorAll('.role-btn');
            
            if (role === 'guru') {
                formGuru.style.display = 'block';
                formSiswa.style.display = 'none';
                
                roleBtns.forEach(btn => {
                    if (btn.getAttribute('data-role') === 'guru') {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            } else {
                formGuru.style.display = 'none';
                formSiswa.style.display = 'block';
                
                roleBtns.forEach(btn => {
                    if (btn.getAttribute('data-role') === 'siswa') {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
        }
        
        // Auto-generate password hint based on NUPTK input
        document.getElementById('nuptk')?.addEventListener('input', function(e) {
            const nuptk = e.target.value;
            const hintDiv = document.getElementById('guruHint');
            const passwordInput = document.getElementById('passwordGuru');
            
            if (nuptk.length >= 4) {
                const last4 = nuptk.slice(-4);
                hintDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i> Format password: <code>simdu#4</code> + <strong>4 digit terakhir NUPTK</strong><br>✅ Password Anda = <strong><code>simdu#4' + last4 + '</code></strong>';
                passwordInput.placeholder = 'simdu#4' + last4;
            } else {
                hintDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i> Format password: <code>simdu#4</code> + <strong>4 digit terakhir NUPTK</strong><br>Contoh: Jika NUPTK = <strong>195875365530062</strong>, password = <strong>simdu#40062</strong>';
                passwordInput.placeholder = 'Masukkan password';
            }
        });
        
        // Auto-generate password hint based on NIS input
        document.getElementById('nis')?.addEventListener('input', function(e) {
            const nis = e.target.value;
            const hintDiv = document.getElementById('siswaHint');
            const passwordInput = document.getElementById('passwordSiswa');
            
            if (nis.length >= 4) {
                const last4 = nis.slice(-4);
                hintDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i> Format password: <code>simdu#4</code> + <strong>4 digit terakhir NIS</strong><br>✅ Password Anda = <strong><code>simdu#4' + last4 + '</code></strong>';
                passwordInput.placeholder = 'simdu#4' + last4;
            } else {
                hintDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i> Format password: <code>simdu#4</code> + <strong>4 digit terakhir NIS</strong><br>Contoh: Jika NIS = <strong>1234567890</strong>, password = <strong>simdu#47890</strong>';
                passwordInput.placeholder = 'Masukkan password';
            }
        });
        
        // Set initial role based on old input
        @if(old('nis'))
            setRole('siswa');
        @else
            setRole('guru');
        @endif
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>