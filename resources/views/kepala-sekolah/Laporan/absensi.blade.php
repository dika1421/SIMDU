@extends('kepala-sekolah.layouts.header')

@section('title', 'Laporan Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Laporan Absensi
    </h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ ($bulan ?? now()->month) == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach(range(now()->year, now()->year-5) as $t)
                    <option value="{{ $t }}" {{ ($tahun ?? now()->year) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="#" class="btn btn-success d-block">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Total Hadir</h6>
                <h3>{{ $rekapSiswa->sum('hadir') + $rekapGuru->sum('hadir') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Total Sakit</h6>
                <h3>{{ $rekapSiswa->sum('sakit') + $rekapGuru->sum('sakit') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Total Izin</h6>
                <h3>{{ $rekapSiswa->sum('izin') + $rekapGuru->sum('izin') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h6>Total Alfa</h6>
                <h3>{{ $rekapSiswa->sum('alfa') + $rekapGuru->sum('alfa') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Absensi -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Grafik Absensi</h5>
    </div>
    <div class="card-body">
        <canvas id="absensiChart" style="height: 300px;"></canvas>
    </div>
</div>

<!-- Rekap Siswa -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Siswa per Kelas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Jml Siswa</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapSiswa as $r)
                    @php
                        $totalSiswa = $r->total;
                        $totalHadir = $r->hadir;
                        $persenKelas = $totalSiswa > 0 ? round(($totalHadir / ($totalSiswa * 20)) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $r->kelas->nama ?? '-' }}</td>
                        <td>{{ $r->kelas->jurusan->nama ?? '-' }}</td>
                        <td class="text-center">{{ $r->total }}</td>
                        <td class="text-center bg-success text-white">{{ $r->hadir }}</td>
                        <td class="text-center bg-warning">{{ $r->sakit }}</td>
                        <td class="text-center bg-info">{{ $r->izin }}</td>
                        <td class="text-center bg-danger text-white">{{ $r->alfa }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $persenKelas >= 90 ? 'success' : ($persenKelas >= 75 ? 'warning' : 'danger') }} p-2">
                                {{ $persenKelas }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Rekap Guru -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Guru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapGuru as $g)
                    @php
                        $totalHadirGuru = $g->hadir;
                        $persenGuru = 20 > 0 ? round(($totalHadirGuru / 20) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $g->user->name }}</td>
                        <td>{{ $g->nip }}</td>
                        <td class="text-center bg-success text-white">{{ $g->hadir }}</td>
                        <td class="text-center bg-warning">{{ $g->sakit }}</td>
                        <td class="text-center bg-info">{{ $g->izin }}</td>
                        <td class="text-center bg-danger text-white">{{ $g->alfa }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $persenGuru >= 90 ? 'success' : ($persenGuru >= 75 ? 'warning' : 'danger') }} p-2">
                                {{ $persenGuru }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Grafik
    const ctx = document.getElementById('absensiChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($rekapSiswa->pluck('kelas.nama')->toArray()) !!},
            datasets: [{
                label: 'Hadir',
                data: {!! json_encode($rekapSiswa->pluck('hadir')->toArray()) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.7)'
            }, {
                label: 'Sakit',
                data: {!! json_encode($rekapSiswa->pluck('sakit')->toArray()) !!},
                backgroundColor: 'rgba(255, 193, 7, 0.7)'
            }, {
                label: 'Izin',
                data: {!! json_encode($rekapSiswa->pluck('izin')->toArray()) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.7)'
            }, {
                label: 'Alfa',
                data: {!! json_encode($rekapSiswa->pluck('alfa')->toArray()) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
@endsection