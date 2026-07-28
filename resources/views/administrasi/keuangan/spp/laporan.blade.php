@extends('administrasi.layouts.header')

@section('title', 'Laporan SPP')

@section('content')
@php
    $bulanListFix = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $bulanInt = (int) ($bulan ?? date('n'));
    $tahunInt = (int) ($tahun ?? date('Y'));
@endphp

<style>
    .stat-card { border-radius: 12px; border: none; color: white; padding: 20px; height: 100%; }
    .stat-card h6 { font-size: 13px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .stat-card h2 { font-weight: 700; margin: 0; font-size: 28px; }
    .stat-card .icon-bg { position: absolute; right: 15px; top: 15px; font-size: 48px; opacity: 0.25; }
    .bg-gradient-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-blue { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }
    .bg-gradient-orange { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: #333 !important; }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4 mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i> Laporan Keuangan SPP</h1>
    <div class="btn-group">
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
        <a href="{{ route('administrasi.keuangan.laporan.export', ['bulan'=>$bulanInt,'tahun'=>$tahunInt]) }}" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" style="border-radius:12px;">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Filter Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach($bulanListFix as $key => $nama)
                        <option value="{{ $key }}" {{ $bulanInt == $key ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Filter Tahun</label>
                <select name="tahun" class="form-select">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $tahunInt == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100" style="border-radius:8px;"><i class="fas fa-filter me-2"></i>Tampilkan Laporan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-green shadow-sm position-relative overflow-hidden">
            <i class="fas fa-money-bill-wave icon-bg"></i>
            <h6>Total Pemasukan</h6>
            <h2>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</h2>
            <small class="opacity-75">Periode {{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-blue shadow-sm position-relative overflow-hidden">
            <i class="fas fa-check-circle icon-bg"></i>
            <h6>Siswa Lunas</h6>
            <h2>{{ $lunas ?? 0 }} Siswa</h2>
            <small class="opacity-75">Sudah melakukan pembayaran</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-orange shadow-sm position-relative overflow-hidden">
            <i class="fas fa-exclamation-triangle icon-bg"></i>
            <h6>Siswa Belum Lunas</h6>
            <h2>{{ $belum ?? 0 }} Siswa</h2>
            <small class="opacity-75">Belum melakukan pembayaran</small>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0" style="border-radius:12px;">
    <div class="card-header bg-white py-3" style="border-radius:12px 12px 0 0;">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Detail Pembayaran SPP Bulan {{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="5%">No</th>
                        <th width="12%">NIS</th>
                        <th width="22%">Nama Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="15%">Jumlah</th>
                        <th width="13%">Tanggal</th>
                        <th width="10%">Metode</th>
                        <th width="8%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data ?? [] as $d)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $d->siswa->nis ?? '-' }}</span></td>
                        <td class="fw-semibold">{{ $d->siswa->user->name ?? $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</td>
                        <td>{{ isset($d->tanggal_bayar) ? \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $d->metode_bayar ?? '-' }}</span></td>
                        <td><span class="badge {{ ($d->status ?? '') == 'lunas' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($d->status ?? 'Belum') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076503.png" width="70" class="mb-3 opacity-50">
                            <p class="text-muted mb-0">Belum ada data pembayaran untuk periode ini</p>
                            <small class="text-muted">Silahkan pilih bulan dan tahun lain</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(($data ?? collect())->count() > 0)
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="4" class="text-end pe-4">Total Pemasukan:</td>
                        <td class="text-success">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection