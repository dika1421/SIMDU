@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Rekap Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.absensi.guru') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-calendar-check"></i> Input Absensi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" onchange="this.form.submit()">
                    @foreach($bulanList as $key => $nama)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Terlambat</th>
                        <th>Total</th>
                        <th>Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap as $index => $r)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $r['nip'] }}</td>
                        <td>{{ $r['nama'] }}</td>
                        <td>{{ $r['mapel'] }}</td>
                        <td class="text-center">{{ $r['hadir'] }}</td>
                        <td class="text-center">{{ $r['sakit'] }}</td>
                        <td class="text-center">{{ $r['izin'] }}</td>
                        <td class="text-center">{{ $r['alfa'] }}</td>
                        <td class="text-center">{{ $r['terlambat'] }}</td>
                        <td class="text-center">{{ $r['total'] }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $r['persentase'] >= 75 ? 'success' : ($r['persentase'] >= 50 ? 'warning' : 'danger') }}">
                                {{ $r['persentase'] }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-chalkboard-user fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data absensi guru</p>
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
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
@endsection