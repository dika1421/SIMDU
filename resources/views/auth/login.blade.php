<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIMDU</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #f0f4ff 0%, #d9e2ef 100%);
            padding: 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.18);
            padding: 32px 28px;
        }

        @media (max-width: 480px) {
            .login-wrapper { padding: 24px 16px; border-radius: 20px; max-width: 100%; }
        }

        .login-header { text-align: center; margin-bottom: 24px; }
        .login-header .logo-img { 
            max-width: 120px; 
            height: auto; 
            margin: 0 auto 10px; 
            display: block; 
            border-radius: 12px;
        }
        .login-header h2 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .login-header .sub-title { color: #64748b; font-size: 0.8rem; font-weight: 600; margin: 0; letter-spacing: 1px; }

        .role-selector {
            display: flex; gap: 6px;
            background: #f1f5f9; padding: 4px;
            border-radius: 12px; margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .role-btn {
            flex: 1; padding: 8px 4px; border: none;
            background: transparent; border-radius: 10px;
            font-weight: 600; font-size: 0.7rem; color: #64748b;
            cursor: pointer; transition: all 0.25s ease;
            display: flex; flex-direction: column; align-items: center; gap: 2px;
        }
        .role-btn i { font-size: 1.1rem; }
        .role-btn.active { background: #ffffff; color: #1e293b; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .role-btn:hover:not(.active) { color: #0f172a; }

        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 0.75rem; font-weight: 600; color: #1e293b; margin-bottom: 4px; }
        .form-label .required { color: #ef4444; margin-left: 2px; }

        .input-group-custom { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }
        .form-control-custom {
            width: 100%; height: 44px; padding: 0 40px 0 40px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.85rem; background: #fafcff;
            transition: all 0.2s ease; color: #0f172a;
        }
        .form-control-custom:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79,70,229,0.08); background: #ffffff; }
        .form-control-custom.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.08); }

        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; font-size: 0.85rem; cursor: pointer; padding: 4px; }
        .password-toggle:hover { color: #4f46e5; }

        .remember-me { display: flex; align-items: center; gap: 8px; margin: 2px 0 14px; }
        .remember-me input[type="checkbox"] { width: 16px; height: 16px; accent-color: #4f46e5; cursor: pointer; }
        .remember-me label { color: #475569; font-size: 0.75rem; cursor: pointer; margin: 0; }

        .btn-login {
            width: 100%; height: 44px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white; border: none; border-radius: 10px;
            font-weight: 700; font-size: 0.9rem;
            cursor: pointer; transition: all 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.30); }
        .btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-login .spinner { display: none; }
        .btn-login.loading .spinner { display: inline-block; }
        .btn-login.loading .btn-text { display: none; }

        .alert { border-radius: 10px; padding: 10px 14px; margin-bottom: 14px; border: none; display: flex; align-items: center; gap: 8px; font-size: 0.8rem; }
        .alert-danger { background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }
        .alert-success { background: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e; }
        .alert i { font-size: 0.9rem; }

        .footer-text { text-align: center; font-size: 0.6rem; color: #94a3b8; margin-top: 18px; border-top: 1px solid #e9edf2; padding-top: 14px; }
        .footer-text strong { color: #4f46e5; }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="login-header">
        <!-- Logo dari Imgur -->
        <img src="https://i.imgur.com/h61CIWN.png" alt="SMK Darul Ulum" class="logo-img" onerror="this.style.display='none'">
        <h2>Sistem Informasi Sekolah</h2>
        <p class="sub-title">SMK DARUL ULUM</p>
    </div>

    <div class="role-selector">
        <button type="button" class="role-btn active" data-role="guru" onclick="setRole('guru')">
            <i class="fas fa-chalkboard-user"></i> Guru / Staf
        </button>
        <button type="button" class="role-btn" data-role="siswa" onclick="setRole('siswa')">
            <i class="fas fa-user-graduate"></i> Siswa
        </button>
    </div>

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form id="loginFormGuru" method="POST" action="{{ route('login.guru') }}" style="display: block;" onsubmit="return handleSubmit(this)">
        @csrf
        <div class="form-group">
            <label class="form-label"><i class="fas fa-id-card me-1"></i> NUPTK <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="form-control-custom @error('nuptk') is-invalid @enderror" name="nuptk" id="nuptk"
                       placeholder="Masukkan NUPTK" value="{{ old('nuptk') }}" autofocus maxlength="20" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-lock me-1"></i> Password <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-key input-icon"></i>
                <input type="password" class="form-control-custom @error('password') is-invalid @enderror" name="password" id="passwordGuru" placeholder="Masukkan password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('passwordGuru', this)"><i class="far fa-eye"></i></button>
            </div>
        </div>

        <div class="remember-me">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ingat saya</label>
        </div>

        <button type="submit" class="btn-login" id="btnLoginGuru">
            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login</span>
            <span class="spinner"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
        </button>
    </form>

    <form id="loginFormSiswa" method="POST" action="{{ route('login.siswa') }}" style="display: none;" onsubmit="return handleSubmit(this)">
        @csrf
        <div class="form-group">
            <label class="form-label"><i class="fas fa-id-badge me-1"></i> NIS <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-user-graduate input-icon"></i>
                <input type="text" class="form-control-custom @error('nis') is-invalid @enderror" name="nis" id="nis" placeholder="Masukkan NIS" value="{{ old('nis') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-lock me-1"></i> Password <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-key input-icon"></i>
                <input type="password" class="form-control-custom @error('password') is-invalid @enderror" name="password" id="passwordSiswa" placeholder="Masukkan password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('passwordSiswa', this)"><i class="far fa-eye"></i></button>
            </div>
        </div>

        <div class="remember-me">
            <input type="checkbox" name="remember_siswa" id="remember_siswa" {{ old('remember_siswa') ? 'checked' : '' }}>
            <label for="remember_siswa">Ingat saya</label>
        </div>

        <button type="submit" class="btn-login" id="btnLoginSiswa">
            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login</span>
            <span class="spinner"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
        </button>
    </form>

    <div class="footer-text">
        &copy; {{ date('Y') }} <strong>SMK Darul Ulum</strong> - Sistem Informasi Sekolah
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
        else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
    }

    function setRole(role) {
        document.getElementById('loginFormGuru').style.display = role === 'guru' ? 'block' : 'none';
        document.getElementById('loginFormSiswa').style.display = role === 'siswa' ? 'block' : 'none';
        document.querySelectorAll('.role-btn').forEach(b => b.classList.toggle('active', b.dataset.role === role));
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    function handleSubmit(form) {
        const btn = form.querySelector('.btn-login');
        btn.classList.add('loading');
        btn.disabled = true;
        return true;
    }

    @if(old('nis')) setRole('siswa'); @else setRole('guru'); @endif
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>