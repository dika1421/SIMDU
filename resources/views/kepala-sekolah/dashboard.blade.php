@extends('kepala-sekolah.layouts.header')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .stat-detail {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 25px 30px;
        margin-bottom: 25px;
        color: white;
    }
    
    .activity-item {
        border-left: 3px solid #667eea;
        padding: 10px 15px;
        background: white;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .activity-time {
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    .badge-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    
    .card-modern .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 15px 20px;
        font-weight: 600;
        border-radius: 12px 12px 0 0;
    }
    
    .card-modern .card-body {
        padding: 20px;
    }

    .quick-action {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .quick-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #667eea;
    }
    
    .quick-action i {
        font-size: 2rem;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .quick-action span {
        display: block;
        font-size: 0.8rem;
        color: #2c3e50;
    }
    
    .info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-1">
                <i class="fas fa-user-tie me-2"></i>
                Selamat Datang, {{ Auth::user()->name ?? 'Kepala Sekolah' }}
            </h4>
            <p class="mb-0 opacity-75">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} | 
                <i class="fas fa-clock ms-2 me-1"></i>
                {{ \Carbon\Carbon::now()->format('H:i') }} WIB
            </p>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card" onclick="window.location.href='{{ route('kepala-sekolah.laporan.statistik-siswa') }}'">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number">{{ number_format($totalSiswa ?? 246) }}</div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-detail">
                <i class="fas fa-check-circle text-success me-1"></i> Aktif: {{ $siswaAktif ?? 246 }}
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" onclick="window.location.href='{{ route('kepala-sekolah.manajemen-guru.index') }}'">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="stat-number">{{ number_format($totalGuru ?? 32) }}</div>
            <div class="stat-label">Total Guru</div>
            <div class="stat-detail">
                <i class="fas fa-check-circle text-success me-1"></i> Aktif: {{ $guruAktif ?? 32 }}
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" onclick="window.location.href='{{ route('kepala-sekolah.laporan.absensi') }}'">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number">{{ $kehadiranHariIni ?? 98 }}%</div>
            <div class="stat-label">Kehadiran Hari Ini</div>
            <div class="stat-detail">
                <i class="fas fa-clock me-1"></i> {{ $hadirHariIni ?? 0 }}/{{ $totalKehadiran ?? 0 }}
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" onclick="window.location.href='{{ route('kepala-sekolah.keuangan.laporan') }}'">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">Rp{{ number_format(($totalKeuangan ?? 50000000) / 1000000, 0) }}M</div>
            <div class="stat-label">Total Keuangan</div>
            <div class="stat-detail">
                <i class="fas fa-arrow-up text-success me-1"></i> {{ $pertumbuhanKeuangan ?? 5 }}% bulan ini
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card-modern">
            <div class="card-header">
                <i class="fas fa-history me-2 text-primary"></i>
                Aktivitas Terbaru
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($aktivitasTerbaru ?? [] as $aktivitas)
                        <div class="col-md-6">
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-{{ $aktivitas->icon ?? 'bell' }} text-primary me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small">{{ $aktivitas->deskripsi }}</p>
                                        <span class="activity-time">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($aktivitas->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik dan Informasi -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-modern">
            <div class="card-header">
                <i class="fas fa-chart-line me-2 text-info"></i>
                Statistik Perkembangan
            </div>
            <div class="card-body">
                <canvas id="statistikChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card-modern">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2 text-success"></i>
                Rekap Kehadiran Bulan Ini
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <canvas id="kehadiranChart" style="height: 200px;"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="d-flex justify-content-between">
                                <span>Hadir</span>
                                <strong>{{ $hadirBulanIni ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Sakit</span>
                                <strong>{{ $sakitBulanIni ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Izin</span>
                                <strong>{{ $izinBulanIni ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Alfa</span>
                                <strong>{{ $alfaBulanIni ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-modern">
            <div class="card-header">
                <i class="fas fa-bolt me-2 text-warning"></i>
                Akses Cepat
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-chalkboard-user"></i>
                            <span>Data Guru</span>
                        </a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.laporan.absensi') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-calendar-check"></i>
                            <span>Absensi</span>
                        </a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Persetujuan</span>
                        </a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.laporan.kinerja-guru') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-star"></i>
                            <span>Kinerja Guru</span>
                        </a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.keuangan.laporan') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Keuangan</span>
                        </a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="{{ route('kepala-sekolah.laporan.statistik-siswa') }}" class="quick-action d-block text-decoration-none">
                            <i class="fas fa-users"></i>
                            <span>Statistik</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Statistik
        const ctx = document.getElementById('statistikChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Siswa',
                    data: [230, 235, 240, 242, 245, 246],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }, {
                    label: 'Guru',
                    data: [28, 29, 30, 30, 31, 32],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Chart Kehadiran
        const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
        new Chart(kehadiranCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [
                        {{ $hadirBulanIni ?? 180 }},
                        {{ $sakitBulanIni ?? 15 }},
                        {{ $izinBulanIni ?? 8 }},
                        {{ $alfaBulanIni ?? 5 }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: 8,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection