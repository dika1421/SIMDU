@extends('administrasi.layouts.header')

@section('title', 'Detail Dokumen')

@section('content')
<style>
    .page-header-show {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 22px 26px;
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
    .page-header-show h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-show p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    /* Hero Section */
    .doc-hero {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 22px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 14px;
        margin-bottom: 20px;
        border: 1px solid #bae6fd;
    }
    .doc-hero-icon {
        width: 72px; height: 72px;
        border-radius: 18px;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.35);
    }
    .doc-hero-info { flex: 1; min-width: 0; }
    .doc-hero-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0c4a6e;
        margin-bottom: 6px;
        word-break: break-word;
    }
    .doc-hero-sub {
        font-size: 0.82rem;
        color: #0369a1;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 14px;
    }
    .info-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px 16px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .info-item:hover {
        background: #fff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .info-item .label {
        font-size: 0.68rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .info-item .label i { color: #0ea5e9; }
    .info-item .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    .info-item.full { grid-column: 1 / -1; }

    .nomor-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Courier New', monospace;
        background: #e0f2fe;
        color: #0c4a6e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #7dd3fc;
    }
    .kategori-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        color: #4338ca;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #c7d2fe;
    }
    .tahun-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Courier New', monospace;
        background: #fef3c7;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #fde68a;
    }
    .uploader-info-lg {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .uploader-avatar-lg {
        width: 40px; height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    /* File Preview */
    .preview-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .preview-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .preview-card-header i { color: #0284c7; font-size: 1.05rem; }
    .preview-card-header h5 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .preview-card-body { padding: 24px; text-align: center; }
    .preview-card-body img {
        max-height: 500px;
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .preview-placeholder {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
    }
    .preview-placeholder i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .btn-download-lg {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-download-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: #fff;
    }
</style>

@php
    $kategoriLabels = [
        'surat_keputusan' => 'Surat Keputusan',
        'laporan_bulanan' => 'Laporan Bulanan',
        'sertifikat' => 'Sertifikat',
        'dokumen_siswa' => 'Dokumen Siswa',
        'dokumen_guru' => 'Dokumen Guru',
        'akreditasi' => 'Akreditasi',
        'kurikulum' => 'Kurikulum',
        'keuangan' => 'Keuangan',
    ];
    $fileExt = $arsip->file_path ? strtolower(pathinfo($arsip->file_path, PATHINFO_EXTENSION)) : '';
    $isImage = in_array($fileExt, ['jpg','jpeg','png','gif','webp']);
    $isPdf = $fileExt === 'pdf';
    $initial = strtoupper(substr($arsip->uploader->name ?? 'U', 0, 1));
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-show">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-file-alt me-2"></i>
                Detail Dokumen
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Informasi lengkap dokumen arsip
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.arsip.index') }}" class="btn-glass">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn-glass">
                <i class="fas fa-download"></i> Download
            </a>
            <a href="{{ route('administrasi.arsip.edit', $arsip->id) }}" class="btn-glass">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
</div>

{{-- ============ INFO CARD ========== --}}
<div class="detail-card">
    <div class="detail-card-header">
        <i class="fas fa-info-circle"></i>
        <h5>Informasi Dokumen</h5>
    </div>
    <div class="detail-card-body">

        {{-- Hero --}}
        <div class="doc-hero">
            <div class="doc-hero-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="doc-hero-info">
                <div class="doc-hero-title">{{ $arsip->nama_dokumen }}</div>
                <div class="doc-hero-sub">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ $arsip->tanggal_dokumen ? \Carbon\Carbon::parse($arsip->tanggal_dokumen)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>
        </div>

        {{-- Grid Info --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="label"><i class="fas fa-hashtag"></i> Nomor Dokumen</div>
                <div class="value">
                    <span class="nomor-badge">
                        <i class="fas fa-file-signature"></i>
                        {{ $arsip->nomor_dokumen ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="label"><i class="fas fa-tags"></i> Kategori</div>
                <div class="value">
                    <span class="kategori-badge">
                        <i class="fas fa-tag"></i>
                        {{ $kategoriLabels[$arsip->kategori] ?? $arsip->kategori }}
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="label"><i class="fas fa-layer-group"></i> Jenis Dokumen</div>
                <div class="value">{{ $arsip->jenis_dokumen ?? '-' }}</div>
            </div>

            <div class="info-item">
                <div class="label"><i class="fas fa-calendar-check"></i> Tanggal Dokumen</div>
                <div class="value">
                    {{ $arsip->tanggal_dokumen ? \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') : '-' }}
                </div>
            </div>

            <div class="info-item">
                <div class="label"><i class="fas fa-calendar"></i> Tahun</div>
                <div class="value">
                    <span class="tahun-badge">
                        <i class="fas fa-calendar"></i>
                        {{ $arsip->tahun ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="label"><i class="fas fa-user-circle"></i> Uploader</div>
                <div class="value">
                    <div class="uploader-info-lg">
                        <div class="uploader-avatar-lg">{{ $initial }}</div>
                        <div>
                            <div class="fw-bold">{{ $arsip->uploader->name ?? '-' }}</div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($arsip->created_at)->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @if($arsip->keterangan)
            <div class="info-item full">
                <div class="label"><i class="fas fa-comment"></i> Keterangan</div>
                <div class="value" style="font-weight:500;">{{ $arsip->keterangan }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ============ PREVIEW CARD ========== --}}
@if($isImage)
<div class="preview-card">
    <div class="preview-card-header">
        <i class="fas fa-image"></i>
        <h5>Preview Gambar</h5>
    </div>
    <div class="preview-card-body">
        <img src="{{ asset('storage/' . $arsip->file_path) }}" alt="{{ $arsip->nama_dokumen }}">
    </div>
</div>
@elseif($isPdf)
<div class="preview-card">
    <div class="preview-card-header">
        <i class="fas fa-file-pdf"></i>
        <h5>Preview PDF</h5>
    </div>
    <div class="preview-card-body">
        <iframe src="{{ asset('storage/' . $arsip->file_path) }}"
                style="width:100%;height:600px;border:1px solid #e2e8f0;border-radius:12px;"></iframe>
    </div>
</div>
@else
<div class="preview-card">
    <div class="preview-card-header">
        <i class="fas fa-file"></i>
        <h5>File Dokumen</h5>
    </div>
    <div class="preview-card-body">
        <div class="preview-placeholder">
            <i class="fas fa-file-alt"></i>
            <h5 class="fw-bold text-dark mb-2">Preview tidak tersedia</h5>
            <p class="text-muted mb-4">File dengan format .{{ $fileExt }} tidak dapat dipreview.</p>
            <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn-download-lg">
                <i class="fas fa-download"></i> Download File
            </a>
        </div>
    </div>
</div>
@endif
@endsection