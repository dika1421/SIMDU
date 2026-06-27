


<?php $__env->startSection('title', 'Kalender Akademik'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .event-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .event-card:hover {
        transform: translateX(5px);
    }
    .event-ujian { border-left-color: #dc3545; }
    .event-libur { border-left-color: #ffc107; }
    .event-acara { border-left-color: #28a745; }
    .event-lainnya { border-left-color: #6c757d; }
</style>

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

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                <h4><?php echo e($eventsBulanIni->count()); ?></h4>
                <p class="text-muted">Total Event</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-pen fa-2x text-danger mb-2"></i>
                <h4><?php echo e($ujian->count()); ?></h4>
                <p class="text-muted">Jadwal Ujian</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-glass-cheers fa-2x text-warning mb-2"></i>
                <h4><?php echo e($libur->count()); ?></h4>
                <p class="text-muted">Hari Libur</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-futbol fa-2x text-success mb-2"></i>
                <h4><?php echo e($kegiatan->count()); ?></h4>
                <p class="text-muted">Kegiatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Event Bulan Ini -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Event <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?>

                </h5>
            </div>
            <div class="card-body">
                <?php if($eventsBulanIni->count() > 0): ?>
                    <div class="row">
                        <?php $__currentLoopData = $eventsBulanIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-3">
                                <div class="event-card event-<?php echo e($event['jenis']); ?> p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?php echo e($event['judul']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($event['tanggal_mulai'])->translatedFormat('d F Y')); ?>

                                                <?php if($event['tanggal_selesai'] && $event['tanggal_selesai'] != $event['tanggal_mulai']): ?>
                                                    - <?php echo e(\Carbon\Carbon::parse($event['tanggal_selesai'])->translatedFormat('d F Y')); ?>

                                                <?php endif; ?>
                                            </small>
                                            <?php if($event['deskripsi']): ?>
                                                <p class="small mt-2 mb-0"><?php echo e($event['deskripsi']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-<?php echo e($event['jenis'] == 'ujian' ? 'danger' : 
                                            ($event['jenis'] == 'libur' ? 'warning' : 
                                            ($event['jenis'] == 'acara' ? 'success' : 'secondary'))); ?>">
                                            <?php echo e(ucfirst($event['jenis'])); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada event pada bulan ini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Ujian Mendatang -->
<?php if($ujian->count() > 0): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-pen-fancy me-2 text-danger"></i>Jadwal Ujian</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $ujian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($u->judul); ?></td>
                                    <td>
                                        <?php echo e(\Carbon\Carbon::parse($u->tanggal_mulai)->translatedFormat('d F Y')); ?>

                                        <?php if($u->tanggal_selesai && $u->tanggal_selesai != $u->tanggal_mulai): ?>
                                            - <?php echo e(\Carbon\Carbon::parse($u->tanggal_selesai)->translatedFormat('d F Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($u->deskripsi ?? '-'); ?></td>
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

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi DataTable jika ada
        if ($('#ujianTable').length) {
            $('#ujianTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                pageLength: 10
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('siswa.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/siswa/kalender/index.blade.php ENDPATH**/ ?>