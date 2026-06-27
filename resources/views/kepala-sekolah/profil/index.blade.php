@extends('kepala-sekolah.layouts.header')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 60%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(30deg);
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 4px solid rgba(255,255,255,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        backdrop-filter: blur(10px);
    }
    .profile-avatar i {
        color: white;
    }
    .profile-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .profile-role {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .info-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .info-card .card-body {
        padding: 25px;
    }
    .info-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .info-icon.primary {
        background: #e8f0fe;
        color: #1a73e8;
    }
    .info-icon.success {
        background: #e6f7e6;
        color: #28a745;
    }
    .info-icon.warning {
        background: #fff3e0;
        color: #ff9800;
    }
    .info-icon.info {
        background: #e3f2fd;
        color: #03a9f4;
    }
    .info-icon.danger {
        background: #fde8e8;
        color: #dc3545;
    }
    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    .btn-action {
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .modal-content {
        border-radius: 16px;
        border: none;
    }
    .modal-header {
        border-bottom: none;
        padding: 25px 30px 15px;
    }
    .modal-body {
        padding: 15px 30px 25px;
    }
    .modal-footer {
        border-top: none;
        padding: 15px 30px 25px;
    }
    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
</style>

<!-- Profile Header -->
<div class="profile-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="profile-avatar me-4">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-role">
                        <i class="fas fa-briefcase me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </p>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-25 text-success px-3 py-2">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                            Aktif
                        </span>
                        <span class="badge bg-primary bg-opacity-25 text-primary px-3 py-2 ms-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Bergabung: {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-block bg-white bg-opacity-20 rounded-3 p-3">
                <i class="fas fa-shield-alt fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card info-card">
            <div class="card-body">
                <div class="info-item">
                    <div class="info-icon primary">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon success">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div class="info-label">No Telepon</div>
                        <div class="info-value">{{ $user->no_telepon ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card">
            <div class="card-body">
                <div class="info-item">
                    <div class="info-icon warning">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div>
                        <div class="info-label">Role</div>
                        <div class="info-value">
                            <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="info-label">Terakhir Login</div>
                        <div class="info-value">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d F Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card">
            <div class="card-body">
                <div class="info-item">
                    <div class="info-icon danger">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon primary">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <div class="info-label">ID Pengguna</div>
                        <div class="info-value">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card info-card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('kepala-sekolah.profil.edit') }}" class="btn btn-primary btn-action">
                <i class="fas fa-edit me-2"></i> Edit Profil
            </a>
            <button type="button" class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <i class="fas fa-key me-2"></i> Ganti Password
            </button>
            <a href="{{ route('kepala-sekolah.dashboard') }}" class="btn btn-outline-secondary btn-action">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Modal Ganti Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2 text-warning"></i>
                    Ganti Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kepala-sekolah.profil.change-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(session('password_error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('password_error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru (min 6 karakter)" required>
                        </div>
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-action" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-action">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto close alert after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.classList.add('fade');
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    });
</script>
@endpush
@endsection