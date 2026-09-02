{{-- resources/views/administrasi/roles/edit.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Edit Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit Role
                    </h5>
                    <a href="{{ route('administrasi.roles.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administrasi.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Nama Role (Readonly) -->
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold">
                                Nama Role <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg bg-light" 
                                   value="{{ $role->name }}" 
                                   readonly
                                   disabled>
                            <small class="text-muted">Nama role tidak dapat diubah</small>
                            <input type="hidden" name="name" value="{{ $role->name }}">
                        </div>

                        <!-- Display Name -->
                        <div class="col-md-12">
                            <label for="display_name" class="form-label fw-bold">
                                Nama Tampilan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="display_name" 
                                   id="display_name" 
                                   class="form-control form-control-lg @error('display_name') is-invalid @enderror" 
                                   value="{{ old('display_name', $role->display_name) }}"
                                   required>
                            @error('display_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-12">
                            <label for="description" class="form-label fw-bold">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Default -->
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="is_default" 
                                       id="is_default" 
                                       class="form-check-input"
                                       {{ old('is_default', $role->is_default) ? 'checked' : '' }}>
                                <label for="is_default" class="form-check-label fw-bold">
                                    Jadikan Role Default
                                </label>
                                <small class="text-muted d-block">Role default akan diberikan ke user baru secara otomatis</small>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-save me-2"></i> Update Role
                            </button>
                            <a href="{{ route('administrasi.roles.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 ms-2">
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