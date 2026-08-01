@extends('administrasi.layouts.header')

@section('title', 'Manajemen Galeri')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-images me-2"></i>
        Manajemen Galeri
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.galeri.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Foto
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

<div class="row">
    @forelse($galleries as $gallery)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm">
            <img src="{{ asset('storage/galleries/' . $gallery->image) }}" 
                 class="card-img-top" 
                 alt="{{ $gallery->title }}"
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h6 class="card-title fw-bold">{{ $gallery->title }}</h6>
                <p class="card-text small text-muted">
                    {{ Str::limit($gallery->description, 50) }}
                </p>
                <span class="badge bg-{{ $gallery->status == 'active' ? 'success' : 'secondary' }}">
                    {{ $gallery->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="badge bg-info">{{ $gallery->category ?? 'Umum' }}</span>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="btn-group w-100">
                    <a href="{{ route('administrasi.galeri.edit', $gallery->id) }}" 
                       class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-danger" 
                            onclick="deleteGallery({{ $gallery->id }})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-images fa-4x text-muted mb-3"></i>
        <p class="text-muted">Belum ada foto di galeri</p>
        <a href="{{ route('administrasi.galeri.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Foto Pertama
        </a>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $galleries->links() }}
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus foto ini?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteGallery(id) {
        document.getElementById('deleteForm').action = '/administrasi/galeri/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection