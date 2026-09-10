@extends('administrasi.layouts.header')

@section('title', 'Dashboard Administrasi')

@section('content')
{{-- ==================== HEADER ==================== --}}
<div class="dash-header d-flex justify-content-between flex-wrap align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold text-dark">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Dashboard Administrasi
        </h1>
        <p class="text-muted mb-0 small">
            <i class="fas fa-calendar-day me-1"></i>
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    <button type="button" class="btn btn-refresh" onclick="window.location.reload()">
        <i class="fas fa-sync-alt me-1"></i> Refresh
    </button>
</div>

{{-- ==================== STATISTIK UTAMA ==================== --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa ?? 0 }}</div>
                <div class="stat-sub">
                    <i class="fas fa-check-circle text-success me-1"></i>
                    Aktif: <strong>{{ $siswaAktif ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Total Guru</div>
                <div class="stat-value">{{ $totalGuru ?? 0 }}</div>
                <div class="stat-sub">
                    <i class="fas fa-id-badge text-success me-1"></i>
                    PNS: <strong>{{ $guruPNS ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-pink">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Pembayaran Bulan Ini</div>
                <div class="stat-value stat-value-sm">
                    Rp {{ number_format($pembayaranBulanIni ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-sub">
                    <i class="fas fa-receipt text-danger me-1"></i>
                    SPP: <strong>{{ $sppBulanIni ?? 0 }}</strong> siswa
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Kehadiran Hari Ini</div>
                <div class="stat-value">{{ $kehadiranPersen ?? 0 }}%</div>
                <div class="stat-sub">
                    <i class="fas fa-users text-info me-1"></i>
                    Siswa: <strong>{{ $hadirSiswa ?? 0 }}/{{ $totalSiswa ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== ROW 2: PEMBAYARAN & ABSENSI ==================== --}}
<div class="row g-3 mb-4">
    {{-- Pembayaran Terbaru --}}
    <div class="col-lg-6">
        <div class="card modern-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="card-title-text">
                    <i class="fas fa-money-bill-wave text-primary me-2"></i>
                    Pembayaran SPP Terbaru
                </span>
                <a href="{{ route('administrasi.keuangan.spp') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table mb-0">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Bulan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembayaranTerbaru ?? [] as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($p->siswa->user->name ?? $p->siswa->nama_lengkap ?? $p->siswa->nama ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="fw-500">
                                            {{ $p->siswa->user->name ?? $p->siswa->nama_lengkap ?? $p->siswa->nama ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $p->bulan ?? '-' }}</span></td>
                                <td class="fw-600">Rp {{ number_format($p->jumlah ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge badge-soft-success">
                                            <i class="fas fa-check-circle me-1"></i>Lunas
                                        </span>
                                    @else
                                        <span class="badge badge-soft-warning">
                                            <i class="fas fa-clock me-1"></i>Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>
                                    <small>Tidak ada data pembayaran</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Absensi Hari Ini --}}
    <div class="col-lg-6">
        <div class="card modern-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="card-title-text">
                    <i class="fas fa-calendar-check text-success me-2"></i>
                    Absensi Hari Ini
                </span>
                <a href="{{ route('administrasi.absensi.siswa') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-edit me-1"></i> Input Absensi
                </a>
            </div>
            <div class="card-body">
                <div class="row g-2 text-center mb-3">
                    <div class="col-3">
                        <div class="abs-box abs-hadir">
                            <div class="abs-icon"><i class="fas fa-user-check"></i></div>
                            <div class="abs-count">{{ $hadirSiswa ?? 0 }}</div>
                            <div class="abs-label">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="abs-box abs-sakit">
                            <div class="abs-icon"><i class="fas fa-thermometer-half"></i></div>
                            <div class="abs-count">{{ $sakitSiswa ?? 0 }}</div>
                            <div class="abs-label">Sakit</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="abs-box abs-izin">
                            <div class="abs-icon"><i class="fas fa-envelope-open-text"></i></div>
                            <div class="abs-count">{{ $izinSiswa ?? 0 }}</div>
                            <div class="abs-label">Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="abs-box abs-alfa">
                            <div class="abs-icon"><i class="fas fa-user-times"></i></div>
                            <div class="abs-count">{{ $alfaSiswa ?? 0 }}</div>
                            <div class="abs-label">Alfa</div>
                        </div>
                    </div>
                </div>

                @php
                    $total = $totalSiswa ?? 1;
                    $hadir = $hadirSiswa ?? 0;
                    $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                    $belumAbsen = ($totalSiswa ?? 0) - (($hadirSiswa ?? 0) + ($sakitSiswa ?? 0) + ($izinSiswa ?? 0) + ($alfaSiswa ?? 0));
                @endphp

                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">
                        Jumlah Siswa: <strong class="text-dark">{{ $totalSiswa ?? 0 }}</strong>
                    </span>
                    <span class="text-muted">
                        Belum Absen: <strong class="text-danger">{{ max(0, $belumAbsen) }}</strong>
                    </span>
                </div>
                <div class="progress modern-progress" style="height: 10px;">
                    <div class="progress-bar bg-gradient-success"
                         role="progressbar"
                         style="width: {{ $persen }}%;"
                         aria-valuenow="{{ $persen }}"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small class="text-muted">Kehadiran</small>
                    <small class="fw-bold text-success">{{ $persen }}%</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== JADWAL HARI INI ==================== --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-header">
                <span class="card-title-text">
                    <i class="fas fa-calendar-alt text-warning me-2"></i>
                    Jadwal Pelajaran Hari Ini
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Jam</th>
                                <th style="width: 15%;">Kelas</th>
                                <th style="width: 30%;">Mata Pelajaran</th>
                                <th style="width: 25%;">Guru</th>
                                <th style="width: 15%;">Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni ?? [] as $j)
                            <tr>
                                <td>
                                    <span class="badge badge-soft-primary">
                                        <i class="fas fa-clock me-1"></i>
                                        @if($j->jam_mulai && $j->jam_selesai)
                                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                        @else
                                            {{ $j->jam_mulai ?? '-' }} - {{ $j->jam_selesai ?? '-' }}
                                        @endif
                                    </span>
                                </td>
                                <td><strong>{{ $j->kelas->nama_kelas ?? $j->kelas->nama ?? '-' }}</strong></td>
                                <td>{{ $j->mataPelajaran->nama_mapel ?? $j->mapel->nama_mapel ?? $j->mapel->nama ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie text-muted me-2"></i>
                                        {{ $j->guru->nama_lengkap ?? $j->guru->user->name ?? $j->guru->nama ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-door-open me-1"></i>
                                        {{ $j->ruangan ?? $j->ruang ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x d-block mb-3 opacity-25"></i>
                                    <p class="mb-0">Tidak ada jadwal hari ini</p>
                                    <small>Jadwal pelajaran akan muncul di sini</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== GRAFIK ==================== --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card modern-card">
            <div class="card-header">
                <span class="card-title-text">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Statistik Kehadiran Bulan Ini
                </span>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card modern-card">
            <div class="card-header">
                <span class="card-title-text">
                    <i class="fas fa-chart-pie text-danger me-2"></i>
                    Distribusi Siswa per Kelas
                </span>
            </div>
            <div class="card-body">
                <canvas id="kelasChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ==================== STYLE KHUSUS DASHBOARD ==================== --}}
@push('styles')
<style>
    /* Header */
    .dash-header {
        background: #fff;
        border-radius: 14px;
        padding: 18px 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .btn-refresh {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-refresh:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-1px);
    }

    /* Stat Card */
    .stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: 0.06;
        background: currentColor;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stat-primary { border-left-color: #667eea; color: #667eea; }
    .stat-success { border-left-color: #11998e; color: #11998e; }
    .stat-pink    { border-left-color: #f5576c; color: #f5576c; }
    .stat-info    { border-left-color: #4facfe; color: #4facfe; }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        flex-shrink: 0;
    }
    .stat-primary .stat-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-success .stat-icon { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .stat-pink    .stat-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-info    .stat-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }

    .stat-body { flex: 1; min-width: 0; }
    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
        margin-bottom: 6px;
    }
    .stat-value-sm { font-size: 1.15rem; }
    .stat-sub {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Modern Card */
    .modern-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }
    .modern-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
    }
    .card-title-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    /* Modern Table */
    .modern-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .modern-table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .modern-table tbody tr {
        transition: background 0.15s;
    }
    .modern-table tbody tr:hover {
        background: #f8fafc;
    }
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }

    /* Avatar */
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Soft Badges */
    .badge-soft-success {
        background: #d1fae5;
        color: #065f46;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #92400e;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
    }
    .badge-soft-primary {
        background: #dbeafe;
        color: #1e40af;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
    }

    /* Absensi Box */
    .abs-box {
        padding: 14px 8px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.25s;
        border: 1px solid transparent;
    }
    .abs-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }
    .abs-icon {
        font-size: 1.2rem;
        margin-bottom: 6px;
        opacity: 0.8;
    }
    .abs-count {
        font-size: 1.4rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 4px;
    }
    .abs-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.85;
    }
    .abs-hadir { background: #d1fae5; color: #065f46; }
    .abs-sakit { background: #fef3c7; color: #92400e; }
    .abs-izin  { background: #cffafe; color: #155e75; }
    .abs-alfa  { background: #fee2e2; color: #991b1b; }

    /* Modern Progress */
    .modern-progress {
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #10b981, #34d399);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dash-header { padding: 14px 16px; }
        .stat-card { padding: 16px; gap: 12px; }
        .stat-icon { width: 48px; height: 48px; font-size: 1.2rem; }
        .stat-value { font-size: 1.4rem; }
        .stat-value-sm { font-size: 1rem; }
        .modern-table tbody td,
        .modern-table thead th { padding: 10px 12px; }
    }
</style>
@endpush

{{-- ==================== SCRIPT ==================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 🔥 Chart Kehadiran
    var ctx1 = document.getElementById('kehadiranChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [
                    {{ $hadirSiswa ?? 0 }},
                    {{ $sakitSiswa ?? 0 }},
                    {{ $izinSiswa ?? 0 }},
                    {{ $alfaSiswa ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: ['#10b981', '#f59e0b', '#06b6d4', '#ef4444'],
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
                    grid: { color: '#f1f5f9', drawBorder: false }
                },
                x: {
                    ticks: { color: '#64748b', font: { size: 12, weight: '600' } },
                    grid: { display: false }
                }
            }
        }
    });

    // 🔥 Chart Distribusi Kelas
    var ctx2 = document.getElementById('kelasChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kelasLabels ?? ['Belum Ada Data']) !!},
            datasets: [{
                data: {!! json_encode($kelasData ?? [1]) !!},
                backgroundColor: [
                    '#667eea', '#11998e', '#f5576c',
                    '#4facfe', '#f59e0b', '#8b5cf6'
                ],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        padding: 12,
                        font: { size: 12 },
                        color: '#475569',
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });
});
</script>
@endpush
@endsection