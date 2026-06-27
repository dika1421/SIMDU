{{-- resources/views/kepala-sekolah/manajemen-guru/absensi.blade.php --}}
@extends('kepala-sekolah.layouts.header')

@section('title', 'Rekap Absensi Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2 text-primary"></i>
        Rekap Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Alert Error -->
@if(isset($error))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
    </div>
@endif

<!-- Info Data -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Info:</strong><br>
    Total guru: <strong>{{ isset($rekapData) ? count($rekapData) : 0 }}</strong><br>
    Bulan: <strong>{{ $bulanList[$bulan] ?? $bulan }}</strong> - Tahun: <strong>{{ $tahun }}</strong><br>
    Total absensi: <strong>{{ $statistik['total_absensi'] ?? 0 }}</strong>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach($bulanList as $key => $value)
                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('kepala-sekolah.manajemen-guru.absensi') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistik Cards -->
@if(isset($statistik) && $statistik['total_guru'] > 0)
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['total_guru'] }}</h4>
            <small>Total Guru</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['total_hadir'] }}</h4>
            <small>Hadir</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['total_sakit'] }}</h4>
            <small>Sakit</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['total_izin'] }}</h4>
            <small>Izin</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['total_alfa'] }}</h4>
            <small>Alfa</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white text-center p-3">
            <h4 class="mb-0">{{ $statistik['rata_persentase'] }}%</h4>
            <small>Rata-rata</small>
        </div>
    </div>
</div>
@endif

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Bulan {{ $bulanList[$bulan] }} {{ $tahun }}</h5>
    </div>
    <div class="card-body">
        @if(isset($rekapData) && count($rekapData) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-warning">Sakit</th>
                        <th class="text-center text-info">Izin</th>
                        <th class="text-center text-danger">Alfa</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapData as $index => $item)
                    @php
                        $persen = $item['persentase'];
                        $badgeColor = $persen >= 90 ? 'success' : ($persen >= 75 ? 'warning' : 'danger');
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $item['nama'] }}</strong>
                            @if($item['nuptk'] && $item['nuptk'] != '-')
                                <br>
                                <small class="text-muted">NUPTK: {{ $item['nuptk'] }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item['nip'] }}</td>
                        <td class="text-center fw-bold text-success">{{ $item['hadir'] }}</td>
                        <td class="text-center">{{ $item['sakit'] }}</td>
                        <td class="text-center">{{ $item['izin'] }}</td>
                        <td class="text-center text-danger">{{ $item['alfa'] }}</td>
                        <td class="text-center fw-bold">{{ $item['total'] }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $badgeColor }} px-3 py-2">
                                {{ $persen }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @if(count($rekapData) > 0)
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-center">TOTAL</td>
                        <td class="text-center text-success">{{ array_sum(array_column($rekapData, 'hadir')) }}</td>
                        <td class="text-center">{{ array_sum(array_column($rekapData, 'sakit')) }}</td>
                        <td class="text-center">{{ array_sum(array_column($rekapData, 'izin')) }}</td>
                        <td class="text-center text-danger">{{ array_sum(array_column($rekapData, 'alfa')) }}</td>
                        <td class="text-center">{{ array_sum(array_column($rekapData, 'total')) }}</td>
                        <td class="text-center">{{ count($rekapData) > 0 ? round(array_sum(array_column($rekapData, 'persentase')) / count($rekapData), 2) : 0 }}%</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Belum ada data absensi</h5>
            <p class="text-muted">Belum ada absensi untuk bulan {{ $bulanList[$bulan] }} {{ $tahun }}</p>
            @if(isset($error))
                <p class="text-danger">{{ $error }}</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<script>
    $(document).ready(function() {
        @if(isset($rekapData) && count($rekapData) > 0)
        $('#rekapTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
        @endif
    });
</script>
@endpush