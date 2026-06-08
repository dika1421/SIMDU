

<?php $__env->startSection('title', 'Tulis Pesan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-pen me-2"></i>
        Tulis Pesan Baru
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
    <div class="card-body">
        <form action="<?php echo e(route('administrasi.komunikasi.store')); ?>" method="POST" id="formKirimPesan">
            <?php echo csrf_field(); ?>
            
            <div class="mb-3">
                <label class="form-label">Judul Pesan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('judul')); ?>" required>
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tipe Pesan <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis" id="tipeIndividual" value="personal" <?php echo e(old('jenis', 'personal') == 'personal' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="tipeIndividual">
                        Pesan Individual
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis" id="tipeBroadcast" value="broadcast" <?php echo e(old('jenis') == 'broadcast' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="tipeBroadcast">
                        Broadcast (Semua Pengguna)
                    </label>
                </div>
            </div>
            
            <div class="mb-3" id="penerimaSection">
                <label class="form-label">Pilih Penerima <span class="text-danger">*</span></label>
                
                <div id="penerimaError" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> Silakan pilih minimal satu penerima!
                </div>
                
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#siswaTab">Siswa <span id="siswaCount" class="badge bg-secondary">0</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#guruTab">Guru <span id="guruCount" class="badge bg-secondary">0</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#adminTab">Administrasi <span id="adminCount" class="badge bg-secondary">0</span></a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Tab Siswa -->
                    <div class="tab-pane fade show active" id="siswaTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllSiswa" onchange="selectAll('siswa-checkbox', this.checked)">
                                    Pilih Semua Siswa
                                </label>
                            </div>
                            <?php $__empty_1 = true; $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($s && $s->user): ?>
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox siswa-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="<?php echo e($s->user->id); ?>"
                                           id="siswa_<?php echo e($s->user->id); ?>"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="siswa_<?php echo e($s->user->id); ?>">
                                        <?php echo e($s->nis ?? '-'); ?> - <?php echo e($s->user->name ?? '-'); ?>

                                        <?php if(isset($s->kelas)): ?>
                                            (<?php echo e($s->kelas->nama_kelas ?? $s->kelas->nama ?? '-'); ?>)
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-muted">Tidak ada data siswa</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Tab Guru -->
                    <div class="tab-pane fade" id="guruTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllGuru" onchange="selectAll('guru-checkbox', this.checked)">
                                    Pilih Semua Guru
                                </label>
                            </div>
                            <?php $__empty_1 = true; $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($g && $g->user): ?>
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox guru-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="<?php echo e($g->user->id); ?>"
                                           id="guru_<?php echo e($g->user->id); ?>"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="guru_<?php echo e($g->user->id); ?>">
                                        <?php echo e($g->nip ?? '-'); ?> - <?php echo e($g->user->name ?? '-'); ?>

                                        <?php if(isset($g->mata_pelajaran)): ?>
                                            (<?php echo e($g->mata_pelajaran); ?>)
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-muted">Tidak ada data guru</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Tab Administrasi -->
                    <div class="tab-pane fade" id="adminTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllAdmin" onchange="selectAll('admin-checkbox', this.checked)">
                                    Pilih Semua Administrasi
                                </label>
                            </div>
                            <?php $__empty_1 = true; $__currentLoopData = $administrasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($a && $a->user): ?>
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox admin-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="<?php echo e($a->user->id); ?>"
                                           id="admin_<?php echo e($a->user->id); ?>"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="admin_<?php echo e($a->user->id); ?>">
                                        <?php echo e($a->user->name ?? '-'); ?>

                                    </label>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-muted">Tidak ada data administrasi</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3" id="selectedInfo" style="display: none;">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <span id="totalCount">0</span> penerima telah dipilih
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" required><?php echo e(old('isi')); ?></textarea>
                <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_urgent" id="isPenting" value="1">
                    <label class="form-check-label" for="isPenting">
                        Tandai sebagai pesan penting
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-primary" id="btnKirim">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Fungsi update jumlah penerima yang dipilih
function updateCount() {
    // Hitung semua checkbox yang tercentang
    var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
    
    // Hitung per kategori
    var siswaChecked = document.querySelectorAll('.siswa-checkbox:checked').length;
    var guruChecked = document.querySelectorAll('.guru-checkbox:checked').length;
    var adminChecked = document.querySelectorAll('.admin-checkbox:checked').length;
    
    // Update badge
    document.getElementById('siswaCount').innerText = siswaChecked;
    document.getElementById('guruCount').innerText = guruChecked;
    document.getElementById('adminCount').innerText = adminChecked;
    document.getElementById('totalCount').innerText = totalChecked;
    
    // Tampilkan/sembunyikan info
    var selectedInfo = document.getElementById('selectedInfo');
    if (totalChecked > 0) {
        selectedInfo.style.display = 'block';
        document.getElementById('penerimaError').style.display = 'none';
    } else {
        selectedInfo.style.display = 'none';
    }
    
    // Update status checkbox "Pilih Semua"
    updateSelectAllStatus();
    
    // Debug
    console.log('Total checked:', totalChecked);
}

// Fungsi untuk select all per kategori
function selectAll(className, isChecked) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(cb) {
        cb.checked = isChecked;
    });
    updateCount();
}

// Update status checkbox "Pilih Semua"
function updateSelectAllStatus() {
    // Siswa
    var siswaTotal = document.querySelectorAll('.siswa-checkbox').length;
    var siswaChecked = document.querySelectorAll('.siswa-checkbox:checked').length;
    var selectAllSiswa = document.getElementById('selectAllSiswa');
    if (selectAllSiswa && siswaTotal > 0) {
        selectAllSiswa.checked = (siswaTotal === siswaChecked);
    }
    
    // Guru
    var guruTotal = document.querySelectorAll('.guru-checkbox').length;
    var guruChecked = document.querySelectorAll('.guru-checkbox:checked').length;
    var selectAllGuru = document.getElementById('selectAllGuru');
    if (selectAllGuru && guruTotal > 0) {
        selectAllGuru.checked = (guruTotal === guruChecked);
    }
    
    // Admin
    var adminTotal = document.querySelectorAll('.admin-checkbox').length;
    var adminChecked = document.querySelectorAll('.admin-checkbox:checked').length;
    var selectAllAdmin = document.getElementById('selectAllAdmin');
    if (selectAllAdmin && adminTotal > 0) {
        selectAllAdmin.checked = (adminTotal === adminChecked);
    }
}

// Toggle section penerima berdasarkan tipe pesan
function togglePenerimaSection() {
    var individual = document.getElementById('tipeIndividual');
    var broadcast = document.getElementById('tipeBroadcast');
    var section = document.getElementById('penerimaSection');
    
    if (broadcast && broadcast.checked) {
        section.style.display = 'none';
    } else {
        section.style.display = 'block';
    }
}

// Validasi sebelum submit
function validateForm() {
    var broadcast = document.getElementById('tipeBroadcast');
    
    // Jika broadcast, tidak perlu validasi penerima
    if (broadcast && broadcast.checked) {
        return true;
    }
    
    // Jika individual, cek apakah ada penerima terpilih
    var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
    
    if (totalChecked === 0) {
        document.getElementById('penerimaError').style.display = 'block';
        document.getElementById('penerimaSection').scrollIntoView({ behavior: 'smooth' });
        return false;
    }
    
    return true;
}

// Reset form
function resetForm() {
    var checkboxes = document.querySelectorAll('input[name="penerima_id[]"]');
    checkboxes.forEach(function(cb) {
        cb.checked = false;
    });
    updateCount();
    document.getElementById('penerimaError').style.display = 'none';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup radio button event
    var individual = document.getElementById('tipeIndividual');
    var broadcast = document.getElementById('tipeBroadcast');
    
    if (individual) {
        individual.addEventListener('change', togglePenerimaSection);
    }
    if (broadcast) {
        broadcast.addEventListener('change', togglePenerimaSection);
    }
    
    // Setup form submit
    var form = document.getElementById('formKirimPesan');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            } else {
                var btn = document.getElementById('btnKirim');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            }
        });
    }
    
    // Initial update
    togglePenerimaSection();
    updateCount();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/komunikasi/create.blade.php ENDPATH**/ ?>