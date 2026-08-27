{{-- resources/views/administrasi/profil/edit.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Edit Profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit Profil
                    </h5>
                    <a href="{{ route('administrasi.profil.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administrasi.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Nama Lengkap -->
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-12">
                            <label for="email" class="form-label fw-bold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" 
                                   placeholder="Masukkan alamat email"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Email akan digunakan untuk login</small>
                        </div>
                        
                        <!-- No Telepon -->
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-bold">
                                No. Telepon
                            </label>
                            <input type="text" 
                                   name="no_hp" 
                                   id="no_hp" 
                                   class="form-control form-control-lg @error('no_hp') is-invalid @enderror" 
                                   value="{{ old('no_hp', $user->no_hp ?? $user->no_telepon ?? '') }}" 
                                   placeholder="Contoh: 08123456789">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Role (Readonly) -->
                        <div class="col-md-6">
                            <label for="role" class="form-label fw-bold">
                                Role / Jabatan
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg bg-light" 
                                   value="{{ ucfirst($user->role ?? 'Administrasi') }}" 
                                   disabled>
                            <small class="text-muted">Role tidak dapat diubah</small>
                        </div>
                        
                        <!-- Alamat -->
                        <div class="col-md-12">
                            <label for="alamat" class="form-label fw-bold">
                                Alamat
                            </label>
                            <textarea name="alamat" 
                                      id="alamat" 
                                      rows="3" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('administrasi.profil.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 ms-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection