

<?php $__env->startSection('title', 'Manajemen Jurusan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-code-branch me-2"></i>
        Manajemen Jurusan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahJurusanModal">
            <i class="fas fa-plus"></i> Tambah Jurusan
        </button>
    </div>
</div>

<!-- Statistik Jurusan -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Jurusan</h6>
                <h3><?php echo e($jurusan->count()); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Kelas</h6>
                <h3><?php echo e(\App\Models\Kelas::count()); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Siswa</h6>
                <h3><?php echo e(\App\Models\Siswa::count()); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Jurusan -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Jurusan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Deskripsi</th>
                        <th>Kepala Jurusan</th>
                        <th>Jumlah Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><span class="badge bg-primary"><?php echo e($j->kode); ?></span></td>
                        <td><strong><?php echo e($j->nama); ?></strong></td>
                        <td><?php echo e(Str::limit($j->deskripsi, 50)); ?></td>
                        <td><?php echo e($j->kepalaJurusan->user->name ?? '-'); ?></td>
                        <td class="text-center"><?php echo e($j->kelas->count()); ?></td>
                        <td class="text-center"><?php echo e($j->siswa->count()); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editJurusanModal<?php echo e($j->id); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="<?php echo e(route('kepala-sekolah.manajemen.jurusan.destroy', $j->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahJurusanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.manajemen.jurusan.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jurusan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kepala Jurusan</label>
                        <select name="kepala_jurusan_id" class="form-control">
                            <option value="">Pilih Guru</option>
                            <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($g->id); ?>"><?php echo e($g->user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
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

<!-- Modal Edit -->
<?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editJurusanModal<?php echo e($j->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.manajemen.jurusan.update', $j->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" class="form-control" value="<?php echo e($j->kode); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo e($j->nama); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo e($j->deskripsi); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kepala Jurusan</label>
                        <select name="kepala_jurusan_id" class="form-control">
                            <option value="">Pilih</option>
                            <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($g->id); ?>" <?php echo e($j->kepala_jurusan_id == $g->id ? 'selected' : ''); ?>>
                                <?php echo e($g->user->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/manajemen-sekolah/jurusan.blade.php ENDPATH**/ ?>