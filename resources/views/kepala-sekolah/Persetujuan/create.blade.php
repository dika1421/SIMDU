@extends('kepala-sekolah.layouts.header')

@section('title', 'Buat Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-plus me-2"></i> Buat Pengajuan</h4>
                <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kepala-sekolah.persetujuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                                       value="{{ old('judul') }}" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pengaju <span class="text-danger">*</span></label>
                                <select name="pengaju_id" class="form-select @error('pengaju_id') is-invalid @enderror" required>
                                    <option value="">Pilih Pengaju</option>
                                    @foreach($pengajuList ?? [] as $p)
                                        <option value="{{ $p->id }}" {{ old('pengaju_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pengaju_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="anggaran" {{ old('tipe') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                                    <option value="izin" {{ old('tipe') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="proyek" {{ old('tipe') == 'proyek' ? 'selected' : '' }}>Proyek</option>
                                    <option value="lainnya" {{ old('tipe') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Anggaran</label>
                                <input type="number" name="jumlah_anggaran" class="form-control @error('jumlah_anggaran') is-invalid @enderror" 
                                       value="{{ old('jumlah_anggaran') }}" step="0.01" min="0">
                                @error('jumlah_anggaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="4" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Lampiran</label>
                                <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 5MB</small>
                                @error('lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection