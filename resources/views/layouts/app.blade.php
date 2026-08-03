<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Sekolah')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* 🔥 TOMBOL TOGGLE SIDEBAR */
        .btn-toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
        }
        
        .btn-toggle-sidebar:hover {
            background: rgba(255,255,255,0.1);
        }
        
        /* 🔥 SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 20px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.open {
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
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-header h5 {
            margin: 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background: #34495e;
        }
        
        .sidebar-menu a.active {
            background: #27ae60;
        }
        
        .sidebar-menu a i {
            width: 24px;
            margin-right: 10px;
        }
        
        .sidebar-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            float: right;
            cursor: pointer;
        }
        
        .sidebar-close:hover {
            opacity: 0.7;
        }
        
        .main-content {
            transition: margin-left 0.3s ease;
        }
        
        .main-content.shifted {
            margin-left: 280px;
        }
        
        @media (max-width: 768px) {
            .main-content.shifted {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- 🔥 OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- 🔥 SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-close" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
            <h5><i class="fas fa-school me-2"></i>SIM Sekolah</h5>
            <small class="text-muted">Menu Utama</small>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Data Siswa</a></li>
            <li><a href="#"><i class="fas fa-chalkboard-teacher"></i> Data Guru</a></li>
            <li><a href="#"><i class="fas fa-school"></i> Data Kelas</a></li>
            <li><a href="#"><i class="fas fa-money-bill-wave"></i> Keuangan</a></li>
            <li><hr style="border-color: rgba(255,255,255,0.1);"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#ff6b6b; width:100%; text-align:left; padding:10px 15px;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
    
    <!-- 🔥 NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- 🔥 TOMBOL TOGGLE -->
            <button class="btn-toggle-sidebar" onclick="toggleSidebar()" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="#">
                <i class="fas fa-school me-2"></i>
                SISekolah
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            {{ auth()->user()->nama ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-id-card me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 main-content" id="mainContent">
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ================================================================
        // 🔥 FUNGSI TOGGLE SIDEBAR
        // ================================================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (!sidebar) return;
            
            const isMobile = window.innerWidth <= 768;
            const isOpen = sidebar.classList.contains('open');
            
            if (isOpen) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                if (!isMobile && mainContent) {
                    mainContent.classList.remove('shifted');
                }
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                if (!isMobile && mainContent) {
                    mainContent.classList.add('shifted');
                }
            }
        }

        // ================================================================
        // 🔥 TUTUP SIDEBAR KETIKA KLIK DI LUAR
        // ================================================================
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.btn-toggle-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (!sidebar || !sidebar.classList.contains('open')) return;
            
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = toggleBtn && toggleBtn.contains(event.target);
            const isClickOnOverlay = overlay && overlay.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickOnToggle && !isClickOnOverlay) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                const mainContent = document.getElementById('mainContent');
                if (mainContent) {
                    mainContent.classList.remove('shifted');
                }
            }
        });

        // ================================================================
        // 🔥 RESIZE WINDOW
        // ================================================================
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 768 && sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('shifted');
            }
        });

        // ================================================================
        // 🔥 DEBUG
        // ================================================================
        console.log('✅ Toggle Sidebar siap digunakan!');
        console.log('📌 Klik tombol ☰ di navbar untuk toggle sidebar.');
    </script>
    
    @stack('scripts')
</body>
</html>