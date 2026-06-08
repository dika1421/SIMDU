

<?php $__env->startSection('title', 'Detail Pesan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope-open me-2"></i>
        Detail Pesan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.komunikasi.index')); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?php echo e($pesan->judul ?? 'Tanpa Judul'); ?></h5>
        <?php if(isset($pesan->is_urgent) && $pesan->is_urgent): ?>
            <span class="badge bg-danger">Penting</span>
        <?php endif; ?>
        <?php if(isset($pesan->jenis) && $pesan->jenis == 'broadcast'): ?>
            <span class="badge bg-info">Broadcast</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Pengirim:</strong> 
            <?php if($pesan->pengirim): ?>
                <?php echo e($pesan->pengirim->name ?? 'Tidak diketahui'); ?>

            <?php else: ?>
                <span class="text-muted">Pengirim tidak ditemukan</span>
            <?php endif; ?>
            <span class="text-muted ms-2">
                (<?php echo e($pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->format('d/m/Y H:i') : '-'); ?>)
            </span>
        </div>
        
        <div class="mb-3">
            <strong>Tipe:</strong> 
            <?php if(isset($pesan->jenis) && $pesan->jenis == 'broadcast'): ?>
                <span class="badge bg-info">Broadcast</span>
            <?php else: ?>
                <span class="badge bg-secondary">Individual</span>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <strong>Status:</strong>
            <?php if(isset($pesan->status)): ?>
                <?php if($pesan->status == 'terkirim'): ?>
                    <span class="badge bg-success">Terkirim</span>
                <?php elseif($pesan->status == 'dibaca'): ?>
                    <span class="badge bg-primary">Dibaca</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?php echo e(ucfirst($pesan->status)); ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span class="badge bg-secondary">-</span>
            <?php endif; ?>
        </div>
        
        <hr>
        
        <div class="mb-4">
            <strong>Isi Pesan:</strong>
            <div class="mt-2 p-3 bg-light rounded">
                <?php echo nl2br(e($pesan->isi ?? '-')); ?>

            </div>
        </div>
        
        <!-- Tampilkan daftar penerima untuk pesan individual -->
        <?php if(isset($pesan->jenis) && $pesan->jenis != 'broadcast'): ?>
        <hr>
        <div class="mt-3">
            <strong>Daftar Penerima:</strong>
            <div class="table-responsive mt-2">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Waktu Dibaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $penerimaList = $pesan->penerimaPesan ?? $pesan->penerima ?? []; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $penerimaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $penerimaItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td>
                                <?php if($penerimaItem->penerima): ?>
                                    <?php echo e($penerimaItem->penerima->name ?? 'Tidak diketahui'); ?>

                                <?php elseif($penerimaItem->user): ?>
                                    <?php echo e($penerimaItem->user->name ?? 'Tidak diketahui'); ?>

                                <?php else: ?>
                                    <span class="text-muted">Penerima tidak ditemukan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($penerimaItem->penerima): ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($penerimaItem->penerima->role ?? '-')); ?></span>
                                <?php elseif($penerimaItem->user): ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($penerimaItem->user->role ?? '-')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(isset($penerimaItem->status)): ?>
                                    <?php if($penerimaItem->status == 'dibaca'): ?>
                                        <span class="badge bg-success">Sudah Dibaca</span>
                                    <?php elseif($penerimaItem->status == 'terkirim'): ?>
                                        <span class="badge bg-warning">Belum Dibaca</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($penerimaItem->status)); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(isset($penerimaItem->tanggal_baca) && $penerimaItem->tanggal_baca): ?>
                                    <?php echo e(\Carbon\Carbon::parse($penerimaItem->tanggal_baca)->format('d/m/Y H:i')); ?>

                                <?php elseif(isset($penerimaItem->dibaca_at) && $penerimaItem->dibaca_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($penerimaItem->dibaca_at)->format('d/m/Y H:i')); ?>

                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data penerima</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Informasi tambahan untuk broadcast -->
        <?php if(isset($pesan->jenis) && $pesan->jenis == 'broadcast'): ?>
        <hr>
        <div class="mt-3">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Pesan ini dikirim sebagai <strong>Broadcast</strong> ke semua pengguna.
                <?php if(isset($pesan->penerimaPesan)): ?>
                    <br>Total penerima: <strong><?php echo e($pesan->penerimaPesan->count()); ?></strong> orang
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between">
            <div>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> Dikirim: <?php echo e($pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->diffForHumans() : '-'); ?>

                </small>
            </div>
            <div>
                <?php if(auth()->id() == ($pesan->pengirim_id ?? null)): ?>
                <form action="<?php echo e(route('administrasi.komunikasi.destroy', $pesan->id)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .table-sm td, .table-sm th {
        padding: 0.5rem;
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/komunikasi/show.blade.php ENDPATH**/ ?>