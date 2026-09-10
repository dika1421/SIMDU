@extends('kepala-sekolah.layouts.header')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-cog me-2"></i>
        Pengaturan Sistem
    </h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="#profil" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                <i class="fas fa-school me-2"></i> Profil Sekolah
            </a>
            <a href="#keamanan" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="fas fa-shield-alt me-2"></i> Keamanan
            </a>
            <a href="#backup" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="fas fa-database me-2"></i> Backup & Restore
            </a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="tab-content">
            <!-- Profil Sekolah -->
            <div class="tab-pane active" id="profil">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Profil Sekolah</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kepala-sekolah.pengaturan.update-profil') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" class="form-control"
                                           value="{{ old('nama_sekolah', $pengaturan->nama_sekolah) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NUPTK</label>
                                    <input type="text" name="nuptk" class="form-control"
                                           value="{{ old('nuptk', $pengaturan->nuptk) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Akreditasi</label>
                                    <select name="akreditasi" class="form-control">
                                        @foreach(['A', 'B', 'C'] as $akr)
                                            <option value="{{ $akr }}"
                                                {{ old('akreditasi', $pengaturan->akreditasi) == $akr ? 'selected' : '' }}>
                                                {{ $akr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kepala Sekolah</label>
                                    <input type="text" name="kepala_sekolah" class="form-control"
                                           value="{{ old('kepala_sekolah', $pengaturan->kepala_sekolah) }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pengaturan->alamat) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" name="telepon" class="form-control"
                                           value="{{ old('telepon', $pengaturan->telepon) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $pengaturan->email) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website</label>
                                    <input type="text" name="website" class="form-control"
                                           value="{{ old('website', $pengaturan->website) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo Sekolah</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    @if($pengaturan->logo)
                                        <small class="text-muted d-block mt-2">
                                            Logo saat ini:
                                            <img src="{{ Storage::url($pengaturan->logo) }}"
                                                 alt="Logo" style="height: 40px; margin-left: 6px; vertical-align: middle;">
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Keamanan -->
            <div class="tab-pane" id="keamanan">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Pengaturan Keamanan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kepala-sekolah.pengaturan.update-keamanan') }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="two_factor"
                                           id="twoFactor" {{ $pengaturan->two_factor ? 'checked' : '' }}>
                                    <label class="form-check-label" for="twoFactor">
                                        Aktifkan Two-Factor Authentication
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notifikasi_login"
                                           id="loginNotif" {{ $pengaturan->notifikasi_login ? 'checked' : '' }}>
                                    <label class="form-check-label" for="loginNotif">
                                        Notifikasi Login
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Masa Berlaku Password (hari)</label>
                                <input type="number" name="masa_berlaku_password" class="form-control"
                                       value="{{ old('masa_berlaku_password', $pengaturan->masa_berlaku_password) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Batasan Percobaan Login</label>
                                <input type="number" name="batas_percobaan_login" class="form-control"
                                       value="{{ old('batas_percobaan_login', $pengaturan->batas_percobaan_login) }}">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Backup -->
            <div class="tab-pane" id="backup">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Backup & Restore Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Terakhir backup: {{ now()->format('d F Y, H:i') }} WIB
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                        <h5>Backup Database</h5>
                                        <p>Download backup database terbaru</p>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download Backup
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-upload fa-3x text-success mb-3"></i>
                                        <h5>Restore Database</h5>
                                        <p>Upload file backup untuk restore</p>
                                        <input type="file" class="form-control mb-2">
                                        <button class="btn btn-success">
                                            <i class="fas fa-upload me-2"></i>Restore
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection