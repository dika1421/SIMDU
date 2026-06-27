

<?php $__env->startSection('title', 'Struktur Organisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-sitemap me-2"></i>
        Struktur Organisasi Sekolah
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
            <i class="fas fa-plus"></i> Tambah Struktur
        </button>
    </div>
</div>

<!-- Tree View Struktur Organisasi -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Bagan Struktur Organisasi</h5>
    </div>
    <div class="card-body">
        <div class="org-tree">
            <?php
                $root = $struktur->whereNull('parent_id');
            ?>
            
            <?php $__empty_1 = true; $__currentLoopData = $root; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="org-node text-center mb-4">
                    <div class="d-inline-block p-3 bg-primary text-white rounded-3 shadow" style="min-width: 250px;">
                        <h5 class="mb-1"><?php echo e($item->nama); ?></h5>
                        <p class="mb-0"><small><?php echo e($item->jabatan); ?></small></p>
                        <?php if($item->guru): ?>
                            <small class="text-white-50"><?php echo e($item->guru->user->name); ?></small>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($item->children->count() > 0): ?>
                        <div class="mt-3">
                            <i class="fas fa-chevron-down fa-2x text-muted"></i>
                        </div>
                        
                        <div class="row mt-3">
                            <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-3 shadow-sm">
                                        <h6><?php echo e($child->nama); ?></h6>
                                        <p class="mb-0"><small><?php echo e($child->jabatan); ?></small></p>
                                        <?php if($child->guru): ?>
                                            <small class="text-muted"><?php echo e($child->guru->user->name); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5">
                    <i class="fas fa-sitemap fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data struktur organisasi</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
                        <i class="fas fa-plus"></i> Tambah Struktur Pertama
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tabel Data Struktur -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Struktur Organisasi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Atasan</th>
                        <th>Penanggung Jawab</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $struktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><strong><?php echo e($s->nama); ?></strong></td>
                        <td><?php echo e($s->jabatan); ?></td>
                        <td><?php echo e($s->parent->nama ?? '-'); ?></td>
                        <td><?php echo e($s->guru->user->name ?? '-'); ?></td>
                        <td><?php echo e($s->urutan); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStrukturModal<?php echo e($s->id); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="<?php echo e(route('kepala-sekolah.manajemen.struktur.destroy', $s->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahStrukturModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.manajemen.struktur.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Struktur Organisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Struktur</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atasan</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Tidak Ada (Root)</option>
                            <?php $__currentLoopData = $struktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>"><?php echo e($s->nama); ?> (<?php echo e($s->jabatan); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab (Guru)</label>
                        <select name="guru_id" class="form-control">
                            <option value="">Pilih Guru</option>
                            <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($g->id); ?>"><?php echo e($g->user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="0" required>
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

<!-- Modal Edit untuk setiap data -->
<?php $__currentLoopData = $struktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editStrukturModal<?php echo e($s->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.manajemen.struktur.update', $s->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Struktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Struktur</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo e($s->nama); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="<?php echo e($s->jabatan); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atasan</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Tidak Ada</option>
                            <?php $__currentLoopData = $struktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php echo e($s->parent_id == $p->id ? 'selected' : ''); ?>>
                                <?php echo e($p->nama); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <select name="guru_id" class="form-control">
                            <option value="">Pilih</option>
                            <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($g->id); ?>" <?php echo e($s->guru_id == $g->id ? 'selected' : ''); ?>>
                                <?php echo e($g->user->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo e($s->deskripsi); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="<?php echo e($s->urutan); ?>" required>
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

<?php $__env->startPush('styles'); ?>
<style>
    .org-tree {
        min-height: 300px;
    }
    .org-node {
        position: relative;
    }
    .org-node .bg-primary {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/manajemen-sekolah/struktur-organisasi.blade.php ENDPATH**/ ?>