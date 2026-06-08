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
            background-color: #3498db;
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
        
        /* MAIN CONTENT */
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        
        .app-navbar {
            padding: 15px 25px;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
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
        
        @media (max-width: 768px) {
            .app-wrapper {
                flex-direction: column;
            }
            
            .app-sidebar {
                width: 100%;
                height: auto;
                max-height: 300px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5>SIM Sekolah</h5>
                <small>Guru</small>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('guru.dashboard') }}" 
                           class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- NILAI & RAPORT -->
                    <li class="menu-section">NILAI & RAPORT</li>
                    <li>
                        <a href="{{ route('guru.nilai.index') }}" 
                           class="menu-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i> Input Nilai
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#pilihSiswaRaportModal">
                            <i class="fas fa-print"></i> Raport Siswa
                        </a>
                    </li>
                    
                    <!-- ABSENSI SISWA -->
                    <li class="menu-section">ABSENSI SISWA</li>
                    <li>
                        <a href="{{ route('guru.absensi.index') }}" 
                           class="menu-item {{ request()->routeIs('guru.absensi.index') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i> Input Absensi
                        </a>
                    </li>
                    
                    <!-- TAMBAHKAN MENU SCAN RFID -->
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
                    
                    <!-- KOMUNIKASI -->
                    <li class="menu-section">KOMUNIKASI</li>
                    <li>
                        <a href="{{ route('guru.komunikasi.index') }}" 
                           class="menu-item {{ request()->routeIs('guru.komunikasi.*') ? 'active' : '' }}">
                            <i class="fas fa-comments"></i> Pesan
                        </a>
                    </li>
                    
                    <!-- KALENDER -->
                    <li class="menu-section">KALENDER</li>
                    <li>
                        @php
                            $kalenderRoute = '';
                            try {
                                $kalenderRoute = route('guru.kalender');
                            } catch(\Exception $e) {
                                $kalenderRoute = url('/guru/kalender');
                            }
                        @endphp
                        <a href="{{ $kalenderRoute }}" 
                           class="menu-item {{ request()->routeIs('guru.kalender') || request()->routeIs('guru.kalender.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Kalender Akademik
                        </a>
                    </li>
                    
                    <!-- KINERJA -->
                    <li class="menu-section">KINERJA</li>
                    <li>
                        <a href="{{ route('guru.kinerja.index') }}" 
                           class="menu-item {{ request()->routeIs('guru.kinerja.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Profil Kinerja
                        </a>
                    </li>
                    
                    <li><hr></li>
                    
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
                <div>
                    <span class="h5 mb-0">Selamat Datang, {{ Auth::user()->name ?? 'User' }}</span>
                </div>
                
                <div class="navbar-actions">
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name ?? 'User' }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('guru.profil.index') }}">Profil</a></li>
                            <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
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

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Modal Pilih Siswa untuk Raport -->
    <div class="modal fade" id="pilihSiswaRaportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-print me-2"></i>
                        Cetak Raport Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('guru.nilai.raport') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                @php
                                    use App\Models\Siswa;
                                    $siswaList = Siswa::with('kelas', 'user')->where('status', 'aktif')->get();
                                @endphp
                                @foreach($siswaList as $s)
                                    <option value="{{ $s->id }}">
                                        {{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }} 
                                        ({{ $s->kelas->nama_kelas ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <select name="tahun_ajaran" class="form-select" required>
                                @php
                                    $tahunAjaranList = [];
                                    for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++) {
                                        $tahunAjaranList[] = $i . '/' . ($i + 1);
                                    }
                                @endphp
                                @foreach($tahunAjaranList as $ta)
                                    <option value="{{ $ta }}" {{ $ta == date('Y') . '/' . (date('Y') + 1) ? 'selected' : '' }}>
                                        {{ $ta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Raport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
            
            $('.select2').select2({
                width: '100%'
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const modalForm = document.querySelector('#pilihSiswaRaportModal form');
            if (modalForm) {
                modalForm.addEventListener('submit', function(e) {
                    const siswaSelect = document.querySelector('#pilihSiswaRaportModal select[name="siswa_id"]');
                    if (!siswaSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih siswa terlebih dahulu!');
                        siswaSelect.focus();
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>