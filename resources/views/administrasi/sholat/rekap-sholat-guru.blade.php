@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        Rekap Absensi Sholat Guru
    </h1>
    <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if(isset($guru) && $guru->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>NUPTK</th>
                            <th>Total Hadir</th>
                            <th>Total Tepat Waktu</th>
                            <th>Total Terlambat</th>
                            <th>Total Izin</th>
                            <th>Total Tidak Hadir</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guru as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->user->name ?? $item->nama_lengkap ?? '-' }}</td>
                                <td>{{ $item->nip ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $item->total_hadir ?? 0 }}</span></td>
                                <td><span class="badge bg-success">{{ $item->total_tepat_waktu ?? 0 }}</span></td>
                                <td><span class="badge bg-warning">{{ $item->total_terlambat ?? 0 }}</span></td>
                                <td><span class="badge bg-info">{{ $item->total_izin ?? 0 }}</span></td>
                                <td><span class="badge bg-danger">{{ $item->total_tidak_hadir ?? 0 }}</span></td>
                                <td>
                                    @php
                                        $total = ($item->total_hadir ?? 0) + ($item->total_izin ?? 0) + ($item->total_tidak_hadir ?? 0);
                                        $persen = $total > 0 ? round(($item->total_hadir ?? 0) / $total * 100, 2) : 0;
                                    @endphp
                                    {{ $persen }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Belum ada data absensi sholat guru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Belum ada data rekap absensi sholat guru.
            </div>
        @endif
    </div>
</div>
@endsection