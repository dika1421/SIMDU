@extends('siswa.layouts.header')

@section('title', 'Dashboard Siswa')

@section('content')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(30deg);
    }
    .stat-card {
        border-radius: 20px;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .stat-card .icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 3rem;
        opacity: 0.3;
    }
    .stat-card .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .gradient-blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .gradient-green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .gradient-orange {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .gradient-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .card-modern:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .table-modern th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        border: none;
        padding: 12px;
    }
    .table-modern td {
        padding: 12px;
        vertical-align: middle;
    }
    .event-item {
        border-left: 4px solid #667eea;
        padding: 12px 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .event-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    .event-date {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .event-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .badge-modern {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .pulse {
        animation: pulse 2s infinite;
    }
    
    /* ===== PERBAIKAN LAYOUT ===== */
    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    /* Statistik Cards - Pastikan 4 kolom sejajar */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    /* Quick Actions - 4 tombol sejajar */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    
    /* Nilai & Event - 2 kolom side by side */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    /* Statistik Kehadiran - full width */
    .attendance-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
        align-items: center;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        .attendance-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .stat-grid {
            grid-template-columns: 1fr 1fr;
        }
        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
        .quick-actions-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="dashboard-wrapper">
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $siswa->nama_lengkap ?? 'Siswa' }}</h2>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-id-card me-1"></i> NIS: {{ $siswa->nis ?? '-' }} 
                                    | <i class="fas fa-users me-1"></i> Kelas: {{ $siswa->kelas->nama ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="rounded-circle bg-white bg-opacity-25 p-3 d-inline-block">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <p class="mt-2 mb-0 opacity-75">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards (4 kolom sejajar) -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card gradient-blue text-white p-3">
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="position-relative">
                    <p class="stat-label">Rata-rata Nilai</p>
                    <h2 class="stat-value">{{ number_format($rataNilai ?? 0, 2) }}</h2>
                    <small class="opacity-75">
                        <i class="fas fa-arrow-up me-1"></i> Dari {{ $nilaiTerbaru->count() ?? 0 }} Mata Pelajaran
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card gradient-green text-white p-3">
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="position-relative">
                    <p class="stat-label">Kehadiran Bulan Ini</p>
                    <h2 class="stat-value">{{ $persentaseKehadiran ?? 0 }}%</h2>
                    <div class="progress bg-white bg-opacity-25 mt-2" style="height: 5px;">
                        <div class="progress-bar bg-white" style="width: {{ $persentaseKehadiran ?? 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-1 d-block">
                        <i class="fas fa-user-check me-1"></i> {{ $statistikAbsensi['hadir'] ?? 0 }} Hadir | 
                        <i class="fas fa-user-clock me-1"></i> {{ $statistikAbsensi['sakit'] ?? 0 }} Sakit
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card gradient-orange text-white p-3">
                <div class="icon">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div class="position-relative">
                    <p class="stat-label">Absensi Hari Ini</p>
                    <h2 class="stat-value">
                        @if(isset($absensiHariIni) && $absensiHariIni)
                            <span class="pulse">
                                @if($absensiHariIni->status == 'hadir')
                                    <i class="fas fa-check-circle"></i> Hadir
                                @elseif($absensiHariIni->status == 'sakit')
                                    <i class="fas fa-thermometer"></i> Sakit
                                @elseif($absensiHariIni->status == 'izin')
                                    <i class="fas fa-envelope"></i> Izin
                                @else
                                    <i class="fas fa-times-circle"></i> {{ ucfirst($absensiHariIni->status) }}
                                @endif
                            </span>
                        @else
                            <span class="pulse">
                                <i class="fas fa-clock"></i> Belum Absen
                            </span>
                        @endif
                    </h2>
                    @if(isset($absensiHariIni) && $absensiHariIni && $absensiHariIni->waktu_masuk)
                        <small class="opacity-75">
                            <i class="fas fa-clock me-1"></i> {{ \Carbon\Carbon::parse($absensiHariIni->waktu_masuk)->format('H:i') }} WIB
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card gradient-purple text-white p-3">
                <div class="icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="position-relative">
                    <p class="stat-label">Peringkat</p>
                    <h2 class="stat-value">
                        <i class="fas fa-medal"></i> {{ $peringkat ?? '-' }}
                    </h2>
                    <small class="opacity-75">
                        <i class="fas fa-users me-1"></i> Di Kelas {{ $siswa->kelas->nama ?? '-' }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions (4 tombol sejajar) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern card">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Akses Cepat
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('siswa.nilai.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded-3 bg-light">
                                    <i class="fas fa-book fa-2x text-primary mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Lihat Nilai</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('siswa.absensi.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded-3 bg-light">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Absensi Saya</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('siswa.pembayaran.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded-3 bg-light">
                                    <i class="fas fa-credit-card fa-2x text-info mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Info Pembayaran</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('siswa.kalender.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded-3 bg-light">
                                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Kalender</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Terbaru & Event Mendatang (2 kolom side by side) -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-modern card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-simple me-2 text-primary"></i>
                            Nilai Terbaru
                        </h5>
                        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if(isset($nilaiTerbaru) && $nilaiTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern table-hover">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th class="text-end">Nilai</th>
                                        <th class="text-center">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilaiTerbaru as $n)
                                    <tr>
                                        <td class="fw-bold">{{ $n->mataPelajaran->nama_mapel ?? '-' }}</td>
                                        <td class="text-end">
                                            <span class="fw-bold">{{ number_format($n->nilai_akhir, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $grade = match(true) {
                                                    $n->nilai_akhir >= 90 => ['A', 'success'],
                                                    $n->nilai_akhir >= 80 => ['B', 'primary'],
                                                    $n->nilai_akhir >= 70 => ['C', 'info'],
                                                    $n->nilai_akhir >= 60 => ['D', 'warning'],
                                                    default => ['E', 'danger']
                                                };
                                            @endphp
                                            <span class="badge-modern bg-{{ $grade[1] }} bg-opacity-10 text-{{ $grade[1] }}">
                                                {{ $grade[0] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book-open"></i>
                            <p class="text-muted mb-0">Belum ada nilai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-modern card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt me-2 text-warning"></i>
                            Event Mendatang
                        </h5>
                        <a href="{{ route('siswa.kalender.index') }}" class="btn btn-sm btn-outline-warning rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if(isset($eventsMendatang) && $eventsMendatang->count() > 0)
                        <div class="timeline">
                            @foreach($eventsMendatang as $event)
                            <div class="event-item">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-center" style="min-width: 60px;">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                                            <div class="fw-bold text-primary">
                                                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('M') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="event-title">{{ $event->judul }}</div>
                                        <div class="event-date mb-2">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('l, d F Y') }}
                                        </div>
                                        <p class="small text-muted mb-0">{{ Str::limit($event->deskripsi, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p class="text-muted mb-0">Tidak ada event mendatang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Kehadiran Bulanan (Full Width) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card-modern card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie me-2 text-success"></i>
                        Statistik Kehadiran Bulan Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="position-relative d-inline-block">
                                <canvas id="attendanceChart" width="150" height="150"></canvas>
                            </div>
                            <div class="mt-3">
                                <h4 class="mb-0">{{ $persentaseKehadiran ?? 0 }}%</h4>
                                <small class="text-muted">Tingkat Kehadiran</small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row text-center">
                                <div class="col-3">
                                    <div class="border rounded-3 p-3">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                        <h4 class="mb-0">{{ $statistikAbsensi['hadir'] ?? 0 }}</h4>
                                        <small class="text-muted">Hadir</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded-3 p-3">
                                        <i class="fas fa-thermometer-half text-warning fa-2x mb-2 d-block"></i>
                                        <h4 class="mb-0">{{ $statistikAbsensi['sakit'] ?? 0 }}</h4>
                                        <small class="text-muted">Sakit</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded-3 p-3">
                                        <i class="fas fa-envelope text-info fa-2x mb-2 d-block"></i>
                                        <h4 class="mb-0">{{ $statistikAbsensi['izin'] ?? 0 }}</h4>
                                        <small class="text-muted">Izin</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded-3 p-3">
                                        <i class="fas fa-times-circle text-danger fa-2x mb-2 d-block"></i>
                                        <h4 class="mb-0">{{ $statistikAbsensi['alpha'] ?? 0 }}</h4>
                                        <small class="text-muted">Alpha</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Attendance Chart (Donut)
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
            datasets: [{
                data: [
                    {{ $statistikAbsensi['hadir'] ?? 0 }}, 
                    {{ $statistikAbsensi['sakit'] ?? 0 }}, 
                    {{ $statistikAbsensi['izin'] ?? 0 }},
                    {{ $statistikAbsensi['alpha'] ?? 0 }}
                ],
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '60%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush