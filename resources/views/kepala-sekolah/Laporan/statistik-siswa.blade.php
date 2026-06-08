@extends('kepala-sekolah.layouts.header')

@section('title', 'Statistik Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>
        Statistik Siswa
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
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun" class="form-control">
                    @foreach(range(now()->year, now()->year-5) as $t)
                    <option value="{{ $t }}" {{ ($tahun ?? now()->year) == $t ? 'selected' : '' }}>
                        {{ $t }}/{{ $t+1 }}
                    </option>
                    @endforeach
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

@php
    $totalSiswa = $perKelas->sum('siswa_count');
    $siswaAktif = $perStatus->where('status', 'aktif')->first()->total ?? 0;
    $siswaLulus = $perStatus->where('status', 'lulus')->first()->total ?? 0;
    $siswaKeluar = $perStatus->where('status', 'keluar')->first()->total ?? 0;
@endphp

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Siswa</h6>
                <h3>{{ $totalSiswa }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Siswa Aktif</h6>
                <h3>{{ $siswaAktif }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Siswa Lulus</h6>
                <h3>{{ $siswaLulus }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Siswa Keluar</h6>
                <h3>{{ $siswaKeluar }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Utama -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Sebaran Siswa per Kelas</h5>
            </div>
            <div class="card-body">
                <canvas id="kelasChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Komposisi Jenis Kelamin</h5>
            </div>
            <div class="card-body">
                <canvas id="jkChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Sebaran per Kelas -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detail Sebaran Siswa per Kelas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Tingkat</th>
                        <th>Jumlah Siswa</th>
                        <th>Laki-laki</th>
                        <th>Perempuan</th>
                        <th>Wali Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perKelas as $kelas)
                    @php
                        $siswaKelas = $kelas->siswa;
                        $laki = $siswaKelas->where('jenis_kelamin', 'L')->count();
                        $perempuan = $siswaKelas->where('jenis_kelamin', 'P')->count();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $kelas->nama }}</strong></td>
                        <td>{{ $kelas->jurusan->nama ?? '-' }}</td>
                        <td class="text-center">Kelas {{ $kelas->tingkat }}</td>
                        <td class="text-center">{{ $kelas->siswa_count }}</td>
                        <td class="text-center">{{ $laki }}</td>
                        <td class="text-center">{{ $perempuan }}</td>
                        <td>{{ $kelas->waliKelas->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data kelas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Statistik Lainnya -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Sebaran Agama</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Agama</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perAgama as $agama)
                        <tr>
                            <td>{{ $agama->agama }}</td>
                            <td>{{ $agama->total }}</td>
                            <td>
                                @if($totalSiswa > 0)
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         style="width: {{ ($agama->total / $totalSiswa) * 100 }}%"
                                         aria-valuenow="{{ ($agama->total / $totalSiswa) * 100 }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ round(($agama->total / $totalSiswa) * 100, 1) }}%
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Status Siswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perStatus as $status)
                        <tr>
                            <td>{{ ucfirst($status->status) }}</td>
                            <td>{{ $status->total }}</td>
                            <td>
                                @if($totalSiswa > 0)
                                <div class="progress">
                                    @php
                                        $bgColor = $status->status == 'aktif' ? 'success' : ($status->status == 'lulus' ? 'info' : 'danger');
                                    @endphp
                                    <div class="progress-bar bg-{{ $bgColor }}" role="progressbar" 
                                         style="width: {{ ($status->total / $totalSiswa) * 100 }}%"
                                         aria-valuenow="{{ ($status->total / $totalSiswa) * 100 }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ round(($status->total / $totalSiswa) * 100, 1) }}%
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Tingkat Kelulusan per Tahun</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tahun Masuk</th>
                            <th>Lulus</th>
                            <th>Total</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelulusan as $k)
                        @php
                            $persenKelulusan = $k->total > 0 ? round(($k->lulus / $k->total) * 100, 1) : 0;
                            $badgeColor = $persenKelulusan >= 90 ? 'success' : ($persenKelulusan >= 75 ? 'warning' : 'danger');
                        @endphp
                        <tr>
                            <td>{{ $k->tahun_masuk }}</td>
                            <td>{{ $k->lulus }}</td>
                            <td>{{ $k->total }}</td>
                            <td>
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ $persenKelulusan }}%
                                </span>
                            </td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Sebaran per Kelas
    const kelasCtx = document.getElementById('kelasChart').getContext('2d');
    new Chart(kelasCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($perKelas->pluck('nama')->toArray()) !!},
            datasets: [{
                label: 'Jumlah Siswa',
                data: {!! json_encode($perKelas->pluck('siswa_count')->toArray()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Jenis Kelamin
    const jkCtx = document.getElementById('jkChart').getContext('2d');
    const jkData = @json($perJk);
    const labels = [];
    const values = [];
    const colors = [];
    
    jkData.forEach(item => {
        labels.push(item.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
        values.push(item.total);
        colors.push(item.jenis_kelamin == 'L' ? '#36a2eb' : '#ff6384');
    });

    new Chart(jkCtx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
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