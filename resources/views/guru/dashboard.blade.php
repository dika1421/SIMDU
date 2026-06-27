@extends('guru.layouts.header')

@section('title', 'Dashboard Guru')

@section('content')
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 16px;
        border: none;
        background: white;
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .stat-card:hover::after {
        opacity: 1;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.15);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
    }
    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
    }
    .welcome-card {
        background: var(--gradient);
        border-radius: 20px;
        border: none;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 50%;
        height: 200%;
        background: rgba(255,255,255,0.05);
        transform: rotate(25deg);
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 30%;
        height: 150%;
        background: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
    }
    .welcome-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        backdrop-filter: blur(10px);
        flex-shrink: 0;
    }
    .schedule-item {
        border-left: 4px solid var(--primary);
        padding: 14px 18px;
        border-radius: 10px;
        background: #f8f9fa;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        position: relative;
    }
    .schedule-item:hover {
        background: #eef2ff;
        transform: translateX(6px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }
    .schedule-time {
        background: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary);
        border: 1px solid #e9ecef;
    }
    .attendance-box {
        padding: 14px 10px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 1px solid transparent;
    }
    .attendance-box:hover {
        transform: scale(1.05);
        border-color: currentColor;
    }
    .attendance-box .number {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
    }
    .attendance-box .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 500;
    }
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .card-modern:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .card-modern .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .progress-sm {
        height: 6px;
        border-radius: 10px;
        background: #e9ecef;
    }
    .progress-sm .progress-bar {
        border-radius: 10px;
    }
    .btn-action {
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .btn-action-primary {
        background: var(--gradient);
        color: white;
    }
    .btn-action-primary:hover {
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }
    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .breadcrumb-modern .breadcrumb-item {
        font-size: 0.9rem;
    }
    .breadcrumb-modern .breadcrumb-item.active {
        color: var(--primary);
        font-weight: 600;
    }
    .badge-status {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .text-gradient {
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    @media (max-width: 768px) {
        .stat-number { font-size: 1.5rem; }
        .grid-2 { grid-template-columns: 1fr; }
        .welcome-avatar { width: 48px; height: 48px; font-size: 1.4rem; }
    }
</style>

<!-- Page Title -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <div>
        <h1 class="h2 fw-bold mb-0">
            <i class="fas fa-grip-lines me-2 text-primary" style="font-size: 1.2rem;"></i>
            Dashboard Guru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-modern">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="window.location.reload()">
            <i class="fas fa-sync-alt me-1"></i> Refresh
        </button>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3">
                            <div class="welcome-avatar">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold">Selamat Datang, {{ Auth::user()->name ?? 'Guru' }}!</h4>
                                <p class="mb-0 opacity-75" style="font-weight: 300;">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                                    <span class="mx-2">|</span>
                                    <i class="far fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::now()->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="badge-status text-white">
                            <i class="fas fa-circle me-1" style="font-size: 8px; color: #52d726;"></i>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Kelas</div>
                        <div class="stat-number mt-1">{{ $totalKelas ?? 0 }}</div>
                        <small class="text-success mt-1 d-block">
                            <i class="fas fa-arrow-up me-1"></i> {{ $kelasBulanIni ?? 0 }} bulan ini
                        </small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-number mt-1">{{ $totalSiswa ?? 0 }}</div>
                        <small class="text-info mt-1 d-block">
                            <i class="fas fa-users me-1"></i> {{ $siswaPerKelas ?? 0 }} per kelas
                        </small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Mata Pelajaran</div>
                        <div class="stat-number mt-1">{{ $totalMapel ?? 0 }}</div>
                        <small class="text-success mt-1 d-block">
                            <i class="fas fa-check-circle me-1"></i> Aktif
                        </small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Rata-rata Nilai</div>
                        <div class="stat-number mt-1">{{ number_format($rataNilai ?? 0, 1) }}</div>
                        <div class="progress progress-sm mt-2" style="width: 100%;">
                            @php $persentase = min(($rataNilai ?? 0), 100); @endphp
                            <div class="progress-bar bg-{{ $persentase >= 75 ? 'success' : 'warning' }}" 
                                 style="width: {{ $persentase }}%"></div>
                        </div>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4">
    <!-- Jadwal Hari Ini -->
    <div class="col-lg-7">
        <div class="card card-modern h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    <span>Jadwal Mengajar Hari Ini</span>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
            <div class="card-body pt-3">
                @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                    @foreach($jadwalHariIni as $j)
                        <div class="schedule-item">
                            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <span class="schedule-time">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                        </span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                            {{ $j->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </div>
                                    <h6 class="mb-0 fw-semibold">{{ $j->mapel->nama_mapel ?? 'Mata Pelajaran' }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-door-open me-1"></i> {{ $j->ruangan ?? 'Ruang 1' }}
                                    </small>
                                </div>
                                <a href="{{ route('guru.absensi.index') }}" class="btn btn-sm btn-action btn-action-primary rounded-pill px-3">
                                    <i class="fas fa-check-circle me-1"></i> Absen
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-calendar-times fa-3x text-muted opacity-25"></i>
                        </div>
                        <h5 class="text-muted">Tidak ada jadwal hari ini</h5>
                        <p class="text-muted small">Selamat beristirahat! 🎉</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Absensi Hari Ini -->
    <div class="col-lg-5">
        <div class="card card-modern h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-fingerprint me-2 text-success"></i>
                    <span>Absensi Hari Ini</span>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                    {{ ($hadirHariIni ?? 0) + ($sakitHariIni ?? 0) + ($izinHariIni ?? 0) + ($alfaHariIni ?? 0) }} Siswa
                </span>
            </div>
            <div class="card-body pt-3">
                <div class="row g-2 mb-3">
                    <div class="col-3">
                        <div class="attendance-box text-success">
                            <div class="number text-success">{{ $hadirHariIni ?? 0 }}</div>
                            <div class="label">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="attendance-box text-warning">
                            <div class="number text-warning">{{ $sakitHariIni ?? 0 }}</div>
                            <div class="label">Sakit</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="attendance-box text-info">
                            <div class="number text-info">{{ $izinHariIni ?? 0 }}</div>
                            <div class="label">Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="attendance-box text-danger">
                            <div class="number text-danger">{{ $alfaHariIni ?? 0 }}</div>
                            <div class="label">Alfa</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Persentase Kehadiran</span>
                        <span class="fw-bold text-success">{{ $persentaseKehadiran ?? 0 }}%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: {{ $persentaseKehadiran ?? 0 }}%"></div>
                    </div>
                </div>

                <div class="grid-2">
                    <a href="{{ route('guru.absensi.index') }}" class="btn btn-action btn-action-primary rounded-pill text-center">
                        <i class="fas fa-plus-circle me-1"></i> Input
                    </a>
                    <a href="{{ route('guru.absensi.riwayat') }}" class="btn btn-action btn-outline-secondary rounded-pill text-center">
                        <i class="fas fa-history me-1"></i> Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Nilai -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-line me-2 text-info"></i>
                    <span>Rata-rata Nilai per Kelas</span>
                </div>
                <span class="badge bg-light text-dark rounded-pill px-3">
                    <i class="far fa-calendar me-1"></i> Semester Ini
                </span>
            </div>
            <div class="card-body">
                <canvas id="nilaiChart" style="height: 260px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    
    const labels = {!! json_encode($chartLabels ?? ['Belum Ada Data']) !!};
    const data = {!! json_encode($chartData ?? [0]) !!};
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
    gradient.addColorStop(1, 'rgba(118, 75, 162, 0.8)');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: data,
                backgroundColor: gradient,
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8,
                barPercentage: 0.6,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: { weight: '600' },
                    callbacks: {
                        label: function(context) {
                            return 'Nilai: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        font: { size: 12 }
                    },
                    title: {
                        display: true,
                        text: 'Rata-rata Nilai',
                        font: { weight: '600', size: 12 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 12, weight: '500' }
                    },
                    title: {
                        display: true,
                        text: 'Kelas',
                        font: { weight: '600', size: 12 }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection