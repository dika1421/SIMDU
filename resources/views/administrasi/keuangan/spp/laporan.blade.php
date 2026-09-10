@extends('administrasi.layouts.header')

@section('title', 'Laporan SPP')

@section('content')
@php
    $bulanListFix = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $bulanInt = (int) ($bulan ?? date('n'));
    $tahunInt = (int) ($tahun ?? date('Y'));
@endphp

<style>
    /* ========== PAGE HEADER ========== */
    .page-header-laporan {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-laporan::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-laporan .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-laporan h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-laporan p {
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

    /* ========== STAT CARDS ========== */
    .stats-laporan {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-laporan-card {
        border-radius: 16px;
        padding: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        min-height: 130px;
    }
    .stat-laporan-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
    }
    .stat-laporan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }
    .stat-laporan-card .stat-icon-bg {
        position: absolute;
        right: 18px;
        top: 18px;
        font-size: 3rem;
        opacity: 0.25;
    }
    .stat-laporan-card .stat-label-l {
        font-size: 0.72rem;
        text-transform: uppercase;
        opacity: 0.9;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }
    .stat-laporan-card .stat-value-l {
        font-size: 1.7rem;
        font-weight: 700;
        margin: 0 0 4px 0;
        position: relative;
        z-index: 2;
        line-height: 1.1;
    }
    .stat-laporan-card .stat-sub-l {
        font-size: 0.75rem;
        opacity: 0.85;
        position: relative;
        z-index: 2;
    }
    .stat-green  { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-blue   { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .stat-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-purple { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }

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
    .filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
    }
    .filter-card .form-select:focus {
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

    /* ========== MAIN CARD ========== */
    .main-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .main-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .main-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .main-card-header h5 i { color: #0284c7; }
    .period-badge {
        background: linear-gradient(135deg, #cffafe, #a5f3fc);
        color: #155e75;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #67e8f9;
    }

    /* ========== TABLE ========== */
    .table-laporan {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-laporan thead th {
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
    .table-laporan tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-laporan tbody tr {
        transition: all 0.2s;
    }
    .table-laporan tbody tr:hover {
        background: #f8fafc;
    }

    .nis-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .jumlah-text {
        font-weight: 700;
        color: #059669;
    }
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .status-chip.lunas { background: #d1fae5; color: #065f46; }
    .status-chip.belum { background: #fee2e2; color: #991b1b; }
    .metode-badge {
        background: #e0e7ff;
        color: #3730a3;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        font-size: 2.5rem;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-laporan { padding: 18px; }
        .page-header-laporan h1 { font-size: 1.15rem; }
        .stat-laporan-card .stat-value-l { font-size: 1.4rem; }
        .table-laporan { font-size: 0.75rem; }
    }

    /* ========== PRINT ========== */
    @media print {
        .page-header-laporan,
        .btn-glass,
        .filter-card,
        .stats-laporan { display: none !important; }
        .main-card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-laporan">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-file-invoice me-2"></i>
                Laporan Keuangan SPP
            </h1>
            <p>
                <i class="fas fa-calendar-alt me-1"></i>
                Periode {{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="window.print()" class="btn-glass">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('administrasi.keuangan.laporan.export', ['bulan'=>$bulanInt,'tahun'=>$tahunInt]) }}" class="btn-glass">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('administrasi.keuangan.spp') }}" class="btn-glass">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Bulan
            </label>
            <select name="bulan" class="form-select">
                @foreach($bulanListFix as $key => $nama)
                    <option value="{{ $key }}" {{ $bulanInt == $key ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">
                <i class="fas fa-calendar"></i> Tahun
            </label>
            <select name="tahun" class="form-select">
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}" {{ $tahunInt == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Tampilkan Laporan
            </button>
        </div>
    </form>
</div>

{{-- ============ STAT CARDS ============ --}}
<div class="stats-laporan">
    <div class="stat-laporan-card stat-green">
        <i class="fas fa-money-bill-wave stat-icon-bg"></i>
        <div class="stat-label-l">Total Pemasukan</div>
        <div class="stat-value-l">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub-l">{{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}</div>
    </div>
    <div class="stat-laporan-card stat-blue">
        <i class="fas fa-check-circle stat-icon-bg"></i>
        <div class="stat-label-l">Siswa Lunas</div>
        <div class="stat-value-l">{{ $lunas ?? 0 }} <small class="fs-6">Siswa</small></div>
        <div class="stat-sub-l">Sudah melakukan pembayaran</div>
    </div>
    <div class="stat-laporan-card stat-orange">
        <i class="fas fa-exclamation-triangle stat-icon-bg"></i>
        <div class="stat-label-l">Siswa Belum Lunas</div>
        <div class="stat-value-l">{{ $belum ?? 0 }} <small class="fs-6">Siswa</small></div>
        <div class="stat-sub-l">Belum melakukan pembayaran</div>
    </div>
    <div class="stat-laporan-card stat-purple">
        <i class="fas fa-file-invoice stat-icon-bg"></i>
        <div class="stat-label-l">Total Transaksi</div>
        <div class="stat-value-l">{{ ($data ?? collect())->count() }}</div>
        <div class="stat-sub-l">Transaksi tercatat</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-list"></i>
            Detail Pembayaran SPP
        </h5>
        <div class="period-badge">
            <i class="fas fa-calendar-week"></i>
            {{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-laporan table-hover mb-0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">NIS</th>
                    <th width="22%">Nama Siswa</th>
                    <th width="13%">Kelas</th>
                    <th width="14%">Jumlah</th>
                    <th width="11%">Tanggal</th>
                    <th width="10%">Metode</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data ?? [] as $d)
                @php
                    $nama = $d->siswa->user->name ?? $d->siswa->nama_lengkap ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));
                    $statusClass = ($d->status ?? '') == 'lunas' ? 'lunas' : 'belum';
                    $statusIcon = ($d->status ?? '') == 'lunas' ? 'fa-check-circle' : 'fa-times-circle';
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <span class="nis-badge">{{ $d->siswa->nis ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.78rem;">
                                {{ $initial }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                            {{ $d->siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="jumlah-text">Rp {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <small>{{ isset($d->tanggal_bayar) ? \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') : '-' }}</small>
                    </td>
                    <td>
                        <span class="metode-badge">{{ $d->metode_bayar ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="status-chip {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }}"></i>
                            {{ ucfirst($d->status ?? 'Belum') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data pembayaran</h5>
                            <p class="text-muted mb-0">
                                Tidak ada data untuk periode {{ $bulanListFix[$bulanInt] }} {{ $tahunInt }}
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(($data ?? collect())->count() > 0)
            <tfoot style="background:#f0fdf4;">
                <tr>
                    <td colspan="4" class="text-end fw-bold py-3">Total Pemasukan:</td>
                    <td class="fw-bold text-success" style="font-size:1rem;">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection