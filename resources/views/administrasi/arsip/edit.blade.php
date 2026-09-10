@extends('administrasi.layouts.header')

@section('title', 'Edit Dokumen')

@section('content')
<style>
    .page-header-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 18px;
        padding: 22px 26px;
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
    .page-header-edit h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-edit p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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
        gap: 8px;
    }
    .form-card-header i { color: #d97706; font-size: 1.05rem; }
    .form-card-header h5 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .form-card-body { padding: 24px; }

    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #92400e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #fef3c7;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title i { color: #f59e0b; }

    .form-label-modern {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
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
        padding: 10px 14px;
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

    .current-file-box {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .current-file-box .ico {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .current-file-box .file-name {
        font-weight: 700;
        color: #92400e;
        font-size: 0.85rem;
    }
    .current-file-box .file-meta {
        font-size: 0.72rem;
        color: #a16207;
        margin-top: 2px;
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
</style>

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

{{-- ============ FORM CARD ========== --}}
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

            {{-- Bagian 1 --}}
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
                    <small class="text-muted">Opsional, untuk pengkodean arsip</small>
                    @error('nomor_dokumen')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-tags"></i> Kategori <span class="required">*</span>
                    </label>
                    <select name="kategori" class="form-select-modern @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $key => $value)
                            <option value="{{ $key }}" {{ old('kategori', $arsip->kategori) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-file-signature"></i> Nama Dokumen <span class="required">*</span>
                    </label>
                    <input type="text" name="nama_dokumen"
                           class="form-control-modern @error('nama_dokumen') is-invalid @enderror"
                           placeholder="Masukkan nama dokumen"
                           value="{{ old('nama_dokumen', $arsip->nama_dokumen) }}" required>
                    @error('nama_dokumen')
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

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-layer-group"></i> Jenis Dokumen
                    </label>
                    <input type="text" name="jenis_dokumen"
                           class="form-control-modern @error('jenis_dokumen') is-invalid @enderror"
                           placeholder="Contoh: Dokumen Guru"
                           value="{{ old('jenis_dokumen', $arsip->jenis_dokumen) }}">
                    @error('jenis_dokumen')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-file-upload"></i> File Dokumen
                    </label>
                    <input type="file" name="file" id="file"
                           class="form-control-modern @error('file') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Kosongkan jika tidak ingin mengganti file. Maksimal 10MB.
                    </small>

                    @if($arsip->file_path)
                        <div class="current-file-box">
                            <div class="ico"><i class="fas fa-file-alt"></i></div>
                            <div>
                                <div class="file-name">{{ basename($arsip->file_path) }}</div>
                                <div class="file-meta">
                                    <i class="fas fa-calendar me-1"></i>
                                    Diupload: {{ $arsip->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="progress mt-2 d-none" id="uploadProgress" style="height:6px;border-radius:3px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                             role="progressbar" style="width: 0%"></div>
                    </div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bagian 2 --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-align-left"></i> Informasi Tambahan
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-comment"></i> Keterangan
                    </label>
                    <textarea name="keterangan"
                              class="form-control-modern @error('keterangan') is-invalid @enderror"
                              rows="3"
                              placeholder="Keterangan singkat tentang dokumen ini...">{{ old('keterangan', $arsip->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2 flex-wrap">
                <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn-cancel-modern" target="_blank">
                    <i class="fas fa-download"></i> File Saat Ini
                </a>
                <a href="{{ route('administrasi.arsip.index') }}" class="btn-cancel-modern">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-submit-modern" id="btnSubmit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Preview file name
        $('#file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $(this).siblings('small').first().html('<i class="fas fa-file me-1"></i> File baru dipilih: ' + fileName);
            }
        });

        // Validate & show progress saat submit
        $('#editForm').on('submit', function(e) {
            var fileInput = document.getElementById('file');
            var progressDiv = document.getElementById('uploadProgress');
            var btnSubmit = document.getElementById('btnSubmit');

            if (fileInput.files.length > 0) {
                var fileSize = fileInput.files[0].size;
                var maxSize = 10 * 1024 * 1024; // 10MB

                if (fileSize > maxSize) {
                    e.preventDefault();
                    Swal.fire('Error', 'Ukuran file terlalu besar! Maksimal 10MB.', 'error');
                    return false;
                }

                // Show progress
                progressDiv.classList.remove('d-none');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupload...';

                var progress = 0;
                var interval = setInterval(function() {
                    progress += 10;
                    var progressBar = document.querySelector('#uploadProgress .progress-bar');
                    if (progressBar) progressBar.style.width = progress + '%';
                    if (progress >= 100) clearInterval(interval);
                }, 200);
            }
        });
    });
</script>
@endpush
@endsection