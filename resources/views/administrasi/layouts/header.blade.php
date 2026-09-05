<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Administrasi') - SIM Sekolah</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .app-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        
        .app-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1000;
        }
        
        .app-sidebar.collapsed {
            margin-left: -280px;
        }
        
        .app-sidebar.mobile-open {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .sidebar-header {
            padding: 30px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .sidebar-header h5 {
            margin: 10px 0 5px;
            color: white;
            font-weight: 700;
        }
        
        .sidebar-header small {
            color: #bdc3c7;
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }
        
        .sidebar-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-content::-webkit-scrollbar-track {
            background: #34495e;
        }
        
        .sidebar-content::-webkit-scrollbar-thumb {
            background: #7f8c8d;
            border-radius: 3px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu .menu-section {
            color: #bdc3c7;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 20px 10px 5px 10px;
            letter-spacing: 0.5px;
        }
        
        .sidebar-menu .menu-item {
            display: block;
            padding: 12px 15px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar-menu .menu-item i {
            width: 24px;
            margin-right: 10px;
        }
        
        .sidebar-menu .menu-item:hover {
            background-color: #34495e;
            transform: translateX(5px);
        }
        
        .sidebar-menu .menu-item.active {
            background-color: #27ae60;
        }
        
        .sidebar-menu .badge {
            float: right;
            background-color: #dc3545;
            color: white;
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
        
        .menu-item.logout {
            margin-top: 20px;
            color: #ff6b6b;
        }
        
        .menu-item.logout:hover {
            background-color: #c0392b;
            color: white;
        }
        
        hr {
            border-color: rgba(255,255,255,0.1);
            margin: 15px 0;
        }
        
        .sidebar-menu .dropdown-toggle {
            display: block;
            padding: 12px 15px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .sidebar-menu .dropdown-toggle i {
            width: 24px;
            margin-right: 10px;
        }
        
        .sidebar-menu .dropdown-toggle:hover {
            background-color: #34495e;
        }
        
        .sidebar-menu .dropdown-toggle .chevron {
            transition: transform 0.3s;
        }
        
        .sidebar-menu .dropdown-toggle[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        
        .sidebar-menu .dropdown-menu {
            position: static;
            float: none;
            width: 100%;
            background-color: #1a252f;
            border: none;
            padding-left: 35px;
            margin-top: 0;
        }
        
        .sidebar-menu .dropdown-menu .menu-item {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        .dropdown-submenu {
            position: static;
        }
        
        .dropdown-submenu .dropdown-menu-sub {
            position: static;
            float: none;
            width: 100%;
            background-color: #0f1a24;
            border: none;
            padding-left: 20px;
            margin-top: 0;
        }
        
        .dropdown-submenu .dropdown-toggle-sub {
            display: block;
            padding: 10px 15px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .dropdown-submenu .dropdown-toggle-sub i {
            width: 24px;
            margin-right: 10px;
        }
        
        .dropdown-submenu .dropdown-toggle-sub:hover {
            background-color: #34495e;
        }
        
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .app-navbar {
            padding: 12px 25px;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 65px;
        }
        
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            display: block !important;
        }
        
        .btn-toggle-sidebar:hover {
            background-color: #f1f3f5;
            color: #1a252f;
        }
        
        .btn-toggle-sidebar:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.2);
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .navbar-left .h5 {
            margin: 0;
            font-size: 1.1rem;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        .user-dropdown:hover {
            background-color: #f8f9fa;
        }
        
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .menu-new-badge {
            background-color: #e74c3c;
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 8px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        @media (max-width: 768px) {
            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1001;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .app-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .app-sidebar.collapsed {
                margin-left: 0;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .navbar-left .h5 {
                font-size: 0.9rem;
            }
            
            .app-navbar {
                padding: 10px 15px;
                min-height: 55px;
            }
            
            .app-content {
                padding: 15px;
            }
            
            .btn-toggle-sidebar {
                font-size: 1.3rem;
                padding: 5px 10px;
            }
        }
        
        @media (min-width: 769px) {
            .app-sidebar.mobile-open {
                transform: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-header">
                <i class="fas fa-school"></i>
                <h5>SIM Sekolah</h5>
                <small>Administrasi</small>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('administrasi.dashboard') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN JURUSAN -->
                    <li class="menu-section">MANAJEMEN JURUSAN</li>
                    <li>
                        <a href="{{ route('administrasi.jurusan.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.jurusan.*') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i> Data Jurusan
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN KELAS -->
                    <li class="menu-section">MANAJEMEN KELAS</li>
                    <li>
                        <a href="{{ route('administrasi.kelas.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.kelas.*') ? 'active' : '' }}">
                            <i class="fas fa-school"></i> Data Kelas
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN SISWA -->
                    <li class="menu-section">MANAJEMEN SISWA</li>
                    <li>
                        <a href="{{ route('administrasi.siswa.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.siswa.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i> Data Siswa
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN GURU -->
                    <li class="menu-section">MANAJEMEN GURU</li>
                    <li>
                        <a href="{{ route('administrasi.guru.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.guru.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-user"></i> Data Guru
                        </a>
                    </li>
                    
                    <!-- ABSENSI -->
                    <li class="menu-section">ABSENSI</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#absensiMenu">
                            <i class="fas fa-calendar-check"></i> Absensi
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.absensi.*') || request()->routeIs('administrasi.rfid.*') ? 'show' : '' }}" id="absensiMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.absensi.scan') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi.scan') ? 'active' : '' }}">
                                        <i class="fas fa-rss"></i> Scan RFID
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi.siswa') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi.siswa') ? 'active' : '' }}">
                                        <i class="fas fa-user-graduate"></i> Absensi Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi.guru') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi.guru') ? 'active' : '' }}">
                                        <i class="fas fa-chalkboard-user"></i> Absensi Guru
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi.rekap-siswa') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi.rekap-siswa') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Rekap Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi.rekap-guru') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi.rekap-guru') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Rekap Guru
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- ABSENSI SHOLAT -->
                    <li class="menu-section">ABSENSI SHOLAT</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#absensiSholatMenu">
                            <i class="fas fa-mosque"></i> Absensi Sholat
                            <span class="menu-new-badge">NEW</span>
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.absensi-sholat.*') ? 'show' : '' }}" id="absensiSholatMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Dashboard Sholat
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.scan') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.scan') ? 'active' : '' }}">
                                        <i class="fas fa-qrcode"></i> Scan QR Code
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.siswa') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.siswa') ? 'active' : '' }}">
                                        <i class="fas fa-user-graduate"></i> Absensi Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.guru') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.guru') ? 'active' : '' }}">
                                        <i class="fas fa-chalkboard-user"></i> Absensi Guru
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.rekap-siswa') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Rekap Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.absensi-sholat.rekap-guru') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.rekap-guru') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Rekap Guru
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- KEUANGAN -->
                    <li class="menu-section">KEUANGAN</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#keuanganMenu">
                            <i class="fas fa-money-bill-wave"></i> Keuangan
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.keuangan.*') ? 'show' : '' }}" id="keuanganMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.keuangan.spp') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.keuangan.spp*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-wave"></i> Pembayaran SPP
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.keuangan.pembayaran-lain.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.keuangan.pembayaran-lain*') ? 'active' : '' }}">
                                        <i class="fas fa-credit-card"></i> Pembayaran Lain
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.keuangan.laporan') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.keuangan.laporan') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice"></i> Laporan Keuangan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- JADWAL -->
                    <li class="menu-section">JADWAL</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#jadwalMenu">
                            <i class="fas fa-calendar-alt"></i> Jadwal
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.jadwal.*') ? 'show' : '' }}" id="jadwalMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.jadwal.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.jadwal.index') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt"></i> Kelola Jadwal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.jadwal.kalender') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.jadwal.kalender') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-week"></i> Kalender Jadwal
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- ARSIP -->
                    <li class="menu-section">ARSIP</li>
                    <li>
                        <a href="{{ route('administrasi.arsip.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.arsip.*') ? 'active' : '' }}">
                            <i class="fas fa-archive"></i> Arsip Dokumen
                        </a>
                    </li>
                    
                    <!-- GALERI -->
                    <li class="menu-section">GALERI</li>
                    <li>
                        <a href="{{ route('administrasi.galeri.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.galeri.*') ? 'active' : '' }}">
                            <i class="fas fa-images"></i> Galeri Kegiatan
                            <span class="badge" style="background-color: #e74c3c; color: white; float: right; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; animation: pulse 1.5s infinite;">NEW</span>
                        </a>
                    </li>
                    
                    <!-- KOMUNIKASI -->
                    <li class="menu-section">KOMUNIKASI</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#komunikasiMenu">
                            <i class="fas fa-envelope"></i> Komunikasi
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.komunikasi.*') ? 'show' : '' }}" id="komunikasiMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.komunikasi.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.komunikasi.index') ? 'active' : '' }}">
                                        <i class="fas fa-envelope"></i> Pesan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.komunikasi.broadcast') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.komunikasi.broadcast') ? 'active' : '' }}">
                                        <i class="fas fa-bullhorn"></i> Broadcast
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- MANAJEMEN AKSES -->
                    <li class="menu-section">MANAJEMEN AKSES</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#rolesMenu">
                            <i class="fas fa-user-shield"></i> Role & Permission
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.roles.*') || request()->routeIs('administrasi.permissions.*') ? 'show' : '' }}" id="rolesMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.roles.index') }}"
                                       class="menu-item {{ request()->routeIs('administrasi.roles.index') || request()->routeIs('administrasi.roles.edit') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Data Role
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.permissions.index') }}"
                                       class="menu-item {{ request()->routeIs('administrasi.permissions.*') ? 'active' : '' }}">
                                        <i class="fas fa-key"></i> Data Permission
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- LOGOUT -->
                    <li>
                        <a href="#" class="menu-item logout" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <main class="app-main">
            <div class="app-navbar">
                <div class="navbar-left">
                    <button class="btn-toggle-sidebar" id="toggleSidebarBtn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="h5 mb-0">Selamat Datang, {{ Auth::user()->name ?? 'User' }}</span>
                </div>
                
                <div class="navbar-actions">
                    <div class="dropdown">
                        <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Tidak ada notifikasi</a></li>
                        </ul>
                    </div>
                    
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('administrasi.profil.index') }}">
                                <i class="fas fa-user me-2"></i> Profil
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('administrasi.pengaturan') }}">
                                <i class="fas fa-cog me-2"></i> Pengaturan
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            });
            
            $('.collapse').each(function() {
                if ($(this).find('.active').length) {
                    $(this).addClass('show');
                }
            });
            
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (!sidebar) return;
            
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile) {
                sidebar.classList.toggle('mobile-open');
                if (overlay) {
                    overlay.classList.toggle('active');
                }
            } else {
                sidebar.classList.toggle('collapsed');
                try {
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ? 'true' : 'false');
                } catch(e) {}
            }
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('appSidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const overlay = document.getElementById('sidebarOverlay');
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile && sidebar && sidebar.classList.contains('mobile-open')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = toggleBtn && toggleBtn.contains(event.target);
                const isClickOnOverlay = overlay && overlay.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle && !isClickOnOverlay) {
                    sidebar.classList.remove('mobile-open');
                    if (overlay) {
                        overlay.classList.remove('active');
                    }
                }
            }
        });

        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 768) {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.remove('active');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('appSidebar');
            if (!sidebar) return;
            
            let isCollapsed = false;
            try {
                isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            } catch(e) {}
            
            if (window.innerWidth > 768 && isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        });

        document.querySelectorAll('.menu-item.logout, .dropdown-item.text-danger').forEach(function(el) {
            el.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin logout?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>