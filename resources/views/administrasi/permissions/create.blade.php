{{-- resources/views/admin/permissions/create.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Tambah Permission')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> Tambah Permission
                    </h5>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold">
                                Nama Permission <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Contoh: siswa.view, siswa.create, guru.edit"
                                   required>
                            <small class="text-muted">Format: {module}.{action} (contoh: siswa.view, siswa.create)</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('display_name') }}" 
                                   placeholder="Contoh: Lihat Siswa, Tambah Guru"
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
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Deskripsi permission ini">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Group -->
                        <div class="col-md-12">
                            <label for="group" class="form-label fw-bold">
                                Group <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="group" 
                                   id="group" 
                                   class="form-control form-control-lg @error('group') is-invalid @enderror" 
                                   value="{{ old('group') }}" 
                                   placeholder="Contoh: Siswa, Guru, Keuangan, Absensi"
                                   required>
                            <small class="text-muted">Gunakan huruf kapital di awal kata, contoh: Siswa, Guru</small>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Permission
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 ms-2">
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