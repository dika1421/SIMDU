@extends('administrasi.layouts.header')

@section('title', 'Pembayaran SPP')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-spp {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-spp::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-spp::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-spp .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-spp h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-spp p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ========== STAT CARDS ========== */
    .stats-spp {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-spp-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-spp-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-spp-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-spp-card .stat-icon-s {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-spp-card .stat-label-s {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-spp-card .stat-value-s {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .stat-spp-card.stat-total   .stat-icon-s { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }
    .stat-spp-card.stat-lunas   .stat-icon-s { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-spp-card.stat-belum   .stat-icon-s { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-spp-card.stat-telat   .stat-icon-s { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-spp-card.stat-total::before { background: linear-gradient(180deg, #0ea5e9, #06b6d4); }
    .stat-spp-card.stat-lunas::before { background: linear-gradient(180deg, #10b981, #34d399); }
    .stat-spp-card.stat-belum::before { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .stat-spp-card.stat-telat::before { background: linear-gradient(180deg, #ef4444, #f87171); }

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
        transition: all 0.2s;
    }
    .filter-card .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
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
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
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
    .main-card-header h5 i { color: #059669; }

    /* ========== TABLE ========== */
    .table-spp {
        margin: 0;
        font-size: 0.82rem;
    }
    .table-spp thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 13px 10px;
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-spp tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-spp tbody tr {
        transition: all 0.2s;
    }
    .table-spp tbody tr:hover {
        background: #f8fafc;
    }

    .avatar-siswa-spp {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .no-trans-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #334155;
        border: 1px solid #e2e8f0;
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
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid #bfdbfe;
    }
    .jumlah-text {
        font-weight: 700;
        color: #059669;
        font-size: 0.85rem;
    }
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-chip.lunas     { background: #d1fae5; color: #065f46; }
    .status-chip.belum     { background: #fef3c7; color: #92400e; }
    .status-chip.terlambat { background: #fee2e2; color: #991b1b; }

    .btn-action {
        width: 32px; height: 32px;
        padding: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: none;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .btn-action.btn-edit {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: #fff;
    }
    .btn-action.btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: 2.5rem;
    }

    /* ========== MODAL MODERN ========== */
    .modal-modern .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-modern .modal-header {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border: none;
        padding: 20px 24px;
    }
    .modal-modern .modal-title {
        font-size: 1rem;
        font-weight: 700;
    }
    .modal-modern .modal-body {
        padding: 26px;
        text-align: center;
    }
    .modal-modern .modal-footer {
        border: none;
        padding: 16px 24px 22px;
    }
    .modal-icon-big {
        width: 70px; height: 70px;
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #fee2e2;
        color: #dc2626;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-spp { padding: 18px; }
        .page-header-spp h1 { font-size: 1.15rem; }
        .table-spp { font-size: 0.72rem; }
        .table-spp tbody td { padding: 9px 6px; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-spp">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-money-bill-wave me-2"></i>
                Pembayaran SPP
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola data pembayaran SPP siswa dengan mudah
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.keuangan.spp.laporan') }}" class="btn-glass">
                <i class="fas fa-chart-line"></i> Laporan
            </a>
            <a href="{{ route('administrasi.keuangan.spp.create') }}" class="btn-glass">
                <i class="fas fa-plus"></i> Tambah Pembayaran
            </a>
        </div>
    </div>
</div>

{{-- ============ ALERT ============ --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ STAT CARDS ============ --}}
@php
    $totalData   = $spp->total() ?? 0;
    $totalLunas  = method_exists($spp, 'getCollection') ? $spp->getCollection()->where('status', 'lunas')->count() : 0;
    $totalBelum  = method_exists($spp, 'getCollection') ? $spp->getCollection()->where('status', 'belum_bayar')->count() : 0;
    $totalTelat  = method_exists($spp, 'getCollection') ? $spp->getCollection()->where('status', 'terlambat')->count() : 0;
@endphp
<div class="stats-spp">
    <div class="stat-spp-card stat-total">
        <div class="stat-icon-s"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-label-s">Total Data</div>
        <div class="stat-value-s">{{ $totalData }}</div>
    </div>
    <div class="stat-spp-card stat-lunas">
        <div class="stat-icon-s"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label-s">Lunas</div>
        <div class="stat-value-s">{{ $totalLunas }}</div>
    </div>
    <div class="stat-spp-card stat-belum">
        <div class="stat-icon-s"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-label-s">Belum Bayar</div>
        <div class="stat-value-s">{{ $totalBelum }}</div>
    </div>
    <div class="stat-spp-card stat-telat">
        <div class="stat-icon-s"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-label-s">Terlambat</div>
        <div class="stat-value-s">{{ $totalTelat }}</div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.keuangan.spp') }}" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Bulan
            </label>
            <select name="bulan" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @foreach($bulanList as $key => $nama)
                    <option value="{{ $key }}" {{ ($bulan ?? '') == $key ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-calendar"></i> Tahun
            </label>
            <select name="tahun" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ ($tahun ?? '') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-school"></i> Kelas
            </label>
            <select name="kelas" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ (request('kelas') ?? '') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-tag"></i> Status
            </label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach($statusList as $key => $nama)
                    <option value="{{ $key }}" {{ (request('status') ?? '') == $key ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-list"></i> Kategori
            </label>
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $key => $nama)
                    <option value="{{ $key }}" {{ (request('kategori') ?? '') == $key ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <a href="{{ route('administrasi.keuangan.spp') }}" class="btn btn-secondary w-100 rounded-3 fw-semibold" style="padding:9px">
                <i class="fas fa-undo me-1"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-table"></i>
            Daftar Pembayaran SPP
        </h5>
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
            <i class="fas fa-database me-1"></i> {{ $spp->total() ?? 0 }} Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-spp table-hover mb-0">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="10%">No. Transaksi</th>
                    <th width="8%">NIS</th>
                    <th width="18%">Nama Siswa</th>
                    <th width="12%">Kelas</th>
                    <th width="11%">Kategori</th>
                    <th width="7%">Bulan</th>
                    <th width="6%">Tahun</th>
                    <th width="11%">Jumlah</th>
                    <th width="9%">Tgl Bayar</th>
                    <th width="8%">Metode</th>
                    <th width="8%">Status</th>
                    <th width="8%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spp as $index => $item)
                @php
                    $nama = $item->siswa->user->name ?? $item->siswa->nama_lengkap ?? $item->siswa->nama ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));
                    $statusClass = $item->status == 'lunas' ? 'lunas' : ($item->status == 'terlambat' ? 'terlambat' : 'belum');
                    $statusIcon = $item->status == 'lunas' ? 'fa-check-circle' : ($item->status == 'terlambat' ? 'fa-exclamation-triangle' : 'fa-hourglass-half');
                    $statusLabel = $item->status == 'lunas' ? 'Lunas' : ($item->status == 'terlambat' ? 'Terlambat' : 'Belum Bayar');
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $spp->firstItem() + $index }}</td>
                    <td>
                        <span class="no-trans-badge">
                            {{ $item->no_transaksi ?? 'SPP-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <span class="nis-badge">{{ $item->siswa->nis ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-siswa-spp">{{ $initial }}</div>
                            <span class="fw-semibold text-dark text-truncate" style="max-width:150px;">
                                {{ $nama }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                            {{ $item->siswa->kelas->nama_kelas ?? $item->siswa->kelas->nama ?? '-' }}
                        </span>
                        @if($item->siswa->kelas && $item->siswa->kelas->tingkat)
                            <small class="text-muted d-block">({{ $item->siswa->kelas->tingkat }})</small>
                        @endif
                    </td>
                    <td>
                        <span class="kategori-badge">
                            <i class="fas fa-tag"></i>
                            {{ Str::limit($item->kategori ?? 'SPP Bulanan', 18) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark">{{ $bulanList[$item->bulan] ?? '-' }}</span>
                    </td>
                    <td class="text-center">{{ $item->tahun }}</td>
                    <td>
                        <span class="jumlah-text">Rp {{ number_format($item->jumlah ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td class="text-center">
                        <small>{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary rounded-pill">{{ ucfirst($item->metode_bayar ?? '-') }}</span>
                    </td>
                    <td class="text-center">
                        <span class="status-chip {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }}"></i> {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('administrasi.keuangan.spp.edit', $item->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn-action btn-delete" title="Hapus"
                                    onclick="deletePayment({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data pembayaran SPP</h5>
                            <p class="text-muted mb-3">Silakan tambahkan data pembayaran baru</p>
                            <a href="{{ route('administrasi.keuangan.spp.create') }}" class="btn btn-primary rounded-3">
                                <i class="fas fa-plus me-1"></i> Tambah Pembayaran
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($spp->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            <i class="fas fa-table me-1"></i>
            Menampilkan <strong>{{ $spp->firstItem() ?? 0 }}</strong> -
            <strong>{{ $spp->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $spp->total() ?? 0 }}</strong> data
        </small>
        <div>{{ $spp->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>

{{-- ============ DELETE MODAL ============ --}}
<div class="modal fade modal-modern" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-big">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="fw-bold mb-2">Yakin ingin menghapus?</h5>
                <p class="text-muted mb-0">Data pembayaran ini akan dihapus permanen.</p>
                <div class="alert alert-warning mb-0 mt-3 rounded-3 text-start small">
                    <i class="fas fa-info-circle me-2"></i>
                    Data yang dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary flex-fill rounded-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger flex-fill rounded-3">
                        <i class="fas fa-trash-alt me-1"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deletePayment(id) {
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.keuangan.spp.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
@endsection