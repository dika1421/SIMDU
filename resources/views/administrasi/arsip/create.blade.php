@extends('administrasi.layouts.header')

@section('title', 'Upload Dokumen')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-upload {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-upload::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-upload::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header-upload .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-upload h1 {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-upload p {
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
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i { color: #4f46e5; font-size: 1.05rem; }
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
        color: #4f46e5;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #e0e7ff;
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
    .form-label-modern i { color: #6366f1; }
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
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
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
        border-color: #a5b4fc;
        background: #f8fafc;
        transform: translateY(-2px);
    }
    .kategori-card input:checked + .card-inner {
        border-color: #6366f1;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
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
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    .kategori-card .cat-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.2;
    }
    .kategori-card input:checked + .card-inner .cat-label {
        color: #4338ca;
    }

    /* ========== DRAG & DROP UPLOAD ========== */
    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 32px 20px;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
        transition: all 0.25s;
        position: relative;
    }
    .upload-dropzone:hover {
        border-color: #6366f1;
        background: #f8fafc;
    }
    .upload-dropzone.dragover {
        border-color: #6366f1;
        background: #eef2ff;
        transform: scale(1.01);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
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
        font-size: 2.5rem;
        color: #6366f1;
        margin-bottom: 10px;
        transition: all 0.25s;
    }
    .upload-dropzone.dragover .drop-icon {
        transform: scale(1.15);
        color: #4f46e5;
    }
    .upload-dropzone.has-file .drop-icon {
        color: #10b981;
    }
    .upload-dropzone .drop-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.92rem;
        margin-bottom: 4px;
    }
    .upload-dropzone .drop-sub {
        font-size: 0.78rem;
        color: #94a3b8;
    }
    .upload-dropzone .file-preview {
        display: none;
        margin-top: 14px;
        padding: 12px 16px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #a7f3d0;
        align-items: center;
        gap: 12px;
        text-align: left;
    }
    .upload-dropzone.has-file .file-preview { display: flex; }
    .file-preview .file-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .file-preview .file-name {
        font-weight: 700;
        color: #065f46;
        font-size: 0.85rem;
        word-break: break-all;
    }
    .file-preview .file-size {
        font-size: 0.72rem;
        color: #059669;
        margin-top: 2px;
    }
    .file-preview .remove-btn {
        background: #fee2e2;
        border: none;
        color: #dc2626;
        width: 32px; height: 32px;
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
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-radius: 12px;
    }
    .upload-progress.show { display: block; }
    .upload-progress .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .upload-progress .progress-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #4338ca;
    }
    .upload-progress .progress-percent {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6366f1;
    }
    .upload-progress .progress-track {
        height: 8px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
    }
    .upload-progress .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #6366f1, #4f46e5);
        border-radius: 5px;
        width: 0%;
        transition: width 0.3s ease;
    }

    /* ========== BUTTONS ========== */
    .btn-submit-modern {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
    }
    .btn-submit-modern::before {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        width: 0; height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .btn-submit-modern:hover::before {
        width: 300px;
        height: 300px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        color: #fff;
    }
    .btn-submit-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    .btn-reset-modern {
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
    }
    .btn-reset-modern:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* ========== SIDEBAR TIPS ========== */
    .tips-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .tips-card-header {
        padding: 14px 18px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tips-card-header i { color: #d97706; font-size: 1rem; }
    .tips-card-header h6 {
        font-size: 0.82rem;
        font-weight: 700;
        color: #92400e;
        margin: 0;
    }
    .tips-card-body { padding: 16px 18px; }
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        font-size: 0.78rem;
        color: #475569;
        line-height: 1.5;
    }
    .tips-list li:not(:last-child) {
        border-bottom: 1px dashed #f1f5f9;
    }
    .tips-list .tip-bullet {
        width: 22px; height: 22px;
        border-radius: 6px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .file-type-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .file-type-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    .file-type-chip.pdf  { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
    .file-type-chip.doc  { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .file-type-chip.xls  { background: #d1fae5; color: #065f46; border-color: #a7f3d0; }
    .file-type-chip.img  { background: #fef3c7; color: #92400e; border-color: #fde68a; }

    @media (max-width: 768px) {
        .page-header-upload { padding: 18px; }
        .page-header-upload h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
        .kategori-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-upload">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-cloud-upload-alt me-2"></i>
                Upload Dokumen Baru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Unggah dokumen ke arsip digital sekolah
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
                <i class="fas fa-file-upload"></i>
                <h5>Form Upload Dokumen</h5>
            </div>
            <div class="form-card-body">
                <form action="{{ route('administrasi.arsip.store') }}" method="POST"
                      enctype="multipart/form-data" id="uploadForm">
                    @csrf

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
                                   value="{{ old('nomor_dokumen') }}">
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
                                   value="{{ old('tanggal_dokumen', date('Y-m-d')) }}" required>
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
                                   value="{{ old('nama_dokumen') }}" required>
                            @error('nama_dokumen')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SECTION 2: KATEGORI (CARD PILIHAN) --}}
                    <div class="form-section-title mt-2">
                        <i class="fas fa-tags"></i> Kategori Dokumen <span style="color:#ef4444;margin-left:4px;">*</span>
                    </div>

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
                    @endphp

                    <div class="kategori-grid mb-4">
                        @foreach($kategoriList as $key => $value)
                            <label class="kategori-card">
                                <input type="radio" name="kategori" value="{{ $key }}"
                                       {{ old('kategori') == $key ? 'checked' : '' }} required>
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

                    {{-- SECTION 3: FILE UPLOAD (DRAG & DROP) --}}
                    <div class="form-section-title">
                        <i class="fas fa-cloud-upload-alt"></i> File Dokumen <span style="color:#ef4444;margin-left:4px;">*</span>
                    </div>

                    <div class="upload-dropzone" id="dropzone">
                        <input type="file" name="file" id="file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>

                        <div class="drop-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-title">Klik untuk pilih file</div>
                        <div class="drop-sub">atau drag & drop file ke sini</div>

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

                    @error('file')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    {{-- SECTION 4: KETERANGAN --}}
                    <div class="form-section-title mt-4">
                        <i class="fas fa-align-left"></i> Keterangan
                    </div>

                    <div class="mb-4">
                        <textarea name="keterangan"
                                  class="form-control-modern @error('keterangan') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Keterangan tambahan tentang dokumen ini (opsional)...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <button type="button" class="btn-reset-modern" id="resetBtn">
                            <i class="fas fa-undo-alt"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit-modern" id="btnSubmit">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload Dokumen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: SIDEBAR TIPS --}}
    <div class="col-lg-4">
        {{-- Tips Penggunaan --}}
        <div class="tips-card">
            <div class="tips-card-header">
                <i class="fas fa-lightbulb"></i>
                <h6>Tips Upload Dokumen</h6>
            </div>
            <div class="tips-card-body">
                <ul class="tips-list">
                    <li>
                        <div class="tip-bullet">1</div>
                        <div>Isi <strong>Nama Dokumen</strong> dengan jelas dan deskriptif.</div>
                    </li>
                    <li>
                        <div class="tip-bullet">2</div>
                        <div>Pilih <strong>Kategori</strong> yang sesuai agar mudah dicari.</div>
                    </li>
                    <li>
                        <div class="tip-bullet">3</div>
                        <div>Format file yang didukung: PDF, DOC, XLS, PPT, dan gambar.</div>
                    </li>
                    <li>
                        <div class="tip-bullet">4</div>
                        <div>Ukuran maksimal file <strong>10 MB</strong>.</div>
                    </li>
                    <li>
                        <div class="tip-bullet">5</div>
                        <div>Gunakan <strong>Nomor Dokumen</strong> untuk memudahkan pencarian.</div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- File Type Support --}}
        <div class="tips-card">
            <div class="tips-card-header" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">
                <i class="fas fa-file-alt" style="color:#059669;"></i>
                <h6 style="color:#065f46;">Format Didukung</h6>
            </div>
            <div class="tips-card-body">
                <div class="file-type-list">
                    <span class="file-type-chip pdf"><i class="fas fa-file-pdf"></i> PDF</span>
                    <span class="file-type-chip doc"><i class="fas fa-file-word"></i> DOC/DOCX</span>
                    <span class="file-type-chip xls"><i class="fas fa-file-excel"></i> XLS/XLSX</span>
                    <span class="file-type-chip doc"><i class="fas fa-file-powerpoint"></i> PPT/PPTX</span>
                    <span class="file-type-chip img"><i class="fas fa-file-image"></i> JPG/PNG</span>
                </div>
                <div class="mt-3 p-2 rounded-2" style="background:#f0fdf4;border:1px solid #a7f3d0;font-size:0.72rem;color:#065f46;">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Max:</strong> 10 MB per file
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="tips-card" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);border:none;">
            <div class="tips-card-body text-center">
                <i class="fas fa-shield-alt" style="font-size:1.8rem;color:#4f46e5;margin-bottom:10px;"></i>
                <h6 style="font-weight:700;color:#1e293b;font-size:0.85rem;margin-bottom:6px;">Aman & Terenkripsi</h6>
                <p style="font-size:0.75rem;color:#64748b;margin:0;">
                    Dokumen Anda tersimpan dengan aman di server sekolah.
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

    // ============ FORMAT FILE SIZE ============
    function formatSize(bytes) {
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return bytes + ' B';
    }

    // ============ TAMPILKAN FILE PREVIEW ============
    function showFileInfo(file) {
        if (!file) return;
        previewName.textContent = file.name;
        previewSize.textContent = formatSize(file.size);
        dropzone.classList.add('has-file');
    }

    // ============ HAPUS FILE ============
    function clearFile() {
        fileInput.value = '';
        dropzone.classList.remove('has-file');
        previewName.textContent = '-';
        previewSize.textContent = '-';
    }

    // ============ KLIK DROPZONE ============
    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) return;
        fileInput.click();
    });

    // ============ FILE DIPILIH ============
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024;

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal file adalah 10 MB.',
                    confirmButtonColor: '#6366f1'
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
                    confirmButtonColor: '#6366f1'
                });
                clearFile();
                return;
            }
            showFileInfo(file);
        }
    });

    // ============ HAPUS FILE VIA TOMBOL ============
    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearFile();
        });
    }

    // ============ SUBMIT ============
    $('#uploadForm').on('submit', function(e) {
        // Validasi kategori
        const kategori = $('input[name="kategori"]:checked').val();
        if (!kategori) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Kategori Belum Dipilih',
                text: 'Silakan pilih salah satu kategori dokumen.',
                confirmButtonColor: '#6366f1'
            });
            return false;
        }

        // Validasi file
        if (!fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih file yang akan diupload.',
                confirmButtonColor: '#6366f1'
            });
            return false;
        }

        // Tampilkan progress
        const progressDiv = document.getElementById('uploadProgress');
        const progressFill = document.getElementById('progressFill');
        const progressPercent = document.getElementById('progressPercent');
        const btnSubmit = document.getElementById('btnSubmit');

        progressDiv.classList.add('show');
        btnSubmit.disabled = true;
        btnSubmit.querySelector('span').textContent = 'Mengupload...';
        btnSubmit.querySelector('i').className = 'fas fa-spinner fa-spin';

        // Simulasi progress
        let progress = 0;
        const interval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress >= 95) progress = 95;
            progressFill.style.width = progress + '%';
            progressPercent.textContent = Math.round(progress) + '%';
        }, 200);

        // Simpan interval ke window agar bisa di-clear nanti
        window.__uploadInterval = interval;
    });

    // ============ RESET ============
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: 'Reset Form?',
            text: 'Semua data yang sudah diisi akan dihapus.',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#uploadForm')[0].reset();
                clearFile();
                $('.form-control-modern').removeClass('is-invalid');
                $('#uploadProgress').removeClass('show');
                $('#progressFill').css('width', '0%');
                $('#progressPercent').text('0%');

                Swal.fire({
                    icon: 'success',
                    title: 'Form Direset',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });
    });

});
</script>
@endpush
@endsection