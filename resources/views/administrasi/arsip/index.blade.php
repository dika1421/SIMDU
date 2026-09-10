@extends('administrasi.layouts.header')

@section('title', 'Arsip Dokumen')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-arsip {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-arsip::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-arsip::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header-arsip .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-arsip h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-arsip p {
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
    .stats-arsip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-arsip-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-arsip-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-arsip-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-arsip-card .stat-icon-s {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-arsip-card .stat-label-s {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-arsip-card .stat-value-s {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .stat-total   .stat-icon-s { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .stat-kat     .stat-icon-s { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-tahun   .stat-icon-s { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .stat-bulan   .stat-icon-s { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-total::before   { background: linear-gradient(180deg, #6366f1, #4f46e5); }
    .stat-kat::before     { background: linear-gradient(180deg, #10b981, #059669); }
    .stat-tahun::before   { background: linear-gradient(180deg, #0ea5e9, #0284c7); }
    .stat-bulan::before   { background: linear-gradient(180deg, #f59e0b, #d97706); }

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
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
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
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
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
    .main-card-header h5 i { color: #4f46e5; }

    /* ========== TABLE ========== */
    .table-arsip { margin: 0; font-size: 0.83rem; }
    .table-arsip thead th {
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
    .table-arsip tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-arsip tbody tr { transition: all 0.2s; }
    .table-arsip tbody tr:hover { background: #f8fafc; }

    .nomor-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .doc-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .doc-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(99, 102, 241, 0.25);
    }
    .doc-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
    }
    .doc-meta {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 2px;
    }
    .kategori-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #eef2ff;
        color: #4338ca;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid #c7d2fe;
    }
    .tahun-badge {
        font-family: 'Courier New', monospace;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        border: 1px solid #fde68a;
    }
    .uploader-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .uploader-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
        flex-shrink: 0;
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
    .btn-action.btn-view { background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #fff; }
    .btn-action.btn-dl   { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .btn-action.btn-edit { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .btn-action.btn-del  { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-size: 2.5rem;
    }

    /* ========== MODAL ========== */
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

    @media (max-width: 768px) {
        .page-header-arsip { padding: 18px; }
        .page-header-arsip h1 { font-size: 1.15rem; }
        .table-arsip { font-size: 0.72rem; }
        .table-arsip tbody td { padding: 9px 6px; }
    }
</style>

@php
    $totalDok    = $arsip->total() ?? 0;
    $totalKat    = count($kategoriList);
    $tahunBaru   = $tahunList->first() ?? date('Y');
    $bulanIni    = $arsip->where('created_at', '>=', now()->startOfMonth())->count();
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-arsip">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-archive me-2"></i>
                Arsip Dokumen Sekolah
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola semua dokumen penting sekolah dengan aman
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.arsip.trash') }}" class="btn-glass">
                <i class="fas fa-trash-restore"></i> Tempat Sampah
            </a>
            <a href="{{ route('administrasi.arsip.create') }}" class="btn-glass">
                <i class="fas fa-upload"></i> Upload Dokumen
            </a>
        </div>
    </div>
</div>

{{-- ============ STAT CARDS ========== --}}
<div class="stats-arsip">
    <div class="stat-arsip-card stat-total">
        <div class="stat-icon-s"><i class="fas fa-file-alt"></i></div>
        <div class="stat-label-s">Total Dokumen</div>
        <div class="stat-value-s">{{ $totalDok }}</div>
    </div>
    <div class="stat-arsip-card stat-kat">
        <div class="stat-icon-s"><i class="fas fa-tags"></i></div>
        <div class="stat-label-s">Kategori</div>
        <div class="stat-value-s">{{ $totalKat }}</div>
    </div>
    <div class="stat-arsip-card stat-tahun">
        <div class="stat-icon-s"><i class="fas fa-calendar"></i></div>
        <div class="stat-label-s">Tahun Terbaru</div>
        <div class="stat-value-s">{{ $tahunBaru }}</div>
    </div>
    <div class="stat-arsip-card stat-bulan">
        <div class="stat-icon-s"><i class="fas fa-chart-line"></i></div>
        <div class="stat-label-s">Bulan Ini</div>
        <div class="stat-value-s">{{ $bulanIni }}</div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.arsip.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-tags"></i> Kategori
            </label>
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $key => $value)
                    <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-calendar"></i> Tahun
            </label>
            <select name="tahun" class="form-select">
                <option value="">Semua Tahun</option>
                @if($tahunList->isNotEmpty())
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                @else
                    @foreach(range(now()->year, now()->year-10) as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari
            </label>
            <input type="text" name="search" class="form-control"
                   placeholder="Nama / nomor dokumen..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-3 fw-semibold flex-grow-1" style="padding:9px">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('administrasi.arsip.index') }}" class="btn btn-secondary rounded-3" style="padding:9px">
                    <i class="fas fa-sync"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ============ MAIN CARD ========== --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-list"></i>
            Daftar Dokumen
        </h5>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">Tampilkan:</span>
            <select name="per_page" class="form-select form-select-sm w-auto" style="border-radius:8px"
                    onchange="window.location.href='{{ route('administrasi.arsip.index') }}?per_page='+this.value+'&kategori={{ request('kategori') }}&tahun={{ request('tahun') }}&search={{ request('search') }}'">
                @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-arsip table-hover mb-0">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="11%">Nomor Dokumen</th>
                    <th width="28%">Nama Dokumen</th>
                    <th width="13%">Kategori</th>
                    <th width="10%">Tanggal</th>
                    <th width="7%">Tahun</th>
                    <th width="13%">Uploader</th>
                    <th width="14%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsip as $index => $a)
                @php
                    $initial = strtoupper(substr($a->uploader->name ?? 'U', 0, 1));
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $arsip->firstItem() + $index }}</td>
                    <td>
                        <span class="nomor-badge">{{ $a->nomor_dokumen ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="doc-info">
                            <div class="doc-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <div class="doc-name">{{ $a->nama_dokumen ?? '-' }}</div>
                                @if($a->keterangan)
                                    <div class="doc-meta">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ Str::limit($a->keterangan, 50) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="kategori-badge">
                            <i class="fas fa-tag"></i>
                            {{ $kategoriList[$a->kategori] ?? $a->kategori }}
                        </span>
                    </td>
                    <td>
                        <small>
                            {{ $a->tanggal_dokumen ? \Carbon\Carbon::parse($a->tanggal_dokumen)->format('d/m/Y') : '-' }}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="tahun-badge">{{ $a->tahun ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="uploader-info">
                            <div class="uploader-avatar">{{ $initial }}</div>
                            <span class="fw-semibold text-dark text-truncate" style="max-width:100px;">
                                {{ $a->uploader->name ?? '-' }}
                            </span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('administrasi.arsip.show', $a->id) }}"
                               class="btn-action btn-view" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('administrasi.arsip.download', $a->id) }}"
                               class="btn-action btn-dl" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route('administrasi.arsip.edit', $a->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn-action btn-del" title="Hapus"
                                    onclick="confirmDelete({{ $a->id }}, '{{ addslashes($a->nama_dokumen) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data arsip</h5>
                            <p class="text-muted mb-3">Silakan upload dokumen pertama Anda</p>
                            <a href="{{ route('administrasi.arsip.create') }}" class="btn btn-primary rounded-3">
                                <i class="fas fa-upload me-1"></i> Upload Dokumen
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($arsip->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            <i class="fas fa-table me-1"></i>
            Menampilkan <strong>{{ $arsip->firstItem() ?? 0 }}</strong> -
            <strong>{{ $arsip->lastItem() ?? 0 }}</strong> dari
            <strong>{{ $arsip->total() }}</strong> entri
        </small>
        <div>{{ $arsip->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>

{{-- ============ DELETE MODAL ========== --}}
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
                <p class="text-muted mb-0">Dokumen <strong id="deleteName" class="text-danger"></strong> akan dipindahkan ke tempat sampah.</p>
                <div class="alert alert-warning mb-0 mt-3 rounded-3 text-start small">
                    <i class="fas fa-info-circle me-2"></i>
                    Dokumen masih dapat direstore dari Tempat Sampah.
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
    function confirmDelete(id, name) {
        document.getElementById('deleteName').innerText = name;
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.arsip.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection