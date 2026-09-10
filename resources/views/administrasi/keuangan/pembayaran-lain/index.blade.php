@extends('administrasi.layouts.header')

@section('title', 'Data Pembayaran Lain')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-pl {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-pl::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-pl::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-pl .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-pl h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-pl p {
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
    .stats-pl {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-pl-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-pl-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-pl-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-pl-card .stat-icon-s {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-pl-card .stat-label-s {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-pl-card .stat-value-s {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .stat-pl-card.stat-total   .stat-icon-s { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-pl-card.stat-trx     .stat-icon-s { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .stat-pl-card.stat-now     .stat-icon-s { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-pl-card.stat-kategori .stat-icon-s { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-pl-card.stat-total::before   { background: linear-gradient(180deg, #8b5cf6, #6d28d9); }
    .stat-pl-card.stat-trx::before     { background: linear-gradient(180deg, #0ea5e9, #0284c7); }
    .stat-pl-card.stat-now::before     { background: linear-gradient(180deg, #10b981, #059669); }
    .stat-pl-card.stat-kategori::before { background: linear-gradient(180deg, #f59e0b, #d97706); }

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
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
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
    .main-card-header h5 i { color: #7c3aed; }

    /* ========== TABLE ========== */
    .table-pl {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-pl thead th {
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
    .table-pl tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-pl tbody tr {
        transition: all 0.2s;
    }
    .table-pl tbody tr:hover {
        background: #f8fafc;
    }

    .avatar-siswa-pl {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .no-trx-badge {
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
        background: #ede9fe;
        color: #5b21b6;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid #ddd6fe;
    }
    .jumlah-text {
        font-weight: 700;
        color: #059669;
        font-size: 0.85rem;
    }
    .metode-badge {
        background: #e0e7ff;
        color: #3730a3;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
    }
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

    /* ========== EMPTY STATE ========== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
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
    .modal-modern .modal-title { font-size: 1rem; font-weight: 700; }
    .modal-modern .modal-body { padding: 26px; text-align: center; }
    .modal-modern .modal-footer { border: none; padding: 16px 24px 22px; }
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
        .page-header-pl { padding: 18px; }
        .page-header-pl h1 { font-size: 1.15rem; }
        .table-pl { font-size: 0.72rem; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-pl">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-money-bill-wave me-2"></i>
                Data Pembayaran Lain
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola semua pembayaran selain SPP (Uang Gedung, Seragam, Buku, dll.)
            </p>
        </div>
        <a href="{{ route('administrasi.keuangan.pembayaran-lain.create') }}" class="btn-glass">
            <i class="fas fa-plus"></i> Tambah Pembayaran
        </a>
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
    $totalData = $pembayaranLain->total() ?? 0;
    $totalNominal = method_exists($pembayaranLain, 'getCollection')
        ? $pembayaranLain->getCollection()->sum(function($i) {
            return $i->jumlah ?? 0;
        })
        : 0;
    $bulanIni = method_exists($pembayaranLain, 'getCollection')
        ? $pembayaranLain->getCollection()->filter(function($i) {
            return isset($i->tanggal_bayar) &&
                   \Carbon\Carbon::parse($i->tanggal_bayar)->format('Y-m') == date('Y-m');
        })->count()
        : 0;
    $totalKategori = method_exists($pembayaranLain, 'getCollection')
        ? $pembayaranLain->getCollection()->pluck('kategori')->unique()->count()
        : 0;
@endphp
<div class="stats-pl">
    <div class="stat-pl-card stat-total">
        <div class="stat-icon-s"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-label-s">Total Data</div>
        <div class="stat-value-s">{{ $totalData }}</div>
    </div>
    <div class="stat-pl-card stat-trx">
        <div class="stat-icon-s"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-label-s">Total Nominal</div>
        <div class="stat-value-s" style="font-size:1rem;">
            Rp {{ number_format($totalNominal, 0, ',', '.') }}
        </div>
    </div>
    <div class="stat-pl-card stat-now">
        <div class="stat-icon-s"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-label-s">Bulan Ini</div>
        <div class="stat-value-s">{{ $bulanIni }}</div>
    </div>
    <div class="stat-pl-card stat-kategori">
        <div class="stat-icon-s"><i class="fas fa-tags"></i></div>
        <div class="stat-label-s">Kategori</div>
        <div class="stat-value-s">{{ $totalKategori }}</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-table"></i>
            Daftar Pembayaran Lain
        </h5>
        <span class="badge bg-purple bg-opacity-10 text-purple rounded-pill px-3 py-2" style="background:#ede9fe!important;color:#5b21b6!important;">
            <i class="fas fa-database me-1"></i> {{ $pembayaranLain->total() ?? 0 }} Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-pl table-hover mb-0">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="10%">No. Transaksi</th>
                    <th width="8%">NIS</th>
                    <th width="18%">Nama Siswa</th>
                    <th width="10%">Kelas</th>
                    <th width="13%">Kategori</th>
                    <th width="12%">Jumlah</th>
                    <th width="9%">Metode</th>
                    <th width="9%">Tgl Bayar</th>
                    <th width="7%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaranLain as $key => $item)
                @php
                    $nama = $item->siswa->user->name ?? $item->siswa->nama_lengkap ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $key + $pembayaranLain->firstItem() }}</td>
                    <td>
                        <span class="no-trx-badge">
                            {{ $item->no_transaksi ?? $item->kode_transaksi ?? 'TRX-' . $item->id }}
                        </span>
                    </td>
                    <td>
                        <span class="nis-badge">{{ $item->siswa->nis ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-siswa-pl">{{ $initial }}</div>
                            <span class="fw-semibold text-dark text-truncate" style="max-width:150px;">
                                {{ $nama }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                            {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="kategori-badge">
                            <i class="fas fa-tag"></i>
                            {{ Str::limit($item->kategori ?? $item->jenis_pembayaran ?? $item->jenis ?? '-', 18) }}
                        </span>
                    </td>
                    <td>
                        <span class="jumlah-text">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="metode-badge">{{ $item->metode_bayar ?? $item->metode ?? '-' }}</span>
                    </td>
                    <td>
                        <small>{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}</small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('administrasi.keuangan.pembayaran-lain.edit', $item->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn-action btn-delete" title="Hapus"
                                    onclick="deleteItem({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data pembayaran lain</h5>
                            <p class="text-muted mb-3">Silakan tambahkan data pembayaran baru</p>
                            <a href="{{ route('administrasi.keuangan.pembayaran-lain.create') }}" class="btn btn-primary rounded-3">
                                <i class="fas fa-plus me-1"></i> Tambah Pembayaran
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pembayaranLain->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            <i class="fas fa-table me-1"></i>
            Menampilkan <strong>{{ $pembayaranLain->firstItem() ?? 0 }}</strong> -
            <strong>{{ $pembayaranLain->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $pembayaranLain->total() ?? 0 }}</strong> data
        </small>
        <div>{{ $pembayaranLain->links('pagination::bootstrap-5') }}</div>
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
    function deleteItem(id) {
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.keuangan.pembayaran-lain.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
@endsection