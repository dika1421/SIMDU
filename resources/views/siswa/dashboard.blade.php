{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $siswa->nama_lengkap ?? 'Rahmat Aditya' }}</h3>
                                    <p class="mb-0 opacity-75">
                                        <i class="fas fa-id-card me-1"></i> NIS: {{ $siswa->nis ?? '232410031' }} 
                                        | <i class="fas fa-users me-1"></i> Kelas: {{ $siswa->kelas->nama ?? 'XII A PEMASARAN' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <span class="badge bg-white text-dark p-2">
                                <i class="fas fa-calendar-alt me-1"></i> {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Rata-rata Nilai -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Rata-rata Nilai</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($rataNilai ?? 0, 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> Dari {{ $nilaiTerbaru->count() ?? 0 }} Mata Pelajaran
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-book fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Kehadiran -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Kehadiran Bulan Ini</p>
                            <h3 class="mb-0 fw-bold">{{ $persentaseKehadiran ?? 0 }}%</h3>
                            <small class="text-muted">
                                <i class="fas fa-user-check me-1 text-success"></i> {{ $statistikAbsensi['hadir'] ?? 0 }} Hadir 
                                | <i class="fas fa-user-clock me-1 text-warning"></i> {{ $statistikAbsensi['sakit'] ?? 0 }} Sakit
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Absensi Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Absensi Hari Ini</p>
                            <h5 class="mb-0 fw-bold">
                                @if($absensiHariIni)
                                    <span class="badge bg-success">Sudah Absen</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Absen</span>
                                @endif
                            </h5>
                            <small class="text-muted">
                                @if($absensiHariIni && $absensiHariIni->waktu_masuk)
                                    <i class="fas fa-clock me-1"></i> {{ Carbon\Carbon::parse($absensiHariIni->waktu_masuk)->format('H:i') }} WIB
                                @else
                                    Silakan absen
                                @endif
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-fingerprint fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Peringkat -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Peringkat</p>
                            <h3 class="mb-0 fw-bold">#{{ $peringkat ?? '-' }}</h3>
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i> Di Kelas {{ $siswa->kelas->nama ?? 'XII A PEMASARAN' }}
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-trophy fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i> Akses Cepat
                    </h5>
                    <div class="row g-2">
                        <div class="col-3 col-md-3">
                            <a href="{{ route('siswa.nilai.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 bg-light rounded-3 hover-shadow">
                                    <i class="fas fa-book fa-2x text-primary mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Nilai</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 col-md-3">
                            <a href="{{ route('siswa.absensi.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 bg-light rounded-3 hover-shadow">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Absensi</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 col-md-3">
                            <a href="{{ route('siswa.pembayaran.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 bg-light rounded-3 hover-shadow">
                                    <i class="fas fa-credit-card fa-2x text-info mb-2 d-block"></i>
                                    <small class="text-dark fw-bold">Pembayaran</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 col-md-3">
                            <a href="{{ route('siswa.kalender.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 bg-light rounded-3 hover-shadow">
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

    <!-- Nilai Terbaru & Event -->
    <div class="row g-3">
        <!-- Nilai Terbaru -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-transparent border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-simple me-2 text-primary"></i> Nilai Terbaru
                        </h5>
                        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if(isset($nilaiTerbaru) && $nilaiTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                        <td class="text-end fw-bold">{{ number_format($n->nilai_akhir, 2) }}</td>
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
                                            <span class="badge bg-{{ $grade[1] }} bg-opacity-10 text-{{ $grade[1] }} p-2">
                                                {{ $grade[0] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada nilai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Event Mendatang -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-transparent border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt me-2 text-warning"></i> Event Mendatang
                        </h5>
                        <a href="{{ route('siswa.kalender.index') }}" class="btn btn-sm btn-outline-warning rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if(isset($eventsMendatang) && $eventsMendatang->count() > 0)
                        @foreach($eventsMendatang as $event)
                        <div class="d-flex align-items-start border-bottom py-2">
                            <div class="text-center me-3" style="min-width: 60px;">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                                    <div class="fw-bold text-primary">
                                        {{ Carbon\Carbon::parse($event->tanggal_mulai)->format('d') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('M') }}
                                    </small>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $event->judul }}</div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('l, d F Y') }}
                                </small>
                                @if(isset($event->deskripsi))
                                    <p class="small text-muted mb-0">{{ Str::limit($event->deskripsi, 80) }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Tidak ada event mendatang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        transition: all 0.3s ease;
    }
    
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
    
    .rounded-3 {
        border-radius: 10px !important;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    
    @media (max-width: 576px) {
        .col-3 {
            flex: 0 0 auto;
            width: 25%;
        }
    }
</style>
@endsection