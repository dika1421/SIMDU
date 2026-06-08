

<?php $__env->startSection('title', 'Arsip Dokumen'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-archive me-2"></i>
        Arsip Dokumen Sekolah
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <a href="<?php echo e(route('administrasi.arsip.create')); ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-upload"></i> Upload Dokumen
            </a>
            <a href="<?php echo e(route('administrasi.arsip.trash')); ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-trash-restore"></i> Tempat Sampah
            </a>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('administrasi.arsip.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e(request('kategori') == $key ? 'selected' : ''); ?>>
                        <?php echo e($value); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    <?php if($tahunList->isNotEmpty()): ?>
                        <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tahun); ?>" <?php echo e(request('tahun') == $tahun ? 'selected' : ''); ?>>
                            <?php echo e($tahun); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <?php $__currentLoopData = range(now()->year, now()->year-10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(request('tahun') == $t ? 'selected' : ''); ?>>
                            <?php echo e($t); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Kode arsip, judul atau deskripsi..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="<?php echo e(route('administrasi.arsip.index')); ?>" class="btn btn-secondary w-100">
                        <i class="fas fa-sync"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Arsip -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Dokumen</h5>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">Tampilkan:</label>
            <select name="per_page" class="form-select form-select-sm w-auto" onchange="window.location.href='<?php echo e(route('administrasi.arsip.index')); ?>?per_page='+this.value+'&kategori=<?php echo e(request('kategori')); ?>&tahun=<?php echo e(request('tahun')); ?>&search=<?php echo e(request('search')); ?>'">
                <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10</option>
                <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25</option>
                <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50</option>
                <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Arsip</th>
                        <th width="25%">Judul Dokumen</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">Tahun</th>
                        <th width="10%">Uploader</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $arsip; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><?php echo e($arsip->firstItem() + $index); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($a->kode_arsip ?? '-'); ?></span>
                        </td>
                        <td>
                            <strong><?php echo e($a->judul ?? '-'); ?></strong>
                            <?php if($a->deskripsi): ?>
                                <br>
                                <small class="text-muted"><?php echo e(Str::limit($a->deskripsi, 50)); ?></small>
                            <?php endif; ?>
                            <?php if($a->nama_file): ?>
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-file"></i> <?php echo e($a->nama_file); ?>

                                </small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <?php echo e($kategoriList[$a->kategori] ?? $a->kategori); ?>

                            </span>
                        </td>
                        <td><?php echo e($a->tanggal_dokumen ? \Carbon\Carbon::parse($a->tanggal_dokumen)->format('d/m/Y') : '-'); ?></td>
                        <td class="text-center">
                            <span class="badge bg-dark"><?php echo e($a->tahun ?? '-'); ?></span>
                        </td>
                        <td><?php echo e($a->uploader ? $a->uploader->name : '-'); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?php echo e(route('administrasi.arsip.show', $a->id)); ?>" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('administrasi.arsip.download', $a->id)); ?>" class="btn btn-success" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="<?php echo e(route('administrasi.arsip.edit', $a->id)); ?>" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($a->id); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="deleteModal<?php echo e($a->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus dokumen:</p>
                                            <p class="fw-bold"><?php echo e($a->judul); ?></p>
                                            <p class="text-muted small">Dokumen akan dipindahkan ke tempat sampah dan dapat direstore kembali.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="<?php echo e(route('administrasi.arsip.destroy', $a->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Belum ada data arsip</h5>
                            <p class="text-muted">Silakan upload dokumen pertama Anda</p>
                            <a href="<?php echo e(route('administrasi.arsip.create')); ?>" class="btn btn-primary mt-2">
                                <i class="fas fa-upload"></i> Upload Dokumen
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Informasi dan Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan <?php echo e($arsip->firstItem() ?? 0); ?> sampai <?php echo e($arsip->lastItem() ?? 0); ?> dari <?php echo e($arsip->total()); ?> entri
            </div>
            <div>
                <?php echo e($arsip->appends(request()->query())->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Statistik Ringkas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Dokumen</h6>
                        <h3 class="mb-0"><?php echo e($arsip->total()); ?></h3>
                    </div>
                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Kategori</h6>
                        <h3 class="mb-0"><?php echo e(count($kategoriList)); ?></h3>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Tahun Terbaru</h6>
                        <h3 class="mb-0"><?php echo e($tahunList->first() ?? date('Y')); ?></h3>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Bulan Ini</h6>
                        <h3 class="mb-0"><?php echo e($arsip->where('created_at', '>=', now()->startOfMonth())->count()); ?></h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-weight: 500;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/arsip/index.blade.php ENDPATH**/ ?>