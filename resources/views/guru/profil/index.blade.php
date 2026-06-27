@extends('guru.layouts.header')

@section('title', 'Profil Guru')

@section('content')
<style>
    .profile-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        background: white;
        height: 100%;
        overflow: hidden;
    }
    
    .profile-avatar-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 20px 20px;
        text-align: center;
        position: relative;
    }
    
    .profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        background: white;
    }
    
    .profile-avatar-placeholder {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
        font-weight: 700;
        margin: 0 auto;
        border: 4px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    
    .profile-name {
        color: white;
        margin-top: 12px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .profile-role {
        color: rgba(255,255,255,0.85);
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    
    .profile-email {
        color: rgba(255,255,255,0.75);
        font-size: 0.8rem;
        margin-bottom: 0;
    }
    
    .profile-info-body {
        padding: 20px;
    }
    
    .info-item {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f5;
        align-items: flex-start;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        min-width: 120px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding-right: 10px;
        flex-shrink: 0;
    }
    
    .info-value {
        font-size: 0.9rem;
        color: #2c3e50;
        font-weight: 500;
        word-break: break-word;
    }
    
    .info-value .badge-role {
        background: #e8f4fd;
        color: #0c5460;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .info-value .badge-status {
        background: #d4edda;
        color: #155724;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .info-value .badge-gender {
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-gender.male {
        background: #cce5ff;
        color: #004085;
    }
    .badge-gender.female {
        background: #f8d7da;
        color: #721c24;
    }
    
    .stat-mini {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px 20px;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    
    .stat-mini .number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
    }
    
    .stat-mini .label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .stat-mini .icon {
        font-size: 1.5rem;
        opacity: 0.5;
        margin-bottom: 5px;
    }
    
    .stat-mini.primary .icon { color: #3498db; }
    .stat-mini.success .icon { color: #2ecc71; }
    .stat-mini.warning .icon { color: #f39c12; }
    
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    .section-title i {
        color: #667eea;
        margin-right: 8px;
    }
    
    .btn-edit {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .btn-edit i {
        margin-right: 6px;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .profile-avatar {
            width: 110px;
            height: 110px;
        }
        .profile-avatar-placeholder {
            width: 110px;
            height: 110px;
            font-size: 3rem;
        }
        .info-label {
            min-width: 100px;
            font-size: 0.7rem;
        }
        .info-value {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 768px) {
        .profile-avatar {
            width: 90px;
            height: 90px;
        }
        .profile-avatar-placeholder {
            width: 90px;
            height: 90px;
            font-size: 2.5rem;
        }
        .profile-name {
            font-size: 1rem;
        }
        .info-label {
            min-width: 80px;
            font-size: 0.65rem;
        }
        .info-value {
            font-size: 0.8rem;
        }
        .stat-mini .number {
            font-size: 1.4rem;
        }
    }
    
    @media print {
        .btn-edit {
            display: none !important;
        }
        .profile-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        .profile-avatar-wrapper {
            background: #f0f0f0 !important;
        }
        .profile-name {
            color: #333 !important;
        }
        .profile-role, .profile-email {
            color: #666 !important;
        }
    }
</style>

<div class="profile-wrapper">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center pt-2 pb-3 flex-wrap">
        <h1 class="h4 fw-bold mb-0">
            <i class="fas fa-user-circle me-2 text-primary"></i>
            Profil Guru
        </h1>
        <div>
            <a href="{{ route('guru.profil.edit') }}" class="btn btn-primary btn-edit">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
            <button type="button" class="btn btn-outline-secondary btn-edit" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column - Avatar & Quick Info -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-avatar-wrapper">
                    @if(isset($guru->foto) && $guru->foto && file_exists(storage_path('app/public/' . $guru->foto)))
                        <img src="{{ asset('storage/' . $guru->foto) }}" 
                             alt="Foto Profil" 
                             class="profile-avatar">
                    @else
                        <div class="profile-avatar-placeholder">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="profile-name">{{ $user->name ?? '-' }}</div>
                    <div class="profile-role">
                        <i class="fas fa-user-tag me-1"></i>
                        {{ ucfirst($user->role ?? 'Guru') }}
                    </div>
                    <div class="profile-email">
                        <i class="fas fa-envelope me-1"></i>
                        {{ $user->email ?? '-' }}
                    </div>
                </div>
                
                <div class="profile-info-body">
                    <!-- Quick Info -->
                    <div class="info-item">
                        <span class="info-label">NIP</span>
                        <span class="info-value">{{ $guru->nip ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NUPTK</span>
                        <span class="info-value">{{ $guru->nuptk ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">
                            @php
                                $gender = $guru->jenis_kelamin ?? null;
                            @endphp
                            @if($gender == 'L')
                                <span class="badge-gender male"><i class="fas fa-male me-1"></i>Laki-laki</span>
                            @elseif($gender == 'P')
                                <span class="badge-gender female"><i class="fas fa-female me-1"></i>Perempuan</span>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="badge-status">
                                <i class="fas fa-check-circle me-1"></i> Aktif
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Terdaftar</span>
                        <span class="info-value">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Detail Info -->
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-info-body">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> Detail Informasi
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ $user->name ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $user->email ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Role / Jabatan</span>
                        <span class="info-value">
                            <span class="badge-role">
                                <i class="fas fa-user-tag me-1"></i>
                                {{ ucfirst($user->role ?? 'Guru') }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nomor HP</span>
                        <span class="info-value">{{ $guru->nomor_hp ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">{{ $guru->alamat ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Terakhir Login</span>
                        <span class="info-value">
                            {{ $user->last_login ? Carbon\Carbon::parse($user->last_login)->translatedFormat('d F Y H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="stat-mini primary">
                <div class="icon"><i class="fas fa-school"></i></div>
                <div class="number">{{ $totalKelas ?? 0 }}</div>
                <div class="label">Kelas Diajar</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-mini success">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="number">{{ $totalSiswa ?? 0 }}</div>
                <div class="label">Total Siswa</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-mini warning">
                <div class="icon"><i class="fas fa-book"></i></div>
                <div class="number">{{ $totalMapel ?? 0 }}</div>
                <div class="label">Mata Pelajaran</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>
                    Ganti Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('guru.profil.change-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Password Baru</label>
                        <input type="password" name="new_password" class="form-control form-control-sm" required minlength="6">
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection