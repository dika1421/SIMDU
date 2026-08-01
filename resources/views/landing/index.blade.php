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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #ffffff;
            overflow-x: hidden;
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #1a237e, #4caf50);
            border-radius: 10px;
        }
        
        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 30px rgba(0,0,0,0.06);
            padding: 12px 0;
            transition: all 0.4s ease;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 5px 40px rgba(0,0,0,0.08);
            padding: 8px 0;
        }
        
        .navbar-brand {
            font-weight: 900;
            font-size: 1.6rem;
            color: #1a237e !important;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand span {
            color: #4caf50;
        }
        
        .navbar-brand i {
            color: #4caf50;
            font-size: 1.4rem;
        }
        
        .navbar .nav-link {
            font-weight: 500;
            color: #555 !important;
            transition: all 0.3s ease;
            margin: 0 8px;
            padding: 8px 16px !important;
            border-radius: 10px;
            position: relative;
        }
        
        .navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #1a237e, #4caf50);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar .nav-link:hover::after {
            width: 60%;
        }
        
        .navbar .nav-link:hover {
            color: #1a237e !important;
            background: rgba(26, 35, 126, 0.05);
        }
        
        .btn-login-nav {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white !important;
            padding: 10px 32px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-login-nav:hover::before {
            left: 100%;
        }
        
        .btn-login-nav:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 35px rgba(26, 35, 126, 0.35);
            background: linear-gradient(135deg, #283593, #1a237e);
        }
        
        /* ===== HERO SECTION ===== */
        .hero-section {
            padding: 140px 0 100px;
            background: linear-gradient(160deg, #f0f2ff 0%, #e8ecf8 40%, #dce3f0 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .hero-section .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
        }
        
        .hero-section .bg-shape-1 {
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, #1a237e, #4caf50);
            top: -200px;
            right: -200px;
            animation: floatShape 8s ease-in-out infinite;
        }
        
        .hero-section .bg-shape-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #4caf50, #1a237e);
            bottom: -150px;
            left: -150px;
            animation: floatShape 10s ease-in-out infinite reverse;
        }
        
        .hero-section .bg-shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #ff6b6b, #ffd93d);
            top: 30%;
            left: 50%;
            animation: floatShape 12s ease-in-out infinite;
        }
        
        @keyframes floatShape {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
        
        .hero-section .floating-dots {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        
        .hero-section .floating-dots span {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #1a237e;
            border-radius: 50%;
            opacity: 0.2;
            animation: floatDot 15s linear infinite;
        }
        
        @keyframes floatDot {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.2; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        
        .hero-badge {
            display: inline-block;
            background: rgba(26, 35, 126, 0.1);
            color: #1a237e;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(26, 35, 126, 0.15);
            backdrop-filter: blur(10px);
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            color: #1a237e;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }
        
        .hero-title .highlight {
            background: linear-gradient(135deg, #4caf50, #66bb6a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-title .highlight-blue {
            background: linear-gradient(135deg, #1a237e, #3949ab);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-subtitle {
            font-size: 1.15rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 35px;
            max-width: 500px;
        }
        
        .hero-image {
            position: relative;
            z-index: 2;
        }
        
        .hero-image .image-wrapper {
            position: relative;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(26, 35, 126, 0.15);
            background: white;
            padding: 20px;
            animation: floatImage 5s ease-in-out infinite;
        }
        
        @keyframes floatImage {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(1deg); }
        }
        
        .hero-image .image-wrapper img {
            width: 100%;
            border-radius: 20px;
            display: block;
        }
        
        .hero-image .floating-badge {
            position: absolute;
            background: white;
            padding: 12px 20px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: floatBadge 4s ease-in-out infinite;
            z-index: 3;
        }
        
        .hero-image .floating-badge-1 {
            top: -20px;
            right: -20px;
            animation-delay: 0s;
        }
        
        .hero-image .floating-badge-2 {
            bottom: -10px;
            left: -30px;
            animation-delay: 2s;
        }
        
        @keyframes floatBadge {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .hero-image .floating-badge .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }
        
        .hero-image .floating-badge .icon.green { background: linear-gradient(135deg, #4caf50, #66bb6a); }
        .hero-image .floating-badge .icon.blue { background: linear-gradient(135deg, #1a237e, #3949ab); }
        .hero-image .floating-badge .text { font-weight: 600; font-size: 0.9rem; color: #333; }
        .hero-image .floating-badge .text small { display: block; font-weight: 400; font-size: 0.7rem; color: #999; }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 16px 45px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(26, 35, 126, 0.25);
        }
        
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-primary-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 45px rgba(26, 35, 126, 0.35);
            color: white;
        }
        
        .btn-outline-custom {
            background: transparent;
            color: #1a237e;
            padding: 16px 45px;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid #1a237e;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-outline-custom:hover {
            background: #1a237e;
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(26, 35, 126, 0.2);
        }
        
        /* ===== STATISTIK ===== */
        .stat-section {
            padding: 70px 0;
            background: white;
            position: relative;
        }
        
        .stat-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(26, 35, 126, 0.1), transparent);
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            position: relative;
        }
        
        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 40px;
            width: 1px;
            background: rgba(0,0,0,0.06);
        }
        
        .stat-item .number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #1a237e, #4caf50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }
        
        .stat-item .label {
            font-size: 0.95rem;
            color: #888;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .stat-item .icon-stat {
            font-size: 2rem;
            color: rgba(26, 35, 126, 0.1);
            margin-bottom: 10px;
            display: block;
        }
        
        /* ===== FITUR ===== */
        .features-section {
            padding: 100px 0;
            background: #f8faff;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 900;
            color: #1a237e;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .section-title .highlight {
            background: linear-gradient(135deg, #4caf50, #66bb6a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .section-subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 60px;
            font-size: 1.1rem;
        }
        
        .feature-card {
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.04);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            height: 100%;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(26, 35, 126, 0.02), rgba(76, 175, 80, 0.02));
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .feature-card:hover::before {
            opacity: 1;
        }
        
        .feature-card:hover {
            transform: translateY(-12px) scale(1.01);
            box-shadow: 0 25px 60px rgba(26, 35, 126, 0.10);
            border-color: rgba(26, 35, 126, 0.1);
        }
        
        .feature-card .icon-wrapper {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
            transition: all 0.5s ease;
            position: relative;
            z-index: 1;
        }
        
        .feature-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(-5deg);
        }
        
        .feature-card .icon-wrapper.gradient-1 { background: linear-gradient(135deg, #1a237e, #3949ab); }
        .feature-card .icon-wrapper.gradient-2 { background: linear-gradient(135deg, #4caf50, #66bb6a); }
        .feature-card .icon-wrapper.gradient-3 { background: linear-gradient(135deg, #ff6b6b, #ff8a80); }
        .feature-card .icon-wrapper.gradient-4 { background: linear-gradient(135deg, #ffd93d, #ffb300); }
        .feature-card .icon-wrapper.gradient-5 { background: linear-gradient(135deg, #6c5ce7, #a29bfe); }
        .feature-card .icon-wrapper.gradient-6 { background: linear-gradient(135deg, #00b894, #55efc4); }
        
        .feature-card .icon-wrapper::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 25px;
            background: inherit;
            opacity: 0.15;
            z-index: -1;
            transform: scale(0.8);
            transition: transform 0.5s ease;
        }
        
        .feature-card:hover .icon-wrapper::after {
            transform: scale(1.2);
        }
        
        .feature-card h5 {
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 12px;
            font-size: 1.2rem;
            position: relative;
            z-index: 1;
        }
        
        .feature-card p {
            color: #777;
            font-size: 0.95rem;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }
        
        .feature-card .learn-more {
            display: inline-block;
            margin-top: 15px;
            color: #1a237e;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .feature-card .learn-more i {
            transition: transform 0.3s ease;
            margin-left: 5px;
        }
        
        .feature-card .learn-more:hover i {
            transform: translateX(5px);
        }
        
        /* ===== GALERI SECTION ===== */
        .gallery-section {
            padding: 100px 0;
            background: #ffffff;
        }
        
        .gallery-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .gallery-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(26, 35, 126, 0.15);
        }
        
        .gallery-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .gallery-card:hover img {
            transform: scale(1.08);
        }
        
        .gallery-card .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 20px 15px;
            background: linear-gradient(transparent, rgba(0,0,0,0.75));
            opacity: 0;
            transition: all 0.4s ease;
        }
        
        .gallery-card:hover .overlay {
            opacity: 1;
        }
        
        .gallery-card .overlay h6 {
            color: white;
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }
        
        .gallery-card .overlay .badge-category {
            background: rgba(76, 175, 80, 0.9);
            color: white;
            font-size: 0.65rem;
            padding: 3px 12px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .gallery-card .overlay .badge-date {
            color: rgba(255,255,255,0.7);
            font-size: 0.65rem;
        }
        
        /* ===== CTA SECTION ===== */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #1a237e, #0d1445);
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(76, 175, 80, 0.1), transparent 70%);
            border-radius: 50%;
        }
        
        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -30%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.03), transparent 70%);
            border-radius: 50%;
        }
        
        .cta-section .container {
            position: relative;
            z-index: 1;
        }
        
        .cta-title {
            font-size: 2.8rem;
            font-weight: 900;
            color: white;
            margin-bottom: 15px;
        }
        
        .cta-title span {
            color: #4caf50;
        }
        
        .cta-subtitle {
            color: rgba(255,255,255,0.7);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .btn-cta {
            background: linear-gradient(135deg, #4caf50, #43a047);
            color: white;
            padding: 16px 45px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
        }
        
        .btn-cta:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 45px rgba(76, 175, 80, 0.4);
            color: white;
        }
        
        /* ===== FOOTER ===== */
        .footer {
            background: #0d1445;
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .footer .social-icons a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: rgba(255,255,255,0.6);
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.05);
        }
        
        .footer .social-icons a:hover {
            background: #4caf50;
            color: white;
            transform: translateY(-4px);
            border-color: #4caf50;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.4);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            .hero-section {
                padding: 120px 0 60px;
                text-align: center;
            }
            .hero-subtitle {
                margin: 0 auto 30px;
            }
            .hero-section .btn-group {
                justify-content: center;
            }
            .stat-item:not(:last-child)::after {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-section {
                padding: 100px 0 40px;
            }
            .section-title {
                font-size: 2rem;
            }
            .stat-item .number {
                font-size: 2rem;
            }
            .hero-image .floating-badge {
                display: none;
            }
            .cta-title {
                font-size: 2rem;
            }
            .hero-image .image-wrapper {
                padding: 10px;
                margin-top: 30px;
            }
            .gallery-card img {
                height: 200px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            .btn-primary-custom, .btn-outline-custom, .btn-cta {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
            .hero-section .btn-group {
                flex-direction: column;
                gap: 10px;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
            .gallery-card img {
                height: 160px;
            }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing') }}">
            <i class="fas fa-school me-2"></i>SIM<span>DU</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
<section class="hero-section" id="home">
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>
    <div class="floating-dots">
        @for($i = 0; $i < 20; $i++)
            <span style="left: {{ rand(5, 95) }}%; top: {{ rand(5, 95) }}%; animation-delay: {{ rand(0, 10) }}s;"></span>
        @endfor
    </div>
    
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="hero-badge">
                    <i class="fas fa-rocket me-2"></i>Solusi Manajemen Sekolah Modern
                </div>
                <h1 class="hero-title">
                    Kelola Sekolah <br>
                    <span class="highlight">Lebih Mudah</span> & <br>
                    <span class="highlight-blue">Terintegrasi</span>
                </h1>
                <p class="hero-subtitle">
                    SIMDU adalah platform manajemen sekolah berbasis web yang menghubungkan 
                    semua elemen sekolah — siswa, guru, staf, dan orang tua — dalam satu 
                    ekosistem digital yang efisien dan transparan.
                </p>
                <div class="btn-group d-flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom">
                        <i class="fas fa-sign-in-alt"></i>Login Sekarang
                    </a>
                    <a href="{{ route('landing.features') }}" class="btn btn-outline-custom">
                        <i class="fas fa-play-circle"></i>Lihat Demo
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="hero-image">
                    <div class="image-wrapper">
                        <img src="{{ asset('images/logo_smk.jpg') }}" alt="SIMDU Dashboard Preview">
                    </div>
                    <div class="floating-badge floating-badge-1">
                        <div class="icon green"><i class="fas fa-check-circle"></i></div>
                        <div class="text">
                            Sistem Terintegrasi
                            <small>Semua dalam satu platform</small>
                        </div>
                    </div>
                    <div class="floating-badge floating-badge-2">
                        <div class="icon blue"><i class="fas fa-users"></i></div>
                        <div class="text">
                            1.200+ Pengguna
                            <small>Aktif setiap hari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATISTIK ===== -->
<section class="stat-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="0">
                <span class="icon-stat"><i class="fas fa-user-graduate"></i></span>
                <div class="number" id="statSiswa">0</div>
                <div class="label">Siswa Aktif</div>
            </div>
            <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="100">
                <span class="icon-stat"><i class="fas fa-chalkboard-teacher"></i></span>
                <div class="number" id="statGuru">0</div>
                <div class="label">Guru & Staf</div>
            </div>
            <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="200">
                <span class="icon-stat"><i class="fas fa-school"></i></span>
                <div class="number" id="statKelas">0</div>
                <div class="label">Kelas</div>
            </div>
            <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="300">
                <span class="icon-stat"><i class="fas fa-building"></i></span>
                <div class="number" id="statJurusan">0</div>
                <div class="label">Jurusan</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FITUR ===== -->
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 class="section-title">
                Fitur <span class="highlight">Unggulan</span>
            </h2>
            <p class="section-subtitle">
                Kelola seluruh aktivitas sekolah dengan fitur-fitur canggih dan terintegrasi
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-1"><i class="fas fa-user-graduate"></i></div>
                    <h5>Manajemen Siswa</h5>
                    <p>Kelola data siswa, absensi, nilai, dan rapor secara digital dan terintegrasi dengan sistem.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-2"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h5>Manajemen Guru</h5>
                    <p>Kelola data guru, jadwal mengajar, absensi, dan kinerja guru dengan mudah dan cepat.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-3"><i class="fas fa-money-bill-wave"></i></div>
                    <h5>Keuangan Sekolah</h5>
                    <p>Kelola pembayaran SPP, pembayaran lain, dan laporan keuangan secara transparan dan akurat.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-4"><i class="fas fa-calendar-alt"></i></div>
                    <h5>Kalender Akademik</h5>
                    <p>Pantau jadwal ujian, kegiatan sekolah, dan event penting lainnya dalam satu kalender terpusat.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-5"><i class="fas fa-chart-line"></i></div>
                    <h5>Laporan Analitik</h5>
                    <p>Lihat statistik dan laporan untuk mendukung pengambilan keputusan berbasis data yang akurat.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="icon-wrapper gradient-6"><i class="fas fa-comments"></i></div>
                    <h5>Komunikasi Terintegrasi</h5>
                    <p>Komunikasi antara guru, siswa, dan orang tua melalui chat dan broadcast dengan mudah.</p>
                    <a href="#" class="learn-more">Pelajari <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== GALERI KEGIATAN ===== 🔥 BARU -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 class="section-title">
                Galeri <span class="highlight">Kegiatan</span>
            </h2>
            <p class="section-subtitle">
                Dokumentasi kegiatan dan prestasi SMK Darul Ulum
            </p>
        </div>

        <div class="row g-4">
            @forelse($galleries ?? [] as $gallery)
                <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="gallery-card">
                        <img src="{{ asset('storage/galleries/' . $gallery->image) }}" 
                             alt="{{ $gallery->title }}"
                             loading="lazy">
                        <div class="overlay">
                            <h6>{{ Str::limit($gallery->title, 30) }}</h6>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="badge-category">
                                    <i class="fas fa-tag me-1"></i>{{ $gallery->category ?? 'Kegiatan' }}
                                </span>
                                @if($gallery->event_date)
                                    <span class="badge-date">
                                        <i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($gallery->event_date)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-images fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted">Belum Ada Galeri</h5>
                        <p class="text-muted small">Dokumentasi kegiatan akan ditampilkan di sini</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(isset($galleries) && $galleries->count() > 8)
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-outline-custom">
                    Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center text-center text-lg-start">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="cta-title">
                    Siap Mengelola Sekolah <br><span>Lebih Efisien?</span>
                </h2>
                <p class="cta-subtitle">
                    Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan <br>
                    mengelola sekolah dengan SIMDU.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="{{ route('login') }}" class="btn btn-cta">
                    <i class="fas fa-rocket"></i>Mulai Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5><i class="fas fa-school me-2"></i>SIMDU</h5>
                <p style="color: rgba(255,255,255,0.6); line-height: 1.8;">
                    Sistem Informasi Manajemen Sekolah SMK Darul Ulum. 
                    Terintegrasi dan efisien untuk mendukung seluruh aktivitas sekolah.
                </p>
                <div class="social-icons mt-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h5>Tautan</h5>
                <ul class="list-unstyled" style="color: rgba(255,255,255,0.6);">
                    <li class="mb-2"><a href="{{ route('landing') }}">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('landing.features') }}">Fitur</a></li>
                    <li class="mb-2"><a href="{{ route('landing.about') }}">Tentang</a></li>
                    <li class="mb-2"><a href="{{ route('landing.contact') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h5>Layanan</h5>
                <ul class="list-unstyled" style="color: rgba(255,255,255,0.6);">
                    <li class="mb-2"><a href="#">Manajemen Siswa</a></li>
                    <li class="mb-2"><a href="#">Manajemen Guru</a></li>
                    <li class="mb-2"><a href="#">Keuangan Sekolah</a></li>
                    <li class="mb-2"><a href="#">Laporan Analitik</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Kontak Kami</h5>
                <p style="color: rgba(255,255,255,0.6); line-height: 1.8;">
                    <i class="fas fa-map-marker-alt me-2"></i>Jl. Pendidikan No. 123, Kota<br>
                    <i class="fas fa-phone me-2"></i>(021) 1234-5678<br>
                    <i class="fas fa-envelope me-2"></i>info@smkdarululum.sch.id
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} <strong>SIMDU</strong> - SMK Darul Ulum. All Rights Reserved.
        </div>
    </div>
</footer>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // ===== INIT AOS =====
    AOS.init({
        once: true,
        duration: 800,
        easing: 'ease-out-cubic'
    });

    // ===== NAVBAR SCROLL EFFECT =====
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // ===== ANIMASI COUNTER =====
    function animateCounter(element, target, duration) {
        let start = 0;
        const step = Math.max(1, Math.ceil(target / (duration / 16)));
        const interval = setInterval(() => {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(interval);
            }
            element.textContent = start.toLocaleString('id-ID');
        }, 16);
    }

    // ===== RUN COUNTER =====
    document.addEventListener('DOMContentLoaded', function() {
        const stats = {
            siswa: {{ $totalSiswa ?? 1250 }},
            guru: {{ $totalGuru ?? 85 }},
            kelas: {{ $totalKelas ?? 36 }},
            jurusan: {{ $totalJurusan ?? 6 }}
        };
        
        animateCounter(document.getElementById('statSiswa'), stats.siswa, 2500);
        animateCounter(document.getElementById('statGuru'), stats.guru, 2500);
        animateCounter(document.getElementById('statKelas'), stats.kelas, 2500);
        animateCounter(document.getElementById('statJurusan'), stats.jurusan, 2500);
    });

    // ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
</body>
</html>