@extends('administrasi.layouts.header')

@section('title', 'Tambah Kelas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle me-2"></i>
        Tambah Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.kelas.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.kelas.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag text-primary"></i> Nama Kelas 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" 
                           value="{{ old('nama_kelas') }}" placeholder="Contoh: X Pemasaran 01" required>
                    <small class="text-muted">Nama kelas harus unik</small>
                    @error('nama_kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-barcode text-primary"></i> Kode Kelas
                    </label>
                    <input type="text" name="kode_kelas" class="form-control @error('kode_kelas') is-invalid @enderror" 
                           value="{{ old('kode_kelas') }}" placeholder="Contoh: X-PMN-01">
                    <small class="text-muted">Kosongkan untuk diisi otomatis</small>
                    @error('kode_kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-layer-group text-primary"></i> Tingkat 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach($tingkatList as $key => $value)
                            <option value="{{ $key }}" {{ old('tingkat') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('tingkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-graduation-cap text-primary"></i> Jurusan
                    </label>
                    <select name="jurusan_id" class="form-select @error('jurusan_id') is-invalid @enderror">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusanList as $jurusan)
                            <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                    @error('jurusan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-chalkboard-user text-primary"></i> Wali Kelas
                    </label>
                    <select name="wali_kelas_id" class="form-select @error('wali_kelas_id') is-invalid @enderror">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->user->name ?? $g->nama_lengkap }} ({{ $g->nip ?? '-' }})
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
                    <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" 
                           value="{{ old('kapasitas', 36) }}" min="1" max="100">
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
                           value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y')+1)) }}">
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
                        @foreach($statusList as $key => $value)
                            <option value="{{ $key }}" {{ old('status', 'aktif') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                              placeholder="Informasi tambahan tentang kelas">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection