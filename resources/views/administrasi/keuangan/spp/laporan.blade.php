@extends('administrasi.layouts.header')

@section('title', 'Laporan SPP')

@section('content')
@php
    $bulanListFix = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $bulanInt = (int) ($bulan ?? date('n'));
    $tahunInt = (int) ($tahun ?? date('Y'));
@endphp

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-invoice me-2"></i>
        Laporan SPP
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

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach($bulanListFix as $key => $nama)
                        <option value="{{ $key }}" {{ $bulanInt == $key ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $tahunInt == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Pemasukan</h6>
                <h3>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Siswa Lunas</h6>
                <h3>{{ $lunas ?? 0 }} Siswa</h3>
            </div>
        </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Siswa Belum Lunas</h6>
                <h3>{{ $belum ?? 0 }} Siswa</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            Detail Pembayaran SPP Bulan {{ $bulanListFix[$bulanInt] ?? '-' }} {{ $tahunInt }}
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" id="sppTable">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Tanggal Bayar</th>
                        <th width="10%">Metode</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data ?? [] as $d)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $d->siswa->nis ?? '-' }}</td>
                        <td><strong>{{ $d->siswa->user->name ?? $d->siswa->nama_lengkap ?? '-' }}</strong></td>
                        <td>{{ $d->siswa->kelas->nama_kelas ?? $d->siswa->kelas->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</td>
                        <td>{{ isset($d->tanggal_bayar) ? \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $d->metode_bayar ?? '-' }}</span></td>
                        <td><span class="badge bg-{{ ($d->status ?? 'belum_lunas') == 'lunas' ? 'success' : 'danger' }}">{{ ($d->status ?? 'belum_lunas') == 'lunas' ? 'Lunas' : 'Belum Lunas' }}</span></td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4"><i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i><p class="text-muted mb-0">Belum ada data pembayaran SPP untuk periode ini</p></td></tr>
                    @endforelse
                </tbody>
                @if(($data ?? collect())->count() > 0)
                <tfoot>
                    <tr><th colspan="4" class="text-end">Total:</th><th colspan="1">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</th><th colspan="3"></th></tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sppTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
@endsection