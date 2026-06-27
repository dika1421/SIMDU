

<?php $__env->startSection('title', 'Dashboard Absensi Sholat'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Animasi */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.5s ease-out;
    }
    
    /* Stat Cards */
    .stat-card-sholat {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-sholat::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .stat-card-sholat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .stat-card-sholat:hover::before {
        left: 100%;
    }
    
    .stat-number-sholat {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 50px;
        opacity: 0.2;
    }
    
    /* Sholat Cards */
    .sholat-card {
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease-out;
    }
    
    .sholat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .sholat-header {
        padding: 15px 20px;
        font-weight: bold;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sholat-subuh .sholat-header { background: linear-gradient(135deg, #1e3799, #4a69bd); }
    .sholat-dzuhur .sholat-header { background: linear-gradient(135deg, #0a8f5e, #1dd1a1); }
    .sholat-ashar .sholat-header { background: linear-gradient(135deg, #e67e22, #f39c12); }
    .sholat-maghrib .sholat-header { background: linear-gradient(135deg, #c0392b, #e74c3c); }
    .sholat-isya .sholat-header { background: linear-gradient(135deg, #8e44ad, #9b59b6); }
    
    .sholat-time {
        background: rgba(255,255,255,0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    /* Status Badges */
    .status-tepat {
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .status-terlambat {
        background: #ffc107;
        color: #333;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .status-tidak {
        background: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    
    /* List Group */
    .list-group-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #667eea;
        transform: translateX(5px);
    }
    
    /* Loading Skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Custom Scrollbar */
    .list-group::-webkit-scrollbar {
        width: 5px;
    }
    
    .list-group::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .list-group::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    /* Jam Digital */
    .live-clock {
        font-size: 14px;
        font-weight: bold;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 25px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Refresh Button */
    .refresh-btn {
        transition: all 0.3s ease;
    }
    
    .refresh-btn:hover {
        transform: rotate(180deg);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <i class="fas fa-mosque me-2"></i>
            Dashboard Absensi Sholat
        </h1>
        <p class="text-muted mb-0 mt-2">
            <i class="fas fa-calendar-alt me-1"></i> 
            <?php echo e(\Carbon\Carbon::parse($tanggal)->format('l, d F Y')); ?>

        </p>
    </div>
    <div class="d-flex gap-2">
        <div class="live-clock" id="liveClock">
            <i class="fas fa-clock me-1"></i>
            <span id="clock"></span>
        </div>
        <a href="<?php echo e(route('administrasi.absensi-sholat.rekap-siswa')); ?>" class="btn btn-info">
            <i class="fas fa-chart-line me-1"></i> Rekap Siswa
        </a>
        <a href="<?php echo e(route('administrasi.absensi-sholat.rekap-guru')); ?>" class="btn btn-info">
            <i class="fas fa-chart-line me-1"></i> Rekap Guru
        </a>
        <button class="btn btn-primary refresh-btn" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter me-1"></i> Filter
        </button>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i> Filter Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo e(request('tanggal', date('Y-m-d'))); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="siswa" <?php echo e(request('role', 'siswa') == 'siswa' ? 'selected' : ''); ?>>
                                <i class="fas fa-user-graduate"></i> Siswa
                            </option>
                            <option value="guru" <?php echo e(request('role') == 'guru' ? 'selected' : ''); ?>>
                                <i class="fas fa-chalkboard-user"></i> Guru
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-sholat animate-fade-in" style="animation-delay: 0.1s">
            <i class="fas fa-users stat-icon"></i>
            <div class="stat-number-sholat"><?php echo e(number_format($statistik['totalUsers'] ?? 0)); ?></div>
            <small>Total <?php echo e(request('role', 'siswa') == 'siswa' ? 'Siswa' : 'Guru'); ?></small>
            <div class="mt-2">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-white" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-sholat animate-fade-in" style="background: linear-gradient(135deg, #28a745, #20c997); animation-delay: 0.2s">
            <i class="fas fa-check-circle stat-icon"></i>
            <div class="stat-number-sholat"><?php echo e(number_format($statistik['tepatWaktu'] ?? 0)); ?></div>
            <small>Tepat Waktu</small>
            <div class="mt-2">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-white" style="width: <?php echo e($statistik['totalUsers'] > 0 ? ($statistik['tepatWaktu'] / $statistik['totalUsers']) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-sholat animate-fade-in" style="background: linear-gradient(135deg, #ffc107, #fd7e14); animation-delay: 0.3s">
            <i class="fas fa-clock stat-icon"></i>
            <div class="stat-number-sholat"><?php echo e(number_format($statistik['terlambat'] ?? 0)); ?></div>
            <small>Terlambat</small>
            <div class="mt-2">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-white" style="width: <?php echo e($statistik['totalUsers'] > 0 ? ($statistik['terlambat'] / $statistik['totalUsers']) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-sholat animate-fade-in" style="background: linear-gradient(135deg, #dc3545, #c82333); animation-delay: 0.4s">
            <i class="fas fa-times-circle stat-icon"></i>
            <div class="stat-number-sholat"><?php echo e(number_format($statistik['tidakHadir'] ?? 0)); ?></div>
            <small>Tidak Hadir</small>
            <div class="mt-2">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-white" style="width: <?php echo e($statistik['totalUsers'] > 0 ? ($statistik['tidakHadir'] / $statistik['totalUsers']) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Sholat -->
<div class="card mb-4 animate-fade-in" style="animation-delay: 0.5s">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-clock me-2"></i> 
        Jadwal Sholat Hari Ini
        <span class="badge bg-light text-dark ms-2" id="nextPrayer"></span>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-light">
                    <i class="fas fa-cloud-moon fa-2x mb-2 text-primary"></i>
                    <h5 class="mb-1">Subuh</h5>
                    <h4 class="text-primary mb-0"><?php echo e($jadwal->subuh ?? '04:30'); ?></h4>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-light">
                    <i class="fas fa-sun fa-2x mb-2 text-warning"></i>
                    <h5 class="mb-1">Dzuhur</h5>
                    <h4 class="text-warning mb-0"><?php echo e($jadwal->dzuhur ?? '12:00'); ?></h4>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-light">
                    <i class="fas fa-sun fa-2x mb-2 text-orange"></i>
                    <h5 class="mb-1">Ashar</h5>
                    <h4 class="text-orange mb-0"><?php echo e($jadwal->ashar ?? '15:30'); ?></h4>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-light">
                    <i class="fas fa-cloud-sun fa-2x mb-2 text-danger"></i>
                    <h5 class="mb-1">Maghrib</h5>
                    <h4 class="text-danger mb-0"><?php echo e($jadwal->maghrib ?? '18:00'); ?></h4>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-light">
                    <i class="fas fa-moon fa-2x mb-2 text-dark"></i>
                    <h5 class="mb-1">Isya</h5>
                    <h4 class="text-dark mb-0"><?php echo e($jadwal->isya ?? '19:30'); ?></h4>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="p-3 rounded-3 bg-primary text-white">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h5 class="mb-1">Kehadiran</h5>
                    <h4 class="mb-0"><?php echo e($statistik['totalUsers'] > 0 ? round((($statistik['tepatWaktu'] + $statistik['terlambat']) / $statistik['totalUsers']) * 100, 1) : 0); ?>%</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Absensi Per Sholat -->
<div class="row">
    <?php $sholatList = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']; ?>
    <?php $__currentLoopData = $sholatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sholat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 col-lg-4">
        <div class="sholat-card sholat-<?php echo e($sholat); ?> animate-fade-in" style="animation-delay: <?php echo e(0.6 + ($index * 0.1)); ?>s">
            <div class="sholat-header">
                <div>
                    <i class="fas fa-mosque me-2"></i> 
                    <?php echo e(ucfirst($sholat)); ?>

                </div>
                <div>
                    <span class="sholat-time">
                        <i class="fas fa-clock me-1"></i>
                        <?php echo e($jadwal->$sholat ?? ($sholat == 'subuh' ? '04:30' : ($sholat == 'dzuhur' ? '12:00' : ($sholat == 'ashar' ? '15:30' : ($sholat == 'maghrib' ? '18:00' : '19:30'))))); ?>

                    </span>
                    <span class="badge bg-light text-dark ms-2">
                        <?php echo e(isset($absensi[$sholat]) ? $absensi[$sholat]->count() : 0); ?>/<?php echo e($statistik['totalUsers'] ?? 0); ?>

                    </span>
                </div>
            </div>
            <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                <?php $__empty_1 = true; $__currentLoopData = ($absensi[$sholat] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                                <?php echo e($role == 'siswa' ? ($absen->user->user->name ?? $absen->user->nama ?? '-') : ($absen->user->user->name ?? $absen->user->nama_lengkap ?? '-')); ?>

                            </strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo e($absen->waktu_absen ? date('H:i:s', strtotime($absen->waktu_absen)) : '-'); ?>

                            </small>
                        </div>
                        <span class="status-<?php echo e($absen->status == 'tepat_waktu' ? 'tepat' : ($absen->status == 'terlambat' ? 'terlambat' : 'tidak')); ?>">
                            <i class="fas <?php echo e($absen->status == 'tepat_waktu' ? 'fa-check-circle' : ($absen->status == 'terlambat' ? 'fa-clock' : 'fa-times-circle')); ?> me-1"></i>
                            <?php echo e($absen->status == 'tepat_waktu' ? 'Tepat Waktu' : ($absen->status == 'terlambat' ? 'Terlambat' : 'Tidak Hadir')); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="list-group-item text-center py-4">
                    <i class="fas fa-info-circle fa-2x text-muted mb-2 d-block"></i>
                    <span class="text-muted">Belum ada absensi</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('clock').textContent = timeString;
    }
    updateClock();
    setInterval(updateClock, 1000);
    
    // Next Prayer Indicator
    function getNextPrayer() {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const currentTime = currentHour * 60 + currentMinute;
        
        const prayers = [
            { name: 'Subuh', time: '04:30', minute: 4*60+30 },
            { name: 'Dzuhur', time: '12:00', minute: 12*60+0 },
            { name: 'Ashar', time: '15:30', minute: 15*60+30 },
            { name: 'Maghrib', time: '18:00', minute: 18*60+0 },
            { name: 'Isya', time: '19:30', minute: 19*60+30 }
        ];
        
        for (let prayer of prayers) {
            if (currentTime < prayer.minute) {
                document.getElementById('nextPrayer').textContent = `Berikutnya: ${prayer.name} ${prayer.time}`;
                return;
            }
        }
        document.getElementById('nextPrayer').textContent = 'Semua sholat hari ini telah berlalu';
    }
    getNextPrayer();
    
    // Auto refresh setiap 60 detik
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrasi.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/administrasi/sholat/sholat.blade.php ENDPATH**/ ?>