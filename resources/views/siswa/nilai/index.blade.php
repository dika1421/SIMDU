@extends('siswa.layouts.header')

@section('title', 'Nilai & Raport')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                            @foreach($tahunAjaranList as $ta)
                                <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>
                                    {{ $ta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" onchange="this.form.submit()">
                            <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kurikulum</label>
                        <select name="kurikulum" class="form-select" onchange="this.form.submit()">
                            @foreach($kurikulumList as $kur)
                                <option value="{{ $kur }}" {{ $kurikulum == $kur ? 'selected' : '' }}>
                                    {{ $kur }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <span class="badge bg-secondary p-2">
                                <i class="fas fa-school me-1"></i> 
                                {{ $siswa->kelas->nama ?? '-' }} | 
                                {{ $siswa->kelas->jurusan->nama ?? '-' }}
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
                                <h6 class="mb-0">{{ $siswa->nama_lengkap ?? $siswa->user->name ?? '-' }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-address-card fa-2x text-success me-3"></i>
                            <div>
                                <small class="text-muted">NIS / NISN</small>
                                <h6 class="mb-0">{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chalkboard fa-2x text-warning me-3"></i>
                            <div>
                                <small class="text-muted">Kelas / Jurusan</small>
                                <h6 class="mb-0">{{ $siswa->kelas->nama ?? '-' }} / {{ $siswa->kelas->jurusan->nama ?? '-' }}</h6>
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
                <h2 class="mb-0">{{ number_format($statistik['rata_rata'], 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Nilai Tertinggi</h6>
                <h2 class="mb-0">{{ number_format($statistik['nilai_tertinggi'], 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Nilai Terendah</h6>
                <h2 class="mb-0">{{ number_format($statistik['nilai_terendah'], 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Mata Pelajaran</h6>
                <h2 class="mb-0">{{ $statistik['jumlah_mapel'] }}</h2>
                <small>Lulus: {{ $statistik['mapel_lulus'] }} | Tidak: {{ $statistik['mapel_tidak_lulus'] }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Nilai -->
@if(isset($mapelNilai) && $mapelNilai->count() > 0)
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
@endif

<!-- Tabel Nilai -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Nilai</h5>
                <span class="badge bg-primary">{{ $nilai->count() }} Mata Pelajaran</span>
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
                            @foreach($nilai as $index => $n)
                                @php
                                    $kkm = $n->mataPelajaran->kkm ?? 75;
                                    $status = $n->nilai_akhir >= $kkm ? 'Lulus' : 'Tidak Lulus';
                                    $statusClass = $n->nilai_akhir >= $kkm ? 'text-success' : 'text-danger';
                                    $kelompok = $n->mataPelajaran->kelompok ?? '-';
                                    $kelompokBadge = $kelompok == 'A' ? 'primary' : ($kelompok == 'B' ? 'success' : 'warning');
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $n->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $kelompokBadge }}">
                                            {{ $kelompok == 'A' ? 'Umum' : ($kelompok == 'B' ? 'Kejuruan' : 'Muatan Lokal') }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $n->rata_rata_harian ?? '-' }}</td>
                                    <td class="text-center">{{ $n->rata_rata_tugas ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_praktek ?? '-' }}</td>
                                    <td class="text-center fw-bold">{{ number_format($n->nilai_akhir, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ 
                                            $n->predikat == 'A' ? 'success' : 
                                            ($n->predikat == 'B' ? 'primary' : 
                                            ($n->predikat == 'C' ? 'warning' : 
                                            ($n->predikat == 'D' ? 'info' : 'danger'))) 
                                        }}">
                                            {{ $n->predikat }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $kkm }}</td>
                                    <td class="text-center {{ $statusClass }}">
                                        <i class="fas fa-{{ $status == 'Lulus' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($nilai->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada nilai yang dipublish</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('siswa.nilai.raport', ['tahun_ajaran' => $tahunAjaran, 'semester' => $semester]) }}" 
           class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak Raport
        </a>
    </div>
</div>

@push('scripts')
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
        @if(isset($mapelNilai) && $mapelNilai->count() > 0)
        var ctx = document.getElementById('nilaiChart').getContext('2d');
        var chartData = @json($mapelNilai);
        
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
        @endif
    });
</script>
@endpush
@endsection