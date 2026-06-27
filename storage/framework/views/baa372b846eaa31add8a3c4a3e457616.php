

<?php $__env->startSection('title', 'Rekap Absensi Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Rekap Absensi Siswa
    </h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b); ?>" <?php echo e(($bulan ?? date('m')) == $b ? 'selected' : ''); ?>>
                        <?php echo e($nama); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(($tahun ?? date('Y')) == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k->id); ?>" <?php echo e(($kelas_id ?? '') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama ?? $k->nama_kelas); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Bulan <?php echo e($bulanList[$bulan ?? date('m')]); ?> <?php echo e($tahun ?? date('Y')); ?></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="20%">Nama</th>
                        <th width="10%">Kelas</th>
                        <th width="8%">Hadir</th>
                        <th width="8%">Sakit</th>
                        <th width="8%">Izin</th>
                        <th width="8%">Alfa</th>
                        <th width="8%">Terlambat</th>
                        <th width="8%">Total</th>
                        <th width="10%">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $stats = $statistik[$s->id] ?? [
                                'hadir' => 0,
                                'sakit' => 0,
                                'izin' => 0,
                                'alfa' => 0,
                                'terlambat' => 0,
                                'total' => 0,
                                'persentase' => 0
                            ];
                            $persentase = $stats['persentase'];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($s->nis ?? '-'); ?></td>
                            <td><strong><?php echo e($s->user->name ?? $s->nama_lengkap ?? '-'); ?></strong></td>
                            <td><?php echo e($s->kelas->nama ?? $s->kelas->nama_kelas ?? '-'); ?></td>
                            <td class="text-center bg-success text-white"><?php echo e($stats['hadir']); ?></td>
                            <td class="text-center bg-warning text-dark"><?php echo e($stats['sakit']); ?></td>
                            <td class="text-center bg-info text-white"><?php echo e($stats['izin']); ?></td>
                            <td class="text-center bg-danger text-white"><?php echo e($stats['alfa']); ?></td>
                            <td class="text-center bg-secondary text-white"><?php echo e($stats['terlambat']); ?></td>
                            <td class="text-center"><?php echo e($stats['total']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo e($persentase >= 90 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger')); ?> p-2 fs-6">
                                    <?php echo e(number_format($persentase, 2)); ?>%
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">Belum ada data absensi untuk periode ini</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rekapTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [4,5,6,7,8,9,10] }
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/absensi/rekap-siswa.blade.php ENDPATH**/ ?>