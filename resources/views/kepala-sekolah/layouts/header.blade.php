<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIM Sekolah</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ========== RESET ========== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* ========== WRAPPER ========== */
        .wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        
        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            overflow: hidden;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
        }
        
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: 16px 14px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        
        .sidebar-header i {
            font-size: 2.2rem;
            color: #fff;
            display: block;
            margin-bottom: 4px;
        }
        
        .sidebar-header h5 {
            color: white;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }
        
        .sidebar-header small {
            color: #bdc3c7;
            font-size: 0.65rem;
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 10px 20px 10px;
        }
        
        .sidebar-content::-webkit-scrollbar { width: 3px; }
        .sidebar-content::-webkit-scrollbar-track { background: #34495e; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #7f8c8d; border-radius: 3px; }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li { margin-bottom: 2px; }
        
        .sidebar-menu .menu-section {
            color: #bdc3c7;
            font-size: 0.55rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 8px 4px 8px;
        }
        
        .sidebar-menu .menu-item {
            display: block;
            padding: 7px 12px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.78rem;
        }
        
        .sidebar-menu .menu-item i {
            width: 20px;
            margin-right: 8px;
            text-align: center;
            font-size: 0.8rem;
        }
        
        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }
        
        .sidebar-menu .menu-item.active {
            background: #3498db;
            color: white;
        }
        
        .sidebar-menu .badge {
            float: right;
            background: #dc3545;
            color: white;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 0.55rem;
        }
        
        .menu-item.logout {
            margin-top: 10px;
            color: #ff6b6b;
        }
        .menu-item.logout:hover { background: #c0392b; color: white; }
        
        hr { border-color: rgba(255,255,255,0.06); margin: 8px 0; }
        
        /* ========== BACKDROP ========== */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
        }
        .sidebar-backdrop.show { display: block; }
        
        /* ========== MAIN CONTENT ========== */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8f9fa;
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* ========== NAVBAR ========== */
        .navbar-top {
            padding: 10px 18px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #2c3e50;
            cursor: pointer;
            padding: 4px 6px;
        }
        
        .navbar-left .greeting {
            font-size: 0.85rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        
        .btn-notification {
            background: none;
            border: none;
            position: relative;
            font-size: 1rem;
            cursor: pointer;
            color: #6c757d;
            padding: 4px 6px;
        }
        
        .badge-notification {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 14px;
            height: 14px;
            font-size: 0.45rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            color: #2c3e50;
            transition: background 0.2s;
        }
        .user-dropdown:hover { background: #f8f9fa; }
        .user-dropdown i { font-size: 1.1rem; color: #6c757d; }
        .user-dropdown .user-name { display: inline; }
        
        /* ========== PAGE CONTENT ========== */
        .page-content {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar { width: 240px; }
            .main-content { margin-left: 240px; }
            .navbar-left .greeting { max-width: 140px; }
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 260px; }
            .sidebar.hidden { transform: translateX(-100%); }
            .sidebar:not(.hidden) { transform: translateX(0); }
            
            .main-content { margin-left: 0 !important; }
            .sidebar-toggle { display: block; }
            
            .navbar-top { padding: 8px 12px; }
            .navbar-left .greeting { font-size: 0.7rem; max-width: 100px; }
            .user-dropdown .user-name { display: none; }
            
            .page-content { padding: 10px; }
            
            .sidebar-header i { font-size: 1.6rem; }
            .sidebar-header h5 { font-size: 0.85rem; }
            .sidebar-menu .menu-item { font-size: 0.72rem; padding: 6px 10px; }
            .sidebar-menu .menu-section { font-size: 0.5rem; padding: 10px 8px 2px 8px; }
        }
        
        @media (max-width: 480px) {
            .navbar-top { padding: 6px 10px; }
            .navbar-left .greeting { font-size: 0.6rem; max-width: 70px; }
            .page-content { padding: 6px; }
            .sidebar { width: 240px; }
            
            .card { margin-bottom: 8px; }
            .card-header { padding: 8px 12px; font-size: 0.75rem; }
            .card-body { padding: 8px 12px; }
        }
        
        /* ========== ALERT ========== */
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: none;
            font-size: 0.8rem;
        }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-danger { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .alert-warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        
        /* ========== CARD ========== */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
            margin-bottom: 12px;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 10px 14px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .card-body { padding: 12px 14px; }
        
        /* ========== TABLE ========== */
        .table thead th {
            border-top: none;
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.7rem;
        }
        .table td { font-size: 0.78rem; vertical-align: middle; }
        
        /* ========== FORM ========== */
        .form-label { font-weight: 600; font-size: 0.8rem; }
        .form-control, .form-select {
            border-radius: 6px;
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }
        .btn-primary {
            background: #3498db;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
        }
        .btn-primary:hover { background: #2980b9; }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- SIDEBAR BACKDROP -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
        
        <!-- SIDEBAR -->
        <aside class="sidebar hidden" id="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-school"></i>
                <h5>SIM Sekolah</h5>
                <small>Kepala Sekolah</small>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('kepala-sekolah.dashboard') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <li class="menu-section">MANAJEMEN SEKOLAH</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.struktur') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.struktur*') ? 'active' : '' }}">
                            <i class="fas fa-sitemap"></i> Struktur Organisasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.jurusan') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.jurusan*') ? 'active' : '' }}">
                            <i class="fas fa-code-branch"></i> Jurusan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.tahun-ajaran') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.tahun-ajaran*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                        </a>
                    </li>
                    
                    <li class="menu-section">MANAJEMEN GURU</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen-guru.index') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i> Data Guru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen-guru.absensi') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen-guru.absensi') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> Rekap Absensi
                        </a>
                    </li>
                    
                    <li class="menu-section">PERSETUJUAN</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.persetujuan.index') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.persetujuan.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i> Daftar Pengajuan
                            @php
                                try {
                                    if (class_exists('App\\Models\\Pengajuan')) {
                                        $menunggu = \App\Models\Pengajuan::where('status', 'menunggu')->count();
                                    } else {
                                        $menunggu = 0;
                                    }
                                } catch (\Exception $e) {
                                    $menunggu = 0;
                                }
                            @endphp
                            @if(isset($menunggu) && $menunggu > 0)
                                <span class="badge">{{ $menunggu }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="menu-section">LAPORAN</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.absensi') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.absensi') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Absensi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.kinerja-guru') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.kinerja-guru') ? 'active' : '' }}">
                            <i class="fas fa-star"></i> Kinerja Guru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.statistik-siswa') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.statistik-siswa') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Statistik Siswa
                        </a>
                    </li>
                    
                    <li><hr></li>
                    
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <a href="#" class="menu-item logout" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </form>
                    </li>
                    
                    <li style="height: 20px;"></li>
                </ul>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="main-content" id="mainContent">
            <!-- Navbar -->
            <div class="navbar-top">
                <div class="navbar-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="greeting">👋 Selamat Datang, {{ Auth::user()->name ?? 'User' }}</span>
                </div>
                
                <div class="navbar-actions">
                    <div class="dropdown">
                        <button class="btn-notification" data-bs-toggle="dropdown" aria-label="Notifikasi">
                            <i class="fas fa-bell"></i>
                            <span class="badge-notification">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8rem; min-width: 180px;">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>Notifikasi 1</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>Notifikasi 2</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>Notifikasi 3</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-primary" href="#">Lihat semua</a></li>
                        </ul>
                    </div>
                    
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem; color: #aaa;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8rem; min-width: 170px;">
                            <li>
                                <a class="dropdown-item" href="{{ route('kepala-sekolah.profil.index') }}">
                                    <i class="fas fa-user me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('kepala-sekolah.pengaturan') }}">
                                    <i class="fas fa-cog me-2"></i> Pengaturan
                                </a>
                            </li>
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
            
            <!-- Page Content -->
            <div class="page-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('hidden');
            backdrop.classList.toggle('show');
        }
        
        // Tutup sidebar jika klik di luar
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            
            if (!sidebar.classList.contains('hidden') && 
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target)) {
                toggleSidebar();
            }
        });
        
        // Tutup sidebar dengan tombol ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebarBackdrop');
                if (!sidebar.classList.contains('hidden')) {
                    toggleSidebar();
                }
            }
        });
        
        // Inisialisasi DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                responsive: true,
                autoWidth: false
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>