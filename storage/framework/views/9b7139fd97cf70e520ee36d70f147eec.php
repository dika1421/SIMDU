<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Guru'); ?> - SIM Sekolah</title>
    
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
            position: sticky;
            top: 0;
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
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 2px;
        }
        
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
        
        .sidebar-menu .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1rem;
        }
        
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
        
        .sidebar-menu .menu-item.active i {
            color: white;
        }
        
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
        
        .menu-item.logout:hover {
            background-color: #c0392b;
            color: white;
        }
        
        hr {
            border-color: rgba(255,255,255,0.1);
            margin: 10px 0;
        }
        
        /* Badge untuk menu baru */
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
        
        /* MAIN CONTENT */
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        
        .app-navbar {
            padding: 12px 25px;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .app-navbar .brand-text {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .app-navbar .brand-text small {
            font-weight: 400;
            color: #7f8c8d;
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
            padding: 5px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        
        .user-dropdown:hover {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        
        .user-dropdown .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-dropdown .user-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: #2c3e50;
        }
        
        /* Dropdown Menu Styling */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            padding: 8px 0;
            min-width: 220px;
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
        
        .dropdown-menu .dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 0.95rem;
        }
        
        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f7ff;
        }
        
        .dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #fde8e8;
        }
        
        .dropdown-menu .dropdown-divider {
            margin: 6px 0;
        }
        
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
        }
        
        .app-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .app-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .app-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .app-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
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
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .alert .btn-close {
            padding: 10px;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
        }
        
        .card-body {
            padding: 20px;
        }
        
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
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Button Styles */
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 6px;
        }
        
        .btn-action i {
            font-size: 0.9rem;
        }
        
        /* Stat Card */
        .stat-card {
            border-radius: 12px;
            padding: 20px 25px;
            color: white;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
        
        .stat-card h2 {
            font-size: 2rem;
            margin: 10px 0 5px;
            font-weight: 700;
        }
        
        .stat-card .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .stat-card.bg-primary-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .stat-card.bg-success-gradient {
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
        }
        
        .stat-card.bg-warning-gradient {
            background: linear-gradient(135deg, #f6d365, #fda085);
        }
        
        .stat-card.bg-danger-gradient {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        }
        
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
        
        /* Responsive */
        @media (max-width: 992px) {
            .app-sidebar {
                width: 240px;
            }
        }
        
        @media (max-width: 768px) {
            .app-wrapper {
                flex-direction: column;
            }
            
            .app-sidebar {
                width: 100%;
                height: auto;
                max-height: 300px;
                position: relative;
            }
            
            .sidebar-content {
                max-height: 200px;
            }
            
            .app-navbar {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .app-content {
                padding: 15px;
            }
            
            .stat-card h2 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .app-navbar {
                padding: 10px 15px;
            }
            
            .user-dropdown .user-name {
                display: none;
            }
            
            .app-content {
                padding: 10px;
            }
        }
        
        /* Print Styles */
        @media print {
            .app-sidebar,
            .app-navbar,
            .no-print {
                display: none !important;
            }
            
            .app-main {
                overflow: visible !important;
            }
            
            .app-content {
                padding: 0 !important;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5>SIM Sekolah</h5>
                <small>Panel Guru</small>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li>
                        <a href="<?php echo e(route('guru.dashboard')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.dashboard') ? 'active' : ''); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- NILAI & RAPORT -->
                    <li class="menu-section">NILAI & RAPORT</li>
                    <li>
                        <a href="<?php echo e(route('guru.nilai.index')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.nilai.index') || request()->routeIs('guru.nilai.input') ? 'active' : ''); ?>">
                            <i class="fas fa-book-open"></i> Input Nilai
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo e(route('guru.nilai.raport')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.nilai.raport') || request()->routeIs('guru.nilai.raport.*') ? 'active' : ''); ?>">
                            <i class="fas fa-file-alt"></i> Raport Siswa
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="menu-item" id="menuCetakRaport">
                            <i class="fas fa-print"></i> Cetak Raport
                            <span class="menu-new-badge">Baru</span>
                        </a>
                    </li>
                    
                    <!-- ABSENSI SISWA -->
                    <li class="menu-section">ABSENSI SISWA</li>
                    <li>
                        <a href="<?php echo e(route('guru.absensi.index')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.absensi.index') ? 'active' : ''); ?>">
                            <i class="fas fa-calendar-check"></i> Input Absensi
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo e(route('guru.absensi.scan')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.absensi.scan') ? 'active' : ''); ?>">
                            <i class="fas fa-rss"></i> Scan RFID
                            <span class="menu-new-badge">NEW</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo e(route('guru.absensi.riwayat')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.absensi.riwayat') ? 'active' : ''); ?>">
                            <i class="fas fa-history"></i> Riwayat Absensi
                        </a>
                    </li>
                    
                    <!-- KOMUNIKASI -->
                    <li class="menu-section">KOMUNIKASI</li>
                    <li>
                        <a href="<?php echo e(route('guru.komunikasi.index')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.komunikasi.*') ? 'active' : ''); ?>">
                            <i class="fas fa-comments"></i> Pesan
                            <?php
                                $unreadCount = 0;
                                try {
                                    $unreadCount = App\Models\Pesan::where('penerima_id', Auth::id())
                                        ->where('penerima_type', 'guru')
                                        ->where('is_read', false)
                                        ->count();
                                } catch(\Exception $e) {}
                            ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="badge-notif"><?php echo e($unreadCount); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- KALENDER -->
                    <li class="menu-section">KALENDER</li>
                    <li>
                        <?php
                            $kalenderRoute = '';
                            try {
                                $kalenderRoute = route('guru.kalender');
                            } catch(\Exception $e) {
                                $kalenderRoute = '#';
                            }
                        ?>
                        <a href="<?php echo e($kalenderRoute); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.kalender') || request()->routeIs('guru.kalender.*') ? 'active' : ''); ?>">
                            <i class="fas fa-calendar-alt"></i> Kalender Akademik
                        </a>
                    </li>
                    
                    <!-- KINERJA -->
                    <li class="menu-section">KINERJA</li>
                    <li>
                        <a href="<?php echo e(route('guru.kinerja.index')); ?>" 
                           class="menu-item <?php echo e(request()->routeIs('guru.kinerja.*') ? 'active' : ''); ?>">
                            <i class="fas fa-chart-line"></i> Profil Kinerja
                        </a>
                    </li>
                    
                    <li><hr></li>
                    
                    <!-- LOGOUT DI SIDEBAR - PERBAIKAN -->
                    <li>
                        <a href="#" class="menu-item logout" id="sidebarLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="app-main">
            <nav class="app-navbar">
                <div>
                    <span class="brand-text">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                        SIM Sekolah
                        <small>| Guru</small>
                    </span>
                </div>
                
                <div class="navbar-actions">
                    <!-- Notifikasi -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm position-relative" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?php if($unreadCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    <?php echo e($unreadCount); ?>

                                </span>
                            <?php endif; ?>
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
                    
                    <!-- User Dropdown - PERBAIKAN LOGOUT -->
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar">
                                <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1))); ?>

                            </div>
                            <span class="user-name"><?php echo e(Auth::user()->name ?? 'User'); ?></span>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Header dengan nama lengkap dan role -->
                            <li class="dropdown-header">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold"><?php echo e(Auth::user()->name ?? 'User'); ?></span>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fas fa-user-tag me-1"></i>
                                        <?php echo e(Auth::user()->role ?? 'Guru'); ?>

                                    </span>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Profil -->
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('guru.profil.index')); ?>">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    Profil
                                </a>
                            </li>
                            
                            <!-- Pengaturan -->
                            <li>
                                <a class="dropdown-item" href="#" onclick="alert('Fitur pengaturan sedang dalam pengembangan');">
                                    <i class="fas fa-cog text-warning"></i>
                                    Pengaturan
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Logout - PERBAIKAN -->
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
            
            <div class="app-content">
                <!-- Alert Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo e(session('info')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <!-- Modal Pilih Siswa untuk Cetak Raport -->
    <div class="modal fade" id="pilihSiswaRaportModal" tabindex="-1" aria-labelledby="pilihSiswaRaportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="pilihSiswaRaportModalLabel">
                        <i class="fas fa-print me-2"></i>
                        Cetak Raport Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="<?php echo e(route('guru.nilai.raport')); ?>" id="formCetakRaportModal">
                    <div class="modal-body">
                        <!-- Pilih Kelas - Hanya kelas yang diajar guru -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-users text-primary me-1"></i>
                                Pilih Kelas <span class="text-danger">*</span>
                            </label>
                            <select name="kelas_id" id="modalKelasSelect" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php
                                    use App\Models\Kelas;
                                    use App\Models\Guru;
                                    use App\Models\Jadwal;
                                    
                                    $user = auth()->user();
                                    $guru = Guru::where('user_id', $user->id)->first();
                                    
                                    if ($guru) {
                                        // Ambil kelas yang diajar oleh guru ini
                                        $kelasDiAjar = Kelas::whereHas('jadwal', function($query) use ($guru) {
                                            $query->where('guru_id', $guru->id);
                                        })->withCount('siswa')->get();
                                    } else {
                                        $kelasDiAjar = collect();
                                    }
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $kelasDiAjar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($k->id); ?>" 
                                        <?php echo e(isset($selectedKelasId) && $selectedKelasId == $k->id ? 'selected' : ''); ?>>
                                        <?php echo e($k->nama_kelas ?? $k->nama); ?> 
                                        <?php if($k->jurusan): ?>
                                            - <?php echo e($k->jurusan->nama); ?>

                                        <?php endif; ?>
                                        <span class="text-muted">(<?php echo e($k->siswa_count ?? 0); ?> siswa)</span>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="" disabled>Anda belum mengajar kelas manapun</option>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan kelas yang Anda ajar
                            </small>
                        </div>
                        
                        <!-- Pilih Siswa - Filter berdasarkan kelas yang dipilih -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-graduate text-primary me-1"></i>
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <select name="siswa_id" id="modalSiswaSelect" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php
                                    // Ambil semua siswa aktif
                                    $allSiswa = App\Models\Siswa::with(['kelas', 'user'])
                                        ->where('status', 'aktif')
                                        ->orderBy('nama_lengkap')
                                        ->get();
                                ?>
                                <?php $__currentLoopData = $allSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>" 
                                            data-kelas="<?php echo e($s->kelas_id); ?>"
                                            data-nama="<?php echo e($s->nama_lengkap ?? $s->user->name ?? '-'); ?>"
                                            data-nis="<?php echo e($s->nis ?? '-'); ?>">
                                        <?php echo e($s->nis ?? ''); ?> - <?php echo e($s->nama_lengkap ?? $s->user->name ?? '-'); ?> 
                                        (<?php echo e($s->kelas->nama_kelas ?? '-'); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Siswa akan otomatis terfilter berdasarkan kelas yang dipilih
                            </small>
                        </div>
                        
                        <!-- Tahun Ajaran -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tahun Ajaran <span class="text-danger">*</span>
                            </label>
                            <select name="tahun_ajaran" class="form-select" required>
                                <?php
                                    $tahunAjaranList = [];
                                    for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++) {
                                        $tahunAjaranList[] = $i . '/' . ($i + 1);
                                    }
                                    $tahunSekarang = date('Y') . '/' . (date('Y') + 1);
                                ?>
                                <?php $__currentLoopData = $tahunAjaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ta); ?>" <?php echo e($ta == $tahunSekarang ? 'selected' : ''); ?>>
                                        <?php echo e($ta); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <!-- Semester -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock text-primary me-1"></i>
                                Semester <span class="text-danger">*</span>
                            </label>
                            <select name="semester" class="form-select" required>
                                <option value="ganjil" <?php echo e(request('semester', 'ganjil') == 'ganjil' ? 'selected' : ''); ?>>
                                    Semester Ganjil
                                </option>
                                <option value="genap" <?php echo e(request('semester', 'ganjil') == 'genap' ? 'selected' : ''); ?>>
                                    Semester Genap
                                </option>
                            </select>
                        </div>
                        
                        <!-- Informasi Tambahan -->
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Raport akan ditampilkan dalam format baru dan siap dicetak</li>
                                        <li>Pastikan data nilai siswa sudah lengkap dan dipublish</li>
                                        <li>Hanya menampilkan siswa di kelas yang Anda ajar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCetakRaportModal">
                            <i class="fas fa-print me-1"></i> Cetak Raport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                responsive: true
            });
            
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5'
            });
            
            // ============================================================
            // FILTER SISWA BERDASARKAN KELAS DI MODAL
            // ============================================================
            
            // Data siswa dari server
            var siswaData = <?php echo json_encode($allSiswa ?? [], 15, 512) ?>;
            
            // Fungsi untuk filter siswa
            function filterSiswaByKelas(kelasId) {
                var siswaSelect = $('#modalSiswaSelect');
                
                // Reset siswa select
                siswaSelect.html('<option value="">-- Pilih Siswa --</option>');
                
                if (kelasId) {
                    // Filter siswa berdasarkan kelas
                    siswaData.forEach(function(siswa) {
                        if (siswa.kelas_id == kelasId) {
                            var optionText = (siswa.nis || '') + ' - ' + 
                                           (siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-')) + 
                                           ' (' + (siswa.kelas ? siswa.kelas.nama_kelas : '-') + ')';
                            
                            siswaSelect.append(
                                '<option value="' + siswa.id + '" ' +
                                'data-kelas="' + siswa.kelas_id + '" ' +
                                'data-nama="' + (siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-')) + '" ' +
                                'data-nis="' + (siswa.nis || '-') + '">' +
                                optionText +
                                '</option>'
                            );
                        }
                    });
                } else {
                    // Tampilkan semua siswa jika tidak ada kelas yang dipilih
                    siswaData.forEach(function(siswa) {
                        var optionText = (siswa.nis || '') + ' - ' + 
                                       (siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-')) + 
                                       ' (' + (siswa.kelas ? siswa.kelas.nama_kelas : '-') + ')';
                        
                        siswaSelect.append(
                            '<option value="' + siswa.id + '" ' +
                            'data-kelas="' + siswa.kelas_id + '" ' +
                            'data-nama="' + (siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-')) + '" ' +
                            'data-nis="' + (siswa.nis || '-') + '">' +
                            optionText +
                            '</option>'
                        );
                    });
                }
            }
            
            // Event change pada select kelas
            $('#modalKelasSelect').on('change', function() {
                var kelasId = $(this).val();
                filterSiswaByKelas(kelasId);
            });
            
            // Trigger change untuk menampilkan siswa awal jika kelas sudah dipilih
            if ($('#modalKelasSelect').val()) {
                $('#modalKelasSelect').trigger('change');
            }
            
            // ============================================================
            // VALIDASI FORM SEBELUM SUBMIT
            // ============================================================
            
            $('#formCetakRaportModal').on('submit', function(e) {
                var kelasId = $('#modalKelasSelect').val();
                var siswaId = $('#modalSiswaSelect').val();
                
                if (!kelasId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih kelas terlebih dahulu!',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#modalKelasSelect').focus();
                    return false;
                }
                
                if (!siswaId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih siswa terlebih dahulu!',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#modalSiswaSelect').focus();
                    return false;
                }
            });
            
            // ============================================================
            // MENU CETAK RAPORT - BUKA MODAL
            // ============================================================
            
            $('#menuCetakRaport').on('click', function(e) {
                e.preventDefault();
                var modal = new bootstrap.Modal(document.getElementById('pilihSiswaRaportModal'));
                modal.show();
            });
            
            // ============================================================
            // AUTO CLOSE ALERT
            // ============================================================
            
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // ============================================================
            // TOOLTIP
            // ============================================================
            
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
        
        // ============================================================
        // FUNGSI LOGOUT DENGAN KONFIRMASI - PERBAIKAN
        // ============================================================
        
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

        // Event listener untuk logout dari sidebar
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar logout button
            var sidebarLogoutBtn = document.getElementById('sidebarLogoutBtn');
            if (sidebarLogoutBtn) {
                sidebarLogoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    confirmLogout();
                });
            }

            // Dropdown logout button
            var dropdownLogoutBtn = document.getElementById('dropdownLogoutBtn');
            if (dropdownLogoutBtn) {
                dropdownLogoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    confirmLogout();
                });
            }
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\php\SIMDU\resources\views/guru/layouts/header.blade.php ENDPATH**/ ?>