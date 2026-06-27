

<?php $__env->startSection('title', 'Tahun Ajaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Manajemen Tahun Ajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTahunAjaranModal">
            <i class="fas fa-plus"></i> Tambah Tahun Ajaran
        </button>
    </div>
</div>

<!-- Info Tahun Ajaran Aktif -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Tahun Ajaran Aktif Saat Ini:</strong>
    <?php $aktif = $tahunAjaran->where('is_aktif', true)->first(); ?>
    <?php if($aktif): ?>
        <?php echo e($aktif->nama); ?> (Semester <?php echo e(ucfirst($aktif->semester)); ?>)
    <?php else: ?>
        Belum ada tahun ajaran aktif
    <?php endif; ?>
</div>

<!-- Daftar Tahun Ajaran -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Tahun Ajaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $tahunAjaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><strong><?php echo e($ta->nama); ?></strong></td>
                        <td>
                            <span class="badge bg-<?php echo e($ta->semester == 'ganjil' ? 'info' : 'success'); ?>">
                                <?php echo e(ucfirst($ta->semester)); ?>

                            </span>
                        </td>
                        <td><?php echo e(\Carbon\Carbon::parse($ta->tanggal_mulai)->format('d/m/Y')); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($ta->tanggal_selesai)->format('d/m/Y')); ?></td>
                        <td>
                            <?php if($ta->is_aktif): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!$ta->is_aktif): ?>
                            <form action="<?php echo e(route('kepala-sekolah.manajemen.tahun-ajaran.set-aktif', $ta->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Set sebagai tahun ajaran aktif?')">
                                    <i class="fas fa-check"></i> Set Aktif
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahTahunAjaranModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.manajemen.tahun-ajaran.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2024/2025" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_aktif" class="form-check-input" value="1">
                            <label class="form-check-label">
                                Jadikan Tahun Ajaran Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/manajemen-sekolah/tahun-ajaran.blade.php ENDPATH**/ ?>