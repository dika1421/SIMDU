@extends('administrasi.layouts.header')

@section('title', 'Manajemen Jurusan')

@section('content')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .table th {
        background-color: #2c3e50;
        color: white;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-delete {
        cursor: pointer;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-code-branch me-2"></i>
        Manajemen Jurusan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jurusan.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Jurusan
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('administrasi.jurusan.index') }}" class="row g-3" id="filterForm">
            <div class="col-md-4">
                <label class="form-label">Cari Jurusan</label>
                <input type="text" name="search" class="form-control" placeholder="Kode atau nama jurusan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-5 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('administrasi.jurusan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>Kode Jurusan</th>
                        <th>Nama Jurusan</th>
                        <th>Kepala Jurusan</th>
                        <th width="80">Jml Kelas</th>
                        <th width="80">Jml Siswa</th>
                        <th width="100">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusan as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + $jurusan->firstItem() }}</td>
                        <td class="text-center">
                            <strong><code>{{ $item->kode_jurusan }}</code></strong>
                        </td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            @if($item->kepalaJurusan)
                                {{ $item->kepalaJurusan->nama_lengkap ?? $item->kepalaJurusan->user->name ?? '-' }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $item->kelas->count() }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $item->siswa->count() }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('administrasi.jurusan.show', $item->id) }}" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('administrasi.jurusan.edit', $item->id) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-delete" 
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->nama }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-database fa-4x text-muted mb-3 d-block"></i>
                                <h5>Tidak ada data jurusan</h5>
                                <p class="text-muted">Silakan tambah jurusan terlebih dahulu</p>
                                <a href="{{ route('administrasi.jurusan.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Jurusan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $jurusan->firstItem() ?? 0 }} - {{ $jurusan->lastItem() ?? 0 }} 
                    dari {{ $jurusan->total() ?? 0 }} data
                </small>
            </div>
            <div>
                {{ $jurusan->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Delete button click handler
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Data jurusan <strong>" + name + "</strong> akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menghapus data jurusan',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Kirim request DELETE via AJAX
                    $.ajax({
                        url: "{{ url('administrasi/jurusan') }}/" + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: 'Jurusan <strong>' + name + '</strong> berhasil dihapus',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    html: response.message || 'Terjadi kesalahan saat menghapus data'
                                });
                            }
                        },
                        error: function(xhr) {
                            var errorMsg = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    errorMsg = response.message || errorMsg;
                                } catch(e) {
                                    errorMsg = xhr.responseText;
                                }
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMsg
                            });
                        }
                    });
                }
            });
        });
        
        // Auto close alert after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush