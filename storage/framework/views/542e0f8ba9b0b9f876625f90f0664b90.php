

<?php $__env->startSection('title', 'Dashboard Guru'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .progress-sm {
        height: 8px;
        border-radius: 4px;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 18px;
        height: 18px;
        background-color: #dc3545;
        border-radius: 50%;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .welcome-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .schedule-item {
        border-left: 3px solid;
        transition: all 0.3s ease;
    }
    
    .schedule-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .attendance-stat {
        position: relative;
        overflow: hidden;
    }
    
    .attendance-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.1);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .attendance-stat:hover::before {
        transform: translateX(0);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 mb-1">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Dashboard Guru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Selamat Datang, <?php echo e(Auth::user()->name ?? 'Guru'); ?>!
                        </h3>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?> | 
                            <i class="fas fa-clock ms-2 me-1"></i>
                            <?php echo e(\Carbon\Carbon::now()->format('H:i')); ?> WIB
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <?php if(isset($notifikasi) && $notifikasi > 0): ?>
                                    <span class="badge bg-danger ms-1"><?php echo e($notifikasi); ?></span>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-check-circle text-success me-2"></i> Nilai perlu diinput (3)
                                </a></li>
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-calendar-check text-warning me-2"></i> Absensi hari ini
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-primary" href="#">
                                    <i class="fas fa-eye me-2"></i> Lihat semua
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100" onclick="window.location.href='<?php echo e(route('guru.nilai.index')); ?>'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded p-3">
                        <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                    <h2 class="mb-0 fw-bold"><?php echo e($totalKelas ?? 0); ?></h2>
                </div>
                <h6 class="text-muted mb-1">Total Kelas</h6>
                <small class="text-success">
                    <i class="fas fa-arrow-up me-1"></i> <?php echo e($kelasBulanIni ?? 0); ?> bulan ini
                </small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100" onclick="window.location.href='<?php echo e(route('guru.absensi.index')); ?>'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                    <h2 class="mb-0 fw-bold"><?php echo e($totalSiswa ?? 0); ?></h2>
                </div>
                <h6 class="text-muted mb-1">Total Siswa</h6>
                <small class="text-info">
                    <i class="fas fa-users me-1"></i> <?php echo e($siswaPerKelas ?? 0); ?> per kelas rata-rata
                </small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100" onclick="window.location.href='<?php echo e(route('guru.nilai.index')); ?>'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <i class="fas fa-book fa-2x text-warning"></i>
                    </div>
                    <h2 class="mb-0 fw-bold"><?php echo e($totalMapel ?? 0); ?></h2>
                </div>
                <h6 class="text-muted mb-1">Mata Pelajaran</h6>
                <small class="text-success">
                    <i class="fas fa-check-circle me-1"></i> Aktif semester ini
                </small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 rounded p-3">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <h2 class="mb-0 fw-bold"><?php echo e(number_format($rataNilai ?? 0, 1)); ?></h2>
                </div>
                <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                <div class="progress progress-sm mt-2">
                    <?php
                        $persentase = ($rataNilai ?? 0) / 100 * 100;
                    ?>
                    <div class="progress-bar bg-info" style="width: <?php echo e($persentase); ?>%"></div>
                </div>
                <small class="text-<?php echo e(($rataNilai ?? 0) >= 75 ? 'success' : 'warning'); ?> mt-1 d-block">
                    <?php if(($rataNilai ?? 0) >= 75): ?>
                        <i class="fas fa-check-circle me-1"></i> Melebihi KKM
                    <?php else: ?>
                        <i class="fas fa-exclamation-triangle me-1"></i> Perlu peningkatan
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Jadwal Hari Ini -->
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    <strong>Jadwal Mengajar Hari Ini</strong>
                </div>
                <span class="badge bg-primary">
                    <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                </span>
            </div>
            <div class="card-body">
                <?php if(isset($jadwalHariIni) && count($jadwalHariIni) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item schedule-item px-0" style="border-left-color: <?php echo e($loop->index % 2 == 0 ? '#667eea' : '#764ba2'); ?>; border-left-width: 3px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            <?php echo e(\Carbon\Carbon::parse($j->jam_mulai)->format('H:i')); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($j->jam_selesai)->format('H:i')); ?>

                                        </h6>
                                        <p class="mb-0 fw-bold"><?php echo e($j->mapel->nama_mapel ?? '-'); ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i> <?php echo e($j->kelas->nama_kelas ?? '-'); ?>

                                            <i class="fas fa-door-open ms-2 me-1"></i> <?php echo e($j->ruangan ?? 'Ruang 1'); ?>

                                        </small>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('guru.absensi.index')); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-check-circle"></i> Absensi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada jadwal hari ini</h5>
                        <p class="text-muted small">Selamat beristirahat! 🎉</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Statistik Absensi & Kehadiran -->
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-chart-pie me-2 text-success"></i>
                <strong>Rekap Absensi Hari Ini</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="attendance-stat text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="mb-0 text-success fw-bold"><?php echo e($hadirHariIni ?? 0); ?></h3>
                            <small class="text-muted">
                                <i class="fas fa-user-check"></i> Hadir
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attendance-stat text-center p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="mb-0 text-warning fw-bold"><?php echo e($sakitHariIni ?? 0); ?></h3>
                            <small class="text-muted">
                                <i class="fas fa-thermometer-half"></i> Sakit
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attendance-stat text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="mb-0 text-info fw-bold"><?php echo e($izinHariIni ?? 0); ?></h3>
                            <small class="text-muted">
                                <i class="fas fa-file-alt"></i> Izin
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attendance-stat text-center p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="mb-0 text-danger fw-bold"><?php echo e($alfaHariIni ?? 0); ?></h3>
                            <small class="text-muted">
                                <i class="fas fa-times-circle"></i> Alfa
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small>Persentase Kehadiran</small>
                        <small class="fw-bold"><?php echo e($persentaseKehadiran ?? 0); ?>%</small>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: <?php echo e($persentaseKehadiran ?? 0); ?>%"></div>
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="<?php echo e(route('guru.absensi.index')); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus-circle"></i> Input Absensi
                    </a>
                    <a href="<?php echo e(route('guru.absensi.riwayat')); ?>" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik dan Informasi Tambahan -->
<div class="row">
    <!-- Grafik Nilai per Kelas -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-line me-2 text-info"></i>
                    <strong>Rata-rata Nilai per Kelas</strong>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chart-simple"></i> Semester Ini
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Semester Ganjil</a></li>
                        <li><a class="dropdown-item" href="#">Semester Genap</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Tahun Ajaran Ini</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <canvas id="nilaiChart" style="height: 320px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Informasi Cepat -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <strong>Informasi Cepat</strong>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-check-circle text-success me-2"></i>Nilai Terinput</span>
                            <span class="fw-bold"><?php echo e($nilaiTerinput ?? 0); ?> / <?php echo e($totalNilai ?? 0); ?></span>
                        </div>
                        <div class="progress progress-sm mt-2">
                            <?php
                                $progressNilai = ($nilaiTerinput ?? 0) / max(($totalNilai ?? 1), 1) * 100;
                            ?>
                            <div class="progress-bar bg-success" style="width: <?php echo e($progressNilai); ?>%"></div>
                        </div>
                    </div>
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-calendar-check text-warning me-2"></i>Hari Efektif</span>
                            <span class="fw-bold"><?php echo e($hariEfektif ?? 0); ?> / <?php echo e($totalHari ?? 30); ?></span>
                        </div>
                        <div class="progress progress-sm mt-2">
                            <?php
                                $progressHari = ($hariEfektif ?? 0) / max(($totalHari ?? 1), 1) * 100;
                            ?>
                            <div class="progress-bar bg-warning" style="width: <?php echo e($progressHari); ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="mb-3">
                    <i class="fas fa-star text-warning me-2"></i>Prestasi Terbaru
                </h6>
                <?php if(isset($prestasiTerbaru) && count($prestasiTerbaru) > 0): ?>
                    <?php $__currentLoopData = $prestasiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0">
                                <i class="fas fa-trophy text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-bold"><?php echo e($p->nama_siswa); ?></small>
                                <small class="text-muted d-block"><?php echo e($p->nama_prestasi); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted text-center small">Belum ada prestasi</p>
                <?php endif; ?>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('guru.nilai.input')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Input Nilai
                    </a>
                    <a href="<?php echo e(route('guru.komunikasi.create')); ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-envelope"></i> Kirim Pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('nilaiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels ?? ['Belum Ada Data']); ?>,
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: <?php echo json_encode($chartData ?? [0]); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Nilai: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Rata-rata Nilai',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Kelas',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('guru.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>