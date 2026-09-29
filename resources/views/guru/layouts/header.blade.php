<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') - SIM Sekolah</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .app-wrapper { display: flex; height: 100vh; width: 100%; }

        /* ============================================
           SIDEBAR
           ============================================ */
        .app-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        /* Sidebar Collapse State */
        .app-sidebar.collapsed {
            width: 0;
            overflow: hidden;
            padding: 0;
        }

        .sidebar-header {
            padding: 30px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header i { font-size: 3rem; margin-bottom: 10px; color: #fff; }
        .sidebar-header h5 { margin: 10px 0 5px; color: white; font-weight: 700; }
        .sidebar-header small { color: #bdc3c7; }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }
        .sidebar-content::-webkit-scrollbar { width: 6px; }
        .sidebar-content::-webkit-scrollbar-track { background: #34495e; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #7f8c8d; border-radius: 3px; }

        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin-bottom: 2px; }

        .sidebar-menu .menu-section {
            color: #bdc3c7;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 20px 10px 8px 10px;
            letter-spacing: 0.5px;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 0.9rem;
            position: relative;
        }
        .sidebar-menu .menu-item i { width: 24px; margin-right: 12px; font-size: 1rem; }
        .sidebar-menu .menu-item:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
            color: white;
        }
        .sidebar-menu .menu-item.active {
            background: linear-gradient(90deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
        }
        .sidebar-menu .menu-item.active i { color: white; }

        .sidebar-menu .badge-notif {
            background-color: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.65rem;
            margin-left: auto;
            font-weight: 600;
        }

        .menu-item.logout {
            margin-top: 10px;
            color: #ff6b6b;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
            cursor: pointer;
        }
        .menu-item.logout:hover { background-color: #c0392b; color: white; }

        hr { border-color: rgba(255,255,255,0.1); margin: 10px 0; }

        .menu-new-badge {
            background-color: #e74c3c;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
            animation: pulse 1.5s infinite;
            font-weight: 600;
        }
        @keyframes pulse {
            0% { opacity: 0.6; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.6; transform: scale(0.95); }
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        /* ============================================
           NAVBAR (TOPBAR) - Disederhanakan
           ============================================ */
        .app-navbar {
            padding: 10px 25px;
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            z-index: 999;
            height: 70px;
        }

        /* Navbar Left: Toggle (Breadcrumb dihilangkan) */
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-toggle-btn {
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: #2c3e50;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-toggle-btn:hover {
            background-color: #f0f7ff;
            color: #3498db;
        }

        /* Navbar Right: Actions */
        .navbar-actions { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }

        .nav-action-btn {
            background: transparent;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .nav-action-btn:hover {
            background-color: #f0f7ff;
            color: #3498db;
        }
        .nav-action-btn .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background-color: #e74c3c;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 12px;
            border-radius: 12px;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        .user-dropdown:hover { 
            background-color: #f8f9fa; 
            border-color: #e9ecef; 
        }
        .user-dropdown .avatar {
            width: 38px; 
            height: 38px; 
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex; 
            align-items: center; 
            justify-content: center;
            color: white; 
            font-weight: 600; 
            font-size: 0.9rem;
        }
        .user-dropdown .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .user-dropdown .user-name { 
            font-weight: 600; 
            font-size: 0.85rem; 
            color: #2c3e50; 
        }
        .user-dropdown .user-role { 
            font-size: 0.7rem; 
            color: #94a3b8; 
        }

        /* Dropdown Menu Styling */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            padding: 8px 0;
            min-width: 220px;
            margin-top: 10px;
        }
        .dropdown-menu .dropdown-header {
            padding: 10px 16px 5px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.85rem;
        }
        .dropdown-menu .dropdown-item {
            padding: 10px 16px;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .dropdown-menu .dropdown-item i { width: 20px; text-align: center; font-size: 0.95rem; }
        .dropdown-menu .dropdown-item:hover { background-color: #f0f7ff; }
        .dropdown-menu .dropdown-item.text-danger:hover { background-color: #fde8e8; }
        .dropdown-menu .dropdown-divider { margin: 6px 0; }

        /* ============================================
           CONTENT AREA
           ============================================ */
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
        }
        .app-content::-webkit-scrollbar { width: 6px; }
        .app-content::-webkit-scrollbar-track { background: #f1f1f1; }
        .app-content::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
        .app-content::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
        }
        .alert-success { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .alert-warning { background-color: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .alert .btn-close { padding: 10px; }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
        }
        .card-body { padding: 20px; }
        .card-footer {
            background-color: white;
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
            border-radius: 0 0 12px 12px;
        }

        /* Table Styles */
        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #495057;
        }
        .table tbody tr:hover { background-color: #f8f9fa; }

        /* Button Styles */
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 6px;
        }
        .btn-action i { font-size: 0.9rem; }

        /* Stat Card */
        .stat-card {
            border-radius: 12px;
            padding: 20px 25px;
            color: white;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
        .stat-card h2 { font-size: 2rem; margin: 10px 0 5px; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.85rem; opacity: 0.9; }
        .stat-card.bg-primary-gradient { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-card.bg-success-gradient { background: linear-gradient(135deg, #84fab0, #8fd3f4); }
        .stat-card.bg-warning-gradient { background: linear-gradient(135deg, #f6d365, #fda085); }
        .stat-card.bg-danger-gradient { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }

        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        .modal-header {
            border-radius: 16px 16px 0 0;
        }
        .modal-footer {
            border-radius: 0 0 16px 16px;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 992px) { 
            .app-sidebar { width: 240px; } 
        }

        @media (max-width: 768px) {
            .app-wrapper { flex-direction: column; }
            .app-sidebar { 
                width: 100%; 
                height: auto; 
                max-height: 300px; 
                position: relative; 
            }
            .sidebar-content { max-height: 200px; }
            .app-navbar { 
                flex-wrap: wrap; 
                gap: 10px; 
                height: auto;
                padding: 10px 15px;
            }
            .app-content { padding: 15px; }
            .stat-card h2 { font-size: 1.5rem; }
            .user-dropdown .user-info { display: none; }
        }

        @media (max-width: 576px) {
            .app-navbar { padding: 10px 15px; }
            .user-dropdown .user-name { display: none; }
            .app-content { padding: 10px; }
            .navbar-actions { gap: 5px; }
        }

        @media print {
            .app-sidebar, .app-navbar, .no-print { display: none !important; }
            .app-main { overflow: visible !important; }
            .app-content { padding: 0 !important; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-header">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5>SIM Sekolah</h5>
                <small>Panel Guru</small>
            </div>

            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('guru.dashboard') }}"
                           class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    <li class="menu-section">NILAI & RAPORT</li>
                    <li>
                        <a href="{{ route('guru.nilai.index') }}"
                           class="menu-item {{ request()->routeIs('guru.nilai.index') || request()->routeIs('guru.nilai.input') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i> Input Nilai
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('guru.nilai.raport') }}"
                           class="menu-item {{ request()->routeIs('guru.nilai.raport') || request()->routeIs('guru.nilai.raport.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Raport Siswa
                        </a>
                    </li>

                    <li>
                        <a href="#" class="menu-item" id="menuCetakRaport">
                            <i class="fas fa-print"></i> Cetak Raport
                            <span class="menu-new-badge">Baru</span>
                        </a>
                    </li>

                    <li class="menu-section">ABSENSI SISWA</li>
                    <li>
                        <a href="{{ route('guru.absensi.index') }}"
                           class="menu-item {{ request()->routeIs('guru.absensi.index') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i> Input Absensi
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('guru.absensi.scan') }}"
                           class="menu-item {{ request()->routeIs('guru.absensi.scan') ? 'active' : '' }}">
                            <i class="fas fa-rss"></i> Scan RFID
                            <span class="menu-new-badge">NEW</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('guru.absensi.riwayat') }}"
                           class="menu-item {{ request()->routeIs('guru.absensi.riwayat') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Riwayat Absensi
                        </a>
                    </li>

                    <li class="menu-section">KOMUNIKASI</li>
                    <li>
                        <a href="{{ route('guru.komunikasi.index') }}"
                           class="menu-item {{ request()->routeIs('guru.komunikasi.*') ? 'active' : '' }}">
                            <i class="fas fa-comments"></i> Pesan
                            @php
                                $unreadCount = 0;
                                try {
                                    $unreadCount = App\Models\Pesan::where('penerima_id', Auth::id())
                                        ->where('penerima_type', 'guru')
                                        ->where('is_read', false)
                                        ->count();
                                } catch(\Exception $e) {}
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge-notif">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="menu-section">KALENDER</li>
                    <li>
                        {{-- ✅ DIPERBAIKI: gunakan route('guru.kalender.index') --}}
                        @php
                            $kalenderRoute = '#';
                            try {
                                $kalenderRoute = route('guru.kalender.index');
                            } catch(\Exception $e) {
                                try {
                                    $kalenderRoute = route('guru.kalender');
                                } catch(\Exception $e2) {
                                    $kalenderRoute = '#';
                                }
                            }
                        @endphp
                        <a href="{{ $kalenderRoute }}"
                           class="menu-item {{ request()->routeIs('guru.kalender.*') || request()->routeIs('guru.kalender') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Kalender Akademik
                        </a>
                    </li>

                    <li class="menu-section">KINERJA</li>
                    <li>
                        <a href="{{ route('guru.kinerja.index') }}"
                           class="menu-item {{ request()->routeIs('guru.kinerja.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Profil Kinerja
                        </a>
                    </li>

                    <li><hr></li>

                    <li>
                        <a href="#" class="menu-item logout" id="sidebarLogoutBtn">
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
            <!-- ============================================
                 NAVBAR (TOPBAR) - Breadcrumb & Search Dihilangkan
                 ============================================ -->
            <nav class="app-navbar">
                <!-- Left: Toggle Only -->
                <div class="navbar-left">
                    <button class="sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- Right: Actions -->
                <div class="navbar-actions">
                    <!-- Notifikasi -->
                    <div class="dropdown">
                        <button class="nav-action-btn" type="button" data-bs-toggle="dropdown" title="Notifikasi">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="badge-dot"></span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                            <li><h6 class="dropdown-header">Notifikasi</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center text-muted py-3">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                Belum ada notifikasi
                            </li>
                        </ul>
                    </div>

                    <!-- Pesan -->
                    <div class="dropdown">
                        <button class="nav-action-btn" type="button" data-bs-toggle="dropdown" title="Pesan">
                            <i class="fas fa-envelope"></i>
                            @if($unreadCount > 0)
                                <span class="badge-dot" style="background-color: #3498db;"></span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                            <li><h6 class="dropdown-header">Pesan Masuk</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center text-muted py-3">
                                <i class="fas fa-envelope-open fa-2x d-block mb-2"></i>
                                Tidak ada pesan baru
                            </li>
                        </ul>
                    </div>

                    <!-- User Profile -->
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                <span class="user-role">{{ Auth::user()->role ?? 'Guru' }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ Auth::user()->name ?? 'User' }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fas fa-user-tag me-1"></i>
                                        {{ Auth::user()->role ?? 'Guru' }}
                                    </span>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ route('guru.profil.index') }}">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    Profil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#" onclick="alert('Fitur pengaturan sedang dalam pengembangan');">
                                    <i class="fas fa-cog text-warning"></i>
                                    Pengaturan
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item text-danger" href="#" id="dropdownLogoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- CONTENT -->
            <div class="app-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- ============================================================
         MODAL CETAK RAPORT
         ============================================================ -->
    <div class="modal fade" id="pilihSiswaRaportModal" tabindex="-1" aria-labelledby="pilihSiswaRaportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">

                {{-- Header Gradient --}}
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; border-bottom:none; padding:1.25rem 1.5rem;">
                    <h5 class="modal-title" id="pilihSiswaRaportModalLabel" style="font-weight:700; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-print"></i>
                        Cetak Raport Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Form --}}
                <form method="GET" action="{{ route('guru.nilai.raport.cetak', ['siswaId' => '__siswa_id__']) }}" id="formCetakRaportModal" target="_blank">
                    @csrf

                    @php
                        $modalGuru = \App\Models\Guru::where('user_id', auth()->id())->first();

                        $modalKelas = collect();
                        if ($modalGuru) {
                            $modalKelas = \App\Models\Kelas::whereHas('jadwal', function($q) use ($modalGuru) {
                                $q->where('guru_id', $modalGuru->id);
                            })->withCount('siswa')->orderBy('nama_kelas')->get();

                            if ($modalKelas->isEmpty()) {
                                $modalKelas = \App\Models\Kelas::withCount('siswa')->orderBy('nama_kelas')->get();
                            }
                        }

                        $modalSiswa = \App\Models\Siswa::with(['kelas', 'user'])
                            ->where('status', 'aktif')
                            ->orderBy('nama_lengkap')
                            ->get();

                        $modalTahun = [];
                        for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++) {
                            $modalTahun[] = $i . '/' . ($i + 1);
                        }
                        $modalTahunNow = date('Y') . '/' . (date('Y') + 1);
                    @endphp

                    <div class="modal-body" style="padding:1.75rem;">

                        {{-- Pilih Kelas --}}
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:700; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#475569;">
                                <i class="fas fa-users text-primary me-1"></i>
                                Pilih Kelas <span class="text-danger">*</span>
                            </label>
                            <select name="kelas_id" id="modalKelasSelect" class="form-select" required
                                    style="border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; font-size:.875rem;">
                                <option value="">— Pilih Kelas —</option>
                                @forelse($modalKelas as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->nama_kelas ?? $k->nama }}
                                        @if($k->jurusan) — {{ $k->jurusan->nama }} @endif
                                        ({{ $k->siswa_count ?? 0 }} siswa)
                                    </option>
                                @empty
                                    <option value="" disabled>Anda belum mengajar kelas manapun</option>
                                @endforelse
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size:.78rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                Hanya menampilkan kelas yang Anda ajar
                            </small>
                        </div>

                        {{-- Pilih Siswa --}}
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:700; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#475569;">
                                <i class="fas fa-user-graduate text-primary me-1"></i>
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <select name="siswa_id" id="modalSiswaSelect" class="form-select" required
                                    style="border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; font-size:.875rem;"
                                    disabled>
                                <option value="">— Pilih Kelas Terlebih Dahulu —</option>
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size:.78rem;">
                                <i class="fas fa-sync-alt me-1"></i>
                                Siswa otomatis terfilter berdasarkan kelas yang dipilih
                            </small>
                        </div>

                        {{-- Tahun Ajaran & Semester --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:700; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#475569;">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                                    Tahun Ajaran <span class="text-danger">*</span>
                                </label>
                                <select name="tahun_ajaran" class="form-select" required
                                        style="border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; font-size:.875rem;">
                                    @foreach($modalTahun as $ta)
                                        <option value="{{ $ta }}" {{ $ta == $modalTahunNow ? 'selected' : '' }}>
                                            {{ $ta }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:700; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#475569;">
                                    <i class="fas fa-clock text-primary me-1"></i>
                                    Semester <span class="text-danger">*</span>
                                </label>
                                <select name="semester" class="form-select" required
                                        style="border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; font-size:.875rem;">
                                    <option value="ganjil">Semester Ganjil</option>
                                    <option value="genap">Semester Genap</option>
                                </select>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="alert alert-info mt-3 mb-0" style="border-radius:10px; border:none; background:#eff6ff; color:#1e40af; font-size:.85rem;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle mt-1"></i>
                                <div>
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0 mt-1 ps-3">
                                        <li>Raport akan terbuka di <strong>tab baru</strong> siap cetak</li>
                                        <li>Pastikan data nilai sudah lengkap dan <strong>dipublish</strong></li>
                                        <li>Hanya menampilkan siswa di kelas yang Anda ajar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer" style="border-top:1px solid #e2e8f0; padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                style="border-radius:10px; padding:8px 20px; font-weight:600;">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCetakRaportModal"
                                style="border-radius:10px; padding:8px 20px; font-weight:600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none;">
                            <i class="fas fa-print me-1"></i> Cetak Raport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Data Siswa untuk JS (JSON) --}}
    <script id="siswaDataScript" type="application/json">
        {!! json_encode($modalSiswa->map(function($s) {
            return [
                'id' => $s->id,
                'nis' => $s->nis ?? '-',
                'nama' => $s->nama_lengkap ?? ($s->user->name ?? '-'),
                'kelas_id' => $s->kelas_id,
                'kelas_nama' => $s->kelas->nama_kelas ?? ($s->kelas->nama ?? '-'),
            ];
        })->values()) !!}
    </script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            if ($('.datatable').length && !$.fn.DataTable.isDataTable('.datatable')) {
                $('.datatable').DataTable({
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                    pageLength: 10,
                    responsive: true
                });
            }

            // Initialize Select2
            if ($('.select2').length) {
                $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });
            }

            // ============================================
            // SIDEBAR TOGGLE
            // ============================================
            $('#sidebarToggle').on('click', function() {
                $('#appSidebar').toggleClass('collapsed');
                var isCollapsed = $('#appSidebar').hasClass('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Cek state sidebar saat halaman dimuat
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                $('#appSidebar').addClass('collapsed');
            }

            // ============================================
            // AMBIL DATA SISWA DARI JSON
            // ============================================
            var siswaData = [];
            try {
                var raw = document.getElementById('siswaDataScript');
                if (raw) {
                    siswaData = JSON.parse(raw.textContent);
                }
            } catch (e) {
                console.error('Gagal parse siswaData:', e);
            }

            console.log('Total siswa loaded:', siswaData.length);

            // ============================================
            // FILTER SISWA BERDASARKAN KELAS
            // ============================================
            var $kelasSelect = $('#modalKelasSelect');
            var $siswaSelect = $('#modalSiswaSelect');

            $kelasSelect.on('change', function() {
                var kelasId = $(this).val();

                $siswaSelect.html('<option value="">— Pilih Siswa —</option>');

                if (!kelasId) {
                    $siswaSelect.prop('disabled', true);
                    $siswaSelect.html('<option value="">— Pilih Kelas Terlebih Dahulu —</option>');
                    return;
                }

                var filtered = siswaData.filter(function(s) {
                    return String(s.kelas_id) === String(kelasId);
                });

                console.log('Kelas dipilih:', kelasId, '| Siswa ditemukan:', filtered.length);

                if (filtered.length === 0) {
                    $siswaSelect.html('<option value="">— Tidak ada siswa di kelas ini —</option>');
                    $siswaSelect.prop('disabled', true);
                    return;
                }

                filtered.forEach(function(s) {
                    var label = s.nis + ' — ' + s.nama + ' (' + s.kelas_nama + ')';
                    $siswaSelect.append(
                        $('<option>', {
                            value: s.id,
                            text: label
                        })
                    );
                });

                $siswaSelect.prop('disabled', false);
            });

            // ============================================
            // BUKA MODAL CETAK RAPORT
            // ============================================
            $('#menuCetakRaport').on('click', function(e) {
                e.preventDefault();

                $kelasSelect.val('');
                $siswaSelect.html('<option value="">— Pilih Kelas Terlebih Dahulu —</option>').prop('disabled', true);

                var modal = new bootstrap.Modal(document.getElementById('pilihSiswaRaportModal'));
                modal.show();
            });

            // ============================================
            // VALIDASI FORM SEBELUM SUBMIT
            // ============================================
            $('#formCetakRaportModal').on('submit', function(e) {
                var kelasId = $kelasSelect.val();
                var siswaId = $siswaSelect.val();

                if (!kelasId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih kelas terlebih dahulu!',
                        confirmButtonColor: '#667eea'
                    });
                    return false;
                }

                if (!siswaId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih siswa terlebih dahulu!',
                        confirmButtonColor: '#667eea'
                    });
                    return false;
                }

                var baseUrl = '{{ route("guru.nilai.raport.cetak", ["siswaId" => "__siswa_id__"]) }}';
                var finalUrl = baseUrl.replace('__siswa_id__', siswaId);
                finalUrl += '?tahun_ajaran=' + encodeURIComponent($('select[name="tahun_ajaran"]').val());
                finalUrl += '&semester=' + encodeURIComponent($('select[name="semester"]').val());

                $(this).attr('action', finalUrl);
                return true;
            });

            // Auto close alert
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // ============================================
        // LOGOUT
        // ============================================
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: 'Anda akan keluar dari sistem',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var sidebarLogoutBtn = document.getElementById('sidebarLogoutBtn');
            if (sidebarLogoutBtn) {
                sidebarLogoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    confirmLogout();
                });
            }

            var dropdownLogoutBtn = document.getElementById('dropdownLogoutBtn');
            if (dropdownLogoutBtn) {
                dropdownLogoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    confirmLogout();
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>