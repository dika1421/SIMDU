

<?php $__env->startSection('title', 'Laporan Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Laporan Absensi
    </h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b); ?>" <?php echo e(($bulan ?? now()->month) == $b ? 'selected' : ''); ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($b)->isoFormat('MMMM')); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    <?php $__currentLoopData = range(now()->year, now()->year-5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(($tahun ?? now()->year) == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="#" class="btn btn-success d-block">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Total Hadir</h6>
                <h3><?php echo e($rekapSiswa->sum('hadir') + $rekapGuru->sum('hadir')); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Total Sakit</h6>
                <h3><?php echo e($rekapSiswa->sum('sakit') + $rekapGuru->sum('sakit')); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Total Izin</h6>
                <h3><?php echo e($rekapSiswa->sum('izin') + $rekapGuru->sum('izin')); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h6>Total Alfa</h6>
                <h3><?php echo e($rekapSiswa->sum('alfa') + $rekapGuru->sum('alfa')); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Absensi -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Grafik Absensi</h5>
    </div>
    <div class="card-body">
        <canvas id="absensiChart" style="height: 300px;"></canvas>
    </div>
</div>

<!-- Rekap Siswa -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Siswa per Kelas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Jml Siswa</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rekapSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalSiswa = $r->total;
                        $totalHadir = $r->hadir;
                        $persenKelas = $totalSiswa > 0 ? round(($totalHadir / ($totalSiswa * 20)) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td><?php echo e($r->kelas->nama ?? '-'); ?></td>
                        <td><?php echo e($r->kelas->jurusan->nama ?? '-'); ?></td>
                        <td class="text-center"><?php echo e($r->total); ?></td>
                        <td class="text-center bg-success text-white"><?php echo e($r->hadir); ?></td>
                        <td class="text-center bg-warning"><?php echo e($r->sakit); ?></td>
                        <td class="text-center bg-info"><?php echo e($r->izin); ?></td>
                        <td class="text-center bg-danger text-white"><?php echo e($r->alfa); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo e($persenKelas >= 90 ? 'success' : ($persenKelas >= 75 ? 'warning' : 'danger')); ?> p-2">
                                <?php echo e($persenKelas); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Rekap Guru -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Guru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rekapGuru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalHadirGuru = $g->hadir;
                        $persenGuru = 20 > 0 ? round(($totalHadirGuru / 20) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($g->user->name); ?></td>
                        <td><?php echo e($g->nip); ?></td>
                        <td class="text-center bg-success text-white"><?php echo e($g->hadir); ?></td>
                        <td class="text-center bg-warning"><?php echo e($g->sakit); ?></td>
                        <td class="text-center bg-info"><?php echo e($g->izin); ?></td>
                        <td class="text-center bg-danger text-white"><?php echo e($g->alfa); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo e($persenGuru >= 90 ? 'success' : ($persenGuru >= 75 ? 'warning' : 'danger')); ?> p-2">
                                <?php echo e($persenGuru); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Grafik
    const ctx = document.getElementById('absensiChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($rekapSiswa->pluck('kelas.nama')->toArray()); ?>,
            datasets: [{
                label: 'Hadir',
                data: <?php echo json_encode($rekapSiswa->pluck('hadir')->toArray()); ?>,
                backgroundColor: 'rgba(40, 167, 69, 0.7)'
            }, {
                label: 'Sakit',
                data: <?php echo json_encode($rekapSiswa->pluck('sakit')->toArray()); ?>,
                backgroundColor: 'rgba(255, 193, 7, 0.7)'
            }, {
                label: 'Izin',
                data: <?php echo json_encode($rekapSiswa->pluck('izin')->toArray()); ?>,
                backgroundColor: 'rgba(23, 162, 184, 0.7)'
            }, {
                label: 'Alfa',
                data: <?php echo json_encode($rekapSiswa->pluck('alfa')->toArray()); ?>,
                backgroundColor: 'rgba(220, 53, 69, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kepala-sekolah.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/kepala-sekolah/laporan/absensi.blade.php ENDPATH**/ ?>