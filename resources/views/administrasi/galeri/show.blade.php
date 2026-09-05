@extends('administrasi.layouts.header')

@section('title', 'Detail Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-image me-2"></i>Detail Galeri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                @if($gallery->gambar)
                                    <img src="{{ asset('storage/' . $gallery->gambar) }}" 
                                         alt="{{ $gallery->judul }}" 
                                         style="max-width: 100%; max-height: 400px; border-radius: 10px;">
                                @else
                                    <div class="alert alert-warning">Tidak ada gambar</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Judul</th>
                                    <td>{{ $gallery->judul }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td><span class="badge bg-primary">{{ ucfirst($gallery->kategori) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $gallery->deskripsi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $gallery->tanggal ? $gallery->tanggal->format('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($gallery->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $gallery->created_at ? $gallery->created_at->format('d F Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                            <div class="d-flex gap-2">
                                <a href="{{ route('administrasi.galeri.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <a href="{{ route('administrasi.galeri.edit', $gallery->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a>
                                <form action="{{ route('administrasi.galeri.destroy', $gallery->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash me-2"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection