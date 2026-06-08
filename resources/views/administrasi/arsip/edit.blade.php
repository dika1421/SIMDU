@extends('administrasi.layouts.header')

@section('title', 'Edit Dokumen')

@section('content')
<style>
    .form-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #0d6efd;
        color: #0d6efd;
    }
    .required-field::after {
        content: " *";
        color: red;
        font-weight: bold;
    }
    .current-file {
        background-color: #e9ecef;
        padding: 10px;
        border-radius: 5px;
        margin-top: 5px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Dokumen
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.arsip.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="fas fa-info-circle me-2"></i> Informasi Dokumen
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Arsip</label>
                        <input type="text" name="kode_arsip" class="form-control @error('kode_arsip') is-invalid @enderror" 
                               placeholder="Contoh: ARS-001" value="{{ old('kode_arsip', $arsip->kode_arsip) }}">
                        <small class="text-muted">Opsional, untuk pengkodean arsip</small>
                        @error('kode_arsip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Kategori</label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriList as $key => $value)
                                <option value="{{ $key }}" {{ old('kategori', $arsip->kategori) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label required-field">Judul Dokumen</label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                               placeholder="Masukkan judul/nama dokumen" value="{{ old('judul', $arsip->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Tanggal Dokumen</label>
                        <input type="date" name="tanggal_dokumen" class="form-control @error('tanggal_dokumen') is-invalid @enderror" 
                               value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen ? $arsip->tanggal_dokumen->format('Y-m-d') : date('Y-m-d')) }}" required>
                        @error('tanggal_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">File Dokumen</label>
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengganti file. Maksimal 10MB.
                        </small>
                        
                        @if($arsip->nama_file)
                            <div class="current-file mt-2">
                                <i class="fas fa-file me-2"></i>
                                <strong>File saat ini:</strong> {{ $arsip->nama_file }}
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> Diupload: {{ $arsip->created_at->format('d/m/Y H:i') }}
                                    <br>
                                    <i class="fas fa-database me-1"></i> Ukuran: {{ $arsip->ukuran_file_formatted ?? round($arsip->ukuran_file / 1024, 2) . ' KB' }}
                                </small>
                            </div>
                        @endif
                        
                        <div class="progress mt-2 d-none" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%">0%</div>
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="fas fa-align-left me-2"></i> Informasi Tambahan
                </h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                  rows="3" placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn btn-info me-2" target="_blank">
                    <i class="fas fa-download"></i> Download File Saat Ini
                </a>
                <button type="reset" class="btn btn-secondary me-2">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
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
        
        // Show upload progress
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
                
                // Show progress bar
                progressDiv.classList.remove('d-none');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupload...';
                
                // Simulate progress
                var progress = 0;
                var interval = setInterval(function() {
                    progress += 10;
                    var progressBar = document.querySelector('#uploadProgress .progress-bar');
                    if (progressBar) {
                        progressBar.style.width = progress + '%';
                        progressBar.innerHTML = progress + '%';
                    }
                    if (progress >= 100) {
                        clearInterval(interval);
                    }
                }, 200);
            }
        });
    });
</script>
@endpush
@endsection