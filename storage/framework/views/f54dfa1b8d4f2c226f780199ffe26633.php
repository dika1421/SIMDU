

<?php $__env->startSection('title', 'Pesan & Komunikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope me-2"></i>
        Pesan & Komunikasi
        <?php if(isset($belumDibaca) && $belumDibaca > 0): ?>
            <span class="badge bg-danger ms-2"><?php echo e($belumDibaca); ?> Baru</span>
        <?php endif; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.komunikasi.create')); ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Pesan Baru
        </a>
        <a href="<?php echo e(route('administrasi.komunikasi.broadcast')); ?>" class="btn btn-sm btn-info ms-2">
            <i class="fas fa-bullhorn"></i> Broadcast
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('info')): ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    <?php echo e(session('info')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="pesanTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab">
            <i class="fas fa-inbox me-1"></i> Pesan Masuk
            <?php if(isset($belumDibaca) && $belumDibaca > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo e($belumDibaca); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button" role="tab">
            <i class="fas fa-paper-plane me-1"></i> Pesan Terkirim
        </button>
    </li>
</ul>

<div class="tab-content" id="pesanTabContent">
    <!-- Tab Pesan Masuk -->
    <div class="tab-pane fade show active" id="masuk" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesan Masuk</h5>
                <?php if(isset($pesanDiterima)): ?>
                <span class="badge bg-info"><?php echo e($pesanDiterima->total() ?? $pesanDiterima->count()); ?> Pesan</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if(isset($pesanDiterima) && $pesanDiterima->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                实例
                                    <th width="5%"></th>
                                    <th width="25%">Pengirim</th>
                                    <th width="40%">Judul</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pesanDiterima; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Cek status baca dari pivot
                                        $penerimaData = $p->penerimaPesan->where('penerima_id', auth()->id())->first();
                                        $sudahDibaca = $penerimaData ? ($penerimaData->status == 'dibaca') : false;
                                        $rowClass = (!$sudahDibaca) ? 'table-info fw-bold' : '';
                                    ?>
                                    <tr class="<?php echo e($rowClass); ?>">
                                        <td>
                                            <?php if($p->is_urgent): ?>
                                                <i class="fas fa-exclamation-circle text-danger" title="Penting"></i>
                                            <?php endif; ?>
                                            <?php if(!$sudahDibaca): ?>
                                                <span class="badge bg-warning ms-1">Baru</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($p->pengirim->name ?? 'Tidak Diketahui'); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e(ucfirst($p->pengirim->role ?? '')); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo e(Str::limit($p->judul, 50)); ?></strong>
                                            <?php if($p->jenis == 'broadcast'): ?>
                                                <span class="badge bg-info ms-2">Broadcast</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e(\Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i')); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($p->created_at)->diffForHumans()); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('administrasi.komunikasi.show', $p->id)); ?>" 
                                               class="btn btn-sm btn-info" title="Baca Pesan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(method_exists($pesanDiterima, 'links')): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan <?php echo e($pesanDiterima->firstItem() ?? 0); ?> - <?php echo e($pesanDiterima->lastItem() ?? 0); ?> 
                                dari <?php echo e($pesanDiterima->total() ?? 0); ?> pesan
                            </div>
                            <div>
                                <?php echo e($pesanDiterima->links()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Pesan Masuk</h5>
                        <p class="text-muted">Anda belum memiliki pesan masuk</p>
                        <a href="<?php echo e(route('administrasi.komunikasi.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Pesan Baru
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Tab Pesan Terkirim -->
    <div class="tab-pane fade" id="keluar" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesan Terkirim</h5>
                <?php if(isset($pesanDikirim)): ?>
                <span class="badge bg-info"><?php echo e($pesanDikirim->total() ?? $pesanDikirim->count()); ?> Pesan</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if(isset($pesanDikirim) && $pesanDikirim->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="40%">Judul</th>
                                    <th width="15%">Jenis</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pesanDikirim; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($p->is_urgent): ?>
                                                <i class="fas fa-exclamation-circle text-danger" title="Penting"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e(Str::limit($p->judul, 50)); ?></strong>
                                            <?php if($p->jenis == 'broadcast'): ?>
                                                <span class="badge bg-info ms-2">Broadcast</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($p->jenis == 'broadcast'): ?>
                                                <span class="badge bg-info">Broadcast</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Personal</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e(\Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i')); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($p->created_at)->diffForHumans()); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Terkirim</span>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('administrasi.komunikasi.show', $p->id)); ?>" 
                                               class="btn btn-sm btn-info" title="Detail Pesan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(method_exists($pesanDikirim, 'links')): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan <?php echo e($pesanDikirim->firstItem() ?? 0); ?> - <?php echo e($pesanDikirim->lastItem() ?? 0); ?> 
                                dari <?php echo e($pesanDikirim->total() ?? 0); ?> pesan
                            </div>
                            <div>
                                <?php echo e($pesanDikirim->links()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-paper-plane fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Pesan Terkirim</h5>
                        <p class="text-muted">Anda belum mengirim pesan apapun</p>
                        <a href="<?php echo e(route('administrasi.komunikasi.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Pesan Baru
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Pesan -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-paper-plane fa-2x mb-2"></i>
                <h3><?php echo e(isset($pesanDikirim) ? ($pesanDikirim->total() ?? $pesanDikirim->count()) : 0); ?></h3>
                <p class="mb-0 opacity-75">Pesan Terkirim</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <h3><?php echo e(isset($pesanDiterima) ? ($pesanDiterima->total() ?? $pesanDiterima->count()) : 0); ?></h3>
                <p class="mb-0 opacity-75">Pesan Diterima</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-2x mb-2"></i>
                <h3><?php echo e($belumDibaca ?? 0); ?></h3>
                <p class="mb-0 opacity-75">Belum Dibaca</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .table-info {
        background-color: #cfe2ff !important;
    }
    .table-info td {
        font-weight: 500;
    }
    .badge {
        font-size: 0.75rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        padding: 0.375rem 0.75rem;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 500;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/komunikasi/index.blade.php ENDPATH**/ ?>