
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Siswa'); ?> - SIM Sekolah</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            white-space: nowrap;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #3498db;
            color: white;
        }
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
        }
        .sidebar .nav-link .badge {
            float: right;
            margin-top: 2px;
        }
        .stat-card {
            border-radius: 15px;
            padding: 20px;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        /* User Dropdown Styles */
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
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .user-dropdown .dropdown-toggle:hover {
            background-color: #f8f9fa;
        }
        .user-dropdown .dropdown-toggle i {
            font-size: 1.2rem;
        }
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            min-width: 200px;
            padding: 8px 0;
        }
        .user-dropdown .dropdown-item {
            padding: 10px 20px;
            transition: background 0.2s;
        }
        .user-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            color: #6c757d;
        }
        .user-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .user-dropdown .dropdown-item.text-danger:hover {
            background-color: #fee;
        }
        .user-dropdown .dropdown-divider {
            margin: 5px 0;
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 25px;
        }
        .navbar-custom .navbar-brand {
            font-weight: 600;
            color: #2c3e50;
        }
        .navbar-custom .navbar-brand i {
            margin-right: 10px;
            color: #3498db;
        }
        .navbar-custom .nav-link {
            color: #6c757d;
        }
        
        /* Sidebar width */
        .sidebar {
            min-width: 260px;
        }
        
        /* Badge untuk notifikasi */
        .notification-badge {
            position: absolute;
            top: 8px;
            right: 15px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-width: 220px;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <div class="text-center py-4 border-bottom border-secondary">
                        <i class="fas fa-user-graduate fa-3x text-white"></i>
                        <h5 class="text-white mt-2"><?php echo e(Auth::user()->name ?? 'Siswa'); ?></h5>
                        <small class="text-white-50">Siswa</small>
                    </div>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('siswa.dashboard') ? 'active' : ''); ?>" 
                               href="<?php echo e(route('siswa.dashboard')); ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('siswa.nilai.*') ? 'active' : ''); ?>" 
                               href="<?php echo e(route('siswa.nilai.index')); ?>">
                                <i class="fas fa-book"></i> Nilai & Raport
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('siswa.absensi.*') ? 'active' : ''); ?>" 
                               href="<?php echo e(route('siswa.absensi.index')); ?>">
                                <i class="fas fa-calendar-check"></i> Absensi Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('siswa.pembayaran.*') ? 'active' : ''); ?>" 
                               href="<?php echo e(route('siswa.pembayaran.index')); ?>">
                                <i class="fas fa-credit-card"></i> Info Pembayaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('siswa.kalender.*') ? 'active' : ''); ?>" 
                               href="<?php echo e(route('siswa.kalender.index')); ?>">
                                <i class="fas fa-calendar-alt"></i> Kalender Akademik
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <!-- Navbar dengan User Dropdown -->
                <div class="navbar-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?php echo $__env->yieldContent('title'); ?></h4>
                    </div>
                    
                    <!-- User Dropdown (Profil, Pengaturan, Logout) -->
                    <div class="user-dropdown">
                        <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo e(Auth::user()->name ?? 'Siswa'); ?></span>
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('siswa.profil.index')); ?>">
                                    <i class="fas fa-user"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('siswa.profil.edit')); ?>">
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
                
                <!-- Alert Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(session('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show mt-3">
                        <i class="fas fa-info-circle me-2"></i> <?php echo e(session('info')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Script untuk active menu based on current route
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
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\php\SIMDU\resources\views/siswa/layouts/header.blade.php ENDPATH**/ ?>