@extends('kepala-sekolah.layouts.header')

@section('title', 'Laporan Kinerja Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-star me-2"></i>
        Laporan Kinerja Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="#" class="btn btn-sm btn-success" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </a>
        <a href="#" class="btn btn-sm btn-danger ms-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach(range(now()->year, now()->year-5) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-control">
                    <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Kinerja -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Rata-rata Nilai Kinerja</h6>
                <h3>{{ round($guru->avg('nilaiKinerja'), 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Rata-rata Kehadiran</h6>
                <h3>{{ round($guru->avg('kehadiran'), 2) }}%</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Mapel Diajar</h6>
                <h3>{{ $guru->sum('jumlahMapel') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Total Siswa Diajar</h6>
                <h3>{{ $guru->sum('jumlahSiswa') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Kinerja Guru -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Kinerja Guru Tahun {{ $tahun }} Semester {{ ucfirst($semester) }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Mapel Diajar</th>
                        <th>Kelas Diajar</th>
                        <th>Jml Siswa</th>
                        <th>Rata Nilai</th>
                        <th>Kehadiran</th>
                        <th>Nilai Kinerja</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru as $g)
                    @php
                        $grade = 'E';
                        if($g->nilaiKinerja >= 90) $grade = 'A';
                        elseif($g->nilaiKinerja >= 80) $grade = 'B';
                        elseif($g->nilaiKinerja >= 70) $grade = 'C';
                        elseif($g->nilaiKinerja >= 60) $grade = 'D';
                        
                        $gradeColor = [
                            'A' => 'success',
                            'B' => 'info',
                            'C' => 'primary',
                            'D' => 'warning',
                            'E' => 'danger'
                        ][$grade];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $g->user->name }}</strong></td>
                        <td>{{ $g->nip }}</td>
                        <td class="text-center">{{ $g->jumlahMapel }}</td>
                        <td class="text-center">{{ $g->jumlahKelas }}</td>
                        <td class="text-center">{{ $g->jumlahSiswa }}</td>
                        <td class="text-center">{{ round($g->rataNilai, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $g->kehadiran >= 90 ? 'success' : ($g->kehadiran >= 75 ? 'warning' : 'danger') }}">
                                {{ $g->kehadiran }}%
                            </span>
                        </td>
                        <td class="text-center"><strong>{{ $g->nilaiKinerja }}</strong></td>
                        <td class="text-center">
                            <span class="badge bg-{{ $gradeColor }} p-2">{{ $grade }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Grafik Kinerja -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Distribusi Grade Kinerja Guru</h5>
            </div>
            <div class="card-body">
                @php
                    $gradeCount = [
                        'A' => $guru->filter(function($g) { return $g->nilaiKinerja >= 90; })->count(),
                        'B' => $guru->filter(function($g) { return $g->nilaiKinerja >= 80 && $g->nilaiKinerja < 90; })->count(),
                        'C' => $guru->filter(function($g) { return $g->nilaiKinerja >= 70 && $g->nilaiKinerja < 80; })->count(),
                        'D' => $guru->filter(function($g) { return $g->nilaiKinerja >= 60 && $g->nilaiKinerja < 70; })->count(),
                        'E' => $guru->filter(function($g) { return $g->nilaiKinerja < 60; })->count(),
                    ];
                @endphp
                <canvas id="gradeChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top 5 Guru Berkinerja Terbaik</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Nilai Kinerja</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guru->sortByDesc('nilaiKinerja')->take(5) as $g)
                            @php
                                $grade = 'E';
                                if($g->nilaiKinerja >= 90) $grade = 'A';
                                elseif($g->nilaiKinerja >= 80) $grade = 'B';
                                elseif($g->nilaiKinerja >= 70) $grade = 'C';
                                elseif($g->nilaiKinerja >= 60) $grade = 'D';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $g->user->name }}</td>
                                <td><strong>{{ $g->nilaiKinerja }}</strong></td>
                                <td><span class="badge bg-{{ $grade == 'A' ? 'success' : ($grade == 'B' ? 'info' : ($grade == 'C' ? 'primary' : ($grade == 'D' ? 'warning' : 'danger'))) }}">{{ $grade }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Distribusi Grade
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    new Chart(gradeCtx, {
        type: 'pie',
        data: {
            labels: ['Grade A (90+)', 'Grade B (80-89)', 'Grade C (70-79)', 'Grade D (60-69)', 'Grade E (<60)'],
            datasets: [{
                data: {{ json_encode(array_values($gradeCount)) }},
                backgroundColor: ['#28a745', '#17a2b8', '#007bff', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection