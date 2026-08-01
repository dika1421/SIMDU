<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIMDU - Sistem Informasi Manajemen SMK Darul Ulum</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #1a237e !important;
        }
        
        .navbar-brand span {
            color: #4caf50;
        }
        
        .navbar .nav-link {
            font-weight: 500;
            color: #333 !important;
            transition: color 0.3s ease;
            margin: 0 10px;
        }
        
        .navbar .nav-link:hover {
            color: #1a237e !important;
        }
        
        .btn-login-nav {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white !important;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-login-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 126, 0.3);
            background: linear-gradient(135deg, #283593, #1a237e);
        }
        
        /* ===== HERO SECTION ===== */
        .hero-section {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(26, 35, 126, 0.1), transparent 70%);
            border-radius: 50%;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(76, 175, 80, 0.08), transparent 70%);
            border-radius: 50%;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #1a237e;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .hero-title span {
            color: #4caf50;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
            max-width: 500px;
        }
        
        .hero-image {
            position: relative;
            z-index: 1;
            animation: float 3s ease-in-out infinite;
        }
        
        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(26, 35, 126, 0.3);
            color: white;
        }
        
        .btn-outline-custom {
            background: transparent;
            color: #1a237e;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid #1a237e;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline-custom:hover {
            background: #1a237e;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(26, 35, 126, 0.2);
        }
        
        /* ===== STATISTIK ===== */
        .stat-section {
            padding: 60px 0;
            background: white;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-item .number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a237e;
        }
        
        .stat-item .label {
            font-size: 0.9rem;
            color: #777;
            font-weight: 500;
        }
        
        /* ===== FITUR ===== */
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 10px;
        }
        
        .section-subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 50px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            text-align: center;
            border-bottom: 4px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            border-bottom-color: #1a237e;
        }
        
        .feature-card .icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #e8eaf6, #c5cae9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            color: #1a237e;
        }
        
        .feature-card h5 {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 10px;
        }
        
        .feature-card p {
            color: #777;
            font-size: 0.95rem;
        }
        
        /* ===== FOOTER ===== */
        .footer {
            background: #1a237e;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .footer .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .footer .social-icons a:hover {
            background: #4caf50;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-section {
                padding: 100px 0 50px;
                text-align: center;
            }
            .hero-subtitle {
                margin: 0 auto 30px;
            }
            .section-title {
                font-size: 2rem;
            }
            .stat-item .number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing') }}">
            <i class="fas fa-school me-2"></i>SIM<span>DU</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing.features') }}">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing.about') }}">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing.contact') }}">Kontak</a></li>
            </ul>
            <a href="{{ route('login') }}" class="btn btn-login-nav ms-3">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
    </div>
</nav>

<!-- ===== HERO SECTION ===== -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    Sistem Informasi <br><span>Manajemen Sekolah</span>
                </h1>
                <p class="hero-subtitle">
                    SIMDU adalah platform manajemen sekolah terintegrasi yang memudahkan 
                    pengelolaan data siswa, guru, keuangan, dan aktivitas akademik secara 
                    efisien dan transparan.
                </p>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary-custom me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                    </a>
                    <a href="{{ route('landing.features') }}" class="btn btn-outline-custom">
                        <i class="fas fa-info-circle me-2"></i>Pelajari
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="{{ asset('images/logo_smk.jpg') }}" alt="SIMDU" class="img-fluid" style="max-height: 400px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATISTIK ===== -->
<section class="stat-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 stat-item">
                <div class="number" id="statSiswa">0</div>
                <div class="label">Siswa Aktif</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="number" id="statGuru">0</div>
                <div class="label">Guru & Staf</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="number" id="statKelas">0</div>
                <div class="label">Kelas</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="number" id="statJurusan">0</div>
                <div class="label">Jurusan</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FITUR ===== -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Kelola seluruh aktivitas sekolah dalam satu platform terintegrasi</p>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <h5>Manajemen Siswa</h5>
                    <p>Kelola data siswa, absensi, nilai, dan rapor secara digital dan terintegrasi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h5>Manajemen Guru</h5>
                    <p>Kelola data guru, jadwal mengajar, absensi, dan kinerja guru dengan mudah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h5>Keuangan Sekolah</h5>
                    <p>Kelola pembayaran SPP, pembayaran lain, dan laporan keuangan secara transparan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    <h5>Kalender Akademik</h5>
                    <p>Pantau jadwal ujian, kegiatan sekolah, dan event penting lainnya.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                    <h5>Laporan Analitik</h5>
                    <p>Lihat statistik dan laporan untuk mendukung pengambilan keputusan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-comments"></i></div>
                    <h5>Komunikasi Terintegrasi</h5>
                    <p>Komunikasi antara guru, siswa, dan orang tua melalui chat dan broadcast.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="fas fa-school me-2"></i>SIMDU</h5>
                <p style="color: rgba(255,255,255,0.7);">
                    Sistem Informasi Manajemen Sekolah SMK Darul Ulum.
                    Terintegrasi dan efisien untuk mendukung aktivitas sekolah.
                </p>
            </div>
            <div class="col-md-4">
                <h5>Link Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('landing') }}">Beranda</a></li>
                    <li><a href="{{ route('landing.features') }}">Fitur</a></li>
                    <li><a href="{{ route('landing.about') }}">Tentang</a></li>
                    <li><a href="{{ route('landing.contact') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Kontak</h5>
                <p style="color: rgba(255,255,255,0.7);">
                    <i class="fas fa-map-marker-alt me-2"></i>Jl. Pendidikan No. 123, Kota<br>
                    <i class="fas fa-phone me-2"></i>(021) 1234-5678<br>
                    <i class="fas fa-envelope me-2"></i>info@smkdarululum.sch.id
                </p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} SIMDU - SMK Darul Ulum. All Rights Reserved.
        </div>
    </div>
</footer>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animasi angka statistik
    function animateCounter(element, target, duration) {
        let start = 0;
        const step = Math.ceil(target / (duration / 16));
        const interval = setInterval(() => {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(interval);
            }
            element.textContent = start.toLocaleString('id-ID');
        }, 16);
    }

    // Jalankan animasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Data statistik (sesuaikan dengan data dari database)
        const stats = {
            siswa: 1250,
            guru: 85,
            kelas: 36,
            jurusan: 6
        };
        
        animateCounter(document.getElementById('statSiswa'), stats.siswa, 2000);
        animateCounter(document.getElementById('statGuru'), stats.guru, 2000);
        animateCounter(document.getElementById('statKelas'), stats.kelas, 2000);
        animateCounter(document.getElementById('statJurusan'), stats.jurusan, 2000);
    });
</script>
</body>
</html>