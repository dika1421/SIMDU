

<?php $__env->startSection('title', 'Kalender Akademik'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Kalender Akademik
    </h1>
</div>

<!-- Filter Tahun -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tahun Akademik</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($thn); ?>" <?php echo e($tahun == $thn ? 'selected' : ''); ?>>
                            <?php echo e($thn); ?>/<?php echo e($thn + 1); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Event -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Event</h5>
                <h2 class="mb-0"><?php echo e($events->count()); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Hari Libur</h5>
                <h2 class="mb-0"><?php echo e($libur->count()); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Jadwal Ujian</h5>
                <h2 class="mb-0"><?php echo e($ujian->count()); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Acara Sekolah</h5>
                <h2 class="mb-0"><?php echo e($acara->count()); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Event -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Kegiatan Akademik</h5>
    </div>
    <div class="card-body">
        <?php if($events->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Event</h5>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        实例
                            <th width="20%">Tanggal</th>
                            <th width="30%">Event</th>
                            <th width="20%">Jenis</th>
                            <th width="20%">Target</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($event->tanggal_mulai)->format('d/m/Y')); ?>

                                <?php if($event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai): ?>
                                    - <?php echo e(\Carbon\Carbon::parse($event->tanggal_selesai)->format('d/m/Y')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($event->judul); ?></strong>
                                <?php if($event->deskripsi): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($event->deskripsi, 100)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $badgeColor = [
                                        'libur' => 'warning',
                                        'ujian' => 'danger',
                                        'pendaftaran' => 'info',
                                        'acara' => 'success',
                                        'rapat' => 'primary',
                                        'ekstrakurikuler' => 'secondary',
                                        'lainnya' => 'secondary'
                                    ];
                                    $color = $badgeColor[$event->jenis] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo e($color); ?>">
                                    <?php echo e(ucfirst($event->jenis)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($event->target == 'semua'): ?>
                                    <span class="badge bg-info">Semua</span>
                                <?php elseif($event->target == 'guru'): ?>
                                    <span class="badge bg-primary">Guru</span>
                                <?php elseif($event->target == 'siswa'): ?>
                                    <span class="badge bg-success">Siswa</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($event->target)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($event->isOngoing()): ?>
                                    <span class="badge bg-success">Berlangsung</span>
                                <?php elseif($event->isUpcoming()): ?>
                                    <span class="badge bg-warning">Akan Datang</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('guru.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/guru/kalender/index.blade.php ENDPATH**/ ?>