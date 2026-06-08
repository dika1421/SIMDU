@extends('administrasi.layouts.header')

@section('title', 'Edit Siswa')

@section('content')
<style>
    .required-field::after {
        content: " *";
        color: red;
    }
    .password-strength {
        margin-top: 5px;
        font-size: 12px;
    }
    .strength-weak { color: #dc3545; }
    .strength-medium { color: #ffc107; }
    .strength-strong { color: #28a745; }
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    .btn-loading i {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .form-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        border-left: 4px solid #0d6efd;
    }
    .form-section h5 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
        color: #0d6efd;
    }
    .info-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-edit me-2"></i>
        Edit Data Siswa
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.siswa.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan validasi:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.siswa.update', $siswa->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <!-- Informasi Dasar -->
            <div class="form-section">
                <h5><i class="fas fa-info-circle me-2"></i> Informasi Dasar</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                               value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $siswa->user->email ?? '-' }}" disabled>
                        <small class="info-text">Email tidak dapat diubah</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" class="form-control" value="{{ $siswa->nis }}" disabled>
                        <small class="info-text">NIS tidak dapat diubah</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" class="form-control" value="{{ $siswa->nisn }}" disabled>
                        <small class="info-text">NISN tidak dapat diubah</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                               value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}" required>
                        <small class="info-text">Format: YYYY-MM-DD</small>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-control">
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama', $siswa->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $siswa->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $siswa->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $siswa->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $siswa->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $siswa->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $siswa->no_telepon) }}">
                    </div>
                </div>
            </div>
            
            <!-- Data Orang Tua -->
            <div class="form-section">
                <h5><i class="fas fa-users me-2"></i> Data Orang Tua</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" 
                               value="{{ old('nama_ayah', $siswa->nama_ayah) }}" required>
                        @error('nama_ayah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" 
                               value="{{ old('nama_ibu', $siswa->nama_ibu) }}" required>
                        @error('nama_ibu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">No Telepon Orang Tua</label>
                        <input type="text" name="no_telp_ortu" class="form-control @error('no_telp_ortu') is-invalid @enderror" 
                               value="{{ old('no_telp_ortu', $siswa->no_telepon_orangtua) }}" required>
                        @error('no_telp_ortu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan Orang Tua</label>
                        <input type="text" name="pekerjaan_ortu" class="form-control" 
                               value="{{ old('pekerjaan_ortu', $siswa->pekerjaan_orangtua) }}">
                    </div>
                </div>
            </div>
            
            <!-- Data Akademik -->
            <div class="form-section">
                <h5><i class="fas fa-graduation-cap me-2"></i> Data Akademik</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Kelas</label>
                        <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }} - {{ $k->tingkat }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control" 
                               value="{{ old('tahun_masuk', $siswa->tahun_masuk) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $siswa->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="lulus" {{ old('status', $siswa->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="dropout" {{ old('status', $siswa->status) == 'dropout' ? 'selected' : '' }}>Drop Out</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Data Login -->
            <div class="form-section">
                <h5><i class="fas fa-key me-2"></i> Data Login</h5>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Login:</strong><br>
                            Email: <strong>{{ $siswa->user->email ?? '-' }}</strong><br>
                            Password saat ini: <strong>••••••••</strong>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        <div id="passwordStrength" class="password-strength"></div>
                        <small class="info-text">Minimal 6 karakter. Isi hanya jika ingin mengganti password.</small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                               placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('administrasi.siswa.reset-password', $siswa->id) }}" method="POST" style="display: inline-block;" id="resetForm">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin ingin mereset password? Password akan direset menjadi NIS: {{ $siswa->nis }}')">
                            <i class="fas fa-key"></i> Reset Password ke NIS
                        </button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Update Data Siswa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    console.log('Script edit siswa loaded');
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            let strengthText = strength <= 2 ? 'Lemah' : (strength <= 4 ? 'Sedang' : 'Kuat');
            let strengthClass = strength <= 2 ? 'strength-weak' : (strength <= 4 ? 'strength-medium' : 'strength-strong');
            
            strengthDiv.innerHTML = `<span class="${strengthClass}">Kekuatan password: ${strengthText}</span>`;
        });
    }
    
    // Reset form function - hanya reset field tertentu
    function resetForm() {
        console.log('Reset form called - hanya mereset field password');
        
        // Hanya reset password fields, jangan reset data lain
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        if (password) password.value = '';
        if (passwordConfirmation) passwordConfirmation.value = '';
        
        const strengthDiv = document.getElementById('passwordStrength');
        if (strengthDiv) strengthDiv.innerHTML = '';
        
        // Remove invalid class from all inputs
        document.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        // Tampilkan pesan
        alert('Field password telah direset. Data lain tetap seperti semula.');
    }
    
    // Handle form submit
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const submitBtn = document.getElementById('submitBtn');
            
            // Validasi password
            if (password && password.value && password.value !== passwordConfirmation.value) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                passwordConfirmation.focus();
                return false;
            }
            
            if (password && password.value && password.value.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                password.focus();
                return false;
            }
            
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.classList.add('btn-loading');
            }
            
            return true;
        });
    } else {
        console.error('Form with id "editForm" not found!');
    }
    
    // Remove invalid class on input
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
@endpush
@endsection