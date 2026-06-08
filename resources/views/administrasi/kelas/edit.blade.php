@extends('administrasi.layouts.header')

@section('title', 'Edit Kelas')

@section('content')
<style>
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }
    
    .info-card h5 {
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .info-card .stat-number {
        font-size: 24px;
        font-weight: bold;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 5px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.kelas.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrasi.kelas.update', $kelas->id) }}" method="POST" id="formKelas">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag text-primary"></i> Nama Kelas 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" 
                                   value="{{ old('nama_kelas', $kelas->nama_kelas ?? $kelas->kelas ?? $kelas->nama) }}" 
                                   placeholder="Contoh: X IPA 1, XI IPS 2, XII Bahasa 1" required>
                            <small class="text-muted">Nama kelas harus unik</small>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-layer-group text-primary"></i> Tingkat 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                                <option value="">-- Pilih Tingkat --</option>
                                @foreach($tingkatList as $key => $value)
                                    <option value="{{ $key }}" {{ old('tingkat', $kelas->tingkat) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-graduation-cap text-primary"></i> Jurusan
                            </label>
                            <select name="jurusan_id" class="form-select @error('jurusan_id') is-invalid @enderror">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusanList as $jurusan)
                                    <option value="{{ $jurusan->id }}" 
                                        {{ old('jurusan_id', $kelas->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                                        {{ $jurusan->nama }} 
                                        @if(isset($jurusan->kode_jurusan))
                                            ({{ $jurusan->kode_jurusan }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('jurusan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-chalkboard-user text-primary"></i> Wali Kelas
                            </label>
                            <select name="wali_kelas_id" class="form-select @error('wali_kelas_id') is-invalid @enderror">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}" 
                                        {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $g->id ? 'selected' : '' }}>
                                        {{ $g->user->name ?? $g->nama_lengkap ?? $g->nama }} 
                                        @if($g->nip)
                                            ({{ $g->nip }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('wali_kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users text-primary"></i> Kapasitas
                            </label>
                            <input type="number" name="kapasitas" id="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" 
                                   value="{{ old('kapasitas', $kelas->kapasitas ?? 36) }}" min="1" max="100">
                            <small class="text-muted">Maksimal 100 siswa</small>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> Tahun Ajaran
                            </label>
                            <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                   value="{{ old('tahun_ajaran', $kelas->tahun_ajaran ?? date('Y') . '/' . (date('Y')+1)) }}"
                                   placeholder="2024/2025">
                            <small class="text-muted">Format: 2024/2025</small>
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                <i class="fas fa-circle text-primary"></i> Status 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach($statusList as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', $kelas->status) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle text-primary"></i> Keterangan
                            </label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" 
                                      placeholder="Informasi tambahan tentang kelas">{{ old('keterangan', $kelas->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-undo-alt"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Update Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="info-card">
            <h5><i class="fas fa-chart-line me-2"></i> Statistik Kelas</h5>
            <div class="row text-center">
                <div class="col-6 mb-3">
                    <div class="stat-number">{{ $kelas->siswa_count ?? $kelas->siswa->count() ?? 0 }}</div>
                    <small>Total Siswa</small>
                </div>
                <div class="col-6 mb-3">
                    <div class="stat-number">{{ $kelas->kapasitas ?? 36 }}</div>
                    <small>Kapasitas</small>
                </div>
                <div class="col-6">
                    <div class="stat-number">
                        {{ ($kelas->siswa->where('jenis_kelamin', 'L')->count() ?? 
                           $kelas->siswa->where('gender', 'L')->count() ?? 0) }}
                    </div>
                    <small>Siswa Laki-laki</small>
                </div>
                <div class="col-6">
                    <div class="stat-number">
                        {{ ($kelas->siswa->where('jenis_kelamin', 'P')->count() ?? 
                           $kelas->siswa->where('gender', 'P')->count() ?? 0) }}
                    </div>
                    <small>Siswa Perempuan</small>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light">
                <i class="fas fa-clock me-2"></i> Informasi Kelas
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="40%" class="text-muted">ID Kelas</td>
                        <td><strong>{{ $kelas->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibuat Pada</td>
                        <td>{{ $kelas->created_at ? $kelas->created_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terakhir Update</td>
                        <td>{{ $kelas->updated_at ? $kelas->updated_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - Edit Kelas');
    
    // Hapus semua event handler sebelumnya dan pasang yang baru
    $('#formKelas').off('submit').on('submit', function(e) {
        console.log('Form submit triggered');
        
        var namaKelas = $('input[name="nama_kelas"]').val().trim();
        var tingkat = $('select[name="tingkat"]').val();
        var status = $('select[name="status"]').val();
        var kapasitas = parseInt($('input[name="kapasitas"]').val());
        var jumlahSiswa = parseInt('{{ $kelas->siswa->count() ?? 0 }}');
        
        console.log('Validasi:', {namaKelas, tingkat, status, kapasitas, jumlahSiswa});
        
        // Validasi nama kelas
        if (!namaKelas) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Validasi',
                text: 'Silakan masukkan nama kelas!',
                confirmButtonColor: '#3085d6'
            });
            $('input[name="nama_kelas"]').focus();
            return false;
        }
        
        // Validasi tingkat
        if (!tingkat) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Validasi',
                text: 'Silakan pilih tingkat!',
                confirmButtonColor: '#3085d6'
            });
            $('select[name="tingkat"]').focus();
            return false;
        }
        
        // Validasi status
        if (!status) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Validasi',
                text: 'Silakan pilih status!',
                confirmButtonColor: '#3085d6'
            });
            $('select[name="status"]').focus();
            return false;
        }
        
        // Validasi kapasitas
        if (kapasitas < jumlahSiswa) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Kapasitas kelas tidak boleh kurang dari jumlah siswa saat ini (' + jumlahSiswa + ' siswa)!',
                confirmButtonColor: '#d33'
            });
            return false;
        }
        
        // Validasi tahun ajaran
        var tahunAjaran = $('input[name="tahun_ajaran"]').val();
        if (tahunAjaran && !/^\d{4}\/\d{4}$/.test(tahunAjaran)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Validasi',
                text: 'Format tahun ajaran harus YYYY/YYYY (contoh: 2024/2025)',
                confirmButtonColor: '#3085d6'
            });
            $('input[name="tahun_ajaran"]').focus();
            return false;
        }
        
        // Konfirmasi update
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Update',
            text: 'Apakah Anda yakin ingin mengupdate data kelas ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4caf50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').html('<span class="loading-spinner"></span> Mengupdate...').prop('disabled', true);
                // Submit form manually
                $('#formKelas')[0].submit();
            }
        });
        
        return false;
    });
    
    // Validasi kapasitas saat diubah
    $('#kapasitas').on('change', function() {
        var kapasitasBaru = parseInt($(this).val());
        var jumlahSiswa = parseInt('{{ $kelas->siswa->count() ?? 0 }}');
        
        if (kapasitasBaru < jumlahSiswa) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Kapasitas baru (' + kapasitasBaru + ') lebih kecil dari jumlah siswa saat ini (' + jumlahSiswa + '). Data tidak akan tersimpan!',
                confirmButtonColor: '#3085d6'
            });
            $(this).val(jumlahSiswa);
        }
    });
});

function resetForm() {
    Swal.fire({
        title: 'Reset Form',
        text: 'Apakah Anda yakin ingin mereset semua field ke nilai awal?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection