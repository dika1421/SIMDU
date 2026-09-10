@extends('administrasi.layouts.header')

@section('title', 'Daftar Guru')

@section('content')
<style>
    /* ========== GLOBAL ========== */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -60%;
        right: 20%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 4px;
        position: relative;
        z-index: 2;
    }
    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
        position: relative;
        z-index: 2;
    }
    .page-header .btn {
        position: relative;
        z-index: 2;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 8px 16px;
        transition: all 0.25s;
        border: none;
    }
    .page-header .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    .btn-white-glass {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }
    .btn-white-glass:hover {
        background: rgba(255,255,255,0.3);
        color: #fff;
    }

    /* ========== STAT CARDS ========== */
    .stat-card-modern {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: width 0.3s;
    }
    .stat-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }
    .stat-card-modern:hover::before {
        width: 100%;
        opacity: 0.06;
    }
    .stat-card-modern.stat-primary::before { background: linear-gradient(180deg, #667eea, #764ba2); }
    .stat-card-modern.stat-info::before    { background: linear-gradient(180deg, #4facfe, #00f2fe); }
    .stat-card-modern.stat-danger::before  { background: linear-gradient(180deg, #f093fb, #f5576c); }
    .stat-card-modern.stat-success::before { background: linear-gradient(180deg, #11998e, #38ef7d); }

    .stat-icon-modern {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
    }
    .stat-primary .stat-icon-modern { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-info    .stat-icon-modern { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .stat-danger  .stat-icon-modern { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-success .stat-icon-modern { background: linear-gradient(135deg, #11998e, #38ef7d); }

    .stat-label-modern {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .stat-value-modern {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin: 0;
    }

    /* ========== MAIN CARD ========== */
    .main-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .main-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    .main-card-body {
        padding: 0;
    }

    /* ========== SEARCH BOX ========== */
    .search-wrapper {
        position: relative;
    }
    .search-wrapper .form-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px 10px 42px;
        font-size: 0.85rem;
        transition: all 0.2s;
        background: #fff;
    }
    .search-wrapper .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    /* ========== TABLE ========== */
    .table-modern {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 14px 12px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .table-modern tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-modern tbody tr {
        transition: all 0.2s;
    }
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }
    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* ========== AVATAR ========== */
    .avatar-guru {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }
    .avatar-guru.color-0 { background: linear-gradient(135deg, #667eea, #764ba2); }
    .avatar-guru.color-1 { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .avatar-guru.color-2 { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .avatar-guru.color-3 { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .avatar-guru.color-4 { background: linear-gradient(135deg, #f59e0b, #fbbf24); }

    /* ========== BADGES ========== */
    .badge-pill-modern {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.2;
    }
    .badge-jk-L {
        background: #dbeafe;
        color: #1e40af;
    }
    .badge-jk-P {
        background: #fce7f3;
        color: #be185d;
    }
    .badge-mapel-modern {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: #4338ca;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.68rem;
        font-weight: 600;
        margin: 2px;
        border: 1px solid #c7d2fe;
    }
    .badge-nuptk {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    /* ========== AKSI BUTTONS ========== */
    .btn-action {
        width: 32px;
        height: 32px;
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
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 2.5rem;
    }

    /* ========== MODAL MODERN ========== */
    .modal-modern .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-modern .modal-header {
        border: none;
        padding: 22px 26px;
    }
    .modal-modern .modal-header.bg-danger-gradient {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .modal-modern .modal-header.bg-success-gradient {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .modal-modern .modal-title {
        font-size: 1rem;
        font-weight: 700;
    }
    .modal-modern .modal-body {
        padding: 26px;
    }
    .modal-modern .modal-footer {
        border: none;
        padding: 16px 26px 22px;
    }
    .modal-icon-big {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    /* ========== FILE INPUT ========== */
    .file-input-modern {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        background: #fafbfc;
    }
    .file-input-modern:hover {
        border-color: #667eea;
        background: #f8fafc;
    }
    .file-input-modern input[type="file"] {
        display: none;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header { padding: 18px; }
        .page-header h1 { font-size: 1.2rem; }
        .stat-value-modern { font-size: 1.3rem; }
        .stat-icon-modern { width: 44px; height: 44px; font-size: 1.1rem; }
        .table-modern { font-size: 0.75rem; }
        .table-modern tbody td { padding: 10px 8px; }
    }
</style>

{{-- ==================== PAGE HEADER ==================== --}}
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1>
                <i class="fas fa-chalkboard-user me-2"></i>
                Daftar Guru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola data guru, mata pelajaran, dan informasi kepegawaian
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-white-glass"
                    data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-csv me-1"></i> Import CSV
            </button>
            <a href="{{ route('administrasi.guru.create') }}" class="btn btn-light">
                <i class="fas fa-plus me-1"></i> Tambah Guru
            </a>
        </div>
    </div>
</div>

{{-- ==================== STAT CARDS ==================== --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern stat-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label-modern">Total Guru</div>
                    <h3 class="stat-value-modern">{{ $guru->total() ?? 0 }}</h3>
                </div>
                <div class="stat-icon-modern">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern stat-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label-modern">Guru Laki-laki</div>
                    <h3 class="stat-value-modern">{{ $guruLaki ?? 0 }}</h3>
                </div>
                <div class="stat-icon-modern">
                    <i class="fas fa-mars"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern stat-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label-modern">Guru Perempuan</div>
                    <h3 class="stat-value-modern">{{ $guruPerempuan ?? 0 }}</h3>
                </div>
                <div class="stat-icon-modern">
                    <i class="fas fa-venus"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern stat-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label-modern">Rata-rata Usia</div>
                    <h3 class="stat-value-modern">
                        {{ $rataUsia ?? 0 }}
                        <small class="fs-6 fw-normal text-muted">thn</small>
                    </h3>
                </div>
                <div class="stat-icon-modern">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== ALERT MESSAGES ==================== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        @if(session('import_errors'))
            <hr>
            <strong>Detail Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ==================== MAIN CARD ==================== --}}
<div class="main-card">
    <div class="main-card-header">
        <div class="row g-2 align-items-center">
            <div class="col-md-7">
                <form action="{{ route('administrasi.guru.index') }}" method="GET" class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nama guru, NUPTK, NIP, atau jabatan..."
                           value="{{ request('search') }}">
                </form>
            </div>
            <div class="col-md-5 text-md-end">
                @if(request('search'))
                    <a href="{{ route('administrasi.guru.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                        <i class="fas fa-times me-1"></i> Reset Pencarian
                    </a>
                @else
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                        <i class="fas fa-database me-1"></i>
                        {{ $guru->total() ?? 0 }} Data Guru
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="main-card-body">
        <div class="table-responsive">
            <table class="table table-modern table-hover mb-0">
                <thead>
                    <tr class="text-center">
                        <th width="45">NO.</th>
                        <th width="200">NAMA GURU</th>
                        <th width="55">JK</th>
                        <th width="150">TTL</th>
                        <th width="180">ALAMAT</th>
                        <th width="130">NUPTK / NIP</th>
                        <th width="110">JABATAN</th>
                        <th width="200">MATA PELAJARAN</th>
                        <th width="140">PENDIDIKAN</th>
                        <th width="100">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $key => $g)
                    @php
                        $initial = strtoupper(substr($g->nama_lengkap ?? 'G', 0, 1));
                        $colorClass = 'color-' . ($key % 5);
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="fw-bold text-muted">{{ $key + $guru->firstItem() }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-guru {{ $colorClass }}">
                                    {{ $initial }}
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 140px;">
                                        {{ $g->nama_lengkap ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope fa-xs me-1"></i>
                                        {{ Str::limit($g->user->email ?? '-', 25) }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if(($g->jenis_kelamin ?? '') == 'L')
                                <span class="badge-pill-modern badge-jk-L">
                                    <i class="fas fa-mars"></i> L
                                </span>
                            @elseif(($g->jenis_kelamin ?? '') == 'P')
                                <span class="badge-pill-modern badge-jk-P">
                                    <i class="fas fa-venus"></i> P
                                </span>
                            @else
                                <span class="badge-pill-modern" style="background:#f1f5f9;color:#64748b;">
                                    <i class="fas fa-minus"></i> -
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $g->tempat_lahir ?? '-' }}</div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt fa-xs me-1"></i>
                                {{ $g->tanggal_lahir ? \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted" style="display:block;max-width:180px;">
                                <i class="fas fa-map-marker-alt fa-xs me-1"></i>
                                {{ Str::limit($g->alamat ?? '-', 55) }}
                            </small>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge-nuptk">
                                    <i class="fas fa-id-card fa-xs me-1"></i>
                                    {{ $g->nuptk ?? '-' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                <strong>NIP:</strong> {{ $g->nip ?? '-' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge-pill-modern" style="background:#f1f5f9;color:#334155;">
                                <i class="fas fa-briefcase fa-xs"></i>
                                {{ $g->status_kepegawaian ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($g->mataPelajaran && $g->mataPelajaran->count() > 0)
                                <div style="max-height: 75px; overflow-y: auto;">
                                    @foreach($g->mataPelajaran as $mapel)
                                        <span class="badge-mapel-modern">
                                            <i class="fas fa-book-open"></i>
                                            {{ Str::limit($mapel->nama_mapel, 22) }}
                                        </span>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-layer-group fa-xs"></i>
                                    {{ $g->mataPelajaran->count() }} Mapel
                                </small>
                            @else
                                <span class="badge-pill-modern" style="background:#f1f5f9;color:#94a3b8;">
                                    <i class="fas fa-minus"></i> Belum ada
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $g->pendidikan_terakhir ?? '-' }}</div>
                            <small class="text-muted d-block">{{ $g->jurusan_pendidikan ?? '-' }}</small>
                            <small class="text-muted fst-italic">{{ Str::limit($g->universitas ?? '-', 20) }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('administrasi.guru.edit', $g->id) }}"
                                   class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn-action btn-delete" title="Hapus"
                                        onclick="confirmDelete({{ $g->id }}, '{{ addslashes($g->nama_lengkap ?? '') }}')">
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
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Belum ada data guru</h5>
                                <p class="text-muted mb-4">
                                    Silakan tambah guru melalui tombol "Tambah Guru" atau "Import CSV"
                                </p>
                                <a href="{{ route('administrasi.guru.create') }}" class="btn btn-primary rounded-3">
                                    <i class="fas fa-plus me-1"></i> Tambah Guru Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($guru->hasPages() || $guru->total() > 0)
            <div class="d-flex justify-content-between align-items-center p-3 border-top flex-wrap gap-2">
                <small class="text-muted">
                    <i class="fas fa-table me-1"></i>
                    Menampilkan <strong>{{ $guru->firstItem() ?? 0 }}</strong> -
                    <strong>{{ $guru->lastItem() ?? 0 }}</strong> dari
                    <strong>{{ $guru->total() ?? 0 }}</strong> data
                </small>
                <div>
                    {{ $guru->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ==================== MODAL KONFIRMASI HAPUS ==================== --}}
<div class="modal fade modal-modern" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger-gradient text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="modal-icon-big" style="background:#fee2e2;color:#dc2626;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="fw-bold mb-2">Yakin ingin menghapus?</h5>
                <p class="mb-3">Data guru <strong id="guruName" class="text-danger"></strong> akan dihapus permanen.</p>
                <div class="alert alert-warning mb-0 rounded-3 text-start">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="" class="w-100 d-flex gap-2">
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

{{-- ==================== MODAL IMPORT CSV ==================== --}}
<div class="modal fade modal-modern" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success-gradient text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-csv me-2"></i> Import Data Guru dari CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('administrasi.guru.import') }}" method="POST"
                  enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info rounded-3 border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk Import CSV:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>File berformat <strong>.CSV</strong> dengan separator <strong>koma (,)</strong></li>
                            <li>Kolom wajib: <strong>Nama, NUPTK, NIP, Jenis Kelamin, Jabatan</strong></li>
                            <li>Mata pelajaran dipisah koma: <em>Matematika, Fisika, Kimia</em></li>
                            <li>Ukuran maksimal file: <strong>2 MB</strong></li>
                        </ul>
                    </div>

                    <label for="file" class="file-input-modern d-block">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                        <div class="fw-bold mb-1">Klik untuk pilih file CSV</div>
                        <small class="text-muted" id="fileName">Atau drag & drop file ke sini</small>
                        <input type="file" name="file" id="file" accept=".csv" required>
                    </label>

                    <div class="progress d-none mt-3" id="importProgress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success rounded-3" id="btnImport">
                        <i class="fas fa-upload me-1"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============ DELETE ============
    function confirmDelete(id, name) {
        document.getElementById('guruName').innerText = name;

        var deleteForm = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.guru.destroy', ':id') }}";
        url = url.replace(':id', id);
        deleteForm.action = url;

        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }

    // ============ SEARCH (Enter) ============
    document.querySelector('.search-wrapper input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });

    // ============ FILE INPUT PREVIEW ============
    document.getElementById('file')?.addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Atau drag & drop file ke sini';
        document.getElementById('fileName').innerText = fileName;
    });

    // ============ IMPORT FORM ============
    document.getElementById('importForm')?.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file');
        const progressDiv = document.getElementById('importProgress');
        const btnImport = document.getElementById('btnImport');

        if (!fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Silakan pilih file CSV terlebih dahulu!',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        const fileName = fileInput.files[0].name;
        const extension = fileName.split('.').pop().toLowerCase();
        if (extension !== 'csv') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Format Salah',
                text: 'File harus berformat .csv!',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        progressDiv.classList.remove('d-none');
        btnImport.disabled = true;
        btnImport.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

        let progress = 0;
        const interval = setInterval(function() {
            progress += 10;
            const progressBar = document.querySelector('#importProgress .progress-bar');
            if (progressBar) {
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
            }
            if (progress >= 100) clearInterval(interval);
        }, 200);
    });
</script>
@endpush