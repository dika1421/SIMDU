@extends('administrasi.layouts.header')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-invoice me-2"></i>
        Laporan Keuangan
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
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach($bulanList as $key => $nama)
                    <option value="{{ $key }}" {{ ($bulan ?? now()->month) == $key ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach($tahunList ?? range(now()->year, now()->year-5) as $t)
                    <option value="{{ $t }}" {{ ($tahun ?? now()->year) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
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

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Pemasukan SPP</h6>
                <h3>Rp {{ number_format($spp ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Pemasukan Lain</h6>
                <h3>Rp {{ number_format($pemasukanLain ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Pengeluaran</h6>
                <h3>Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Total -->
<div class="card bg-primary text-white mb-4">
    <div class="card-body text-center">
        <h5>Saldo Bersih Bulan {{ $bulanList[$bulan ?? now()->month] ?? '' }} {{ $tahun ?? now()->year }}</h5>
        <h2>Rp {{ number_format(($spp ?? 0) + ($pemasukanLain ?? 0) - ($pengeluaran ?? 0), 0, ',', '.') }}</h2>
    </div>
</div>

<!-- Detail SPP -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detail Pemasukan SPP</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jumlah</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailSPP ?? [] as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->siswa->nis ?? '-' }}</td>
                        <td><strong>{{ $d->siswa->user->name ?? '-' }}</strong></td>
                        <td>{{ $d->siswa->kelas->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($d->jumlah ?? $d->nominal ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $d->tanggal_bayar ? \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data SPP</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Pemasukan Lain -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detail Pemasukan Lain</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPemasukanLain ?? [] as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->siswa->user->name ?? '-' }}</td>
                        <td>{{ $d->kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $d->keterangan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pemasukan lain</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Pengeluaran -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detail Pengeluaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-warning">
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPengeluaran ?? [] as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $d->keterangan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection