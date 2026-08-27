{{-- resources/views/admin/roles/create.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Tambah Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> Tambah Role
                    </h5>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Nama Role -->
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold">
                                Nama Role <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Contoh: super_admin, kepala_sekolah, guru"
                                   required>
                            <small class="text-muted">Gunakan huruf kecil dan underscore (_), contoh: super_admin</small>
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
                                   placeholder="Contoh: Super Administrator"
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
                                      placeholder="Deskripsi role ini">{{ old('description') }}</textarea>
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
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <label for="is_default" class="form-check-label fw-bold">
                                    Jadikan Role Default
                                </label>
                                <small class="text-muted d-block">Role default akan diberikan ke user baru secara otomatis</small>
                            </div>
                        </div>

                        <!-- Assign Permissions -->
                        <div class="col-md-12 mt-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="fas fa-key me-2 text-warning"></i> Assign Permission
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $permissionGroups = \App\Models\Permission::all()->groupBy('group');
                                        @endphp
                                        
                                        @forelse($permissionGroups as $group => $permissions)
                                            <div class="col-md-6 mb-3">
                                                <h6 class="fw-bold text-primary">{{ $group ?? 'General' }}</h6>
                                                @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="perm_{{ $permission->id }}"
                                                               class="form-check-input">
                                                        <label for="perm_{{ $permission->id }}" class="form-check-label small">
                                                            {{ $permission->display_name ?? $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-muted text-center">Belum ada permission yang tersedia</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 ms-2">
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