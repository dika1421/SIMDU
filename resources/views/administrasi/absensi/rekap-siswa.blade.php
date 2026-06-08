@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Rekap Absensi Siswa
    </h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach($bulanList as $b => $nama)
                    <option value="{{ $b }}" {{ ($bulan ?? date('m')) == $b ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ ($tahun ?? date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ ($kelas_id ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama ?? $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Rekap Absensi Bulan {{ $bulanList[$bulan ?? date('m')] }} {{ $tahun ?? date('Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="20%">Nama</th>
                        <th width="10%">Kelas</th>
                        <th width="8%">Hadir</th>
                        <th width="8%">Sakit</th>
                        <th width="8%">Izin</th>
                        <th width="8%">Alfa</th>
                        <th width="8%">Terlambat</th>
                        <th width="8%">Total</th>
                        <th width="10%">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                        @php
                            $stats = $statistik[$s->id] ?? [
                                'hadir' => 0,
                                'sakit' => 0,
                                'izin' => 0,
                                'alfa' => 0,
                                'terlambat' => 0,
                                'total' => 0,
                                'persentase' => 0
                            ];
                            $persentase = $stats['persentase'];
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td><strong>{{ $s->user->name ?? $s->nama_lengkap ?? '-' }}</strong></td>
                            <td>{{ $s->kelas->nama ?? $s->kelas->nama_kelas ?? '-' }}</td>
                            <td class="text-center bg-success text-white">{{ $stats['hadir'] }}</td>
                            <td class="text-center bg-warning text-dark">{{ $stats['sakit'] }}</td>
                            <td class="text-center bg-info text-white">{{ $stats['izin'] }}</td>
                            <td class="text-center bg-danger text-white">{{ $stats['alfa'] }}</td>
                            <td class="text-center bg-secondary text-white">{{ $stats['terlambat'] }}</td>
                            <td class="text-center">{{ $stats['total'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $persentase >= 90 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger') }} p-2 fs-6">
                                    {{ number_format($persentase, 2) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">Belum ada data absensi untuk periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rekapTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [4,5,6,7,8,9,10] }
            ]
        });
    });
</script>
@endpush
@endsection