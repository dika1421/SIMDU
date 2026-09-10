@extends('administrasi.layouts.header')

@section('title', 'Laporan Keuangan')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-lap {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-lap::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-lap::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-lap .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-lap h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-lap p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ========== FILTER CARD ========== */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .filter-card .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .filter-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .filter-card .form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }
    .btn-filter {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        width: 100%;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(14, 165, 233, 0.35);
        color: #fff;
    }

    /* ========== STAT CARDS ========== */
    .stats-lap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-lap-card {
        border-radius: 16px;
        padding: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        min-height: 130px;
    }
    .stat-lap-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
    }
    .stat-lap-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }
    .stat-lap-card .stat-icon-bg {
        position: absolute;
        right: 18px;
        top: 18px;
        font-size: 3rem;
        opacity: 0.25;
    }
    .stat-lap-card .stat-label-l {
        font-size: 0.72rem;
        text-transform: uppercase;
        opacity: 0.9;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }
    .stat-lap-card .stat-value-l {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 4px 0;
        position: relative;
        z-index: 2;
        line-height: 1.1;
    }
    .stat-lap-card .stat-sub-l {
        font-size: 0.75rem;
        opacity: 0.85;
        position: relative;
        z-index: 2;
    }
    .stat-green  { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-blue   { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .stat-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-red    { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    /* ========== SALDO BANNER ========== */
    .saldo-banner {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 16px;
        padding: 26px;
        color: #fff;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(139, 92, 246, 0.3);
        text-align: center;
    }
    .saldo-banner::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .saldo-banner::after {
        content: '';
        position: absolute;
        bottom: -60%; left: 20%;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .saldo-banner .saldo-content {
        position: relative;
        z-index: 2;
    }
    .saldo-banner .saldo-icon {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.6rem;
        backdrop-filter: blur(10px);
    }
    .saldo-banner .saldo-label {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .saldo-banner .saldo-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        line-height: 1;
        text-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .saldo-banner .saldo-sub {
        font-size: 0.82rem;
        opacity: 0.9;
    }

    /* ========== SECTION CARD ========== */
    .section-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .section-header {
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .section-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-header.green  { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
    .section-header.blue   { background: linear-gradient(135deg, #e0f2fe, #cffafe); }
    .section-header.orange { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .section-header.green h5 i  { color: #059669; }
    .section-header.blue h5 i   { color: #0284c7; }
    .section-header.orange h5 i { color: #d97706; }

    .total-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .total-badge.green  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .total-badge.blue   { background: #cffafe; color: #155e75; border: 1px solid #67e8f9; }
    .total-badge.orange { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

    /* ========== TABLE ========== */
    .table-lap {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-lap thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 13px 12px;
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-lap tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-lap tbody tr {
        transition: all 0.2s;
    }
    .table-lap tbody tr:hover {
        background: #f8fafc;
    }

    .nis-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .kategori-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid;
    }
    .kategori-badge.blue { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .kategori-badge.green { background: #d1fae5; color: #065f46; border-color: #a7f3d0; }
    .kategori-badge.orange { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .kategori-badge.purple { background: #ede9fe; color: #5b21b6; border-color: #ddd6fe; }

    .jumlah-text {
        font-weight: 700;
        font-size: 0.85rem;
        color: #059669;
    }
    .jumlah-text.red { color: #dc2626; }

    /* ========== EMPTY STATE ========== */
    .empty-state-sm {
        padding: 40px 20px;
        text-align: center;
    }
    .empty-state-sm i {
        font-size: 2.5rem;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }
    .empty-state-sm p {
        color: #94a3b8;
        margin: 0;
        font-size: 0.85rem;
    }

    /* ========== FOOTER TOTAL ========== */
    tfoot tr td {
        padding: 14px 12px !important;
        background: #f8fafc;
        font-weight: 700;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-lap { padding: 18px; }
        .page-header-lap h1 { font-size: 1.15rem; }
        .saldo-banner .saldo-value { font-size: 1.6rem; }
        .stat-lap-card .stat-value-l { font-size: 1.3rem; }
        .table-lap { font-size: 0.75rem; }
        .table-lap tbody td { padding: 9px 6px; }
    }

    /* ========== PRINT ========== */
    @media print {
        .page-header-lap,
        .filter-card,
        .btn-glass,
        .stats-lap { display: none !important; }
        .section-card { box-shadow: none !important; border: 1px solid #ddd !important; page-break-inside: avoid; }
        .saldo-banner { page-break-after: avoid; }
    }
</style>

@php
    $bulanInt = (int) ($bulan ?? now()->month);
    $tahunVal = $tahun ?? now()->year;
    $saldo = ($spp ?? 0) + ($pemasukanLain ?? 0) - ($pengeluaran ?? 0);
    $saldoClass = $saldo >= 0 ? 'stat-green' : 'stat-red';
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-lap">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-file-invoice me-2"></i>
                Laporan Keuangan
            </h1>
            <p>
                <i class="fas fa-calendar-alt me-1"></i>
                Periode {{ $bulanList[$bulanInt] ?? '-' }} {{ $tahunVal }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="window.print()" class="btn-glass">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="#" class="btn-glass" onclick="return false;">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Bulan
            </label>
            <select name="bulan" class="form-control">
                @foreach($bulanList as $key => $nama)
                    <option value="{{ $key }}" {{ $bulanInt == $key ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label">
                <i class="fas fa-calendar"></i> Tahun
            </label>
            <select name="tahun" class="form-control">
                @foreach($tahunList ?? range(now()->year, now()->year - 5) as $t)
                    <option value="{{ $t }}" {{ $tahunVal == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

{{-- ============ STAT CARDS ============ --}}
<div class="stats-lap">
    <div class="stat-lap-card stat-green">
        <i class="fas fa-money-bill-wave stat-icon-bg"></i>
        <div class="stat-label-l">Pemasukan SPP</div>
        <div class="stat-value-l">Rp {{ number_format($spp ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub-l">Dari pembayaran SPP siswa</div>
    </div>
    <div class="stat-lap-card stat-blue">
        <i class="fas fa-hand-holding-usd stat-icon-bg"></i>
        <div class="stat-label-l">Pemasukan Lain</div>
        <div class="stat-value-l">Rp {{ number_format($pemasukanLain ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub-l">Pembayaran non-SPP</div>
    </div>
    <div class="stat-lap-card stat-orange">
        <i class="fas fa-shopping-cart stat-icon-bg"></i>
        <div class="stat-label-l">Pengeluaran</div>
        <div class="stat-value-l">Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub-l">Total biaya keluar</div>
    </div>
</div>

{{-- ============ SALDO BANNER ============ --}}
<div class="saldo-banner">
    <div class="saldo-content">
        <div class="saldo-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="saldo-label">Saldo Bersih Bulan {{ $bulanList[$bulanInt] ?? '-' }} {{ $tahunVal }}</div>
        <h2 class="saldo-value">
            Rp {{ number_format($saldo, 0, ',', '.') }}
        </h2>
        <div class="saldo-sub">
            <i class="fas fa-{{ $saldo >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
            {{ $saldo >= 0 ? 'Surplus' : 'Defisit' }} dari selisih pemasukan & pengeluaran
        </div>
    </div>
</div>

{{-- ============ DETAIL SPP ============ --}}
<div class="section-card">
    <div class="section-header green">
        <h5>
            <i class="fas fa-money-bill-wave"></i>
            Detail Pemasukan SPP
        </h5>
        <span class="total-badge green">
            <i class="fas fa-database me-1"></i>
            {{ count($detailSPP ?? []) }} Transaksi · Rp {{ number_format($spp ?? 0, 0, ',', '.') }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-lap table-hover mb-0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">NIS</th>
                    <th width="25%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="20%">Jumlah</th>
                    <th width="15%">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailSPP ?? [] as $index => $d)
                @php
                    $nama = $d->siswa->user->name ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <span class="nis-badge">{{ $d->siswa->nis ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                                {{ $initial }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                            {{ $d->siswa->kelas->nama ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="jumlah-text">Rp {{ number_format($d->jumlah ?? $d->nominal ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <small>{{ $d->tanggal_bayar ? \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') : '-' }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state-sm">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada data pemasukan SPP untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(($detailSPP ?? collect())->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total Pemasukan SPP:</td>
                    <td class="text-success" style="font-size:0.95rem;">Rp {{ number_format($spp ?? 0, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ============ DETAIL PEMASUKAN LAIN ============ --}}
<div class="section-card">
    <div class="section-header blue">
        <h5>
            <i class="fas fa-hand-holding-usd"></i>
            Detail Pemasukan Lain
        </h5>
        <span class="total-badge blue">
            <i class="fas fa-database me-1"></i>
            {{ count($detailPemasukanLain ?? []) }} Transaksi · Rp {{ number_format($pemasukanLain ?? 0, 0, ',', '.') }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-lap table-hover mb-0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="22%">Nama Siswa</th>
                    <th width="18%">Kategori</th>
                    <th width="18%">Jumlah</th>
                    <th width="22%">Keterangan</th>
                    <th width="15%">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailPemasukanLain ?? [] as $index => $d)
                @php
                    $nama = $d->siswa->user->name ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));
                    $katColors = ['blue', 'green', 'orange', 'purple'];
                    $katClass = $katColors[$index % 4];
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                                {{ $initial }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="kategori-badge {{ $katClass }}">
                            <i class="fas fa-tag"></i>
                            {{ $d->kategori ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="jumlah-text">Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ Str::limit($d->keterangan ?? '-', 40) }}</small>
                    </td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state-sm">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada data pemasukan lain untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(($detailPemasukanLain ?? collect())->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">Total Pemasukan Lain:</td>
                    <td class="text-success" style="font-size:0.95rem;">Rp {{ number_format($pemasukanLain ?? 0, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ============ DETAIL PENGELUARAN ============ --}}
<div class="section-card">
    <div class="section-header orange">
        <h5>
            <i class="fas fa-shopping-cart"></i>
            Detail Pengeluaran
        </h5>
        <span class="total-badge orange">
            <i class="fas fa-database me-1"></i>
            {{ count($detailPengeluaran ?? []) }} Transaksi · Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-lap table-hover mb-0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="22%">Kategori</th>
                    <th width="20%">Jumlah</th>
                    <th width="38%">Keterangan</th>
                    <th width="15%">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailPengeluaran ?? [] as $index => $d)
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <span class="kategori-badge orange">
                            <i class="fas fa-tag"></i>
                            {{ $d->kategori ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="jumlah-text red">- Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ Str::limit($d->keterangan ?? '-', 50) }}</small>
                    </td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state-sm">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada data pengeluaran untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(($detailPengeluaran ?? collect())->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end">Total Pengeluaran:</td>
                    <td class="text-danger" style="font-size:0.95rem;">- Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection