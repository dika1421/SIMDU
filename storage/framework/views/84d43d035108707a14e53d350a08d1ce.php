

<?php $__env->startSection('title', 'Dashboard Kepala Sekolah'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        opacity: 0.2;
        font-size: 4rem;
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
    
    .trend-up {
        color: #28a745;
        font-size: 0.8rem;
    }
    
    .trend-down {
        color: #dc3545;
        font-size: 0.8rem;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
    }
    
    .schedule-item {
        border-left: 3px solid;
        transition: all 0.3s ease;
        background: white;
    }
    
    .schedule-item:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .attendance-stat {
        text-align: center;
        padding: 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .attendance-stat:hover {
        transform: scale(1.05);
    }
    
    .list-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .list-item:hover {
        border-left-color: #667eea;
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 mb-1">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Dashboard Kepala Sekolah
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

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="fas fa-user-graduate me-2"></i>
                Selamat Datang, <?php echo e(Auth::user()->name ?? 'Kepala Sekolah'); ?>

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
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i> Persetujuan menunggu (<?php echo e($persetujuanMenunggu ?? 0); ?>)
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-calendar-check text-info me-2"></i> Kegiatan hari ini (<?php echo e($kegiatanHariIni ?? 0); ?>)
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

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-primary text-white h-100" onclick="window.location.href='<?php echo e(route('kepala-sekolah.laporan.statistik-siswa')); ?>'">
            <div class="card-body position-relative">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h6 class="mb-2 opacity-75">Total Siswa</h6>
                <h2 class="mb-2 fw-bold"><?php echo e(number_format($totalSiswa ?? 123)); ?></h2>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75">
                        <i class="fas fa-user-graduate me-1"></i> Aktif: <?php echo e($siswaAktif ?? 0); ?>

                    </small>
                    <small class="trend-up">
                        <i class="fas fa-arrow-up me-1"></i> <?php echo e($pertumbuhanSiswa ?? 0); ?>%
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-success text-white h-100" onclick="window.location.href='<?php echo e(route('kepala-sekolah.manajemen-guru.index')); ?>'">
            <div class="card-body position-relative">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <h6 class="mb-2 opacity-75">Total Guru</h6>
                <h2 class="mb-2 fw-bold"><?php echo e(number_format($totalGuru ?? 45)); ?></h2>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75">
                        <i class="fas fa-check-circle me-1"></i> Aktif: <?php echo e($guruAktif ?? 0); ?>

                    </small>
                    <small class="trend-up">
                        <i class="fas fa-arrow-up me-1"></i> <?php echo e($pertumbuhanGuru ?? 0); ?>%
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-warning text-white h-100" onclick="window.location.href='<?php echo e(route('kepala-sekolah.laporan.absensi')); ?>'">
            <div class="card-body position-relative">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h6 class="mb-2 opacity-75">Kehadiran</h6>
                <h2 class="mb-2 fw-bold"><?php echo e($kehadiranHariIni ?? 98); ?>%</h2>
                <div class="progress progress-sm mt-2">
                    <div class="progress-bar bg-white" style="width: <?php echo e($kehadiranHariIni ?? 98); ?>%"></div>
                </div>
                <small class="opacity-75 mt-2 d-block">
                    <i class="fas fa-clock me-1"></i> Hari ini: <?php echo e($hadirHariIni ?? 0); ?>/<?php echo e($totalKehadiran ?? 0); ?>

                </small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-danger text-white h-100" onclick="window.location.href='<?php echo e(route('kepala-sekolah.keuangan.laporan')); ?>'">
            <div class="card-body position-relative">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h6 class="mb-2 opacity-75">Keuangan</h6>
                <h3 class="mb-2 fw-bold">Rp <?php echo e(number_format($totalKeuangan ?? 50000000)); ?></h3>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75">
                        <i class="fas fa-wallet me-1"></i> Bulan ini
                    </small>
                    <small class="trend-up">
                        <i class="fas fa-arrow-up me-1"></i> <?php echo e($pertumbuhanKeuangan ?? 0); ?>%
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Grafik Statistik -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-line me-2 text-info"></i>
                    <strong>Statistik Perkembangan Sekolah</strong>
                </div>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary active" onclick="updateChart('siswa')">Siswa</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="updateChart('guru')">Guru</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="updateChart('keuangan')">Keuangan</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statistikChart" style="height: 320px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Aktivitas Terbaru -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-history me-2 text-primary"></i>
                <strong>Aktivitas Terbaru</strong>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $aktivitasTerbaru ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aktivitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item list-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-<?php echo e($aktivitas->icon ?? 'bell'); ?> text-<?php echo e($aktivitas->warna ?? 'primary'); ?> mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 small"><?php echo e($aktivitas->deskripsi); ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($aktivitas->created_at)->diffForHumans()); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik Kehadiran -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-chart-pie me-2 text-success"></i>
                <strong>Rekap Kehadiran Bulan Ini</strong>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" style="height: 280px;"></canvas>
                <div class="row mt-3 text-center">
                    <div class="col-3">
                        <div class="badge-status bg-success text-white">Hadir</div>
                        <h5 class="mt-2"><?php echo e($hadirBulanIni ?? 0); ?></h5>
                    </div>
                    <div class="col-3">
                        <div class="badge-status bg-warning text-white">Sakit</div>
                        <h5 class="mt-2"><?php echo e($sakitBulanIni ?? 0); ?></h5>
                    </div>
                    <div class="col-3">
                        <div class="badge-status bg-info text-white">Izin</div>
                        <h5 class="mt-2"><?php echo e($izinBulanIni ?? 0); ?></h5>
                    </div>
                    <div class="col-3">
                        <div class="badge-status bg-danger text-white">Alfa</div>
                        <h5 class="mt-2"><?php echo e($alfaBulanIni ?? 0); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Agenda Mendatang -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-calendar-alt me-2 text-warning"></i>
                <strong>Agenda Mendatang</strong>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $agendaMendatang ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="schedule-item p-3 mb-3" style="border-left-color: <?php echo e($agenda->warna ?? '#667eea'); ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-bold"><?php echo e($agenda->judul); ?></h6>
                                <p class="mb-1 small text-muted"><?php echo e($agenda->deskripsi); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> 
                                    <?php echo e(\Carbon\Carbon::parse($agenda->tanggal_mulai)->translatedFormat('d F Y')); ?>

                                    <?php if($agenda->waktu_mulai): ?>
                                        <i class="fas fa-clock ms-2 me-1"></i> 
                                        <?php echo e(\Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i')); ?> WIB
                                    <?php endif; ?>
                                </small>
                            </div>
                            <span class="badge bg-<?php echo e($agenda->warna_badge ?? 'primary'); ?>">
                                <?php echo e($agenda->jenis ?? 'Acara'); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada agenda mendatang</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Per Kelas -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <i class="fas fa-school me-2 text-primary"></i>
                <strong>Statistik Per Kelas</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="kelasTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Jumlah Siswa</th>
                                <th>Rata-rata Nilai</th>
                                <th>Kehadiran</th>
                                <th>Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $statistikKelas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td class="fw-bold"><?php echo e($kelas->nama_kelas); ?></td>
                                    <td><?php echo e($kelas->jurusan->nama ?? '-'); ?></td>
                                    <td><?php echo e($kelas->jumlah_siswa ?? 0); ?></td>
                                    <td>
                                        <span class="fw-bold <?php echo e(($kelas->rata_nilai ?? 0) >= 75 ? 'text-success' : 'text-warning'); ?>">
                                            <?php echo e(number_format($kelas->rata_nilai ?? 0, 1)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-sm flex-grow-1 me-2" style="width: 100px;">
                                                <div class="progress-bar bg-success" style="width: <?php echo e($kelas->persentase_kehadiran ?? 0); ?>%"></div>
                                            </div>
                                            <span><?php echo e(number_format($kelas->persentase_kehadiran ?? 0, 1)); ?>%</span>
                                        </div>
                                    </td>
                                    <td><?php echo e($kelas->waliKelas->nama_lengkap ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-database fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data kelas</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let currentChart;
    
    // Data untuk chart
    const chartData = {
        siswa: {
            labels: <?php echo json_encode($chartSiswaLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']); ?>,
            data: <?php echo json_encode($chartSiswaData ?? [100, 110, 115, 120, 118, 123]); ?>

        },
        guru: {
            labels: <?php echo json_encode($chartGuruLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']); ?>,
            data: <?php echo json_encode($chartGuruData ?? [40, 42, 43, 44, 44, 45]); ?>

        },
        keuangan: {
            labels: <?php echo json_encode($chartKeuanganLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']); ?>,
            data: <?php echo json_encode($chartKeuanganData ?? [30, 35, 42, 48, 55, 50]); ?>

        }
    };
    
    function updateChart(type) {
        const data = chartData[type];
        if (currentChart) {
            currentChart.data.labels = data.labels;
            currentChart.data.datasets[0].data = data.data;
            currentChart.update();
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Statistik
        const ctx = document.getElementById('statistikChart').getContext('2d');
        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.siswa.labels,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: chartData.siswa.data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Chart Kehadiran
        const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
        new Chart(kehadiranCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [
                        <?php echo e($hadirBulanIni ?? 0); ?>,
                        <?php echo e($sakitBulanIni ?? 0); ?>,
                        <?php echo e($izinBulanIni ?? 0); ?>,
                        <?php echo e($alfaBulanIni ?? 0); ?>

                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                }
            }
        });
        
        // Inisialisasi DataTable
        if (document.getElementById('kelasTable')) {
            $('#kelasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                ordering: true
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/dashboard.blade.php ENDPATH**/ ?>