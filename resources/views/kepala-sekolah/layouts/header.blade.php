<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kepala Sekolah') - SIM Sekolah</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            font-size: .875rem;
            background-color: #f8f9fa;
        }
        
        /* LAYOUT WRAPPER */
        .wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        
        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* HEADER SIDEBAR */
        .sidebar-header {
            padding: 30px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        
        .sidebar-header i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .sidebar-header h5 {
            margin: 10px 0 5px;
            color: white;
            font-weight: 600;
        }
        
        .sidebar-header small {
            color: #bdc3c7;
            font-size: 0.85rem;
        }
        
        /* KONTEN SIDEBAR - BISA SCROLL */
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 15px 15px 30px 15px;
        }
        
        /* Styling scrollbar */
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
        
        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: #95a5a6;
        }
        
        /* MENU SIDEBAR */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu .menu-section {
            color: #bdc3c7;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 10px 5px 10px;
        }
        
        .sidebar-menu .menu-item {
            display: block;
            padding: 12px 15px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .sidebar-menu .menu-item i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }
        
        .sidebar-menu .menu-item:hover {
            background-color: #34495e;
            transform: translateX(5px);
            color: white;
        }
        
        .sidebar-menu .menu-item.active {
            background-color: #3498db;
            color: white;
        }
        
        .sidebar-menu .badge {
            float: right;
            background-color: #dc3545;
            color: white;
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
        
        /* LOGOUT BUTTON */
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
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        
        /* NAVBAR */
        .navbar-top {
            padding: 15px 25px;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-notification {
            background: none;
            border: none;
            position: relative;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .user-dropdown:hover {
            background-color: #f8f9fa;
        }
        
        .user-dropdown i {
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        /* PAGE CONTENT - BISA SCROLL */
        .page-content {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
        }
        
        /* ALERTS */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
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
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        /* CARDS */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        /* STAT CARD */
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
        }
        
        .stat-card h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        
        .stat-card p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        /* TABLE */
        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.2rem;
            border-radius: 0.5rem;
        }
        
        /* FORM STYLES */
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            padding: 0.6rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background-color: #3498db;
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                max-height: 300px;
            }
            
            .sidebar-content {
                max-height: 250px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <!-- Header -->
            <div class="sidebar-header">
                <i class="fas fa-school"></i>
                <h5>SIM Sekolah</h5>
                <small>Kepala Sekolah</small>
            </div>
            
            <!-- Konten Sidebar -->
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('kepala-sekolah.dashboard') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN SEKOLAH -->
                    <li class="menu-section">MANAJEMEN SEKOLAH</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.struktur') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.struktur*') ? 'active' : '' }}">
                            <i class="fas fa-sitemap"></i>
                            Struktur Organisasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.jurusan') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.jurusan*') ? 'active' : '' }}">
                            <i class="fas fa-code-branch"></i>
                            Jurusan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen.tahun-ajaran') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen.tahun-ajaran*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            Tahun Ajaran
                        </a>
                    </li>
                    
                    <!-- MANAJEMEN GURU -->
                    <li class="menu-section">MANAJEMEN GURU</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen-guru.index') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            Data Guru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.manajemen-guru.absensi') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.manajemen-guru.absensi') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            Rekap Absensi
                        </a>
                    </li>
                    
                    <!-- PERSETUJUAN -->
                    <li class="menu-section">PERSETUJUAN</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.persetujuan.index') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.persetujuan.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            Daftar Pengajuan
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
                    
                    <!-- LAPORAN -->
                    <li class="menu-section">LAPORAN</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.absensi') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.absensi') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            Absensi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.kinerja-guru') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.kinerja-guru') ? 'active' : '' }}">
                            <i class="fas fa-star"></i>
                            Kinerja Guru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.laporan.statistik-siswa') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.laporan.statistik-siswa') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            Statistik Siswa
                        </a>
                    </li>
                    
                    <!-- ========== MENU PENGATURAN DIHAPUS DARI SIDEBAR ========== -->
                    <!-- 
                    <li class="menu-section">PENGATURAN</li>
                    <li>
                        <a href="{{ route('kepala-sekolah.profil.index') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.profil.*') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i>
                            Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepala-sekolah.pengaturan') }}" 
                           class="menu-item {{ request()->routeIs('kepala-sekolah.pengaturan') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            Pengaturan Sistem
                        </a>
                    </li>
                    -->
                    
                    <li><hr></li>
                    
                    <!-- LOGOUT -->
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <a href="#" class="menu-item logout" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </form>
                    </li>
                    
                    <!-- SPACER -->
                    <li style="height: 30px;"></li>
                </ul>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- Navbar -->
            <div class="navbar-top">
                <div>
                    <span class="h5 mb-0">Selamat Datang, {{ Auth::user()->name }}</span>
                </div>
                
                <div class="navbar-actions">
                    <div class="dropdown">
                        <button class="btn-notification" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge-notification">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Notifikasi 1</a></li>
                            <li><a class="dropdown-item" href="#">Notifikasi 2</a></li>
                            <li><a class="dropdown-item" href="#">Notifikasi 3</a></li>
                        </ul>
                    </div>
                    
                    <!-- USER DROPDOWN - MENU PROFIL & PENGATURAN ADA DI SINI -->
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Profil -->
                            <li>
                                <a class="dropdown-item" href="{{ route('kepala-sekolah.profil.index') }}">
                                    <i class="fas fa-user me-2"></i> Profil
                                </a>
                            </li>
                            <!-- Pengaturan Sistem -->
                            <li>
                                <a class="dropdown-item" href="{{ route('kepala-sekolah.pengaturan') }}">
                                    <i class="fas fa-cog me-2"></i> Pengaturan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Logout -->
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
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Content -->
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
        // Inisialisasi DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>