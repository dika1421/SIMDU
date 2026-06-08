@extends('kepala-sekolah.layouts.header')

@section('title', 'Absensi Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-check me-2"></i>
        Rekap Absensi Guru
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
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach(range(now()->year, now()->year-5) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
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

<!-- Tabel Absensi -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Guru Bulan {{ \Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Total</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru as $g)
                    @php
                        $total = $g->statistik['hadir'] + $g->statistik['sakit'] + $g->statistik['izin'] + $g->statistik['alfa'];
                        $persen = $total > 0 ? round(($g->statistik['hadir'] / $total) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $g->user->name }}</td>
                        <td>{{ $g->nip }}</td>
                        <td class="text-center bg-success text-white">{{ $g->statistik['hadir'] }}</td>
                        <td class="text-center bg-warning">{{ $g->statistik['sakit'] }}</td>
                        <td class="text-center bg-info">{{ $g->statistik['izin'] }}</td>
                        <td class="text-center bg-danger text-white">{{ $g->statistik['alfa'] }}</td>
                        <td class="text-center">{{ $total }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $persen >= 90 ? 'success' : ($persen >= 75 ? 'warning' : 'danger') }} p-2">
                                {{ $persen }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection