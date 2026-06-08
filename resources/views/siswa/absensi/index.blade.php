{{-- resources/views/siswa/absensi/index.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Kehadiran Saya')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                            @foreach($bulanList as $key => $nama)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Kehadiran -->
<div class="row g-4 mb-4">
    <div class="col-md-2">
        <div class="stat-card bg-success text-white text-center">
            <h3 class="mb-0">{{ $statistik['hadir'] }}</h3>
            <small>Hadir</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-warning text-white text-center">
            <h3 class="mb-0">{{ $statistik['sakit'] }}</h3>
            <small>Sakit</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-info text-white text-center">
            <h3 class="mb-0">{{ $statistik['izin'] }}</h3>
            <small>Izin</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-danger text-white text-center">
            <h3 class="mb-0">{{ $statistik['alfa'] }}</h3>
            <small>Alfa</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-secondary text-white text-center">
            <h3 class="mb-0">{{ $statistik['terlambat'] }}</h3>
            <small>Terlambat</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-primary text-white text-center">
            <h3 class="mb-0">{{ $statistik['persentase'] }}%</h3>
            <small>Kehadiran</small>
        </div>
    </div>
</div>

<!-- Grafik Kehadiran Mingguan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Grafik Kehadiran per Minggu</h5>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Keterlambatan -->
@if($terlambatList->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Keterlambatan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            实例
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($terlambatList as $t)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($t['tanggal'])->format('d/m/Y') }}</td>
                                <td>{{ $t['waktu'] }} WIB</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tabel Absensi Harian -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Absensi Harian</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="absensiTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Waktu Masuk</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $a)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $a->status == 'hadir' ? 'success' : 
                                        ($a->status == 'sakit' ? 'warning' : 
                                        ($a->status == 'izin' ? 'info' : 'danger')) 
                                    }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                                <td>{{ $a->waktu_masuk ? \Carbon\Carbon::parse($a->waktu_masuk)->format('H:i') : '-' }}</td>
                                <td>{{ $a->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($absensi->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data absensi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Kehadiran Mingguan
        var ctx = document.getElementById('kehadiranChart').getContext('2d');
        var mingguanData = @json($mingguan);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mingguanData.map(item => item.minggu),
                datasets: [
                    {
                        label: 'Hadir',
                        data: mingguanData.map(item => item.hadir),
                        backgroundColor: '#28a745'
                    },
                    {
                        label: 'Sakit',
                        data: mingguanData.map(item => item.sakit),
                        backgroundColor: '#ffc107'
                    },
                    {
                        label: 'Izin',
                        data: mingguanData.map(item => item.izin),
                        backgroundColor: '#17a2b8'
                    },
                    {
                        label: 'Alfa',
                        data: mingguanData.map(item => item.alfa),
                        backgroundColor: '#dc3545'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });
        
        // DataTable
        $('#absensiTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            pageLength: 10
        });
    });
</script>
@endpush
@endsection