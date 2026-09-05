@extends('administrasi.layouts.header')

@section('title', 'Tambah Jurusan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle me-2"></i>
        Tambah Jurusan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jurusan.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.jurusan.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-barcode text-primary"></i> Kode Jurusan
                    </label>
                    <input type="text" name="kode_jurusan" class="form-control @error('kode_jurusan') is-invalid @enderror" 
                           value="{{ old('kode_jurusan') }}" placeholder="Contoh: PMN, TBG, JBG">
                    <small class="text-muted">Kosongkan untuk diisi otomatis</small>
                    @error('kode_jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag text-primary"></i> Nama Jurusan 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Contoh: Pemasaran, Tata Boga, Jasa Boga" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-circle text-primary"></i> Status 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-chalkboard-user text-primary"></i> Kepala Jurusan
                    </label>
                    <select name="kepala_jurusan_id" class="form-select @error('kepala_jurusan_id') is-invalid @enderror">
                        <option value="">-- Pilih Kepala Jurusan --</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->id }}" {{ old('kepala_jurusan_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama_lengkap ?? $guru->user->name ?? '-' }} ({{ $guru->nip ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Guru yang ditunjuk sebagai ketua jurusan</small>
                    @error('kepala_jurusan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle text-primary"></i> Deskripsi
                    </label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" 
                              placeholder="Informasi tambahan tentang jurusan">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
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
                    <i class="fas fa-save"></i> Simpan Jurusan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection