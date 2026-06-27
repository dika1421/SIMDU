

<?php $__env->startSection('title', 'Input Absensi Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-check me-2"></i>
        Input Absensi Siswa
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.absensi.rekap-siswa')); ?>" class="btn btn-sm btn-info">
            <i class="fas fa-chart-line"></i> Lihat Rekap
        </a>
    </div>
</div>

<!-- Form Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo e($tanggal ?? date('Y-m-d')); ?>">
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
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Absensi -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Siswa - Tanggal <?php echo e(\Carbon\Carbon::parse($tanggal ?? date('Y-m-d'))->format('d/m/Y')); ?></h5>
        <span class="badge bg-info"><?php echo e($siswa->count() ?? 0); ?> Siswa</span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('administrasi.absensi.store-siswa')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tanggal" value="<?php echo e($tanggal ?? date('Y-m-d')); ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">NIS</th>
                            <th width="20%">Nama</th>
                            <th width="10%">Kelas</th>
                            <th width="15%">Status Kehadiran</th>
                            <th width="12%">Waktu Masuk</th>
                            <th width="12%">Waktu Keluar</th>
                            <th width="16%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $siswa ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($s->nis ?? '-'); ?></td>
                            <td><strong><?php echo e($s->user->name ?? $s->nama_lengkap ?? '-'); ?></strong></td>
                            <td><?php echo e($s->kelas->nama ?? $s->kelas->nama_kelas ?? '-'); ?></td>
                            <td>
                                <select name="absensi[<?php echo e($s->id); ?>][status]" class="form-select">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="hadir" <?php echo e(optional($s->absensi_hari_ini)->status == 'hadir' ? 'selected' : ''); ?>>Hadir</option>
                                    <option value="sakit" <?php echo e(optional($s->absensi_hari_ini)->status == 'sakit' ? 'selected' : ''); ?>>Sakit</option>
                                    <option value="izin" <?php echo e(optional($s->absensi_hari_ini)->status == 'izin' ? 'selected' : ''); ?>>Izin</option>
                                    <option value="alfa" <?php echo e(optional($s->absensi_hari_ini)->status == 'alfa' ? 'selected' : ''); ?>>Alfa</option>
                                    <option value="terlambat" <?php echo e(optional($s->absensi_hari_ini)->status == 'terlambat' ? 'selected' : ''); ?>>Terlambat</option>
                                </select>
                            </td>
                            <td>
                                <input type="time" name="absensi[<?php echo e($s->id); ?>][waktu_masuk]" class="form-control" 
                                       value="<?php echo e(optional($s->absensi_hari_ini)->waktu_masuk ? \Carbon\Carbon::parse($s->absensi_hari_ini->waktu_masuk)->format('H:i') : ''); ?>">
                            </td>
                            <td>
                                <input type="time" name="absensi[<?php echo e($s->id); ?>][waktu_keluar]" class="form-control"
                                       value="<?php echo e(optional($s->absensi_hari_ini)->waktu_keluar ? \Carbon\Carbon::parse($s->absensi_hari_ini->waktu_keluar)->format('H:i') : ''); ?>">
                            </td>
                            <td>
                                <input type="text" name="absensi[<?php echo e($s->id); ?>][keterangan]" class="form-control" 
                                       value="<?php echo e(optional($s->absensi_hari_ini)->keterangan); ?>">
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Tidak ada data siswa</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(($siswa ?? collect())->count() > 0): ?>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto submit filter ketika kelas berubah
    document.querySelector('select[name="kelas_id"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/absensi/siswa.blade.php ENDPATH**/ ?>