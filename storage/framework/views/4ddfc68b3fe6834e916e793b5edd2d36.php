


<?php $__env->startSection('title', 'Dashboard Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .gradient-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .gradient-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .gradient-card-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .gradient-card-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .gradient-card-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .payment-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .payment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .progress-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        position: relative;
    }
    .table-modern th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        border: none;
    }
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    .btn-custom {
        border-radius: 10px;
        padding: 8px 20px;
        transition: all 0.3s;
    }
    .btn-custom:hover {
        transform: translateY(-2px);
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="h2 mb-1 fw-bold">
                    <i class="fas fa-credit-card me-2 text-primary"></i>
                    Dashboard Pembayaran
                </h1>
                <p class="text-muted">Kelola dan pantau status pembayaran Anda</p>
            </div>
            <div>
                <a href="<?php echo e(route('siswa.pembayaran.riwayat')); ?>" class="btn btn-outline-primary btn-custom me-2">
                    <i class="fas fa-history me-1"></i> Riwayat
                </a>
                <a href="<?php echo e(route('siswa.pembayaran.tagihan-tahunan')); ?>" class="btn btn-outline-info btn-custom">
                    <i class="fas fa-calendar-alt me-1"></i> Tagihan Tahunan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card gradient-card text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Tagihan</p>
                        <h3 class="stat-number">Rp <?php echo e(number_format($totalNominal ?? 0, 0, ',', '.')); ?></h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-receipt fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress bg-white bg-opacity-25" style="height: 8px;">
                        <div class="progress-bar bg-white" style="width: <?php echo e($persentase ?? 0); ?>%"></div>
                    </div>
                    <small class="mt-2 d-block opacity-75">Progress <?php echo e($persentase ?? 0); ?>%</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card gradient-card-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Sudah Dibayar</p>
                        <h3 class="stat-number">Rp <?php echo e(number_format($totalDibayar ?? 0, 0, ',', '.')); ?></h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="opacity-75">
                        <i class="fas fa-check me-1"></i> <?php echo e($statistikStatus['lunas'] ?? 0); ?> Tagihan Lunas
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card gradient-card-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Sisa Tagihan</p>
                        <h3 class="stat-number">Rp <?php echo e(number_format($totalSisa ?? 0, 0, ',', '.')); ?></h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="opacity-75">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?php echo e($statistikStatus['belum_lunas'] ?? 0); ?> Belum Lunas
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card gradient-card-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Transaksi</p>
                        <h3 class="stat-number"><?php echo e($pembayaran->count() ?? 0); ?></h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="opacity-75">
                        <i class="fas fa-calendar me-1"></i> Sepanjang Tahun Ini
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan per Jenis Pembayaran -->
<?php if(isset($byJenis) && count($byJenis) > 0): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card payment-card">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    Ringkasan per Jenis Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $byJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0"><?php echo e($jenis['label']); ?></h6>
                                <span class="badge bg-secondary rounded-pill"><?php echo e($jenis['count']); ?> tagihan</span>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Tagihan</small>
                                    <small class="fw-bold">Rp <?php echo e(number_format($jenis['nominal'], 0, ',', '.')); ?></small>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Terbayar</small>
                                    <small class="text-success fw-bold">Rp <?php echo e(number_format($jenis['dibayar'], 0, ',', '.')); ?></small>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Sisa</small>
                                    <small class="text-danger fw-bold">Rp <?php echo e(number_format($jenis['sisa'], 0, ',', '.')); ?></small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo e($jenis['persentase']); ?>%"></div>
                                </div>
                                <small class="text-muted mt-1 d-block"><?php echo e($jenis['persentase']); ?>% terlunasi</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Chart Section -->
<?php if(isset($chartData) && count($chartData['labels']) > 0): ?>
<div class="row mb-5">
    <div class="col-lg-8">
        <div class="card payment-card">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    Grafik Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <canvas id="paymentChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card payment-card">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Statistik Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="250"></canvas>
                <div class="mt-3 text-center">
                    <div class="row">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <h5 class="mb-0"><?php echo e($statistikStatus['lunas'] ?? 0); ?></h5>
                                <small class="text-muted">Lunas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <i class="fas fa-clock text-warning"></i>
                                <h5 class="mb-0"><?php echo e($statistikStatus['belum_lunas'] ?? 0); ?></h5>
                                <small class="text-muted">Belum Lunas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <i class="fas fa-hourglass-half text-info"></i>
                                <h5 class="mb-0"><?php echo e($statistikStatus['pending'] ?? 0); ?></h5>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Riwayat Pembayaran -->
<div class="card payment-card">
    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-table me-2 text-primary"></i>
            Riwayat Pembayaran Terbaru
        </h5>
        <div>
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari transaksi..." style="width: 250px;">
        </div>
    </div>
    <div class="card-body">
        <?php if(isset($pembayaran) && $pembayaran->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-modern table-hover" id="paymentTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No. Transaksi</th>
                        <th>Jenis Pembayaran</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-end">Dibayar</th>
                        <th class="text-end">Sisa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <span class="fw-bold"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')); ?></span>
                            <br>
                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')); ?></small>
                        </td>
                        <td>
                            <code class="small"><?php echo e($item->no_transaksi ?? '-'); ?></code>
                        </td>
                        <td>
                            <span class="fw-bold">
                                <?php
                                    $label = match($item->jenis_pembayaran) {
                                        'spp' => 'SPP',
                                        'uang_bangunan' => 'Uang Bangunan',
                                        'uang_kegiatan' => 'Uang Kegiatan',
                                        default => ucfirst(str_replace('_', ' ', $item->jenis_pembayaran ?? '-'))
                                    };
                                ?>
                                <?php echo e($label); ?>

                            </span>
                        </td>
                        <td class="text-end">Rp <?php echo e(number_format($item->nominal ?? 0, 0, ',', '.')); ?></td>
                        <td class="text-end text-success fw-bold">Rp <?php echo e(number_format($item->jumlah_dibayar ?? 0, 0, ',', '.')); ?></td>
                        <td class="text-end text-danger fw-bold">Rp <?php echo e(number_format($item->sisa ?? 0, 0, ',', '.')); ?></td>
                        <td>
                            <?php
                                $badgeClass = match($item->status) {
                                    'lunas' => 'success',
                                    'belum_lunas' => 'danger',
                                    'pending' => 'warning',
                                    default => 'secondary'
                                };
                                $badgeIcon = match($item->status) {
                                    'lunas' => 'check-circle',
                                    'belum_lunas' => 'times-circle',
                                    'pending' => 'hourglass-half',
                                    default => 'circle'
                                };
                            ?>
                            <span class="status-badge bg-<?php echo e($badgeClass); ?> bg-opacity-10 text-<?php echo e($badgeClass); ?> border border-<?php echo e($badgeClass); ?> border-opacity-25">
                                <i class="fas fa-<?php echo e($badgeIcon); ?> me-1"></i>
                                <?php echo e(ucfirst(str_replace('_', ' ', $item->status ?? '-'))); ?>

                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill" 
                                    data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($item->id); ?>">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            
                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal<?php echo e($item->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-receipt me-2"></i> Detail Pembayaran
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">No. Transaksi</small>
                                                    <p class="fw-bold"><?php echo e($item->no_transaksi ?? '-'); ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Tanggal</small>
                                                    <p class="fw-bold"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i')); ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Jenis Pembayaran</small>
                                                    <p class="fw-bold"><?php echo e($label); ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Metode</small>
                                                    <p class="fw-bold"><?php echo e(ucfirst($item->metode_pembayaran ?? '-')); ?></p>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <small class="text-muted">Keterangan</small>
                                                    <p><?php echo e($item->keterangan ?? '-'); ?></p>
                                                </div>
                                                <div class="col-12">
                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Tagihan:</span>
                                                        <span class="fw-bold">Rp <?php echo e(number_format($item->nominal, 0, ',', '.')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-success">
                                                        <span>Dibayar:</span>
                                                        <span class="fw-bold">Rp <?php echo e(number_format($item->jumlah_dibayar, 0, ',', '.')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-danger">
                                                        <span>Sisa:</span>
                                                        <span class="fw-bold">Rp <?php echo e(number_format($item->sisa, 0, ',', '.')); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <?php if($item->status == 'lunas'): ?>
                                            <a href="<?php echo e(route('siswa.pembayaran.cetak-struk', $item->id)); ?>" class="btn btn-primary" target="_blank">
                                                <i class="fas fa-print"></i> Cetak Struk
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-credit-card"></i>
            <h5 class="mt-3">Belum Ada Data Pembayaran</h5>
            <p class="text-muted">Silakan hubungi bagian administrasi untuk informasi pembayaran.</p>
            <div class="mt-3">
                <i class="fas fa-phone-alt me-2 text-primary"></i> Hubungi Admin
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Chart Pembayaran
    <?php if(isset($chartData) && count($chartData['labels']) > 0): ?>
    var ctx = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartData['labels']); ?>,
            datasets: [
                {
                    label: 'Tagihan',
                    data: <?php echo json_encode($chartData['nominal']); ?>,
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderRadius: 8
                },
                {
                    label: 'Dibayar',
                    data: <?php echo json_encode($chartData['dibayar']); ?>,
                    backgroundColor: 'rgba(56, 239, 125, 0.7)',
                    borderRadius: 8
                },
                {
                    label: 'Sisa',
                    data: <?php echo json_encode($chartData['sisa']); ?>,
                    backgroundColor: 'rgba(245, 87, 108, 0.7)',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + 
                                new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>
    
    // Chart Status
    <?php if(isset($statistikStatus)): ?>
    var ctx2 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Belum Lunas', 'Pending'],
            datasets: [{
                data: [
                    <?php echo e($statistikStatus['lunas'] ?? 0); ?>, 
                    <?php echo e($statistikStatus['belum_lunas'] ?? 0); ?>, 
                    <?php echo e($statistikStatus['pending'] ?? 0); ?>

                ],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    <?php endif; ?>
    
    // Search filter
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#paymentTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('siswa.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/siswa/pembayaran/index.blade.php ENDPATH**/ ?>