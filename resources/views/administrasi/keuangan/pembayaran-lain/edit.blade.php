@extends('administrasi.layouts.header')

@section('title', 'Edit Pembayaran Lain')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Pembayaran Lain
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- PERBAIKAN: Gunakan route index dengan .index -->
        <a href="{{ route('administrasi.keuangan.pembayaran-lain.index') }}" class="btn btn-sm btn-secondary">
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.update', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswaByKelas as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id', $pembayaran->siswa_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->nis }} - {{ $s->user->name ?? $s->nama_lengkap }} ({{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori Pembayaran <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $key => $value)
                            <option value="{{ $key }}" {{ old('kategori', $pembayaran->kategori) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah', $pembayaran->jumlah) }}" required min="1000">
                    </div>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                        <option value="">Pilih Metode</option>
                        @foreach($metodeList as $key => $value)
                            <option value="{{ $key }}" {{ old('metode_bayar', $pembayaran->metode_bayar) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('metode_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                           value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar ? date('Y-m-d', strtotime($pembayaran->tanggal_bayar)) : date('Y-m-d')) }}">
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection