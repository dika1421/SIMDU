@extends('administrasi.layouts.header')

@section('title', 'Manajemen Galeri')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-galeri {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-galeri::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-galeri .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-galeri h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-galeri p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 9px 18px;
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

    /* ========== STATS ========== */
    .stats-galeri {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card-g {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card-g::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-card-g:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-card-g .stat-icon-g {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-card-g .stat-label-g {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-card-g .stat-value-g {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .sc-total .stat-icon-g { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .sc-aktif .stat-icon-g { background: linear-gradient(135deg, #10b981, #059669); }
    .sc-nonaktif .stat-icon-g { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .sc-kategori .stat-icon-g { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .sc-total::before { background: linear-gradient(180deg, #6366f1, #4f46e5); }
    .sc-aktif::before { background: linear-gradient(180deg, #10b981, #059669); }
    .sc-nonaktif::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
    .sc-kategori::before { background: linear-gradient(180deg, #f59e0b, #d97706); }

    /* ========== FILTER ========== */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px 22px;
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

    /* ========== GALLERY GRID ========== */
    .gallery-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .gallery-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.12);
    }
    .gallery-image-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4/3;
        background: #f1f5f9;
    }
    .gallery-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover .gallery-image-wrap img {
        transform: scale(1.08);
    }
    .gallery-image-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.7));
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .gallery-card:hover .gallery-image-wrap::after {
        opacity: 1;
    }
    .gallery-badges {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 6px;
        z-index: 2;
        flex-wrap: wrap;
    }
    .badge-status-modern {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }
    .badge-status-modern.aktif {
        background: rgba(16, 185, 129, 0.9);
        color: #fff;
    }
    .badge-status-modern.nonaktif {
        background: rgba(107, 114, 128, 0.9);
        color: #fff;
    }
    .badge-kategori-modern {
        background: rgba(255,255,255,0.95);
        color: #4f46e5;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .gallery-body {
        padding: 16px 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .gallery-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .gallery-desc {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 10px;
        flex: 1;
    }
    .gallery-date {
        font-size: 0.72rem;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 12px;
    }

    .gallery-actions {
        display: flex;
        gap: 6px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    .btn-gallery-action {
        flex: 1;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-gallery-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .btn-gallery-view {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: #4338ca;
    }
    .btn-gallery-edit {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }
    .btn-gallery-delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state-g {
        background: #fff;
        border-radius: 16px;
        padding: 60px 20px;
        text-align: center;
        border: 1px solid #f1f5f9;
    }
    .empty-state-g-icon {
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
        .page-header-galeri { padding: 18px; }
        .page-header-galeri h1 { font-size: 1.15rem; }
    }
</style>

@php
    $totalGaleri    = $galleries->total() ?? 0;
    $totalAktif     = $galleries->where('status', 'active')->count();
    $totalNonaktif  = $galleries->where('status', 'inactive')->count();
    $totalKategori  = $galleries->pluck('category')->filter()->unique()->count();
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-galeri">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-images me-2"></i>
                Manajemen Galeri
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola semua foto dan dokumentasi kegiatan sekolah
            </p>
        </div>
        <a href="{{ route('administrasi.galeri.create') }}" class="btn-glass">
            <i class="fas fa-plus"></i> Tambah Foto
        </a>
    </div>
</div>

{{-- ============ ALERT ========== --}}
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

{{-- ============ STATS ========== --}}
<div class="stats-galeri">
    <div class="stat-card-g sc-total">
        <div class="stat-icon-g"><i class="fas fa-images"></i></div>
        <div class="stat-label-g">Total Foto</div>
        <div class="stat-value-g">{{ $totalGaleri }}</div>
    </div>
    <div class="stat-card-g sc-aktif">
        <div class="stat-icon-g"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label-g">Aktif</div>
        <div class="stat-value-g">{{ $totalAktif }}</div>
    </div>
    <div class="stat-card-g sc-nonaktif">
        <div class="stat-icon-g"><i class="fas fa-eye-slash"></i></div>
        <div class="stat-label-g">Nonaktif</div>
        <div class="stat-value-g">{{ $totalNonaktif }}</div>
    </div>
    <div class="stat-card-g sc-kategori">
        <div class="stat-icon-g"><i class="fas fa-tags"></i></div>
        <div class="stat-label-g">Kategori</div>
        <div class="stat-value-g">{{ $totalKategori }}</div>
    </div>
</div>

{{-- ============ FILTER ========== --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.galeri.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-tags"></i> Kategori
            </label>
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach(['Kegiatan', 'Prestasi', 'Acara', 'Sekolah', 'Lainnya'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-toggle-on"></i> Status
            </label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari
            </label>
            <input type="text" name="search" class="form-control"
                   placeholder="Cari judul foto..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-3 fw-semibold flex-grow-1" style="padding:9px">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('administrasi.galeri.index') }}" class="btn btn-secondary rounded-3" style="padding:9px">
                    <i class="fas fa-sync"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ============ GALLERY GRID ========== --}}
@if($galleries->count() > 0)
    <div class="row g-3">
        @foreach($galleries as $gallery)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-card">
                <div class="gallery-image-wrap">
                    <img src="{{ asset('storage/galleries/' . $gallery->image) }}"
                         alt="{{ $gallery->title }}"
                         onerror="this.src='https://via.placeholder.com/400x300/e2e8f0/64748b?text=No+Image'">
                    <div class="gallery-badges">
                        <span class="badge-status-modern {{ $gallery->status == 'active' ? 'aktif' : 'nonaktif' }}">
                            <i class="fas fa-{{ $gallery->status == 'active' ? 'check-circle' : 'eye-slash' }}"></i>
                            {{ $gallery->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($gallery->category)
                            <span class="badge-kategori-modern">
                                <i class="fas fa-tag"></i>
                                {{ $gallery->category }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="gallery-body">
                    <h6 class="gallery-title">{{ $gallery->title }}</h6>
                    <p class="gallery-desc">{{ $gallery->description ?: 'Tidak ada deskripsi' }}</p>
                    @if($gallery->event_date)
                        <div class="gallery-date">
                            <i class="fas fa-calendar-day"></i>
                            {{ \Carbon\Carbon::parse($gallery->event_date)->translatedFormat('d F Y') }}
                        </div>
                    @endif
                    <div class="gallery-actions">
                        <a href="{{ route('administrasi.galeri.show', $gallery->id) }}"
                           class="btn-gallery-action btn-gallery-view" title="Detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('administrasi.galeri.edit', $gallery->id) }}"
                           class="btn-gallery-action btn-gallery-edit" title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button"
                                class="btn-gallery-action btn-gallery-delete"
                                onclick="deleteGallery({{ $gallery->id }}, '{{ addslashes($gallery->title) }}')"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $galleries->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="empty-state-g">
        <div class="empty-state-g-icon">
            <i class="fas fa-images"></i>
        </div>
        <h5 class="fw-bold mb-2">Belum ada foto di galeri</h5>
        <p class="text-muted mb-4">Mulai tambahkan foto kegiatan sekolah untuk dokumentasi</p>
        <a href="{{ route('administrasi.galeri.create') }}" class="btn btn-primary rounded-3">
            <i class="fas fa-plus me-1"></i> Tambah Foto Pertama
        </a>
    </div>
@endif

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
                <h5 class="fw-bold mb-2">Yakin ingin menghapus foto?</h5>
                <p class="text-muted mb-0">Foto <strong id="deleteTitle" class="text-danger"></strong> akan dihapus permanen.</p>
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
    function deleteGallery(id, title) {
        document.getElementById('deleteTitle').innerText = title;
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.galeri.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection