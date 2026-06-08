@extends('guru.layouts.header')

@section('title', 'Profil Kinerja')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Profil Kinerja Guru
    </h1>
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
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Info Guru -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 text-center">
                <i class="fas fa-user-circle fa-4x text-primary"></i>
            </div>
            <div class="col-md-10">
                <h4>{{ $guru->user->name }}</h4>
                <p>
                    NIP: {{ $guru->nip }} | 
                    Jabatan: {{ $guru->jabatan->nama ?? '-' }} |
                    Status: {{ ucfirst($guru->status_kepegawaian) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Rata-rata Nilai</h6>
                <h2>{{ round($rataNilai, 2) }}</h2>
                <small>Dari {{ $totalNilai }} nilai</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Tingkat Kehadiran</h6>
                <h2>{{ $persenHadir }}%</h2>
                <small>Hadir: {{ $statistikAbsensi['hadir'] }} hari</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Mapel Diajar</h6>
                <h2>{{ $mapel->count() }}</h2>
                <small>Mata Pelajaran</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik Nilai -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Rata-rata Nilai per Bulan - {{ $tahun }}</h5>
            </div>
            <div class="card-body">
                <canvas id="nilaiChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Distribusi Nilai -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Distribusi Nilai</h5>
            </div>
            <div class="card-body">
                <canvas id="distribusiChart" style="height: 250px;"></canvas>
                <div class="mt-3">
                    <small class="text-muted">A (90-100): {{ $distribusiNilai['A'] }}</small><br>
                    <small class="text-muted">B (80-89): {{ $distribusiNilai['B'] }}</small><br>
                    <small class="text-muted">C (70-79): {{ $distribusiNilai['C'] }}</small><br>
                    <small class="text-muted">D (60-69): {{ $distribusiNilai['D'] }}</small><br>
                    <small class="text-muted">E (<60): {{ $distribusiNilai['E'] }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Absensi -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statistik Absensi - {{ $tahun }}</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2">
                        <div class="p-3 bg-success text-white rounded">
                            <h3>{{ $statistikAbsensi['hadir'] }}</h3>
                            <small>Hadir</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-warning text-white rounded">
                            <h3>{{ $statistikAbsensi['sakit'] }}</h3>
                            <small>Sakit</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-info text-white rounded">
                            <h3>{{ $statistikAbsensi['izin'] }}</h3>
                            <small>Izin</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-danger text-white rounded">
                            <h3>{{ $statistikAbsensi['alfa'] }}</h3>
                            <small>Alfa</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-secondary text-white rounded">
                            <h3>{{ $statistikAbsensi['terlambat'] }}</h3>
                            <small>Terlambat</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-primary text-white rounded">
                            <h3>{{ $statistikAbsensi['hadir'] + $statistikAbsensi['sakit'] + $statistikAbsensi['izin'] + $statistikAbsensi['alfa'] }}</h3>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Nilai per Bulan
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Rata-rata Nilai',
                data: {{ json_encode($nilaiPerBulan) }},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Grafik Distribusi Nilai
    const ctx2 = document.getElementById('distribusiChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'E (<60)'],
            datasets: [{
                data: [
                    {{ $distribusiNilai['A'] }},
                    {{ $distribusiNilai['B'] }},
                    {{ $distribusiNilai['C'] }},
                    {{ $distribusiNilai['D'] }},
                    {{ $distribusiNilai['E'] }}
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545']
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