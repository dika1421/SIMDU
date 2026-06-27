

<?php $__env->startSection('title', 'Input Absensi Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-user me-2"></i>
        Input Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.absensi.rekap-guru')); ?>" class="btn btn-sm btn-info">
            <i class="fas fa-chart-line"></i> Rekap Absensi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo e($tanggal); ?>" onchange="this.form.submit()">
            </div>
        </form>

        <form action="<?php echo e(route('administrasi.absensi.store-guru')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tanggal" value="<?php echo e($tanggal); ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">NIP</th>
                            <th width="25%">Nama Guru</th>
                            <th width="20%">Mata Pelajaran</th>
                            <th width="15%">Status</th>
                            <th width="20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($g->nip ?? '-'); ?></td>
                            <td>
                                <strong><?php echo e($g->nama_lengkap ?? '-'); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($g->user->email ?? '-'); ?></small>
                            </td>
                            <td><?php echo e($g->mata_pelajaran_utama ?? '-'); ?></td>
                            <td>
                                <select name="absensi[<?php echo e($g->id); ?>][status]" class="form-select">
                                    <option value="">-- Pilih Status --</option>
                                    <?php $__currentLoopData = $statusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($g->absensi_hari_ini && $g->absensi_hari_ini->status == $key ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="absensi[<?php echo e($g->id); ?>][keterangan]" class="form-control" 
                                       value="<?php echo e($g->absensi_hari_ini->keterangan ?? ''); ?>" placeholder="Keterangan">
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/absensi/guru.blade.php ENDPATH**/ ?>