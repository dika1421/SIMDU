


<?php $__env->startSection('title', 'Kehadiran Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                            <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e($bulan == $key ? 'selected' : ''); ?>>
                                    <?php echo e($nama); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($thn); ?>" <?php echo e($tahun == $thn ? 'selected' : ''); ?>>
                                    <?php echo e($thn); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Kehadiran -->
<div class="row g-4 mb-4">
    <div class="col-md-2">
        <div class="stat-card bg-success text-white text-center">
            <h3 class="mb-0"><?php echo e($statistik['hadir'] ?? 0); ?></h3>
            <small>Hadir</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-warning text-white text-center">
            <h3 class="mb-0"><?php echo e($statistik['sakit'] ?? 0); ?></h3>
            <small>Sakit</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-info text-white text-center">
            <h3 class="mb-0"><?php echo e($statistik['izin'] ?? 0); ?></h3>
            <small>Izin</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-danger text-white text-center">
            
            <h3 class="mb-0"><?php echo e($statistik['alpha'] ?? 0); ?></h3>
            <small>Alpha</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-secondary text-white text-center">
            <h3 class="mb-0"><?php echo e($statistik['terlambat'] ?? 0); ?></h3>
            <small>Terlambat</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-primary text-white text-center">
            <h3 class="mb-0"><?php echo e($statistik['persentase'] ?? 0); ?>%</h3>
            <small>Kehadiran</small>
        </div>
    </div>
</div>

<!-- Grafik Kehadiran Mingguan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Grafik Kehadiran per Minggu</h5>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Keterlambatan -->
<?php if(isset($terlambatList) && $terlambatList->count() > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Keterlambatan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Waktu Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $terlambatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($t['tanggal'] ?? '-'); ?></td>
                                <td><?php echo e($t['hari'] ?? '-'); ?></td>
                                <td><?php echo e($t['waktu'] ?? '-'); ?> WIB</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Absensi Harian -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Absensi Harian</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="absensiTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Status</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($a->tanggal_formatted ?? \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y')); ?></td>
                                <td><?php echo e($a->nama_hari ?? \Carbon\Carbon::parse($a->tanggal)->translatedFormat('l')); ?></td>
                                <td>
                                    <?php
                                        $badgeClass = match($a->status) {
                                            'hadir' => 'success',
                                            'sakit' => 'warning',
                                            'izin' => 'info',
                                            'alpha' => 'danger',
                                            'terlambat' => 'secondary',
                                            default => 'secondary'
                                        };
                                        $statusText = match($a->status) {
                                            'hadir' => 'Hadir',
                                            'sakit' => 'Sakit',
                                            'izin' => 'Izin',
                                            'alpha' => 'Alpha',
                                            'terlambat' => 'Terlambat',
                                            default => ucfirst($a->status)
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo e($badgeClass); ?>">
                                        <?php echo e($statusText); ?>

                                    </span>
                                </td>
                                <td><?php echo e($a->waktu_masuk_formatted ?? ($a->waktu_masuk ? \Carbon\Carbon::parse($a->waktu_masuk)->format('H:i') : '-')); ?></td>
                                <td><?php echo e($a->waktu_keluar_formatted ?? ($a->waktu_keluar ? \Carbon\Carbon::parse($a->waktu_keluar)->format('H:i') : '-')); ?></td>
                                <td><?php echo e($a->keterangan ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada data absensi untuk bulan ini</p>
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

<?php $__env->startPush('styles'); ?>
<style>
    .stat-card {
        padding: 15px;
        border-radius: 10px;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card h3 {
        font-size: 2rem;
        font-weight: bold;
    }
    .stat-card small {
        font-size: 0.8rem;
        opacity: 0.9;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Kehadiran Mingguan
        var ctx = document.getElementById('kehadiranChart');
        
        <?php if(isset($mingguan) && count($mingguan) > 0): ?>
            var mingguanData = <?php echo json_encode($mingguan, 15, 512) ?>;
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: mingguanData.map(item => item.minggu),
                    datasets: [
                        {
                            label: 'Hadir',
                            data: mingguanData.map(item => item.hadir),
                            backgroundColor: '#28a745',
                            borderRadius: 5
                        },
                        {
                            label: 'Sakit',
                            data: mingguanData.map(item => item.sakit),
                            backgroundColor: '#ffc107',
                            borderRadius: 5
                        },
                        {
                            label: 'Izin',
                            data: mingguanData.map(item => item.izin),
                            backgroundColor: '#17a2b8',
                            borderRadius: 5
                        },
                        {
                            label: 'Alpha',
                            data: mingguanData.map(item => item.alpha),
                            backgroundColor: '#dc3545',
                            borderRadius: 5
                        },
                        {
                            label: 'Terlambat',
                            data: mingguanData.map(item => item.terlambat),
                            backgroundColor: '#6c757d',
                            borderRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: { 
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Minggu'
                            }
                        },
                        y: { 
                            stacked: true, 
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Kehadiran'
                            }
                        }
                    }
                }
            });
        <?php else: ?>
            console.log('Data mingguan kosong');
        <?php endif; ?>
        
        // DataTable
        if ($('#absensiTable tbody tr').length > 0) {
            $('#absensiTable').DataTable({
                language: { 
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                order: [[0, 'desc']]
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('siswa.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/siswa/absensi/index.blade.php ENDPATH**/ ?>