{{-- resources/views/administrasi/profil/index.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-4">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name ?? 'Administrasi' }}</h4>
                <p class="text-muted mb-3">
                    <i class="fas fa-briefcase me-1"></i> Administrasi
                </p>
                <hr>
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $user->email ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-phone me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $user->no_hp ?? $user->no_telepon ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $user->alamat ?? '-' }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-clock me-2 text-secondary" style="width: 20px;"></i> 
                        Bergabung: {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
                <a href="{{ route('administrasi.profil.edit') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                    <i class="fas fa-edit me-1"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2 text-primary"></i> Informasi Akun
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Nama Lengkap</td>
                                <td class="fw-bold">: {{ $user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td class="fw-bold">: {{ $user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Telepon</td>
                                <td class="fw-bold">: {{ $user->no_hp ?? $user->no_telepon ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Role</td>
                                <td class="fw-bold">
                                    <span class="badge bg-primary">{{ ucfirst($user->role ?? 'Administrasi') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td class="fw-bold">
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bergabung</td>
                                <td class="fw-bold">: {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Alamat:</strong>
                    <p class="mb-0">{{ $user->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-key me-2 text-warning"></i> Ubah Password
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('administrasi.profil.change-password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="password" name="current_password" class="form-control" placeholder="Password Saat Ini" required>
                            @error('current_password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="password" class="form-control" placeholder="Password Baru (min 8)" required>
                            @error('password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning rounded-pill px-4">
                                <i class="fas fa-save me-1"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection