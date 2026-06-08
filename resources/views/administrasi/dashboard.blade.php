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
                <span>Pembayaran SPP Terbaru</span>
                <a href="{{ route('administrasi.keuangan.spp') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
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
                                <td>{{ $p->siswa->user->name ?? $p->siswa->nama_lengkap ?? '-' }}</td>
                                <td>{{ $p->bulan ?? '-' }}</td>
                                <td>Rp {{ number_format($p->jumlah ?? 0, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $p->status == 'lunas' ? 'success' : 'warning' }}">
                                    {{ ucfirst($p->status ?? 'Belum Lunas') }}
                                </span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data</td>
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
                <span>Absensi Hari Ini</span>
                <a href="{{ route('administrasi.absensi.siswa') }}" class="btn btn-sm btn-primary">Input Absensi</a>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <div class="p-3 bg-success text-white rounded">
                            <h5>{{ $hadirSiswa ?? 0 }}</h5>
                            <small>Hadir</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-warning text-white rounded">
                            <h5>{{ $sakitSiswa ?? 0 }}</h5>
                            <small>Sakit</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-info text-white rounded">
                            <h5>{{ $izinSiswa ?? 0 }}</h5>
                            <small>Izin</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-3 bg-danger text-white rounded">
                            <h5>{{ $alfaSiswa ?? 0 }}</h5>
                            <small>Alfa</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <small>Jumlah Siswa: {{ $totalSiswa ?? 0 }}</small>
                        <small>Belum Absen: {{ ($totalSiswa ?? 0) - (($hadirSiswa ?? 0) + ($sakitSiswa ?? 0) + ($izinSiswa ?? 0) + ($alfaSiswa ?? 0)) }}</small>
                    </div>
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
                <span>Jadwal Pelajaran Hari Ini</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
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
                                <td>{{ $j->jam_mulai ?? '-' }} - {{ $j->jam_selesai ?? '-' }}</td>
                                <td>{{ $j->kelas->nama ?? $j->kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $j->mataPelajaran->nama_mapel ?? $j->mapel->nama ?? '-' }}</td>
                                <td>{{ $j->guru->nama_lengkap ?? $j->guru->user->name ?? '-' }}</td>
                                <td>{{ $j->ruangan ?? $j->ruang ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada jadwal hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection