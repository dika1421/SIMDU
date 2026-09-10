@extends('administrasi.layouts.header')

@section('title', 'Edit Dokumen')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-edit::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-edit::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header-edit .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-edit h1 {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-edit p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
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

    /* ========== FORM CARD ========== */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i { color: #d97706; font-size: 1.05rem; }
    .form-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .form-card-body { padding: 24px; }

    .form-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #92400e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #fef3c7;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label-modern {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-modern i { color: #f59e0b; }
    .form-label-modern .required { color: #ef4444; }

    .form-control-modern {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 14px;
        font-size: 0.88rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-control-modern:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }
    .form-control-modern.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }

    /* ========== KATEGORI CARDS ========== */
    .kategori-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 8px;
    }
    .kategori-card {
        position: relative;
        cursor: pointer;
    }
    .kategori-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .kategori-card .card-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px 8px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #fff;
        transition: all 0.2s;
        text-align: center;
        min-height: 82px;
    }
    .kategori-card .card-inner:hover {
        border-color: #fcd34d;
        background: #fffbeb;
        transform: translateY(-2px);
    }
    .kategori-card input:checked + .card-inner {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }
    .kategori-card .cat-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .kategori-card input:checked + .card-inner .cat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    .kategori-card .cat-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.2;
    }
    .kategori-card input:checked + .card-inner .cat-label {
        color: #92400e;
    }

    /* ========== CURRENT FILE BOX ========== */
    .current-file-box {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .current-file-box .ico {
        width: 44px; height: 44px;
        border-radius: 11px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }
    .current-file-box .file-name {
        font-weight: 700;
        color: #92400e;
        font-size: 0.85rem;
        word-break: break-all;
    }
    .current-file-box .file-meta {
        font-size: 0.72rem;
        color: #a16207;
        margin-top: 3px;
    }

    /* ========== DRAG & DROP UPLOAD ========== */
    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
        transition: all 0.25s;
        position: relative;
    }
    .upload-dropzone:hover {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    .upload-dropzone.dragover {
        border-color: #f59e0b;
        background: #fef3c7;
        transform: scale(1.01);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
    }
    .upload-dropzone.has-file {
        border-style: solid;
        border-color: #10b981;
        background: #f0fdf4;
    }
    .upload-dropzone input[type="file"] {
        display: none;
    }
    .upload-dropzone .drop-icon {
        font-size: 2rem;
        color: #f59e0b;
        margin-bottom: 8px;
        transition: all 0.25s;
    }
    .upload-dropzone.dragover .drop-icon {
        transform: scale(1.15);
    }
    .upload-dropzone.has-file .drop-icon {
        color: #10b981;
    }
    .upload-dropzone .drop-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.88rem;
        margin-bottom: 4px;
    }
    .upload-dropzone .drop-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .upload-dropzone .file-preview {
        display: none;
        margin-top: 12px;
        padding: 10px 14px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #a7f3d0;
        align-items: center;
        gap: 12px;
        text-align: left;
    }
    .upload-dropzone.has-file .file-preview { display: flex; }
    .file-preview .file-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .file-preview .file-name {
        font-weight: 700;
        color: #065f46;
        font-size: 0.82rem;
        word-break: break-all;
    }
    .file-preview .file-size {
        font-size: 0.7rem;
        color: #059669;
        margin-top: 2px;
    }
    .file-preview .remove-btn {
        background: #fee2e2;
        border: none;
        color: #dc2626;
        width: 30px; height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .file-preview .remove-btn:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    /* ========== PROGRESS ========== */
    .upload-progress {
        display: none;
        margin-top: 14px;
        padding: 14px;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border-radius: 12px;
    }
    .upload-progress.show { display: block; }
    .upload-progress .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .upload-progress .progress-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #92400e;
    }
    .upload-progress .progress-percent {
        font-size: 0.82rem;
        font-weight: 700;
        color: #d97706;
    }
    .upload-progress .progress-track {
        height: 8px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
    }
    .upload-progress .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        border-radius: 5px;
        width: 0%;
        transition: width 0.3s ease;
    }

    /* ========== BUTTONS ========== */
    .btn-submit-modern {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        color: #fff;
    }
    .btn-submit-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    .btn-cancel-modern {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-cancel-modern:hover { background: #e2e8f0; color: #1e293b; }

    /* ========== SIDEBAR INFO ========== */
    .info-side-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .info-side-header {
        padding: 14px 18px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-side-header i { color: #d97706; font-size: 1rem; }
    .info-side-header h6 {
        font-size: 0.82rem;
        font-weight: 700;
        color: #92400e;
        margin: 0;
    }
    .info-side-body { padding: 16px 18px; }

    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 0.78rem;
    }
    .meta-row:last-child { border-bottom: none; }
    .meta-row .meta-label {
        color: #94a3b8;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .meta-row .meta-label i { color: #f59e0b; }
    .meta-row .meta-value {
        font-weight: 700;
        color: #1e293b;
        text-align: right;
        word-break: break-word;
        max-width: 60%;
    }

    /* ========== ICON CIRCLE (id) ========== */
    .doc-hero-mini {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 12px;
        margin-bottom: 4px;
    }
    .doc-hero-mini .hero-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }
    .doc-hero-mini .hero-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #92400e;
        line-height: 1.2;
        word-break: break-word;
    }
    .doc-hero-mini .hero-sub {
        font-size: 0.7rem;
        color: #a16207;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .page-header-edit { padding: 18px; }
        .page-header-edit h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
        .kategori-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

@php
    $katIcons = [
        'surat_masuk'  => 'fa-envelope-open-text',
        'surat_keluar' => 'fa-paper-plane',
        'keputusan'    => 'fa-gavel',
        'laporan'      => 'fa-chart-line',
        'notulen'      => 'fa-clipboard-list',
        'sertifikat'   => 'fa-award',
        'ijazah'       => 'fa-graduation-cap',
        'lainnya'      => 'fa-folder',
    ];
    $currentKatLabel = $kategoriList[$arsip->kategori] ?? $arsip->kategori;
    $fileName = $arsip->file_path ? basename($arsip->file_path) : '-';
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-edit">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-edit me-2"></i>
                Edit Dokumen
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Perbarui informasi dokumen arsip
            </p>
        </div>
        <a href="{{ route('administrasi.arsip.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ ALERT ========== --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ LAYOUT: FORM + SIDEBAR ========== --}}
<div class="row g-3">
    {{-- KOLOM KIRI: FORM --}}
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-edit"></i>
                <h5>Form Edit Dokumen</h5>
            </div>
            <div class="form-card-body">
                <form action="{{ route('administrasi.arsip.update', $arsip->id) }}" method="POST"
                      enctype="multipart/form-data" id="editForm">
                    @csrf
                    @method('PUT')

                    {{-- SECTION 1: INFO DOKUMEN --}}
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Informasi Dokumen
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-hashtag"></i> Nomor Dokumen
                            </label>
                            <input type="text" name="nomor_dokumen"
                                   class="form-control-modern @error('nomor_dokumen') is-invalid @enderror"
                                   placeholder="Contoh: ARS-001"
                                   value="{{ old('nomor_dokumen', $arsip->nomor_dokumen) }}">
                            <small class="text-muted" style="font-size:0.72rem;">Opsional, untuk pengkodean arsip</small>
                            @error('nomor_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-calendar-check"></i> Tanggal Dokumen <span class="required">*</span>
                            </label>
                            <input type="date" name="tanggal_dokumen"
                                   class="form-control-modern @error('tanggal_dokumen') is-invalid @enderror"
                                   value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen ? \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @error('tanggal_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label-modern">
                                <i class="fas fa-file-signature"></i> Nama Dokumen <span class="required">*</span>
                            </label>
                            <input type="text" name="nama_dokumen"
                                   class="form-control-modern @error('nama_dokumen') is-invalid @enderror"
                                   placeholder="Masukkan nama/judul dokumen"
                                   value="{{ old('nama_dokumen', $arsip->nama_dokumen) }}" required>
                            @error('nama_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SECTION 2: KATEGORI CARD --}}
                    <div class="form-section-title mt-2">
                        <i class="fas fa-tags"></i> Kategori Dokumen <span style="color:#ef4444;margin-left:4px;">*</span>
                    </div>

                    <div class="kategori-grid mb-4">
                        @foreach($kategoriList as $key => $value)
                            <label class="kategori-card">
                                <input type="radio" name="kategori" value="{{ $key }}"
                                       {{ old('kategori', $arsip->kategori) == $key ? 'checked' : '' }} required>
                                <div class="card-inner">
                                    <div class="cat-icon">
                                        <i class="fas {{ $katIcons[$key] ?? 'fa-file' }}"></i>
                                    </div>
                                    <div class="cat-label">{{ $value }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('kategori')
                        <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    {{-- SECTION 3: FILE UPLOAD --}}
                    <div class="form-section-title">
                        <i class="fas fa-file-upload"></i> File Dokumen
                    </div>

                    {{-- Current file --}}
                    @if($arsip->file_path)
                        <div class="current-file-box mb-3">
                            <div class="ico"><i class="fas fa-file-alt"></i></div>
                            <div style="flex:1;min-width:0;">
                                <div class="file-name">{{ $fileName }}</div>
                                <div class="file-meta">
                                    <i class="fas fa-calendar me-1"></i>
                                    Diupload: {{ $arsip->created_at->format('d/m/Y H:i') }}
                                    @if(file_exists(storage_path('app/public/'.$arsip->file_path)))
                                        · <i class="fas fa-database ms-1 me-1"></i>
                                        {{ round(filesize(storage_path('app/public/'.$arsip->file_path)) / 1024, 1) }} KB
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('administrasi.arsip.download', $arsip->id) }}"
                               class="btn-cancel-modern" style="padding:8px 14px;font-size:0.78rem;" target="_blank">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endif

                    <div class="upload-dropzone" id="dropzone">
                        <input type="file" name="file" id="file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">

                        <div class="drop-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-title">Klik untuk pilih file baru</div>
                        <div class="drop-sub">atau drag & drop file ke sini (opsional)</div>

                        <div class="file-preview" id="filePreview">
                            <div class="file-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="file-name" id="previewName">-</div>
                                <div class="file-size" id="previewSize">-</div>
                            </div>
                            <button type="button" class="remove-btn" id="removeFile" title="Hapus file">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="upload-progress" id="uploadProgress">
                        <div class="progress-info">
                            <span class="progress-label">
                                <i class="fas fa-spinner fa-spin me-1"></i> Mengupload...
                            </span>
                            <span class="progress-percent" id="progressPercent">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2" style="font-size:0.72rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Kosongkan jika tidak ingin mengganti file. Maksimal 10 MB.
                    </small>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    {{-- SECTION 4: KETERANGAN --}}
                    <div class="form-section-title mt-4">
                        <i class="fas fa-align-left"></i> Keterangan
                    </div>

                    <div class="mb-4">
                        <textarea name="keterangan"
                                  class="form-control-modern @error('keterangan') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Keterangan tambahan tentang dokumen ini (opsional)...">{{ old('keterangan', $arsip->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <a href="{{ route('administrasi.arsip.index') }}" class="btn-cancel-modern">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn-submit-modern" id="btnSubmit">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: SIDEBAR INFO --}}
    <div class="col-lg-4">
        {{-- Card Meta Dokumen --}}
        <div class="info-side-card">
            <div class="info-side-header">
                <i class="fas fa-info-circle"></i>
                <h6>Informasi Dokumen</h6>
            </div>
            <div class="info-side-body">
                <div class="doc-hero-mini">
                    <div class="hero-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div style="min-width:0;">
                        <div class="hero-title">{{ \Illuminate\Support\Str::limit($arsip->nama_dokumen, 40) }}</div>
                        <div class="hero-sub">{{ $currentKatLabel }}</div>
                    </div>
                </div>

                <div class="meta-row">
                    <span class="meta-label"><i class="fas fa-hashtag"></i> Nomor</span>
                    <span class="meta-value">{{ $arsip->nomor_dokumen ?? '-' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label"><i class="fas fa-calendar"></i> Tahun</span>
                    <span class="meta-value">{{ $arsip->tahun ?? '-' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label"><i class="fas fa-user"></i> Uploader</span>
                    <span class="meta-value">{{ $arsip->uploader->name ?? '-' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label"><i class="fas fa-clock"></i> Dibuat</span>
                    <span class="meta-value">{{ $arsip->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label"><i class="fas fa-sync-alt"></i> Update</span>
                    <span class="meta-value">{{ $arsip->updated_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Card Tips Edit --}}
        <div class="info-side-card">
            <div class="info-side-header" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">
                <i class="fas fa-lightbulb" style="color:#059669;"></i>
                <h6 style="color:#065f46;">Tips Edit Dokumen</h6>
            </div>
            <div class="info-side-body">
                <ul class="tips-list" style="list-style:none;padding:0;margin:0;">
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">1</div>
                        <div>Perubahan akan tersimpan secara langsung.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">2</div>
                        <div>File lama akan terhapus jika Anda upload file baru.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">3</div>
                        <div>Field <strong>Kategori</strong> wajib diisi.</div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Card Warning --}}
        <div class="info-side-card" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:none;">
            <div class="info-side-body text-center" style="padding:18px;">
                <i class="fas fa-exclamation-triangle" style="font-size:1.6rem;color:#d97706;margin-bottom:8px;"></i>
                <h6 style="font-weight:700;color:#92400e;font-size:0.82rem;margin-bottom:6px;">Perhatian</h6>
                <p style="font-size:0.72rem;color:#a16207;margin:0;">
                    Pastikan data yang diubah sudah benar sebelum disimpan.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('filePreview');
    const previewName = document.getElementById('previewName');
    const previewSize = document.getElementById('previewSize');
    const removeBtn = document.getElementById('removeFile');

    // ============ FORMAT SIZE ============
    function formatSize(bytes) {
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return bytes + ' B';
    }

    // ============ SHOW FILE INFO ============
    function showFileInfo(file) {
        if (!file) return;
        previewName.textContent = file.name;
        previewSize.textContent = formatSize(file.size);
        dropzone.classList.add('has-file');
    }

    // ============ CLEAR FILE ============
    function clearFile() {
        fileInput.value = '';
        dropzone.classList.remove('has-file');
        previewName.textContent = '-';
        previewSize.textContent = '-';
    }

    // ============ CLICK DROPZONE ============
    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) return;
        fileInput.click();
    });

    // ============ FILE SELECTED ============
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024;

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal file adalah 10 MB.',
                    confirmButtonColor: '#f59e0b'
                });
                clearFile();
                return;
            }
            showFileInfo(file);
        }
    });

    // ============ DRAG & DROP ============
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files && files[0]) {
            fileInput.files = files;
            const file = files[0];
            const maxSize = 10 * 1024 * 1024;

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal file adalah 10 MB.',
                    confirmButtonColor: '#f59e0b'
                });
                clearFile();
                return;
            }
            showFileInfo(file);
        }
    });

    // ============ REMOVE FILE ============
    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearFile();
        });
    }

    // ============ SUBMIT ============
    $('#editForm').on('submit', function(e) {
        // Validasi kategori
        const kategori = $('input[name="kategori"]:checked').val();
        if (!kategori) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Kategori Belum Dipilih',
                text: 'Silakan pilih salah satu kategori dokumen.',
                confirmButtonColor: '#f59e0b'
            });
            return false;
        }

        // Kalau ada file baru, tampilkan progress
        if (fileInput.files.length > 0) {
            const progressDiv = document.getElementById('uploadProgress');
            const progressFill = document.getElementById('progressFill');
            const progressPercent = document.getElementById('progressPercent');
            const btnSubmit = document.getElementById('btnSubmit');

            progressDiv.classList.add('show');
            btnSubmit.disabled = true;
            btnSubmit.querySelector('span').textContent = 'Menyimpan...';
            btnSubmit.querySelector('i').className = 'fas fa-spinner fa-spin';

            let progress = 0;
            const interval = setInterval(function() {
                progress += Math.random() * 15;
                if (progress >= 95) progress = 95;
                progressFill.style.width = progress + '%';
                progressPercent.textContent = Math.round(progress) + '%';
            }, 200);

            window.__uploadInterval = interval;
        }
    });

});
</script>
@endpush
@endsection