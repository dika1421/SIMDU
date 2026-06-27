

<?php $__env->startSection('title', 'Nilai & Raport'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                            <?php $__currentLoopData = $tahunAjaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ta); ?>" <?php echo e($tahunAjaran == $ta ? 'selected' : ''); ?>>
                                    <?php echo e($ta); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" onchange="this.form.submit()">
                            <option value="ganjil" <?php echo e($semester == 'ganjil' ? 'selected' : ''); ?>>Ganjil</option>
                            <option value="genap" <?php echo e($semester == 'genap' ? 'selected' : ''); ?>>Genap</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kurikulum</label>
                        <select name="kurikulum" class="form-select" onchange="this.form.submit()">
                            <?php $__currentLoopData = $kurikulumList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kur); ?>" <?php echo e($kurikulum == $kur ? 'selected' : ''); ?>>
                                    <?php echo e($kur); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <span class="badge bg-secondary p-2">
                                <i class="fas fa-school me-1"></i> 
                                <?php echo e($siswa->kelas->nama ?? '-'); ?> | 
                                <?php echo e($siswa->kelas->jurusan->nama ?? '-'); ?>

                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Siswa -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-graduate fa-2x text-primary me-3"></i>
                            <div>
                                <small class="text-muted">Nama Siswa</small>
                                <h6 class="mb-0"><?php echo e($siswa->nama_lengkap ?? $siswa->user->name ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-address-card fa-2x text-success me-3"></i>
                            <div>
                                <small class="text-muted">NIS / NISN</small>
                                <h6 class="mb-0"><?php echo e($siswa->nis ?? '-'); ?> / <?php echo e($siswa->nisn ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chalkboard fa-2x text-warning me-3"></i>
                            <div>
                                <small class="text-muted">Kelas / Jurusan</small>
                                <h6 class="mb-0"><?php echo e($siswa->kelas->nama ?? '-'); ?> / <?php echo e($siswa->kelas->jurusan->nama ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Rata-rata Nilai</h6>
                <h2 class="mb-0"><?php echo e(number_format($statistik['rata_rata'], 2)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Nilai Tertinggi</h6>
                <h2 class="mb-0"><?php echo e(number_format($statistik['nilai_tertinggi'], 2)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Nilai Terendah</h6>
                <h2 class="mb-0"><?php echo e(number_format($statistik['nilai_terendah'], 2)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Mata Pelajaran</h6>
                <h2 class="mb-0"><?php echo e($statistik['jumlah_mapel']); ?></h2>
                <small>Lulus: <?php echo e($statistik['mapel_lulus']); ?> | Tidak: <?php echo e($statistik['mapel_tidak_lulus']); ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Nilai -->
<?php if(isset($mapelNilai) && $mapelNilai->count() > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Grafik Nilai per Mata Pelajaran</h5>
            </div>
            <div class="card-body">
                <canvas id="nilaiChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Nilai -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Nilai</h5>
                <span class="badge bg-primary"><?php echo e($nilai->count()); ?> Mata Pelajaran</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="nilaiTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelompok</th>
                                <th>Nilai Harian</th>
                                <th>Nilai Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Praktek</th>
                                <th>Nilai Akhir</th>
                                <th>Predikat</th>
                                <th>KKM</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $nilai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $kkm = $n->mataPelajaran->kkm ?? 75;
                                    $status = $n->nilai_akhir >= $kkm ? 'Lulus' : 'Tidak Lulus';
                                    $statusClass = $n->nilai_akhir >= $kkm ? 'text-success' : 'text-danger';
                                    $kelompok = $n->mataPelajaran->kelompok ?? '-';
                                    $kelompokBadge = $kelompok == 'A' ? 'primary' : ($kelompok == 'B' ? 'success' : 'warning');
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="fw-bold"><?php echo e($n->mataPelajaran->nama_mapel ?? '-'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($kelompokBadge); ?>">
                                            <?php echo e($kelompok == 'A' ? 'Umum' : ($kelompok == 'B' ? 'Kejuruan' : 'Muatan Lokal')); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e($n->rata_rata_harian ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($n->rata_rata_tugas ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($n->nilai_uts ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($n->nilai_uas ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($n->nilai_praktek ?? '-'); ?></td>
                                    <td class="text-center fw-bold"><?php echo e(number_format($n->nilai_akhir, 2)); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo e($n->predikat == 'A' ? 'success' : 
                                            ($n->predikat == 'B' ? 'primary' : 
                                            ($n->predikat == 'C' ? 'warning' : 
                                            ($n->predikat == 'D' ? 'info' : 'danger')))); ?>">
                                            <?php echo e($n->predikat); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e($kkm); ?></td>
                                    <td class="text-center <?php echo e($statusClass); ?>">
                                        <i class="fas fa-<?php echo e($status == 'Lulus' ? 'check-circle' : 'times-circle'); ?> me-1"></i>
                                        <?php echo e($status); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($nilai->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada nilai yang dipublish</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="<?php echo e(route('siswa.nilai.raport', ['tahun_ajaran' => $tahunAjaran, 'semester' => $semester])); ?>" 
           class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak Raport
        </a>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable
        if (document.getElementById('nilaiTable')) {
            $('#nilaiTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                order: [[0, 'asc']]
            });
        }
        
        // Chart Nilai
        <?php if(isset($mapelNilai) && $mapelNilai->count() > 0): ?>
        var ctx = document.getElementById('nilaiChart').getContext('2d');
        var chartData = <?php echo json_encode($mapelNilai, 15, 512) ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.mapel),
                datasets: [{
                    label: 'Nilai Akhir',
                    data: chartData.map(item => item.nilai),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }, {
                    label: 'KKM',
                    data: chartData.map(item => item.kkm),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    type: 'line',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: { display: true, text: 'Nilai' }
                    },
                    x: {
                        title: { display: true, text: 'Mata Pelajaran' }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('siswa.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\php\SIMDU\resources\views/siswa/nilai/index.blade.php ENDPATH**/ ?>