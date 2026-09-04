@extends('layouts.header')

@section('title', 'Edit Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-edit me-2"></i>Edit Galeri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('administrasi.galeri.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="kegiatan" {{ old('category', $gallery->category) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                        <option value="prestasi" {{ old('category', $gallery->category) == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                                        <option value="acara" {{ old('category', $gallery->category) == 'acara' ? 'selected' : '' }}>Acara</option>
                                        <option value="lainnya" {{ old('category', $gallery->category) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="event_date" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                           id="event_date" name="event_date" value="{{ old('event_date', $gallery->event_date ? date('Y-m-d', strtotime($gallery->event_date)) : '') }}">
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', $gallery->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $gallery->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar</label>
                                    @if($gallery->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/galleries/' . $gallery->image) }}" 
                                                 alt="{{ $gallery->title }}" 
                                                 style="max-width: 200px; max-height: 150px; border-radius: 5px;">
                                            <br>
                                            <small class="text-muted">Gambar saat ini</small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Maks: 5MB (jpg, jpeg, png, gif, webp)</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('administrasi.galeri.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Galeri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection