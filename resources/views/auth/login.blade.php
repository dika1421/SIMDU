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
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 540px;
            padding: 40px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .login-header h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: #888;
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .role-selector {
            display: flex;
            gap: 8px;
            margin-bottom: 25px;
            background: #f0f2f5;
            padding: 6px;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            flex-wrap: wrap;
        }
        
        .role-btn {
            flex: 1;
            min-width: 60px;
            padding: 8px 6px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #888;
            font-size: 0.75rem;
            text-align: center;
        }
        
        .role-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .role-btn i {
            display: block;
            margin: 0 auto 4px;
            font-size: 1.2rem;
        }
        
        .role-btn:hover:not(.active) {
            background: #e8e8e8;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
            font-size: 0.9rem;
        }
        
        .form-label .required {
            color: #e74c3c;
            margin-left: 3px;
        }
        
        .form-label .role-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 8px;
        }
        
        .role-badge.ks {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .role-badge.admin {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .role-badge.guru {
            background: #fff3e0;
            color: #e65100;
        }
        
        .role-badge.siswa {
            background: #fce4ec;
            color: #c62828;
        }
        
        .input-group-custom {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            z-index: 1;
            font-size: 1rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 10;
            background: transparent;
            border: none;
            font-size: 1rem;
            padding: 5px;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .form-control-custom {
            padding-left: 45px;
            padding-right: 45px;
            height: 50px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            width: 100%;
            background: #fafafa;
        }
        
        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
            background: white;
        }
        
        .form-control-custom.is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
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
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-login .spinner {
            display: none;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef0ef;
            color: #c0392b;
            border-left: 4px solid #e74c3c;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }
        
        .alert i {
            font-size: 1.2rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .remember-me label {
            color: #666;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .password-hint {
            background: #f0f7ff;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 12px;
            margin-top: 10px;
            border: 1px solid #d6e4ff;
        }
        
        .password-hint code {
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .password-hint .highlight {
            color: #667eea;
            font-weight: 700;
        }
        
        .password-hint .role-number {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
        }
        
        .demo-accounts {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }
        
        .demo-accounts h6 {
            color: #888;
            font-weight: 600;
            margin-bottom: 12px;
            text-align: center;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .demo-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            font-size: 11px;
            color: #666;
            border: 1px solid #eee;
            transition: all 0.3s;
        }
        
        .demo-item:hover {
            background: #f0f2ff;
            border-color: #667eea;
        }
        
        .demo-item strong {
            color: #333;
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
        }
        
        .demo-item .label {
            color: #999;
            font-size: 10px;
        }
        
        .demo-item .value {
            font-weight: 600;
            color: #667eea;
            font-size: 12px;
            word-break: break-all;
        }
        
        .demo-item .pass {
            font-family: monospace;
            font-size: 10px;
            color: #27ae60;
            margin-top: 3px;
            display: block;
            background: #e8f5e9;
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        .demo-item .role-badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
            margin-top: 2px;
        }
        
        .role-badge.ks {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .role-badge.admin {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .role-badge.guru {
            background: #fff3e0;
            color: #e65100;
        }
        
        .role-badge.siswa {
            background: #fce4ec;
            color: #c62828;
        }
        
        .footer-text {
            margin-top: 20px;
            text-align: center;
            color: #bbb;
            font-size: 12px;
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 25px 20px;
                margin: 15px;
                border-radius: 15px;
            }
            
            .demo-grid {
                grid-template-columns: 1fr;
            }
            
            .login-header .logo {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .role-btn {
                font-size: 0.7rem;
                padding: 6px 4px;
                min-width: 50px;
            }
            
            .role-btn i {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Sistem Informasi Sekolah</h3>
            <p>SMK Bisa</p>
        </div>
        
        <!-- Role Selector -->
        <div class="role-selector">
            <button type="button" class="role-btn active" data-role="guru" onclick="setRole('guru')">
                <i class="fas fa-chalkboard-user"></i>
                Guru / Staf
            </button>
            <button type="button" class="role-btn" data-role="siswa" onclick="setRole('siswa')">
                <i class="fas fa-user-graduate"></i>
                Siswa
            </button>
        </div>
        
        <!-- Alert Messages -->
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <!-- Form Login untuk Guru/Staf -->
        <form id="loginFormGuru" method="POST" action="{{ route('login.guru') }}" style="display: block;" onsubmit="return handleSubmit(this)">
            @csrf
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-id-card me-1"></i> 
                    NUPTK <span class="required">*</span>
                    <span class="role-badge guru">Guru</span>
                    <span class="role-badge ks">Kepsek</span>
                    <span class="role-badge admin">Admin</span>
                </label>
                <div class="input-group-custom">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" 
                           class="form-control-custom @error('nuptk') is-invalid @enderror" 
                           name="nuptk" 
                           id="nuptk"
                           placeholder="Masukkan NUPTK (16 digit)"
                           value="{{ old('nuptk') }}"
                           autofocus
                           maxlength="20"
                           required>
                </div>
                <div class="info-text text-muted small mt-1">
                    <i class="fas fa-info-circle"></i> Masukkan NUPTK yang terdaftar (contoh: 195875365530062)
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock me-1"></i> 
                    Password <span class="required">*</span>
                </label>
                <div class="input-group-custom">
                    <i class="fas fa-key input-icon"></i>
                    <input type="password" 
                           class="form-control-custom @error('password') is-invalid @enderror" 
                           name="password" 
                           id="passwordGuru"
                           placeholder="Masukkan password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('passwordGuru', this)">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="password-hint" id="guruHint">
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">1/2/3</span></code> + <strong class="highlight">4 digit terakhir NUPTK</strong>
                    <br>
                    <span class="badge bg-success me-1">#1</span> Kepala Sekolah &nbsp;
                    <span class="badge bg-primary me-1">#2</span> Admin &nbsp;
                    <span class="badge bg-warning text-dark me-1">#3</span> Guru
                    <br>Contoh: NUPTK = <strong>195875365530062</strong> → <code>simdu#10062</code> (Kepsek) / <code>simdu#30062</code> (Guru)
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>
            
            <button type="submit" class="btn-login" id="btnLoginGuru">
                <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login sebagai Guru / Staf</span>
                <span class="spinner"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
            </button>
        </form>
        
        <!-- Form Login untuk Siswa -->
        <form id="loginFormSiswa" method="POST" action="{{ route('login.siswa') }}" style="display: none;" onsubmit="return handleSubmit(this)">
            @csrf
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-id-badge me-1"></i> 
                    NIS <span class="required">*</span>
                    <span class="role-badge siswa">Siswa</span>
                </label>
                <div class="input-group-custom">
                    <i class="fas fa-user-graduate input-icon"></i>
                    <input type="text" 
                           class="form-control-custom @error('nis') is-invalid @enderror" 
                           name="nis" 
                           id="nis"
                           placeholder="Masukkan NIS"
                           value="{{ old('nis') }}"
                           required>
                </div>
                <div class="info-text text-muted small mt-1">
                    <i class="fas fa-info-circle"></i> Masukkan NIS (Nomor Induk Siswa) yang terdaftar
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock me-1"></i> 
                    Password <span class="required">*</span>
                </label>
                <div class="input-group-custom">
                    <i class="fas fa-key input-icon"></i>
                    <input type="password" 
                           class="form-control-custom @error('password') is-invalid @enderror" 
                           name="password" 
                           id="passwordSiswa"
                           placeholder="Masukkan password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('passwordSiswa', this)">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="password-hint" id="siswaHint">
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">4</span></code> + <strong class="highlight">4 digit terakhir NIS</strong>
                    <br>
                    <span class="badge bg-danger">#4</span> Siswa
                    <br>Contoh: Jika NIS = <strong>1234567890</strong>, password = <strong><code>simdu#47890</code></strong>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember_siswa" id="remember_siswa" {{ old('remember_siswa') ? 'checked' : '' }}>
                <label for="remember_siswa">Ingat saya</label>
            </div>
            
            <button type="submit" class="btn-login" id="btnLoginSiswa">
                <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login sebagai Siswa</span>
                <span class="spinner"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
            </button>
        </form>
        
        <!-- Informasi Akun Demo -->
        <div class="demo-accounts">
            <h6>🔑 Informasi Login</h6>
            <div class="demo-grid">
                <div class="demo-item">
                    <strong>👨‍🏫 Kepala Sekolah</strong>
                    <span class="label">NUPTK:</span>
                    <span class="value">195875365530062</span>
                    <span class="pass">simdu#10062</span>
                    <span class="role-badge ks">Kepsek</span>
                    <span class="role-badge guru">Guru</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🏫 Admin</strong>
                    <span class="label">NUPTK:</span>
                    <span class="value">604074464420003</span>
                    <span class="pass">simdu#20003</span>
                    <span class="role-badge admin">Admin</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🏫 Guru</strong>
                    <span class="label">NUPTK:</span>
                    <span class="value">623775065320013</span>
                    <span class="pass">simdu#30013</span>
                    <span class="role-badge guru">Guru</span>
                </div>
                <div class="demo-item">
                    <strong>👨‍🎓 Siswa</strong>
                    <span class="label">NIS:</span>
                    <span class="value">1234567890</span>
                    <span class="pass">simdu#47890</span>
                    <span class="role-badge siswa">Siswa</span>
                </div>
            </div>
            <div class="mt-2 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Multi-Role:</strong> Kepala Sekolah bisa login sebagai Guru dengan <code>simdu#30062</code>
                </small>
            </div>
        </div>
        
        <div class="footer-text">
            &copy; {{ date('Y') }} SMK Bisa - Sistem Informasi Sekolah
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility
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
        
        // Set Role
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
            
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }
        
        // Handle form submit - loading state
        function handleSubmit(form) {
            const btn = form.querySelector('.btn-login');
            btn.classList.add('loading');
            btn.disabled = true;
            return true;
        }
        
        // Auto-generate password hint based on NUPTK input
        document.getElementById('nuptk')?.addEventListener('input', function(e) {
            const nuptk = e.target.value.replace(/\s/g, '');
            const hintDiv = document.getElementById('guruHint');
            const passwordInput = document.getElementById('passwordGuru');
            
            if (nuptk.length >= 4) {
                const last4 = nuptk.slice(-4);
                hintDiv.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">1/2/3</span></code> + <strong class="highlight">4 digit terakhir NUPTK</strong>
                    <br>
                    <span class="badge bg-success me-1">#1</span> Kepala Sekolah &nbsp;
                    <span class="badge bg-primary me-1">#2</span> Admin &nbsp;
                    <span class="badge bg-warning text-dark me-1">#3</span> Guru
                    <br>✅ Password Anda:
                    <br><code>simdu#1` + last4 + `</code> (Kepsek) 
                    <br><code>simdu#2` + last4 + `</code> (Admin) 
                    <br><code>simdu#3` + last4 + `</code> (Guru)
                `;
                passwordInput.placeholder = 'simdu#3' + last4;
            } else {
                hintDiv.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">1/2/3</span></code> + <strong class="highlight">4 digit terakhir NUPTK</strong>
                    <br>
                    <span class="badge bg-success me-1">#1</span> Kepala Sekolah &nbsp;
                    <span class="badge bg-primary me-1">#2</span> Admin &nbsp;
                    <span class="badge bg-warning text-dark me-1">#3</span> Guru
                    <br>Contoh: NUPTK = <strong>195875365530062</strong> → <code>simdu#10062</code> (Kepsek) / <code>simdu#30062</code> (Guru)
                `;
                passwordInput.placeholder = 'Masukkan password';
            }
        });
        
        // Auto-generate password hint based on NIS input
        document.getElementById('nis')?.addEventListener('input', function(e) {
            const nis = e.target.value.replace(/\s/g, '');
            const hintDiv = document.getElementById('siswaHint');
            const passwordInput = document.getElementById('passwordSiswa');
            
            if (nis.length >= 4) {
                const last4 = nis.slice(-4);
                hintDiv.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">4</span></code> + <strong class="highlight">4 digit terakhir NIS</strong>
                    <br>
                    <span class="badge bg-danger">#4</span> Siswa
                    <br>✅ Password Anda = <strong><code>simdu#4` + last4 + `</code></strong>
                `;
                passwordInput.placeholder = 'simdu#4' + last4;
            } else {
                hintDiv.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Format password: <code>simdu#<span class="role-number">4</span></code> + <strong class="highlight">4 digit terakhir NIS</strong>
                    <br>
                    <span class="badge bg-danger">#4</span> Siswa
                    <br>Contoh: Jika NIS = <strong>1234567890</strong>, password = <strong><code>simdu#47890</code></strong>
                `;
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>