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
        
        /* SIDEBAR */
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
        
        /* 🔥 SIDEBAR COLLAPSED */
        .app-sidebar.collapsed {
            margin-left: -280px;
        }
        
        /* 🔥 OVERLAY UNTUK MOBILE */
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
        
        /* Dropdown Menu dalam Sidebar */
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
        
        /* Submenu level 2 */
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
        
        /* MAIN CONTENT */
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .app-navbar {
            padding: 15px 25px;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* 🔥 TOGGLE SIDEBAR BUTTON */
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .btn-toggle-sidebar:hover {
            background-color: #f1f3f5;
            color: #1a252f;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
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
        
        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
        }
        
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
        
        .stat-card h2 {
            font-size: 2rem;
            margin: 10px 0 5px;
        }

        /* Badge untuk menu baru */
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
        
        /* 🔥 RESPONSIVE */
        @media (max-width: 768px) {
            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1001;
                transform: translateX(-100%);
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
            
            .btn-toggle-sidebar {
                display: block !important;
            }
        }
        
        @media (min-width: 769px) {
            .btn-toggle-sidebar {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- 🔥 OVERLAY UNTUK MOBILE -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
        <!-- SIDEBAR -->
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
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#jurusanMenu">
                            <i class="fas fa-graduation-cap"></i> Jurusan
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.jurusan.*') ? 'show' : '' }}" id="jurusanMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.jurusan.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.jurusan.index') || request()->routeIs('administrasi.jurusan.show') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Data Jurusan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.jurusan.create') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.jurusan.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus"></i> Tambah Jurusan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- MANAJEMEN KELAS -->
                    <li class="menu-section">MANAJEMEN KELAS</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#kelasMenu">
                            <i class="fas fa-school"></i> Kelas
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.kelas.*') ? 'show' : '' }}" id="kelasMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.kelas.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.kelas.index') || request()->routeIs('administrasi.kelas.show') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Data Kelas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.kelas.create') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.kelas.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus"></i> Tambah Kelas
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- MANAJEMEN SISWA -->
                    <li class="menu-section">MANAJEMEN SISWA</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#siswaMenu">
                            <i class="fas fa-user-graduate"></i> Siswa
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.siswa.*') ? 'show' : '' }}" id="siswaMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.siswa.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.siswa.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Data Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.siswa.create') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.siswa.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus"></i> Tambah Siswa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- MANAJEMEN GURU -->
                    <li class="menu-section">MANAJEMEN GURU</li>
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#guruMenu">
                            <i class="fas fa-chalkboard-user"></i> Guru
                            <i class="fas fa-chevron-down chevron float-end mt-1"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('administrasi.guru.*') ? 'show' : '' }}" id="guruMenu">
                            <ul class="nav flex-column">
                                <li>
                                    <a href="{{ route('administrasi.guru.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.guru.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Data Guru
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administrasi.guru.create') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.guru.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus"></i> Tambah Guru
                                    </a>
                                </li>
                            </ul>
                        </div>
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
                                <!-- Scan RFID -->
                                <li>
                                    <a href="{{ route('administrasi.absensi.scan') }}" 
                                    class="menu-item {{ request()->routeIs('administrasi.absensi.scan') ? 'active' : '' }}">
                                        <i class="fas fa-rss"></i> Scan RFID
                                    </a>
                                </li>
                                
                                <!-- Input Absensi -->
                                <li>
                                    <a href="#" class="dropdown-toggle-sub" data-bs-toggle="collapse" data-bs-target="#inputAbsensiMenu">
                                        <i class="fas fa-edit"></i> Input Manual
                                        <i class="fas fa-chevron-right float-end mt-1"></i>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('administrasi.absensi.siswa') || request()->routeIs('administrasi.absensi.guru') ? 'show' : '' }}" id="inputAbsensiMenu">
                                        <ul class="nav flex-column">
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
                                        </ul>
                                    </div>
                                </li>
                                
                                <!-- Rekap Absensi -->
                                <li>
                                    <a href="#" class="dropdown-toggle-sub" data-bs-toggle="collapse" data-bs-target="#rekapAbsensiMenu">
                                        <i class="fas fa-chart-line"></i> Rekap Absensi
                                        <i class="fas fa-chevron-right float-end mt-1"></i>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('administrasi.absensi.rekap-siswa') || request()->routeIs('administrasi.absensi.rekap-guru') ? 'show' : '' }}" id="rekapAbsensiMenu">
                                        <ul class="nav flex-column">
                                            <li>
                                                <a href="{{ route('administrasi.absensi.rekap-siswa') }}" 
                                                class="menu-item {{ request()->routeIs('administrasi.absensi.rekap-siswa') ? 'active' : '' }}">
                                                    <i class="fas fa-user-graduate"></i> Rekap Siswa
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('administrasi.absensi.rekap-guru') }}" 
                                                class="menu-item {{ request()->routeIs('administrasi.absensi.rekap-guru') ? 'active' : '' }}">
                                                    <i class="fas fa-chalkboard-user"></i> Rekap Guru
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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
                                    <a href="{{ route('administrasi.absensi-sholat.index') }}" 
                                       class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.index') || request()->routeIs('administrasi.absensi-sholat.dashboard') ? 'active' : '' }}">
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
                                    <a href="#" class="dropdown-toggle-sub" data-bs-toggle="collapse" data-bs-target="#inputSholatMenu">
                                        <i class="fas fa-edit"></i> Input Manual
                                        <i class="fas fa-chevron-right float-end mt-1"></i>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('administrasi.absensi-sholat.siswa') || request()->routeIs('administrasi.absensi-sholat.guru') ? 'show' : '' }}" id="inputSholatMenu">
                                        <ul class="nav flex-column">
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
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-toggle-sub" data-bs-toggle="collapse" data-bs-target="#rekapSholatMenu">
                                        <i class="fas fa-chart-line"></i> Rekap Sholat
                                        <i class="fas fa-chevron-right float-end mt-1"></i>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('administrasi.absensi-sholat.rekap-siswa') || request()->routeIs('administrasi.absensi-sholat.rekap-guru') ? 'show' : '' }}" id="rekapSholatMenu">
                                        <ul class="nav flex-column">
                                            <li>
                                                <a href="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" 
                                                   class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.rekap-siswa') ? 'active' : '' }}">
                                                    <i class="fas fa-user-graduate"></i> Rekap Siswa
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('administrasi.absensi-sholat.rekap-guru') }}" 
                                                   class="menu-item {{ request()->routeIs('administrasi.absensi-sholat.rekap-guru') ? 'active' : '' }}">
                                                    <i class="fas fa-chalkboard-user"></i> Rekap Guru
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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
                    
                    <li><hr></li>
                    
                    <!-- PROFIL -->
                    <li>
                        <a href="{{ route('administrasi.profil.index') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.profil.*') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i> Profil
                        </a>
                    </li>
                    
                    <!-- PENGATURAN -->
                    <li>
                        <a href="{{ route('administrasi.pengaturan') }}" 
                           class="menu-item {{ request()->routeIs('administrasi.pengaturan') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i> Pengaturan
                        </a>
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
        
        <!-- MAIN CONTENT -->
        <main class="app-main">
            <div class="app-navbar">
                <div class="navbar-left">
                    <!-- 🔥 TOMBOL TOGGLE SIDEBAR (GARIS TIGA) -->
                    <button class="btn-toggle-sidebar" id="toggleSidebarBtn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="h5 mb-0">Selamat Datang, {{ Auth::user()->name ?? 'User' }}</span>
                </div>
                
                <div class="navbar-actions">
                    <!-- Notifikasi -->
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
            // Inisialisasi DataTable
            $('.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            });
            
            // Menjaga dropdown menu di sidebar tetap terbuka saat active
            $('.collapse').each(function() {
                if ($(this).find('.active').length) {
                    $(this).addClass('show');
                }
            });
            
            // Auto close alert setelah 5 detik
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        // ===== FUNGSI TOGGLE SIDEBAR =====
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Untuk desktop
            if (window.innerWidth > 768) {
                sidebar.classList.toggle('collapsed');
            } 
            // Untuk mobile
            else {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            }
        }

        // ===== TUTUP SIDEBAR KETIKA KLIK DI LUAR (MOBILE) =====
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('appSidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile && sidebar.classList.contains('mobile-open')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = toggleBtn.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('mobile-open');
                    document.getElementById('sidebarOverlay').classList.remove('active');
                }
            }
        });

        // ===== RESIZE WINDOW =====
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });

        // ===== SIDEBAR COLLAPSE STATE (DESKTOP) =====
        // Simpan state di localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('appSidebar');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (window.innerWidth > 768 && isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        });

        // Simpan state saat toggle (desktop)
        document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('appSidebar');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', !isCollapsed);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>