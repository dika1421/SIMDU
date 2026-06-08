<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Sistem Informasi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            text-align: center;
            max-width: 600px;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h1 {
            color: #333;
            font-weight: 800;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        
        .school-icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        p {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
            line-height: 1.6;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
        }
        
        .feature {
            text-align: center;
        }
        
        .feature i {
            font-size: 30px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .feature h5 {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <i class="fas fa-school school-icon"></i>
        <h1>SIMDU</h1>
        <p>
            Aplikasi manajemen sekolah terintegrasi untuk Kepala Sekolah, 
            Staf Administrasi, Guru, dan Siswa. Kelola data sekolah dengan mudah dan efisien.
        </p>
        
        <a href="{{ route('login') }}" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>
            Mulai Login
        </a>
        
        <div class="features">
            <div class="feature">
                <i class="fas fa-chart-line"></i>
                <h5>Dashboard Interaktif</h5>
            </div>
            <div class="feature">
                <i class="fas fa-users"></i>
                <h5>Manajemen Pengguna</h5>
            </div>
            <div class="feature">
                <i class="fas fa-file-alt"></i>
                <h5>Laporan Lengkap</h5>
            </div>
        </div>
        
        <div class="mt-4 text-muted">
            <small>&copy; {{ date('Y') }} Sistem Informasi Sekolah</small>
        </div>
    </div>
</body>
</html>