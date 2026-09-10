@extends('administrasi.layouts.header')

@section('title', 'Detail Galeri')

@section('content')
<style>
    .page-header-show {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-show::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-show .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-show h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-show p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .detail-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-card-header i { color: #0284c7; font-size: 1.05rem; }
    .detail-card-header h5 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .detail-card-body { padding: 24px; }

    /* ========== IMAGE PREVIEW ========== */
    .image-preview-large {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 14px;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        position: relative;
        overflow: hidden;
    }
    .image-preview-large img {
        max-width: 100%;
        max-height: 500px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        object-fit: contain;
        transition: transform 0.3s;
        cursor: zoom-in;
    }
    .image-preview-large img:hover {
        transform: scale(1.02);
    }

    /* ========== INFO LIST ========== */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .info-item-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px dashed #f1f5f9;
    }
    .info-item-modern:last-child { border-bottom: none; }
    .info-item-modern .ico-wrap {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .info-item-modern .info-content {
        flex: 1;
        min-width: 0;
    }
    .info-item-modern .info-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 2px;
    }
    .info-item-modern .info-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }

    .badge-status-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .badge-status-detail.aktif { background: #d1fae5; color: #065f46; }
    .badge-status-detail.nonaktif { background: #f1f5f9; color: #64748b; }

    .badge-kategori-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }

    /* ========== BUTTONS ========== */
    .btn-detail-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
        border: none;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-detail-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .btn-detail-edit {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    .btn-detail-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }

    @media (max-width: 768px) {
        .page-header-show { padding: 18px; }
        .page-header-show h1 { font-size: 1.15rem; }
        .detail-card-body { padding: 18px; }
    }
</style>

@php
    $isActive = $gallery->status == 'active';
@endphp

<div class="page-header-show">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-image me-2"></i>
                Detail Galeri
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Informasi lengkap foto galeri
            </p>
        </div>
        <a href="{{ route('administrasi.galeri.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- KIRI: IMAGE PREVIEW --}}
    <div class="col-lg-7">
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fas fa-image"></i>
                <h5>Preview Foto</h5>
            </div>
            <div class="detail-card-body">
                <div class="image-preview-large">
                    @if($gallery->image)
                        <img src="{{ asset('storage/galleries/' . $gallery->image) }}"
                             alt="{{ $gallery->title }}"
                             onclick="window.open(this.src, '_blank')"
                             onerror="this.src='https://via.placeholder.com/500x400/e2e8f0/64748b?text=No+Image'">
                    @else
                        <div style="text-align:center;color:#94a3b8;">
                            <i class="fas fa-image" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                            <p>Tidak ada gambar</p>
                        </div>
                    @endif
                </div>
                <p style="text-align:center;font-size:0.75rem;color:#94a3b8;margin-top:12px;margin-bottom:0;">
                    <i class="fas fa-search-plus me-1"></i>
                    Klik gambar untuk membuka di tab baru
                </p>
            </div>
        </div>
    </div>

    {{-- KANAN: INFO DETAIL --}}
    <div class="col-lg-5">
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fas fa-info-circle"></i>
                <h5>Informasi Foto</h5>
            </div>
            <div class="detail-card-body">
                <div class="info-list">
                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-heading"></i></div>
                        <div class="info-content">
                            <div class="info-label">Judul</div>
                            <div class="info-value">{{ $gallery->title }}</div>
                        </div>
                    </div>

                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-tag"></i></div>
                        <div class="info-content">
                            <div class="info-label">Kategori</div>
                            <div class="info-value">
                                <span class="badge-kategori-detail">
                                    <i class="fas fa-tag"></i>
                                    {{ $gallery->category ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-toggle-on"></i></div>
                        <div class="info-content">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="badge-status-detail {{ $isActive ? 'aktif' : 'nonaktif' }}">
                                    <i class="fas fa-{{ $isActive ? 'check-circle' : 'eye-slash' }}"></i>
                                    {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($gallery->event_date)
                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-calendar-day"></i></div>
                        <div class="info-content">
                            <div class="info-label">Tanggal Event</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($gallery->event_date)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-clock"></i></div>
                        <div class="info-content">
                            <div class="info-label">Dibuat</div>
                            <div class="info-value">
                                {{ $gallery->created_at ? $gallery->created_at->translatedFormat('d F Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="info-item-modern">
                        <div class="ico-wrap"><i class="fas fa-sync-alt"></i></div>
                        <div class="info-content">
                            <div class="info-label">Terakhir Update</div>
                            <div class="info-value">
                                {{ $gallery->updated_at ? $gallery->updated_at->translatedFormat('d F Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DESKRIPSI --}}
        @if($gallery->description)
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fas fa-align-left"></i>
                <h5>Deskripsi</h5>
            </div>
            <div class="detail-card-body">
                <p style="font-size:0.85rem;color:#475569;line-height:1.6;margin:0;">
                    {{ $gallery->description }}
                </p>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('administrasi.galeri.edit', $gallery->id) }}"
                       class="btn-detail-action btn-detail-edit flex-fill">
                        <i class="fas fa-edit"></i> Edit Foto
                    </a>
                    <button type="button"
                            class="btn-detail-action btn-detail-delete"
                            onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- HIDDEN FORM DELETE --}}
<form id="deleteForm" action="{{ route('administrasi.galeri.destroy', $gallery->id) }}"
      method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Foto?',
            html: 'Foto <strong>"{{ addslashes($gallery->title) }}"</strong> akan dihapus permanen.',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endpush
@endsection