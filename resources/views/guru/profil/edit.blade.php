@extends('guru.layouts.header')

@section('title', 'Edit Profil Guru')

@section('content')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .profile-avatar-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        font-weight: 700;
        margin: 0 auto;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .form-label-modern {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }
    .form-control-modern {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 16px;
        transition: all 0.3s;
    }
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-modern {
        border-radius: 10px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <h1 class="h2 fw-bold mb-0">
        <i class="fas fa-user-edit me-2 text-primary"></i>
        Edit Profil Guru
    </h1>
    <div>
        <a href="{{ route('guru.profil.index') }}" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card profile-card">
            <div class="card-body text-center py-4">
                @if(isset($guru->foto) && $guru->foto)
                    <img src="{{ asset('storage/' . $guru->foto) }}" 
                         alt="Foto Profil" 
                         class="profile-avatar mb-3"
                         id="profilePreview">
                @else
                    <div class="profile-avatar-placeholder mb-3" id="profilePreviewPlaceholder">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <img src="" alt="Foto Profil" class="profile-avatar mb-3 d-none" id="profilePreview">
                @endif
                
                <h4 class="fw-bold mb-1">{{ $user->name ?? '-' }}</h4>
                <p class="text-muted">{{ $user->email ?? '-' }}</p>
                
                <div class="mt-3">
                    <label for="foto" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-camera me-1"></i> Upload Foto
                    </label>
                    <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                </div>
                <small class="text-muted">Format: JPG, PNG, JPEG. Max 2MB</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card profile-card">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Form Edit Profil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="foto" id="fotoInput">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-modern" 
                                   value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-modern" 
                                   value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">NIP</label>
                            <input type="text" name="nip" class="form-control form-control-modern" 
                                   value="{{ old('nip', $guru->nip ?? '') }}" readonly>
                            <small class="text-muted">NIP tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">NUPTK</label>
                            <input type="text" name="nuptk" class="form-control form-control-modern" 
                                   value="{{ old('nuptk', $guru->nuptk ?? '') }}" readonly>
                            <small class="text-muted">NUPTK tidak dapat diubah</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control form-control-modern">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ (old('jenis_kelamin', $guru->jenis_kelamin ?? '') == 'L') ? 'selected' : '' }}>
                                    <i class="fas fa-male"></i> Laki-laki
                                </option>
                                <option value="P" {{ (old('jenis_kelamin', $guru->jenis_kelamin ?? '') == 'P') ? 'selected' : '' }}>
                                    <i class="fas fa-female"></i> Perempuan
                                </option>
                            </select>
                            @error('jenis_kelamin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Nomor HP</label>
                            <input type="text" name="nomor_hp" class="form-control form-control-modern" 
                                   value="{{ old('nomor_hp', $guru->nomor_hp ?? '') }}">
                            @error('nomor_hp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-modern">Alamat</label>
                        <textarea name="alamat" class="form-control form-control-modern" rows="3">{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('guru.profil.index') }}" class="btn btn-secondary btn-modern">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                        <button type="button" class="btn btn-warning btn-modern ms-auto" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i> Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>
                    Ganti Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('guru.profil.change-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                        <small class="text-muted">Minimal 6 karakter</small>
                        @error('new_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Preview foto sebelum upload
        $('#foto').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreview').attr('src', e.target.result).removeClass('d-none');
                    $('#profilePreviewPlaceholder').addClass('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
@endsection