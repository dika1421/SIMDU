

<?php $__env->startSection('title', 'Rekap Absensi Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Rekap Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.absensi.guru')); ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-calendar-check"></i> Input Absensi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" onchange="this.form.submit()">
                    <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($bulan == $key ? 'selected' : ''); ?>><?php echo e($nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($thn); ?>" <?php echo e($tahun == $thn ? 'selected' : ''); ?>><?php echo e($thn); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Terlambat</th>
                        <th>Total</th>
                        <th>Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($r['nip']); ?></td>
                        <td><?php echo e($r['nama']); ?></td>
                        <td><?php echo e($r['mapel']); ?></td>
                        <td class="text-center"><?php echo e($r['hadir']); ?></td>
                        <td class="text-center"><?php echo e($r['sakit']); ?></td>
                        <td class="text-center"><?php echo e($r['izin']); ?></td>
                        <td class="text-center"><?php echo e($r['alfa']); ?></td>
                        <td class="text-center"><?php echo e($r['terlambat']); ?></td>
                        <td class="text-center"><?php echo e($r['total']); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo e($r['persentase'] >= 75 ? 'success' : ($r['persentase'] >= 50 ? 'warning' : 'danger')); ?>">
                                <?php echo e($r['persentase']); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-chalkboard-user fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data absensi guru</p>
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
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/absensi/rekap-guru.blade.php ENDPATH**/ ?>