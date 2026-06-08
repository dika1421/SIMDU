@extends('administrasi.layouts.header')

@section('title', 'Tambah Siswa')

@section('content')
<style>
    .tab-pane {
        min-height: 400px;
    }
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .required-field::after {
        content: " *";
        color: red;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .step {
        flex: 1;
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        position: relative;
        z-index: 1;
    }
    .step.active {
        background: #007bff;
        color: white;
    }
    .step.completed {
        background: #28a745;
        color: white;
    }
    .password-strength {
        margin-top: 5px;
        font-size: 12px;
    }
    .strength-weak { color: #dc3545; }
    .strength-medium { color: #ffc107; }
    .strength-strong { color: #28a745; }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>
        Tambah Siswa Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.siswa.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan:</strong>
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
        <form action="{{ route('administrasi.siswa.store') }}" method="POST" id="siswaForm">
            @csrf
            
            <!-- Step Indicators -->
            <div class="step-indicator mb-4">
                <div class="step active" id="step1">1. Data Pribadi</div>
                <div class="step" id="step2">2. Data Orang Tua</div>
                <div class="step" id="step3">3. Data Akademik</div>
            </div>
            
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist" style="display: none;">
                <li class="nav-item">
                    <button class="nav-link active" id="data-pribadi-tab" data-bs-toggle="tab" data-bs-target="#data-pribadi" type="button">
                        Data Pribadi
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="data-ortu-tab" data-bs-toggle="tab" data-bs-target="#data-ortu" type="button">
                        Data Orang Tua
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="data-akademik-tab" data-bs-toggle="tab" data-bs-target="#data-akademik" type="button">
                        Data Akademik
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">
                <!-- Tab Data Pribadi -->
                <div class="tab-pane fade show active" id="data-pribadi" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">NIS</label>
                            <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-control">
                                <option value="">Pilih</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan untuk menggunakan NIS sebagai password">
                            <div id="passwordStrength" class="password-strength"></div>
                            <small class="text-muted">Minimal 6 karakter. Jika kosong, password akan menggunakan NIS.</small>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" onclick="nextTab()">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                
                <!-- Tab Data Orang Tua -->
                <div class="tab-pane fade" id="data-ortu" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">No Telepon Orang Tua</label>
                            <input type="text" name="no_telp_ortu" class="form-control @error('no_telp_ortu') is-invalid @enderror" value="{{ old('no_telp_ortu') }}" required>
                            @error('no_telp_ortu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan Orang Tua</label>
                            <input type="text" name="pekerjaan_ortu" class="form-control" value="{{ old('pekerjaan_ortu') }}">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" onclick="prevTab()"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
                        <button type="button" class="btn btn-primary" onclick="nextTab()">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                
                <!-- Tab Data Akademik -->
                <div class="tab-pane fade" id="data-akademik" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Kelas</label>
                            <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
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
                            <input type="number" name="tahun_masuk" class="form-control" value="{{ old('tahun_masuk', date('Y')) }}">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" onclick="prevTab()"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Data Siswa
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3" id="passwordInfo">
                <i class="fas fa-info-circle me-2"></i>
                <span id="passwordInfoText">Password default untuk siswa adalah <strong>NIS</strong> (contoh: jika NIS = 2024001, maka password = 2024001)</span>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentTab = 0;
    const tabs = ['data-pribadi', 'data-ortu', 'data-akademik'];
    const steps = ['step1', 'step2', 'step3'];
    
    function showTab(index) {
        tabs.forEach(tab => {
            document.getElementById(tab).classList.remove('show', 'active');
        });
        
        document.getElementById(tabs[index]).classList.add('show', 'active');
        
        steps.forEach((step, i) => {
            const stepElement = document.getElementById(step);
            if (i < index) {
                stepElement.classList.add('completed');
                stepElement.classList.remove('active');
            } else if (i === index) {
                stepElement.classList.add('active');
                stepElement.classList.remove('completed');
            } else {
                stepElement.classList.remove('active', 'completed');
            }
        });
        
        currentTab = index;
    }
    
    function nextTab() {
        if (validateCurrentTab()) {
            if (currentTab < tabs.length - 1) {
                showTab(currentTab + 1);
            }
        }
    }
    
    function prevTab() {
        if (currentTab > 0) {
            showTab(currentTab - 1);
        }
    }
    
    function validateCurrentTab() {
        let isValid = true;
        const currentTabId = tabs[currentTab];
        
        if (currentTabId === 'data-pribadi') {
            const requiredFields = ['nama_lengkap', 'email', 'nis', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'];
            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input && !input.value) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            });
        } else if (currentTabId === 'data-ortu') {
            const noTelpOrtu = document.querySelector('[name="no_telp_ortu"]');
            if (noTelpOrtu && !noTelpOrtu.value) {
                noTelpOrtu.classList.add('is-invalid');
                isValid = false;
            } else if (noTelpOrtu) {
                noTelpOrtu.classList.remove('is-invalid');
            }
        } else if (currentTabId === 'data-akademik') {
            const kelas = document.querySelector('[name="kelas_id"]');
            if (kelas && !kelas.value) {
                kelas.classList.add('is-invalid');
                isValid = false;
            } else if (kelas) {
                kelas.classList.remove('is-invalid');
            }
        }
        
        if (!isValid) {
            alert('Harap isi semua field yang wajib diisi (ditandai dengan *)');
        }
        
        return isValid;
    }
    
    // Password strength checker
    document.getElementById('password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');
        const passwordInfo = document.getElementById('passwordInfoText');
        
        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            passwordInfo.innerHTML = 'Password default untuk siswa adalah <strong>NIS</strong> (contoh: jika NIS = 2024001, maka password = 2024001)';
            return;
        }
        
        let strength = 0;
        if (password.length >= 6) strength++;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        let strengthText = '';
        let strengthClass = '';
        
        if (strength <= 2) {
            strengthText = 'Lemah';
            strengthClass = 'strength-weak';
        } else if (strength <= 4) {
            strengthText = 'Sedang';
            strengthClass = 'strength-medium';
        } else {
            strengthText = 'Kuat';
            strengthClass = 'strength-strong';
        }
        
        strengthDiv.innerHTML = `<span class="${strengthClass}">Kekuatan password: ${strengthText}</span>`;
        passwordInfo.innerHTML = 'Password yang diisi akan digunakan untuk login.';
    });
    
    // Validasi sebelum submit
    document.getElementById('siswaForm').addEventListener('submit', function(e) {
        let isValid = true;
        const submitBtn = document.getElementById('submitBtn');
        
        const requiredFields = ['nama_lengkap', 'email', 'nis', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'];
        requiredFields.forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input && !input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        const noTelpOrtu = document.querySelector('[name="no_telp_ortu"]');
        if (noTelpOrtu && !noTelpOrtu.value) {
            noTelpOrtu.classList.add('is-invalid');
            isValid = false;
        }
        
        const kelas = document.querySelector('[name="kelas_id"]');
        if (kelas && !kelas.value) {
            kelas.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib diisi (ditandai dengan *)');
            showTab(0);
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        }
    });
    
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
@endpush
@endsection