{{-- resources/views/administrasi/profil/index.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Profil Saya')

@section('content')
<style>
.up-prof-wrapper {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-warning: #f59e0b;
    --up-warning-dark: #d97706;
    --up-success: #10b981;
    --up-danger: #dc2626;
    --up-text: #1e293b;
    --up-text-muted: #64748b;
    --up-text-light: #94a3b8;
    --up-bg-soft: #f8fafc;
    --up-border: #e2e8f0;
    --up-radius: 16px;
    --up-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    color: var(--up-text);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-bottom: 2rem;
}
.up-prof-wrapper * { box-sizing: border-box; }

.up-prof-wrapper .up-page-head {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-prof-wrapper .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-prof-wrapper .up-page-title i { color: var(--up-primary); }
.up-prof-wrapper .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}

/* Layout grid */
.up-prof-wrapper .up-grid {
    display: grid;
    grid-template-columns: minmax(280px, 1fr) minmax(400px, 2fr);
    gap: 1.5rem;
}
@media (max-width: 992px) {
    .up-prof-wrapper .up-grid {
        grid-template-columns: 1fr;
    }
}

/* Card */
.up-prof-wrapper .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}
.up-prof-wrapper .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-prof-wrapper .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-prof-wrapper .up-card-body {
    padding: 1.75rem 1.5rem;
}

/* Profile sidebar card */
.up-prof-wrapper .up-profile-card {
    background: #fff;
    border-radius: var(--up-radius);
    box-shadow: var(--up-shadow);
    overflow: hidden;
    text-align: center;
}
.up-prof-wrapper .up-profile-banner {
    height: 100px;
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    position: relative;
}
.up-prof-wrapper .up-profile-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: #fff;
    border: 5px solid #fff;
    margin: -55px auto 0;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.25);
}
.up-prof-wrapper .up-profile-avatar-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--up-primary);
    font-size: 2.5rem;
    font-weight: 800;
    text-transform: uppercase;
}
.up-prof-wrapper .up-profile-content {
    padding: 1rem 1.5rem 1.5rem;
}
.up-prof-wrapper .up-profile-name {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--up-text);
    margin: 0 0 4px 0;
}
.up-prof-wrapper .up-profile-role {
    color: var(--up-text-light);
    font-size: .85rem;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.up-prof-wrapper .up-info-list {
    text-align: left;
    border-top: 1px dashed var(--up-border);
    padding-top: 1rem;
    margin-bottom: 1rem;
}
.up-prof-wrapper .up-info-list-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 8px 0;
    font-size: .85rem;
    color: var(--up-text);
}
.up-prof-wrapper .up-info-list-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #eef2ff;
    color: var(--up-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.up-prof-wrapper .up-info-list-text {
    flex-grow: 1;
    min-width: 0;
    word-break: break-word;
    padding-top: 4px;
}
.up-prof-wrapper .up-info-list-label {
    display: block;
    font-size: .7rem;
    color: var(--up-text-light);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 2px;
}

/* Buttons */
.up-prof-wrapper .up-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 22px;
    font-size: .85rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    white-space: nowrap;
    font-family: inherit;
}
.up-prof-wrapper .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25);
}
.up-prof-wrapper .up-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, .4);
    color: #fff;
}
.up-prof-wrapper .up-btn-warning {
    background: linear-gradient(135deg, var(--up-warning) 0%, var(--up-warning-dark) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(245, 158, 11, .25);
}
.up-prof-wrapper .up-btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, .4);
    color: #fff;
}
.up-prof-wrapper .up-btn-block { width: 100%; }

/* Info table */
.up-prof-wrapper .up-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.up-prof-wrapper .up-info-block {
    padding: 14px 16px;
    background: var(--up-bg-soft);
    border-radius: 12px;
    border: 1px solid var(--up-border);
}
.up-prof-wrapper .up-info-block-label {
    font-size: .7rem;
    color: var(--up-text-light);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.up-prof-wrapper .up-info-block-value {
    font-size: .9rem;
    color: var(--up-text);
    font-weight: 600;
    word-break: break-word;
}

/* Badges */
.up-prof-wrapper .up-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}
.up-prof-wrapper .up-badge-role {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
}
.up-prof-wrapper .up-badge-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

/* Form */
.up-prof-wrapper .up-form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.up-prof-wrapper .up-form-control {
    width: 100%;
    padding: 12px 16px;
    font-size: .9rem;
    color: var(--up-text);
    background: #fff;
    border: 1.5px solid var(--up-border);
    border-radius: 10px;
    outline: none;
    transition: all .2s;
    font-family: inherit;
}
.up-prof-wrapper .up-form-control:focus {
    border-color: var(--up-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.up-prof-wrapper .up-form-control.is-invalid {
    border-color: var(--up-danger);
}
.up-prof-wrapper .invalid-feedback {
    color: var(--up-danger);
    font-size: .78rem;
    margin-top: 4px;
    display: block;
}

/* Alert */
.up-prof-wrapper .up-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: .9rem;
}
.up-prof-wrapper .up-alert-success {
    background: #ecfdf5;
    color: #065f46;
    border-left: 4px solid var(--up-success);
}
.up-prof-wrapper .up-alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}
</style>

<div class="up-prof-wrapper">

    {{-- Page Header --}}
    <div class="up-page-head">
        <h1 class="up-page-title">
            <i class="fas fa-user-circle"></i>
            Profil Saya
        </h1>
        <p class="up-page-sub">Informasi akun dan pengaturan keamanan</p>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="up-alert up-alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="up-grid">

        {{-- ============================================================
             KOLOM KIRI: Profile Card
             ============================================================ --}}
        <div>
            <div class="up-profile-card">
                <div class="up-profile-banner"></div>

                <div class="up-profile-avatar">
                    <div class="up-profile-avatar-inner">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                </div>

                <div class="up-profile-content">
                    <h4 class="up-profile-name">{{ $user->name ?? 'Administrasi' }}</h4>
                    <p class="up-profile-role">
                        <i class="fas fa-briefcase"></i>
                        Administrasi
                    </p>

                    <div class="up-info-list">
                        <div class="up-info-list-item">
                            <div class="up-info-list-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="up-info-list-text">
                                <span class="up-info-list-label">Email</span>
                                {{ $user->email ?? '-' }}
                            </div>
                        </div>

                        <div class="up-info-list-item">
                            <div class="up-info-list-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="up-info-list-text">
                                <span class="up-info-list-label">No. Telepon</span>
                                {{ $user->no_hp ?? $user->no_telepon ?? '-' }}
                            </div>
                        </div>

                        <div class="up-info-list-item">
                            <div class="up-info-list-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="up-info-list-text">
                                <span class="up-info-list-label">Alamat</span>
                                {{ $user->alamat ?? '-' }}
                            </div>
                        </div>

                        <div class="up-info-list-item">
                            <div class="up-info-list-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="up-info-list-text">
                                <span class="up-info-list-label">Bergabung</span>
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('administrasi.profil.edit') }}" class="up-btn up-btn-primary up-btn-block">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================================================
             KOLOM KANAN: Info Akun + Ganti Password
             ============================================================ --}}
        <div>

            {{-- Info Akun --}}
            <div class="up-card">
                <div class="up-card-head">
                    <h5><i class="fas fa-info-circle"></i> Informasi Akun</h5>
                </div>
                <div class="up-card-body">
                    <div class="up-info-grid">
                        <div class="up-info-block">
                            <div class="up-info-block-label">Nama Lengkap</div>
                            <div class="up-info-block-value">{{ $user->name ?? '-' }}</div>
                        </div>

                        <div class="up-info-block">
                            <div class="up-info-block-label">Email</div>
                            <div class="up-info-block-value">{{ $user->email ?? '-' }}</div>
                        </div>

                        <div class="up-info-block">
                            <div class="up-info-block-label">No. Telepon</div>
                            <div class="up-info-block-value">{{ $user->no_hp ?? $user->no_telepon ?? '-' }}</div>
                        </div>

                        <div class="up-info-block">
                            <div class="up-info-block-label">Role / Jabatan</div>
                            <div class="up-info-block-value">
                                <span class="up-badge up-badge-role">
                                    <i class="fas fa-user-shield"></i>
                                    {{ ucfirst($user->role ?? 'Administrasi') }}
                                </span>
                            </div>
                        </div>

                        <div class="up-info-block">
                            <div class="up-info-block-label">Status</div>
                            <div class="up-info-block-value">
                                <span class="up-badge up-badge-success">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            </div>
                        </div>

                        <div class="up-info-block">
                            <div class="up-info-block-label">Bergabung</div>
                            <div class="up-info-block-value">
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:1.25rem;">
                        <div class="up-info-block-label" style="margin-bottom:6px;">Alamat</div>
                        <div class="up-info-block-value" style="font-weight:500;">
                            {{ $user->alamat ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="up-card">
                <div class="up-card-head">
                    <h5><i class="fas fa-key"></i> Ubah Password</h5>
                </div>
                <div class="up-card-body">
                    <form action="{{ route('administrasi.profil.change-password') }}" method="POST">
                        @csrf

                        <div class="up-form-row">
                            <div>
                                <input type="password"
                                       name="current_password"
                                       class="up-form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Password Saat Ini"
                                       required>
                                @error('current_password')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <input type="password"
                                       name="password"
                                       class="up-form-control @error('password') is-invalid @enderror"
                                       placeholder="Password Baru (min 8)"
                                       required>
                                @error('password')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <input type="password"
                                       name="password_confirmation"
                                       class="up-form-control"
                                       placeholder="Konfirmasi Password"
                                       required>
                            </div>
                        </div>

                        <button type="submit" class="up-btn up-btn-warning" style="margin-top:1rem;">
                            <i class="fas fa-save"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection