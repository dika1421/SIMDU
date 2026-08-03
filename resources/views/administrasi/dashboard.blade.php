@extends('administrasi.layouts.header')

@section('title', 'Dashboard Administrasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard Administrasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6>Total Siswa</h6>
            <h2>{{ $totalSiswa ?? 0 }}</h2>
            <small>Aktif: {{ $siswaAktif ?? 0 }}</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <h6>Total Guru</h6>
            <h2>{{ $totalGuru ?? 0 }}</h2>
            <small>PNS: {{ $guruPNS ?? 0 }}</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h6>Pembayaran Bulan Ini</h6>
            <h4>Rp {{ number_format($pembayaranBulanIni ?? 0, 0, ',', '.') }}</h4>
            <small>SPP: {{ $sppBulanIni ?? 0 }} siswa</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h6>Kehadiran Hari Ini</h6>
            <h2>{{ $kehadiranPersen ?? 0 }}%</h2>
            <small>Siswa: {{ $hadirSiswa ?? 0 }}/{{ $totalSiswa ?? 0 }}</small>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pembayaran Terbaru -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-money-bill-wave me-2"></i>Pembayaran SPP Terbaru</span>
                <a href="{{ route('administrasi.keuangan.spp') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
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
                                <td>{{ $p->siswa->user->name ?? $p->siswa->nama_lengkap ?? $p->siswa->nama ?? '-' }}</td>
                                <td>{{ $p->bulan ?? '-' }}</td>
                                <td>Rp {{ number_format($p->jumlah ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                    Tidak ada data pembayaran
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Absensi Hari Ini -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-calendar-check me-2"></i>Absensi Hari Ini</span>
                <a href="{{ route('administrasi.absensi.siswa') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Input Absensi
                </a>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <div class="p-3 bg-success text-white rounded">
                            <h5 class="mb-0">{{ $hadirSiswa ?? 0 }}</h5>
                            <small>Hadir</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-warning text-dark rounded">
                            <h5 class="mb-0">{{ $sakitSiswa ?? 0 }}</h5>
                            <small>Sakit</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-info text-white rounded">
                            <h5 class="mb-0">{{ $izinSiswa ?? 0 }}</h5>
                            <small>Izin</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-danger text-white rounded">
                            <h5 class="mb-0">{{ $alfaSiswa ?? 0 }}</h5>
                            <small>Alfa</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <small>Jumlah Siswa: <strong>{{ $totalSiswa ?? 0 }}</strong></small>
                        <small>Belum Absen: <strong>{{ ($totalSiswa ?? 0) - (($hadirSiswa ?? 0) + ($sakitSiswa ?? 0) + ($izinSiswa ?? 0) + ($alfaSiswa ?? 0)) }}</strong></small>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        @php
                            $total = $totalSiswa ?? 1;
                            $hadir = $hadirSiswa ?? 0;
                            $persen = round(($hadir / $total) * 100);
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $persen }}%;"></div>
                    </div>
                    <small class="text-muted">Kehadiran: {{ $persen ?? 0 }}%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran Hari Ini</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Guru</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni ?? [] as $j)
                            <tr>
                                <td>
                                    @if($j->jam_mulai && $j->jam_selesai)
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                    @else
                                        {{ $j->jam_mulai ?? '-' }} - {{ $j->jam_selesai ?? '-' }}
                                    @endif
                                </td>
                                <td><strong>{{ $j->kelas->nama_kelas ?? $j->kelas->nama ?? '-' }}</strong></td>
                                <td>{{ $j->mataPelajaran->nama_mapel ?? $j->mapel->nama_mapel ?? $j->mapel->nama ?? '-' }}</td>
                                <td>{{ $j->guru->nama_lengkap ?? $j->guru->user->name ?? $j->guru->nama ?? '-' }}</td>
                                <td>{{ $j->ruangan ?? $j->ruang ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-times fa-2x d-block mb-2"></i>
                                    Tidak ada jadwal hari ini
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

<!-- Grafik/Tambahan -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-chart-bar me-2"></i>Statistik Kehadiran Bulan Ini</span>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-chart-pie me-2"></i>Distribusi Siswa per Kelas</span>
            </div>
            <div class="card-body">
                <canvas id="kelasChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

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
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                borderColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // 🔥 Chart Distribusi Kelas
    var ctx2 = document.getElementById('kelasChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: {!! json_encode($kelasLabels ?? ['Belum Ada Data']) !!},
            datasets: [{
                data: {!! json_encode($kelasData ?? [1]) !!},
                backgroundColor: ['#667eea', '#11998e', '#f093fb', '#4facfe', '#ff6b6b', '#ffd93d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection