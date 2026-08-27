{{-- resources/views/siswa/layouts/header.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Siswa') - SIM Sekolah</title>
    
    <!-- ===== CSS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            background: #f0f2f5;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        /* ===== LAYOUT WRAPPER ===== */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1a2332 0%, #0f1724 100%);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        
        .sidebar-brand {
            text-align: center;
            padding: 25px 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        
        .sidebar-brand .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            color: white;
            border: 3px solid rgba(255,255,255,0.15);
            transition: all 0.3s ease;
        }
        
        .sidebar-brand .avatar:hover {
            transform: scale(1.05);
            border-color: #4facfe;
        }
        
        .sidebar-brand h5 {
            color: white;
            margin-bottom: 2px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .sidebar-brand small {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
        }
        
        /* ===== SIDEBAR NAV ===== */
        .sidebar .nav {
            padding: 10px 0;
        }
        
        .sidebar .nav-item {
            padding: 0 12px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 11px 16px;
            border-radius: 10px;
            margin: 3px 0;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(3px);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(79, 172, 254, 0.3);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 1rem;
        }
        
        .sidebar .nav-link .badge {
            margin-left: auto;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 0.7rem;
            padding: 2px 10px;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            min-height: 100vh;
            background: #f0f2f5;
            overflow-x: hidden;
        }
        
        /* ===== NAVBAR ATAS ===== */
        .navbar-custom {
            background: white;
            padding: 14px 28px;
            border-bottom: 1px solid #e9edf4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        
        .navbar-custom .page-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #1a2332;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-custom .page-title i {
            color: #4facfe;
            font-size: 1.3rem;
        }
        
        /* ===== USER DROPDOWN ===== */
        .user-dropdown .dropdown-toggle {
            background: transparent;
            border: none;
            color: #1a2332;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            background: #f0f2f5;
        }
        
        .user-dropdown .dropdown-toggle .avatar-small {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
            color: #8a94a6;
            transition: transform 0.3s ease;
        }
        
        .user-dropdown .dropdown-toggle[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.12);
            min-width: 220px;
            padding: 8px 0;
            margin-top: 10px;
            animation: slideDown 0.25s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-dropdown .dropdown-item {
            padding: 10px 22px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            color: #1a2332;
        }
        
        .user-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            color: #8a94a6;
            text-align: center;
        }
        
        .user-dropdown .dropdown-item:hover {
            background: #f0f7ff;
            color: #4facfe;
        }
        
        .user-dropdown .dropdown-item:hover i {
            color: #4facfe;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover {
            background: #fef0f0;
            color: #dc3545;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover i {
            color: #dc3545;
        }
        
        .user-dropdown .dropdown-divider {
            margin: 6px 0;
            border-color: #f0f2f5;
        }
        
        /* ===== CONTENT AREA ===== */
        .content-area {
            padding: 24px 28px;
        }
        
        /* ===== ALERT ===== */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 14px 20px;
        }
        
        .alert .btn-close {
            padding: 12px;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            
            .content-area {
                padding: 20px;
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
                padding: 5px 0;
            }
            
            .sidebar .nav-item {
                padding: 0 4px;
            }
            
            .sidebar .nav-link {
                padding: 8px 14px;
                font-size: 0.8rem;
                margin: 2px 0;
                border-radius: 8px;
            }
            
            .sidebar .nav-link i {
                margin-right: 6px;
                font-size: 0.9rem;
            }
            
            .sidebar .nav-link .badge {
                display: none;
            }
            
            .sidebar-brand {
                padding: 15px 0;
            }
            
            .sidebar-brand .avatar {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .sidebar-brand h5 {
                font-size: 0.9rem;
            }
            
            .navbar-custom {
                padding: 12px 16px;
                flex-wrap: wrap;
                gap: 8px;
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
            .sidebar .nav-link {
                font-size: 0.7rem;
                padding: 6px 10px;
            }
            
            .sidebar .nav-link i {
                font-size: 0.8rem;
            }
            
            .navbar-custom .page-title {
                font-size: 0.9rem;
            }
            
            .content-area {
                padding: 12px;
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
        <ul class="nav flex-column">
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

<!-- ===== LOGOUT FORM ===== -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- ===== SCRIPTS ===== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Highlight Active Menu =====
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href) {
                // Jika href sama dengan current path atau current path dimulai dengan href
                if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                    // Hapus active dari semua link
                    navLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            }
        });
        
        // ===== Auto Close Alert =====
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }, 5000);
        });
        
        // ===== Fix untuk dropdown di mobile =====
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.closest('.dropdown');
                if (dropdown) {
                    const menu = dropdown.querySelector('.dropdown-menu');
                    if (menu) {
                        const isOpen = menu.classList.contains('show');
                        // Tutup semua dropdown lain
                        document.querySelectorAll('.dropdown-menu.show').forEach(m => {
                            if (m !== menu) {
                                m.classList.remove('show');
                            }
                        });
                        if (isOpen) {
                            menu.classList.remove('show');
                        } else {
                            menu.classList.add('show');
                        }
                    }
                }
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>