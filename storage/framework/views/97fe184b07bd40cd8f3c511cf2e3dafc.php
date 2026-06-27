


<?php $__env->startSection('title', 'Rekap Absensi Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2 text-primary"></i>
        Rekap Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('kepala-sekolah.manajemen-guru.index')); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Alert Error -->
<?php if(isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e($error); ?>

    </div>
<?php endif; ?>

<!-- Info Data -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Info:</strong><br>
    Total guru: <strong><?php echo e(isset($rekapData) ? count($rekapData) : 0); ?></strong><br>
    Bulan: <strong><?php echo e($bulanList[$bulan] ?? $bulan); ?></strong> - Tahun: <strong><?php echo e($tahun); ?></strong><br>
    Total absensi: <strong><?php echo e($statistik['total_absensi'] ?? 0); ?></strong>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e($bulan == $key ? 'selected' : ''); ?>>
                        <?php echo e($value); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e($tahun == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <a href="<?php echo e(route('kepala-sekolah.manajemen-guru.absensi')); ?>" class="btn btn-secondary w-100">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistik Cards -->
<?php if(isset($statistik) && $statistik['total_guru'] > 0): ?>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['total_guru']); ?></h4>
            <small>Total Guru</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['total_hadir']); ?></h4>
            <small>Hadir</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['total_sakit']); ?></h4>
            <small>Sakit</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['total_izin']); ?></h4>
            <small>Izin</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['total_alfa']); ?></h4>
            <small>Alfa</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white text-center p-3">
            <h4 class="mb-0"><?php echo e($statistik['rata_persentase']); ?>%</h4>
            <small>Rata-rata</small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Bulan <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?></h5>
    </div>
    <div class="card-body">
        <?php if(isset($rekapData) && count($rekapData) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-warning">Sakit</th>
                        <th class="text-center text-info">Izin</th>
                        <th class="text-center text-danger">Alfa</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rekapData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $persen = $item['persentase'];
                        $badgeColor = $persen >= 90 ? 'success' : ($persen >= 75 ? 'warning' : 'danger');
                    ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <strong><?php echo e($item['nama']); ?></strong>
                            <?php if($item['nuptk'] && $item['nuptk'] != '-'): ?>
                                <br>
                                <small class="text-muted">NUPTK: <?php echo e($item['nuptk']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo e($item['nip']); ?></td>
                        <td class="text-center fw-bold text-success"><?php echo e($item['hadir']); ?></td>
                        <td class="text-center"><?php echo e($item['sakit']); ?></td>
                        <td class="text-center"><?php echo e($item['izin']); ?></td>
                        <td class="text-center text-danger"><?php echo e($item['alfa']); ?></td>
                        <td class="text-center fw-bold"><?php echo e($item['total']); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo e($badgeColor); ?> px-3 py-2">
                                <?php echo e($persen); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <?php if(count($rekapData) > 0): ?>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-center">TOTAL</td>
                        <td class="text-center text-success"><?php echo e(array_sum(array_column($rekapData, 'hadir'))); ?></td>
                        <td class="text-center"><?php echo e(array_sum(array_column($rekapData, 'sakit'))); ?></td>
                        <td class="text-center"><?php echo e(array_sum(array_column($rekapData, 'izin'))); ?></td>
                        <td class="text-center text-danger"><?php echo e(array_sum(array_column($rekapData, 'alfa'))); ?></td>
                        <td class="text-center"><?php echo e(array_sum(array_column($rekapData, 'total'))); ?></td>
                        <td class="text-center"><?php echo e(count($rekapData) > 0 ? round(array_sum(array_column($rekapData, 'persentase')) / count($rekapData), 2) : 0); ?>%</td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Belum ada data absensi</h5>
            <p class="text-muted">Belum ada absensi untuk bulan <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?></p>
            <?php if(isset($error)): ?>
                <p class="text-danger"><?php echo e($error); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<script>
    $(document).ready(function() {
        <?php if(isset($rekapData) && count($rekapData) > 0): ?>
        $('#rekapTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/manajemen-guru/absensi.blade.php ENDPATH**/ ?>