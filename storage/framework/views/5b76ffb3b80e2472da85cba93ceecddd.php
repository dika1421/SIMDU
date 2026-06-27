

<?php $__env->startSection('title', 'Manajemen Kelas'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .badge-count {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .table th {
        background-color: #2c3e50;
        color: white;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-delete {
        cursor: pointer;
    }
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-school me-2"></i>
        Manajemen Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('administrasi.kelas.create')); ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo session('success'); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <form method="GET" action="<?php echo e(route('administrasi.kelas.index')); ?>" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label class="form-label">Cari Kelas</label>
                <input type="text" name="search" class="form-control" placeholder="Nama kelas..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php $__currentLoopData = $tingkatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('tingkat') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jurusan</label>
                <select name="jurusan_id" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php $__currentLoopData = $jurusanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($jurusan->id); ?>" <?php echo e(request('jurusan_id') == $jurusan->id ? 'selected' : ''); ?>>
                            <?php echo e($jurusan->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php $__currentLoopData = $statusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="<?php echo e(route('administrasi.kelas.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>Nama Kelas</th>
                        <th>Kode Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Siswa</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><?php echo e($loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage()); ?></td>
                        <td>
                            <strong><?php echo e($item->nama ?? '-'); ?></strong>
                            <?php if(!empty($item->keterangan)): ?>
                                <br>
                                <small class="text-muted"><?php echo e(Str::limit($item->keterangan, 30)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <code><?php echo e($item->kode_kelas ?? '-'); ?></code>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary"><?php echo e($item->tingkat ?? '-'); ?></span>
                        </td>
                        <td>
                            <?php if($item->jurusan): ?>
                                <?php echo e($item->jurusan->nama); ?>

                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->waliKelas): ?>
                                <?php echo e($item->waliKelas->nama_lengkap); ?>

                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo e($item->kapasitas ?? '-'); ?></td>
                        <td class="text-center">
                            <span class="badge bg-info"><?php echo e($item->siswa->count() ?? 0); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if($item->status == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Non Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?php echo e(route('administrasi.kelas.show', $item->id)); ?>" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('administrasi.kelas.edit', $item->id)); ?>" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-delete" 
                                        data-id="<?php echo e($item->id); ?>"
                                        data-name="<?php echo e($item->nama); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-database fa-4x text-muted mb-3 d-block"></i>
                                <h5>Tidak ada data kelas</h5>
                                <p class="text-muted">Silakan tambah kelas terlebih dahulu</p>
                                <a href="<?php echo e(route('administrasi.kelas.create')); ?>" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Kelas
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
            <div>
                <small class="text-muted">
                    Menampilkan <?php echo e($kelas->firstItem() ?? 0); ?> - <?php echo e($kelas->lastItem() ?? 0); ?> 
                    dari <?php echo e($kelas->total() ?? 0); ?> data
                </small>
            </div>
            <div>
                <?php echo e($kelas->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Delete button click handler
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Data kelas <strong>" + name + "</strong> akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menghapus data kelas',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: "<?php echo e(url('administrasi/kelas')); ?>/" + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: 'Kelas <strong>' + name + '</strong> berhasil dihapus',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            var errorMsg = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });
        
        // Auto close alert after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/kelas/index.blade.php ENDPATH**/ ?>