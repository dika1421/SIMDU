

<?php $__env->startSection('title', 'Pesan'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .message-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s;
        cursor: pointer;
    }
    .message-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .message-card.unread {
        border-left: 4px solid #3498db;
        background: #f8fbff;
    }
    .message-card .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        flex-shrink: 0;
    }
    .message-card .avatar.bg-primary { background: #3498db; }
    .message-card .avatar.bg-success { background: #2ecc71; }
    .message-card .avatar.bg-warning { background: #f39c12; }
    .message-card .avatar.bg-danger { background: #e74c3c; }
    .message-card .avatar.bg-purple { background: #9b59b6; }
    
    .message-subject {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }
    .message-preview {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }
    .message-meta {
        font-size: 0.75rem;
        color: #adb5bd;
    }
    .badge-unread {
        background: #3498db;
        color: white;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 10px;
    }
    .btn-action-small {
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        border-left: 4px solid;
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .stat-card.primary { border-left-color: #3498db; }
    .stat-card.success { border-left-color: #2ecc71; }
    .stat-card.warning { border-left-color: #f39c12; }
    .stat-card.info { border-left-color: #1abc9c; }
    .stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
    }
    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #7f8c8d;
    }
    .stat-card .stat-icon {
        font-size: 1.5rem;
        opacity: 0.5;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <h1 class="h2 fw-bold mb-0">
        <i class="fas fa-envelope me-2 text-primary"></i>
        Pesan
        <?php if(isset($belumDibaca) && $belumDibaca > 0): ?>
            <span class="badge bg-danger ms-2"><?php echo e($belumDibaca); ?> Baru</span>
        <?php endif; ?>
    </h1>
    <div>
        <a href="<?php echo e(route('guru.komunikasi.create')); ?>" class="btn btn-primary btn-action">
            <i class="fas fa-plus me-1"></i> Pesan Baru
        </a>
        <?php if(isset($belumDibaca) && $belumDibaca > 0): ?>
            <a href="<?php echo e(route('guru.komunikasi.mark-all-read')); ?>" class="btn btn-outline-secondary btn-action">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Alert Messages -->
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo e(($pesanMasuk->total() ?? 0) + ($pesanKeluar->total() ?? 0)); ?></div>
                    <div class="stat-label">Total Pesan</div>
                </div>
                <i class="fas fa-envelope stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo e($pesanMasuk->total() ?? 0); ?></div>
                    <div class="stat-label">Pesan Masuk</div>
                </div>
                <i class="fas fa-inbox stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo e($pesanKeluar->total() ?? 0); ?></div>
                    <div class="stat-label">Pesan Keluar</div>
                </div>
                <i class="fas fa-paper-plane stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo e($belumDibaca ?? 0); ?></div>
                    <div class="stat-label">Belum Dibaca</div>
                </div>
                <i class="fas fa-bell stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="messageTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab">
            <i class="fas fa-inbox me-1"></i> Kotak Masuk
            <?php if($belumDibaca > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo e($belumDibaca); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab">
            <i class="fas fa-paper-plane me-1"></i> Terkirim
        </button>
    </li>
</ul>

<div class="tab-content" id="messageTabsContent">
    <!-- Kotak Masuk -->
    <div class="tab-pane fade show active" id="inbox" role="tabpanel">
        <?php if($pesanMasuk->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada pesan masuk</h5>
                <p class="text-muted small">Belum ada pesan yang masuk ke kotak Anda.</p>
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $pesanMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Cek status pesan untuk user ini
                $penerimaData = $p->penerimaPesan->where('penerima_id', auth()->id())->first();
                $sudahDibaca = $penerimaData ? ($penerimaData->status == 'dibaca') : true;
                $isPenting = $p->is_urgent ?? false;
                $isPengirim = $p->pengirim_id == auth()->id();
            ?>
            <div class="card message-card mb-3 <?php echo e(!$sudahDibaca ? 'unread' : ''); ?>">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="avatar bg-<?php echo e(['primary', 'success', 'warning', 'danger', 'purple'][$p->id % 5]); ?>">
                                <?php echo e(strtoupper(substr($p->pengirim->name ?? 'U', 0, 1))); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fw-semibold"><?php echo e($p->pengirim->name ?? 'Pengirim'); ?></div>
                            <div class="message-meta">
                                <i class="far fa-clock me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($p->created_at)->diffForHumans()); ?>

                            </div>
                            <small class="text-muted"><?php echo e(ucfirst($p->pengirim->role ?? '')); ?></small>
                        </div>
                        <div class="col-md-4">
                            <div class="message-subject">
                                <?php echo e($p->judul); ?>

                                <?php if($isPenting): ?>
                                    <span class="badge bg-danger ms-1">Penting</span>
                                <?php endif; ?>
                                <?php if(!$sudahDibaca): ?>
                                    <span class="badge-unread ms-1">Baru</span>
                                <?php endif; ?>
                            </div>
                            <div class="message-preview"><?php echo e(Str::limit($p->isi, 80)); ?></div>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="badge bg-light text-dark">
                                <?php echo e($isPengirim ? 'Dikirim' : 'Diterima'); ?>

                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="<?php echo e(route('guru.komunikasi.show', $p->id)); ?>" 
                               class="btn btn-sm btn-info btn-action-small">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger btn-action-small" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal<?php echo e($p->id); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Delete -->
            <div class="modal fade" id="deleteModal<?php echo e($p->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Konfirmasi Hapus
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-3">
                                <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Apakah Anda yakin ingin menghapus pesan ini?</h6>
                                <p class="text-muted small">
                                    <strong>Judul:</strong> <?php echo e($p->judul); ?><br>
                                    <strong>Dari:</strong> <?php echo e($p->pengirim->name ?? 'Pengirim'); ?>

                                </p>
                                <p class="text-danger small mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pesan yang dihapus tidak dapat dikembalikan!
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <form action="<?php echo e(route('guru.komunikasi.destroy', $p->id)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($pesanMasuk->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Terkirim -->
    <div class="tab-pane fade" id="sent" role="tabpanel">
        <?php if($pesanKeluar->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pesan terkirim</h5>
                <p class="text-muted small">Pesan yang Anda kirim akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $pesanKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card message-card mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="avatar bg-secondary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fw-semibold">
                                <?php
                                    $penerimaFirst = $p->penerimaPesan->first();
                                ?>
                                <?php echo e($penerimaFirst ? $penerimaFirst->penerima->name : 'Penerima'); ?>

                            </div>
                            <div class="message-meta">
                                <i class="far fa-clock me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($p->created_at)->diffForHumans()); ?>

                            </div>
                            <small class="text-muted">
                                <?php echo e($p->jenis == 'broadcast' ? 'Broadcast' : 'Personal'); ?>

                            </small>
                        </div>
                        <div class="col-md-4">
                            <div class="message-subject"><?php echo e($p->judul); ?></div>
                            <div class="message-preview"><?php echo e(Str::limit($p->isi, 80)); ?></div>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="badge bg-light text-dark">
                                <?php echo e($p->penerimaPesan->count()); ?> penerima
                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="<?php echo e(route('guru.komunikasi.show', $p->id)); ?>" 
                               class="btn btn-sm btn-info btn-action-small">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger btn-action-small" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal<?php echo e($p->id); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Delete -->
            <div class="modal fade" id="deleteModal<?php echo e($p->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Konfirmasi Hapus
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-3">
                                <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Apakah Anda yakin ingin menghapus pesan ini?</h6>
                                <p class="text-muted small">
                                    <strong>Judul:</strong> <?php echo e($p->judul); ?><br>
                                    <strong>Kepada:</strong> <?php echo e($p->penerimaPesan->count()); ?> penerima
                                </p>
                                <p class="text-danger small mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pesan yang dihapus tidak dapat dikembalikan!
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <form action="<?php echo e(route('guru.komunikasi.destroy', $p->id)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($pesanKeluar->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Auto refresh unread count setiap 30 detik
        setInterval(function() {
            $.ajax({
                url: '<?php echo e(route("guru.komunikasi.unread-count")); ?>',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Update badge di sidebar
                        $('.badge-notif').text(response.count);
                        if (response.count > 0) {
                            $('.badge-notif').show();
                        } else {
                            $('.badge-notif').hide();
                        }
                    }
                }
            });
        }, 30000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('guru.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/guru/komunikasi/index.blade.php ENDPATH**/ ?>