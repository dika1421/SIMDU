@extends('administrasi.layouts.header')

@section('title', 'Tambah Galeri')

@section('content')
<style>
    .page-header-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-create::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-create .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-create h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-create p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i { color: #059669; font-size: 1.05rem; }
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
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #d1fae5;
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
    .form-label-modern i { color: #10b981; }
    .form-label-modern .required { color: #ef4444; }

    .form-control-modern,
    .form-select-modern {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 14px;
        font-size: 0.88rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .form-control-modern.is-invalid,
    .form-select-modern.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }

    /* ========== UPLOAD DROPZONE ========== */
    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 32px 20px;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
        transition: all 0.25s;
        position: relative;
        min-height: 260px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .upload-dropzone:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .upload-dropzone.dragover {
        border-color: #10b981;
        background: #d1fae5;
        transform: scale(1.01);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
    }
    .upload-dropzone.has-image {
        border-style: solid;
        border-color: #10b981;
        padding: 0;
        min-height: auto;
    }
    .upload-dropzone input[type="file"] {
        display: none;
    }
    .upload-dropzone .drop-icon {
        font-size: 3rem;
        color: #10b981;
        margin-bottom: 12px;
        transition: all 0.25s;
    }
    .upload-dropzone.dragover .drop-icon {
        transform: scale(1.15);
    }
    .upload-dropzone .drop-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    .upload-dropzone .drop-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .upload-dropzone .drop-info {
        margin-top: 14px;
        padding: 8px 14px;
        background: #f0fdf4;
        border-radius: 8px;
        font-size: 0.72rem;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .upload-dropzone .image-preview {
        display: none;
        position: relative;
        width: 100%;
        border-radius: 14px;
        overflow: hidden;
    }
    .upload-dropzone .image-preview img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        display: block;
    }
    .upload-dropzone .image-preview .remove-overlay {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: #fff;
        border: none;
        border-radius: 8px;
        width: 34px; height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-dropzone .image-preview .remove-overlay:hover {
        background: #dc2626;
        transform: scale(1.05);
    }
    .upload-dropzone .image-preview .file-info-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.8), transparent);
        color: #fff;
        padding: 30px 14px 12px;
        font-size: 0.75rem;
    }
    .upload-dropzone.has-image .drop-icon,
    .upload-dropzone.has-image .drop-title,
    .upload-dropzone.has-image .drop-sub,
    .upload-dropzone.has-image .drop-info {
        display: none;
    }
    .upload-dropzone.has-image .image-preview {
        display: block;
    }

    .btn-submit-modern {
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
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
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

    /* ========== KATEGORI CARD ========== */
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
        border-color: #6ee7b7;
        background: #f0fdf4;
        transform: translateY(-2px);
    }
    .kategori-card input:checked + .card-inner {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
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
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    .kategori-card .cat-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.2;
    }
    .kategori-card input:checked + .card-inner .cat-label {
        color: #065f46;
    }

    @media (max-width: 768px) {
        .page-header-create { padding: 18px; }
        .page-header-create h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
        .kategori-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

@php
    $katIcons = [
        'Kegiatan' => 'fa-users',
        'Prestasi' => 'fa-trophy',
        'Acara'    => 'fa-calendar-star',
        'Sekolah'  => 'fa-school',
        'Lainnya'  => 'fa-folder',
    ];
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-create">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-plus-circle me-2"></i>
                Tambah Foto Galeri
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Upload foto baru untuk dokumentasi kegiatan
            </p>
        </div>
        <a href="{{ route('administrasi.galeri.index') }}" class="btn-glass">
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

{{-- ============ FORM ========== --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-image"></i>
                <h5>Form Tambah Foto</h5>
            </div>
            <div class="form-card-body">
                <form action="{{ route('administrasi.galeri.store') }}" method="POST"
                      enctype="multipart/form-data" id="galeriForm">
                    @csrf

                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Informasi Foto
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-heading"></i> Judul <span class="required">*</span>
                            </label>
                            <input type="text" name="title"
                                   class="form-control-modern @error('title') is-invalid @enderror"
                                   placeholder="Masukkan judul foto"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-calendar"></i> Tanggal Event
                            </label>
                            <input type="date" name="event_date"
                                   class="form-control-modern @error('event_date') is-invalid @enderror"
                                   value="{{ old('event_date') }}">
                            @error('event_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea name="description"
                                      class="form-control-modern @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Masukkan deskripsi foto...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="form-section-title mt-2">
                        <i class="fas fa-tags"></i> Kategori
                    </div>

                    <div class="kategori-grid mb-4">
                        @foreach(['Kegiatan', 'Prestasi', 'Acara', 'Sekolah', 'Lainnya'] as $cat)
                            <label class="kategori-card">
                                <input type="radio" name="category" value="{{ $cat }}"
                                       {{ old('category') == $cat ? 'checked' : '' }}>
                                <div class="card-inner">
                                    <div class="cat-icon">
                                        <i class="fas {{ $katIcons[$cat] ?? 'fa-folder' }}"></i>
                                    </div>
                                    <div class="cat-label">{{ $cat }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- STATUS --}}
                    <div class="form-section-title">
                        <i class="fas fa-toggle-on"></i> Status
                    </div>

                    <div class="mb-4">
                        <select name="status" class="form-select-modern @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔒 Nonaktif</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- UPLOAD --}}
                    <div class="form-section-title">
                        <i class="fas fa-cloud-upload-alt"></i> Gambar <span style="color:#ef4444;margin-left:4px;">*</span>
                    </div>

                    <div class="upload-dropzone" id="dropzone">
                        <input type="file" name="image" id="image"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>

                        <div class="drop-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-title">Klik atau Drag & Drop gambar</div>
                        <div class="drop-sub">Format: JPG, JPEG, PNG, GIF, WebP</div>
                        <div class="drop-info">
                            <i class="fas fa-info-circle me-1"></i>
                            Ukuran maksimal 5 MB
                        </div>

                        <div class="image-preview" id="imagePreviewBox">
                            <img id="imagePreview" src="" alt="Preview">
                            <button type="button" class="remove-overlay" id="removeImage" title="Hapus gambar">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="file-info-overlay">
                                <div id="previewName" style="font-weight:700;"></div>
                                <div id="previewSize" style="opacity:0.85;font-size:0.7rem;"></div>
                            </div>
                        </div>
                    </div>

                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <button type="button" class="btn-cancel-modern" id="resetBtn">
                            <i class="fas fa-undo-alt"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit-modern" id="btnSubmit">
                            <i class="fas fa-save"></i>
                            <span>Simpan Foto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Tips --}}
        <div class="form-card mb-3">
            <div class="form-card-header" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                <i class="fas fa-lightbulb" style="color:#d97706;"></i>
                <h5 style="color:#92400e;">Tips Upload Foto</h5>
            </div>
            <div class="form-card-body" style="padding:18px;">
                <ul style="list-style:none;padding:0;margin:0;">
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">1</div>
                        <div>Gunakan foto dengan resolusi yang baik.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">2</div>
                        <div>Pilih kategori agar mudah dikelompokkan.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">3</div>
                        <div>Isi tanggal event untuk dokumentasi kronologis.</div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Info --}}
        <div class="form-card" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);border:none;">
            <div class="form-card-body text-center" style="padding:20px;">
                <i class="fas fa-image" style="font-size:1.8rem;color:#4f46e5;margin-bottom:10px;"></i>
                <h6 style="font-weight:700;color:#1e293b;font-size:0.85rem;margin-bottom:6px;">Format Didukung</h6>
                <div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;margin-top:10px;">
                    <span style="padding:4px 10px;background:#fff;border-radius:6px;font-size:0.7rem;font-weight:600;color:#4338ca;">JPG</span>
                    <span style="padding:4px 10px;background:#fff;border-radius:6px;font-size:0.7rem;font-weight:600;color:#4338ca;">JPEG</span>
                    <span style="padding:4px 10px;background:#fff;border-radius:6px;font-size:0.7rem;font-weight:600;color:#4338ca;">PNG</span>
                    <span style="padding:4px 10px;background:#fff;border-radius:6px;font-size:0.7rem;font-weight:600;color:#4338ca;">GIF</span>
                    <span style="padding:4px 10px;background:#fff;border-radius:6px;font-size:0.7rem;font-weight:600;color:#4338ca;">WebP</span>
                </div>
                <div style="margin-top:12px;padding:8px;background:#fff;border-radius:8px;font-size:0.72rem;color:#065f46;">
                    <i class="fas fa-info-circle me-1"></i>
                    Maks: <strong>5 MB</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewName = document.getElementById('previewName');
    const previewSize = document.getElementById('previewSize');
    const removeBtn = document.getElementById('removeImage');

    function formatSize(bytes) {
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return bytes + ' B';
    }

    function showImage(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewName.textContent = file.name;
            previewSize.textContent = formatSize(file.size);
            dropzone.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }

    function clearImage() {
        fileInput.value = '';
        preview.src = '';
        previewName.textContent = '';
        previewSize.textContent = '';
        dropzone.classList.remove('has-image');
    }

    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('.remove-overlay')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal gambar adalah 5 MB.',
                    confirmButtonColor: '#10b981'
                });
                clearImage();
                return;
            }
            showImage(file);
        }
    });

    ['dragenter', 'dragover'].forEach(ev => {
        dropzone.addEventListener(ev, e => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(ev => {
        dropzone.addEventListener(ev, e => {
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
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal gambar adalah 5 MB.',
                    confirmButtonColor: '#10b981'
                });
                clearImage();
                return;
            }
            showImage(file);
        }
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearImage();
        });
    }

    // Validasi submit
    $('#galeriForm').on('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Gambar Belum Dipilih',
                text: 'Silakan pilih gambar untuk diupload.',
                confirmButtonColor: '#10b981'
            });
            return false;
        }
        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
    });

    // Reset
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: 'Reset Form?',
            text: 'Semua data akan dihapus.',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then(r => {
            if (r.isConfirmed) {
                $('#galeriForm')[0].reset();
                clearImage();
                $('.is-invalid').removeClass('is-invalid');
                $('#btnSubmit').html('<i class="fas fa-save"></i> <span>Simpan Foto</span>').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
@endsection