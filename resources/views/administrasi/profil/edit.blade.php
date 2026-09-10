{{-- resources/views/administrasi/profil/edit.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Edit Profil')

@section('content')
<style>
.up-prof-edit {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
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
.up-prof-edit * { box-sizing: border-box; }

.up-prof-edit .up-page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-prof-edit .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-prof-edit .up-page-title i { color: var(--up-primary); }
.up-prof-edit .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}

.up-prof-edit .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    max-width: 800px;
    margin: 0 auto;
}
.up-prof-edit .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-prof-edit .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-prof-edit .up-card-head small {
    color: rgba(255,255,255,.85);
    font-size: .8rem;
    display: block;
    margin-top: 4px;
}
.up-prof-edit .up-card-body {
    padding: 2rem 1.75rem;
}

/* Avatar preview */
.up-prof-edit .up-avatar-row {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px dashed var(--up-border);
}
.up-prof-edit .up-avatar-big {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    color: var(--up-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    text-transform: uppercase;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .2);
}
.up-prof-edit .up-avatar-info-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0 0 2px 0;
}
.up-prof-edit .up-avatar-info-role {
    font-size: .82rem;
    color: var(--up-text-light);
    margin: 0;
}

/* Section divider */
.up-prof-edit .up-section-title {
    font-size: .8rem;
    font-weight: 700;
    color: var(--up-text-muted);
    text-transform: uppercase;
    letter-spacing: .8px;
    margin: 1.5rem 0 1rem 0;
    padding-bottom: .75rem;
    border-bottom: 1px dashed var(--up-border);
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-prof-edit .up-section-title i {
    color: var(--up-primary);
    font-size: .9rem;
}
.up-prof-edit .up-section-title:first-child { margin-top: 0; }

/* Form */
.up-prof-edit .up-form-group {
    margin-bottom: 1.25rem;
}
.up-prof-edit .up-form-label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    color: var(--up-text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: .5rem;
}
.up-prof-edit .up-form-label .req { color: var(--up-danger); }
.up-prof-edit .up-form-control {
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
.up-prof-edit .up-form-control:focus {
    border-color: var(--up-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.up-prof-edit .up-form-control:disabled {
    background: var(--up-bg-soft);
    color: var(--up-text-muted);
    cursor: not-allowed;
}
.up-prof-edit textarea.up-form-control {
    min-height: 90px;
    resize: vertical;
}
.up-prof-edit .up-form-control.is-invalid {
    border-color: var(--up-danger);
}
.up-prof-edit .invalid-feedback {
    color: var(--up-danger);
    font-size: .78rem;
    margin-top: 6px;
    font-weight: 500;
    display: block;
}
.up-prof-edit .up-form-help {
    display: block;
    color: var(--up-text-light);
    font-size: .78rem;
    margin-top: 6px;
}

/* Buttons */
.up-prof-edit .up-btn {
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
.up-prof-edit .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25);
}
.up-prof-edit .up-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, .4);
    color: #fff;
}
.up-prof-edit .up-btn-outline {
    background: #fff;
    color: var(--up-text-muted);
    border: 1.5px solid var(--up-border);
}
.up-prof-edit .up-btn-outline:hover {
    background: var(--up-bg-soft);
    color: var(--up-text);
}

/* Footer */
.up-prof-edit .up-card-foot {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 1.25rem 1.75rem;
    border-top: 1px solid var(--up-border);
    background: var(--up-bg-soft);
}

@media (max-width: 768px) {
    .up-prof-edit .up-card-foot {
        flex-direction: column;
    }
    .up-prof-edit .up-card-foot .up-btn {
        width: 100%;
    }
    .up-prof-edit .up-avatar-row {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<div class="up-prof-edit">

    {{-- Page Header --}}
    <div class="up-page-head">
        <div>
            <h1 class="up-page-title">
                <i class="fas fa-user-edit"></i>
                Edit Profil
            </h1>
            <p class="up-page-sub">Perbarui informasi akun Anda</p>
        </div>
        <a href="{{ route('administrasi.profil.index') }}" class="up-btn up-btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Card --}}
    <div class="up-card">
        <div class="up-card-head">
            <div>
                <h5><i class="fas fa-id-card"></i> Form Edit Profil</h5>
                <small>Field bertanda <b style="color:#fecaca;">*</b> wajib diisi</small>
            </div>
        </div>

        <form action="{{ route('administrasi.profil.update') }}" method="POST" id="profForm">
            @csrf
            @method('PUT')

            <div class="up-card-body">

                {{-- Avatar Preview --}}
                <div class="up-avatar-row">
                    <div class="up-avatar-big">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="up-avatar-info-name">{{ $user->name ?? '-' }}</p>
                        <p class="up-avatar-info-role">
                            <i class="fas fa-user-shield me-1"></i>
                            {{ ucfirst($user->role ?? 'Administrasi') }}
                        </p>
                    </div>
                </div>

                {{-- Section 1: Identitas --}}
                <div class="up-section-title">
                    <i class="fas fa-user"></i>
                    Identitas
                </div>

                <div class="up-form-group">
                    <label for="name" class="up-form-label">
                        Nama Lengkap <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="up-form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Masukkan nama lengkap"
                           required
                           autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="up-form-group">
                    <label for="email" class="up-form-label">
                        Email <span class="req">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="up-form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Masukkan alamat email"
                           required
                           autocomplete="off">
                    <small class="up-form-help">Email akan digunakan untuk login.</small>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Section 2: Kontak & Role --}}
                <div class="up-section-title">
                    <i class="fas fa-address-book"></i>
                    Kontak & Jabatan
                </div>

                <div class="up-form-group">
                    <label for="no_hp" class="up-form-label">No. Telepon</label>
                    <input type="text"
                           name="no_hp"
                           id="no_hp"
                           class="up-form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp', $user->no_hp ?? $user->no_telepon ?? '') }}"
                           placeholder="Contoh: 08123456789"
                           autocomplete="off">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="up-form-group">
                    <label for="role" class="up-form-label">Role / Jabatan</label>
                    <input type="text"
                           id="role"
                           class="up-form-control"
                           value="{{ ucfirst($user->role ?? 'Administrasi') }}"
                           disabled>
                    <small class="up-form-help">Role tidak dapat diubah dari halaman ini.</small>
                </div>

                {{-- Section 3: Alamat --}}
                <div class="up-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Alamat
                </div>

                <div class="up-form-group">
                    <label for="alamat" class="up-form-label">Alamat Lengkap</label>
                    <textarea name="alamat"
                              id="alamat"
                              class="up-form-control @error('alamat') is-invalid @enderror"
                              placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="up-card-foot">
                <a href="{{ route('administrasi.profil.index') }}" class="up-btn up-btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" form="profForm" class="up-btn up-btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection