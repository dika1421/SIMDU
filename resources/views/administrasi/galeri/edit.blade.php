@extends('administrasi.layouts.header')

@section('title', 'Edit Galeri')

@section('content')
<style>
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
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
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
    .page-header-edit h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-edit p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }
    .form-control-modern.is-invalid,
    .form-select-modern.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }

    .current-image-card {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fcd34d;
        border-radius: 14px;
        padding: 14px;
        display: flex;
        gap: 14px;
        align-items: center;
        margin-bottom: 14px;
    }
    .current-image-card img {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    .current-image-card .info {
        flex: 1;
        min-width: 0;
    }
    .current-image-card .info-title {
        font-weight: 700;
        color: #92400e;
        font-size: 0.85rem;
    }
    .current-image-card .info-sub {
        font-size: 0.72rem;
        color: #a16207;
        margin-top: 3px;
    }

    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 22px 20px;
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
    }
    .upload-dropzone.has-image {
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
    }
    .upload-dropzone.has-image .drop-icon {
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
    .upload-dropzone .image-preview {
        display: none;
        margin-top: 14px;
        position: relative;
        border-radius: 10px;
        overflow: hidden;
    }
    .upload-dropzone.has-image .image-preview { display: block; }
    .upload-dropzone .image-preview img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        display: block;
    }
    .upload-dropzone .remove-overlay {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: #fff;
        border: none;
        border-radius: 8px;
        width: 32px; height: 32px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-dropzone .remove-overlay:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

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
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
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
    }
    .kategori-card input:checked + .card-inner .cat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    .kategori-card .cat-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #475569;
    }
    .kategori-card input:checked + .card-inner .cat-label {
        color: #92400e;
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
        'kegiatan' => 'fa-users',
        'prestasi' => 'fa-trophy',
        'acara'    => 'fa-calendar-star',
        'sekolah'  => 'fa-school',
        'lainnya'  => 'fa-folder',
    ];
    $currentKat = strtolower($gallery->category ?? '');
@endphp

<div class="page-header-edit">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-edit me-2"></i>
                Edit Galeri
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Perbarui informasi foto galeri
            </p>
        </div>
        <a href="{{ route('administrasi.galeri.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

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

<div class="row g-3">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-edit"></i>
                <h5>Form Edit Foto</h5>
            </div>
            <div class="form-card-body">
                <form action="{{ route('administrasi.galeri.update', $gallery->id) }}"
                      method="POST" enctype="multipart/form-data" id="galeriForm">
                    @csrf
                    @method('PUT')

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
                                   value="{{ old('title', $gallery->title) }}" required>
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
                                   value="{{ old('event_date', $gallery->event_date ? date('Y-m-d', strtotime($gallery->event_date)) : '') }}">
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
                                      placeholder="Deskripsi foto...">{{ old('description', $gallery->description) }}</textarea>
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
                        @foreach(['kegiatan' => 'Kegiatan', 'prestasi' => 'Prestasi', 'acara' => 'Acara', 'sekolah' => 'Sekolah', 'lainnya' => 'Lainnya'] as $key => $label)
                            <label class="kategori-card">
                                <input type="radio" name="category" value="{{ ucfirst($key) }}"
                                       {{ old('category', $gallery->category) == ucfirst($key) ? 'checked' : '' }}>
                                <div class="card-inner">
                                    <div class="cat-icon">
                                        <i class="fas {{ $katIcons[$key] ?? 'fa-folder' }}"></i>
                                    </div>
                                    <div class="cat-label">{{ $label }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- STATUS --}}
                    <div class="form-section-title">
                        <i class="fas fa-toggle-on"></i> Status
                    </div>

                    <div class="mb-4">
                        <select name="status" class="form-select-modern">
                            <option value="active" {{ old('status', $gallery->status) == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="inactive" {{ old('status', $gallery->status) == 'inactive' ? 'selected' : '' }}>🔒 Nonaktif</option>
                        </select>
                    </div>

                    {{-- GAMBAR --}}
                    <div class="form-section-title">
                        <i class="fas fa-image"></i> Gambar
                    </div>

                    @if($gallery->image)
                        <div class="current-image-card">
                            <img src="{{ asset('storage/galleries/' . $gallery->image) }}"
                                 alt="{{ $gallery->title }}"
                                 onerror="this.src='https://via.placeholder.com/100x100/e2e8f0/64748b?text=No'">
                            <div class="info">
                                <div class="info-title">Gambar Saat Ini</div>
                                <div class="info-sub">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Biarkan kosong jika tidak ingin mengganti gambar
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="upload-dropzone" id="dropzone">
                        <input type="file" name="image" id="image"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">

                        <div class="drop-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-title">Klik atau Drag & Drop gambar baru</div>
                        <div class="drop-sub">Format: JPG, JPEG, PNG, GIF, WebP · Maks 5 MB</div>

                        <div class="image-preview" id="imagePreviewBox">
                            <img id="imagePreview" src="" alt="Preview">
                            <button type="button" class="remove-overlay" id="removeImage">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <a href="{{ route('administrasi.galeri.index') }}" class="btn-cancel-modern">
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

    <div class="col-lg-4">
        <div class="form-card mb-3">
            <div class="form-card-header" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">
                <i class="fas fa-lightbulb" style="color:#059669;"></i>
                <h5 style="color:#065f46;">Tips Edit Foto</h5>
            </div>
            <div class="form-card-body" style="padding:18px;">
                <ul style="list-style:none;padding:0;margin:0;">
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">1</div>
                        <div>Perubahan akan tersimpan langsung.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;border-bottom:1px dashed #f1f5f9;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">2</div>
                        <div>Gambar lama akan terhapus jika Anda upload gambar baru.</div>
                    </li>
                    <li style="display:flex;gap:10px;padding:8px 0;font-size:0.78rem;color:#475569;">
                        <div style="width:22px;height:22px;border-radius:6px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">3</div>
                        <div>Field <strong>Judul</strong> wajib diisi.</div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="form-card" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:none;">
            <div class="form-card-body text-center" style="padding:18px;">
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
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImage');

    function showImage(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            dropzone.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }

    function clearImage() {
        fileInput.value = '';
        preview.src = '';
        dropzone.classList.remove('has-image');
    }

    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('.remove-overlay')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal 5 MB.',
                    confirmButtonColor: '#f59e0b'
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
            if (files[0].size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran maksimal 5 MB.',
                    confirmButtonColor: '#f59e0b'
                });
                clearImage();
                return;
            }
            showImage(files[0]);
        }
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearImage();
        });
    }

    $('#galeriForm').on('submit', function(e) {
        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
    });
});
</script>
@endpush
@endsection