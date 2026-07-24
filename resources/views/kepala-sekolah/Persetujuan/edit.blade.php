@extends('kepala-sekolah.layouts.header')

@section('title', 'Edit Pengajuan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i> Edit Pengajuan
                <div class="float-end">
                    <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.persetujuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Pengaju <span class="text-danger">*</span></label>
                        <select name="pengaju_id" class="form-select" required>
                            @foreach($pengajuList as $user)
                            <option value="{{ $user->id }}" {{ $pengajuan->pengaju_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('pengaju_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $pengajuan->judul) }}" required>
                        @error('judul')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <option value="anggaran" {{ $pengajuan->tipe == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                            <option value="izin" {{ $pengajuan->tipe == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="proyek" {{ $pengajuan->tipe == 'proyek' ? 'selected' : '' }}>Proyek</option>
                            <option value="lainnya" {{ $pengajuan->tipe == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('tipe')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $pengajuan->deskripsi) }}</textarea>
                        @error('deskripsi')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Anggaran</label>
                        <input type="number" name="jumlah_anggaran" class="form-control" value="{{ old('jumlah_anggaran', $pengajuan->jumlah_anggaran ?? 0) }}">
                        @error('jumlah_anggaran')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioritas</label>
                        <select name="prioritas" class="form-select">
                            <option value="rendah" {{ $pengajuan->prioritas == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ $pengajuan->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ $pengajuan->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                        @error('prioritas')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran</label>
                        @if($pengajuan->lampiran)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $pengajuan->lampiran) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> Lihat Lampiran Saat Ini
                                </a>
                            </div>
                        @endif
                        <input type="file" name="lampiran" class="form-control">
                        <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                        @error('lampiran')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection