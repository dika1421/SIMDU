<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIMDU</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #0f172a 0%, #1a237e 40%, #0d1445 100%);
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        /* ===== BACKGROUND ANIMASI ===== */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(76, 175, 80, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floatBg 12s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.10), transparent 70%);
            border-radius: 50%;
            animation: floatBg 15s ease-in-out infinite reverse;
        }

        @keyframes floatBg {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -40px) scale(1.1); }
        }

        /* ===== FLOATING DOTS ===== */
        .floating-dots {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .floating-dots span {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: floatDot 20s linear infinite;
        }

        @keyframes floatDot {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }

        /* ===== LOGIN WRAPPER ===== */
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.05);
            padding: 36px 32px 28px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(40px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 480px) {
            .login-wrapper {
                padding: 24px 18px 20px;
                border-radius: 20px;
                max-width: 100%;
                margin: 10px;
            }
        }

        /* ===== BACK BUTTON ===== */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 50px;
            transition: all 0.3s ease;
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid transparent;
            margin-bottom: 16px;
        }

        .back-button:hover {
            color: #1a237e;
            background: rgba(26, 35, 126, 0.06);
            border-color: rgba(26, 35, 126, 0.1);
            transform: translateX(-4px);
        }

        .back-button i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .back-button:hover i {
            transform: translateX(-4px);
        }

        /* ===== LOGIN HEADER ===== */
        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-header .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .login-header .logo-wrapper::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(26, 35, 126, 0.15), rgba(76, 175, 80, 0.15));
            z-index: -1;
            animation: pulseGlow 3s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        .login-header .logo-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .login-header h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }

        .login-header .sub-title {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 600;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .login-header .sub-title span {
            color: #4caf50;
        }

        /* ===== ROLE SELECTOR ===== */
        .role-selector {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 14px;
            margin-bottom: 22px;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .role-selector .slider {
            position: absolute;
            top: 4px;
            bottom: 4px;
            width: calc(50% - 4px);
            background: white;
            border-radius: 11px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.35s cubic-bezier(0.23, 1, 0.32, 1);
            left: 4px;
        }

        .role-selector .slider.slide-right {
            transform: translateX(calc(100% + 2px));
        }

        .role-btn {
            flex: 1;
            padding: 10px 4px;
            border: none;
            background: transparent;
            border-radius: 11px;
            font-weight: 600;
            font-size: 0.7rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            position: relative;
            z-index: 1;
        }

        .role-btn i {
            font-size: 1.15rem;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            color: #1a237e;
        }

        .role-btn.active i {
            color: #1a237e;
        }

        .role-btn:hover:not(.active) {
            color: #0f172a;
        }

        .role-btn:hover:not(.active) i {
            transform: scale(1.1);
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control-custom {
            width: 100%;
            height: 46px;
            padding: 0 44px 0 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.85rem;
            background: #fafcff;
            transition: all 0.25s ease;
            color: #0f172a;
            font-weight: 500;
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
            background: #ffffff;
        }

        .form-control-custom:focus ~ .input-icon {
            color: #4f46e5;
        }

        .form-control-custom.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .form-control-custom.is-invalid ~ .input-icon {
            color: #ef4444;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 4px;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: #4f46e5;
            background: rgba(79, 70, 229, 0.06);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 18px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #4f46e5;
            cursor: pointer;
            border-radius: 4px;
            margin: 0;
        }

        .remember-me label {
            color: #475569;
            font-size: 0.75rem;
            cursor: pointer;
            margin: 0;
            font-weight: 500;
        }

        .forgot-link {
            color: #4f46e5;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: #1a237e;
            text-decoration: underline;
        }

        /* ===== BUTTON LOGIN ===== */
        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1a237e, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(79, 70, 229, 0.35);
            background: linear-gradient(135deg, #283593, #5a4fcf);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(-1px);
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
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        /* ===== ALERT ===== */
        .alert {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            animation: slideDown 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border-left: 4px solid #22c55e;
        }

        .alert i {
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ===== FOOTER ===== */
        .footer-text {
            text-align: center;
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 20px;
            border-top: 1px solid #e9edf2;
            padding-top: 16px;
        }

        .footer-text strong {
            color: #4f46e5;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .login-header .logo-img {
                width: 60px;
                height: 60px;
            }
            .login-header h2 {
                font-size: 1.1rem;
            }
            .form-control-custom {
                height: 42px;
                font-size: 0.8rem;
            }
            .btn-login {
                height: 44px;
                font-size: 0.85rem;
            }
            .role-btn {
                font-size: 0.65rem;
                padding: 8px 4px;
            }
            .role-btn i {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- ===== FLOATING DOTS ===== -->
<div class="floating-dots">
    @for($i = 0; $i < 25; $i++)
        <span style="left: {{ rand(2, 98) }}%; animation-delay: {{ rand(0, 20) }}s; animation-duration: {{ rand(15, 30) }}s; width: {{ rand(2, 6) }}px; height: {{ rand(2, 6) }}px;"></span>
    @endfor
</div>

<div class="login-wrapper">

    <!-- ===== BACK BUTTON ===== -->
    <a href="{{ route('landing') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <!-- ===== LOGIN HEADER ===== -->
    <div class="login-header">
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo_smk.jpg') }}" alt="SMK Darul Ulum" class="logo-img">
        </div>
        <h2>Sistem Informasi Sekolah</h2>
        <p class="sub-title">SMK <span>Darul Ulum</span></p>
    </div>

    <!-- ===== ROLE SELECTOR ===== -->
    <div class="role-selector" id="roleSelector">
        <div class="slider" id="roleSlider"></div>
        <button type="button" class="role-btn active" data-role="guru" onclick="setRole('guru')">
            <i class="fas fa-chalkboard-user"></i> Guru / Staf
        </button>
        <button type="button" class="role-btn" data-role="siswa" onclick="setRole('siswa')">
            <i class="fas fa-user-graduate"></i> Siswa
        </button>
    </div>

    <!-- ===== ALERTS ===== -->
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- ===== FORM GURU ===== -->
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
                <button type="button" class="password-toggle" onclick="togglePassword('passwordGuru', this)">
                    <i class="far fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>
            <a href="#" class="forgot-link">Lupa password?</a>
        </div>

        <button type="submit" class="btn-login" id="btnLoginGuru">
            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login</span>
            <span class="spinner"><i class="fas fa-circle-notch"></i></span>
        </button>
    </form>

    <!-- ===== FORM SISWA ===== -->
    <form id="loginFormSiswa" method="POST" action="{{ route('login.siswa') }}" style="display: none;" onsubmit="return handleSubmit(this)">
        @csrf

        <div class="form-group">
            <label class="form-label"><i class="fas fa-id-badge me-1"></i> NIS <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-user-graduate input-icon"></i>
                <input type="text" class="form-control-custom @error('nis') is-invalid @enderror" name="nis" id="nis"
                       placeholder="Masukkan NIS" value="{{ old('nis') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-lock me-1"></i> Password <span class="required">*</span></label>
            <div class="input-group-custom">
                <i class="fas fa-key input-icon"></i>
                <input type="password" class="form-control-custom @error('password') is-invalid @enderror" name="password" id="passwordSiswa" placeholder="Masukkan password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('passwordSiswa', this)">
                    <i class="far fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" name="remember_siswa" id="remember_siswa" {{ old('remember_siswa') ? 'checked' : '' }}>
                <label for="remember_siswa">Ingat saya</label>
            </div>
            <a href="#" class="forgot-link">Lupa password?</a>
        </div>

        <button type="submit" class="btn-login" id="btnLoginSiswa">
            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Login</span>
            <span class="spinner"><i class="fas fa-circle-notch"></i></span>
        </button>
    </form>

    <div class="footer-text">
        &copy; {{ date('Y') }} <strong>SMK Darul Ulum</strong> - Sistem Informasi Sekolah
    </div>
</div>

<script>
    // ===== TOGGLE PASSWORD =====
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // ===== SET ROLE =====
    function setRole(role) {
        // Toggle forms
        document.getElementById('loginFormGuru').style.display = role === 'guru' ? 'block' : 'none';
        document.getElementById('loginFormSiswa').style.display = role === 'siswa' ? 'block' : 'none';

        // Toggle active class
        document.querySelectorAll('.role-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.role === role);
        });

        // Toggle slider
        const slider = document.getElementById('roleSlider');
        if (role === 'siswa') {
            slider.classList.add('slide-right');
        } else {
            slider.classList.remove('slide-right');
        }

        // Remove invalid state
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // ===== HANDLE SUBMIT =====
    function handleSubmit(form) {
        const btn = form.querySelector('.btn-login');
        btn.classList.add('loading');
        btn.disabled = true;
        return true;
    }

    // ===== INITIALIZE ROLE =====
    @if(old('nis'))
        setRole('siswa');
    @else
        setRole('guru');
    @endif
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>