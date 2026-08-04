{{-- resources/views/siswa/layouts/header.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Siswa') - SIM Sekolah</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ===== LAYOUT UTAMA ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            background: #f5f6fa;
        }
        
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(52, 152, 219, 0.3);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: #3498db;
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }
        
        .sidebar .nav-link .badge {
            float: right;
            margin-top: 2px;
        }
        
        .sidebar-brand {
            text-align: center;
            padding: 25px 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 30px;
            color: white;
            border: 3px solid rgba(255,255,255,0.2);
        }
        
        .sidebar-brand h5 {
            color: white;
            margin-bottom: 2px;
            font-weight: 600;
        }
        
        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
        }
        
        /* ===== KONTEN UTAMA ===== */
        .main-content {
            flex: 1;
            min-height: 100vh;
            padding: 0;
            background: #f5f6fa;
            overflow-x: hidden;
        }
        
        /* ===== NAVBAR ATAS ===== */
        .navbar-custom {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        
        .navbar-custom .page-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: #2c3e50;
            margin: 0;
        }
        
        .navbar-custom .page-title i {
            color: #3498db;
            margin-right: 10px;
        }
        
        /* ===== USER DROPDOWN ===== */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown .dropdown-toggle {
            background: transparent;
            border: none;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            background-color: #f8f9fa;
        }
        
        .user-dropdown .dropdown-toggle .avatar-small {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-dropdown .dropdown-toggle .user-name {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .user-dropdown .dropdown-toggle .chevron {
            font-size: 0.7rem;
            color: #999;
            transition: transform 0.3s ease;
        }
        
        .user-dropdown .dropdown-toggle[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            min-width: 220px;
            padding: 8px 0;
            margin-top: 8px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-dropdown .dropdown-item {
            padding: 10px 20px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            color: #333;
        }
        
        .user-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            color: #6c757d;
            text-align: center;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: #f0f4ff;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover {
            background-color: #fee;
            color: #dc3545;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover i {
            color: #dc3545;
        }
        
        .user-dropdown .dropdown-divider {
            margin: 5px 0;
            border-color: #f0f0f0;
        }
        
        /* ===== CONTENT AREA ===== */
        .content-area {
            padding: 25px;
        }
        
        /* ===== ALERT ===== */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-wrapper {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                min-height: auto;
                height: auto;
                position: relative;
                padding-bottom: 10px;
            }
            
            .sidebar .nav {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 2px;
            }
            
            .sidebar .nav-item {
                flex: 0 0 auto;
            }
            
            .sidebar .nav-link {
                padding: 8px 14px;
                font-size: 0.8rem;
                margin: 2px 4px;
            }
            
            .sidebar .nav-link i {
                margin-right: 5px;
            }
            
            .sidebar-brand {
                padding: 15px 0;
            }
            
            .sidebar-brand .avatar {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }
            
            .sidebar-brand h5 {
                font-size: 0.95rem;
            }
            
            .navbar-custom {
                padding: 12px 15px;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .navbar-custom .page-title {
                font-size: 1rem;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .user-dropdown .dropdown-toggle .user-name {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar .nav {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .sidebar .nav-link {
                font-size: 0.7rem;
                padding: 6px 10px;
                margin: 2px;
            }
            
            .sidebar .nav-link i {
                font-size: 0.9rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper">
    <!-- ===== SIDEBAR ===== -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <div class="avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h5>{{ Auth::user()->name ?? 'Siswa' }}</h5>
            <small>Siswa</small>
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" 
                   href="{{ route('siswa.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}" 
                   href="{{ route('siswa.nilai.index') }}">
                    <i class="fas fa-book"></i> Nilai & Raport
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}" 
                   href="{{ route('siswa.absensi.index') }}">
                    <i class="fas fa-calendar-check"></i> Absensi Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.pembayaran.*') ? 'active' : '' }}" 
                   href="{{ route('siswa.pembayaran.index') }}">
                    <i class="fas fa-credit-card"></i> Info Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.kalender.*') ? 'active' : '' }}" 
                   href="{{ route('siswa.kalender.index') }}">
                    <i class="fas fa-calendar-alt"></i> Kalender Akademik
                </a>
            </li>
        </ul>
    </nav>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
        <!-- Navbar Atas -->
        <div class="navbar-custom">
            <h5 class="page-title">
                <i class="fas fa-graduation-cap"></i> @yield('title', 'Dashboard Siswa')
            </h5>
            
            <!-- User Dropdown -->
            <div class="user-dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar-small">
                        {{ Str::substr(Auth::user()->name ?? 'S', 0, 1) }}
                    </span>
                    <span class="user-name">{{ Auth::user()->name ?? 'Siswa' }}</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('siswa.profil.index') }}">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('siswa.profil.edit') }}">
                            <i class="fas fa-cog"></i> Pengaturan
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight active menu
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '/siswa/dashboard') {
                link.classList.add('active');
            } else if (currentPath === '/siswa/dashboard' && href === '/siswa/dashboard') {
                link.classList.add('active');
            }
        });
        
        // Auto close alert after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>

@stack('scripts')
</body>
</html>