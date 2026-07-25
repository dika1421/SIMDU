@extends('administrasi.layouts.header')

@section('title', 'Manajemen Kelas')

@section('content')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .badge-count {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
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
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-school me-2"></i>
        Manajemen Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- TAMBAHAN: TOMBOL IMPORT -->
        <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-csv"></i> Import CSV
        </button>
        <a href="{{ route('administrasi.kelas.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Kelas
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

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('administrasi.kelas.index') }}" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label class="form-label">Cari Kelas</label>
                <input type="text" name="search" class="form-control" placeholder="Nama kelas..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($tingkatList as $key => $value)
                        <option value="{{ $key }}" {{ request('tingkat') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jurusan</label>
                <select name="jurusan_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($statusList as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('administrasi.kelas.index') }}" class="btn btn-secondary">
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
                        <th>Nama Kelas</th>
                        <th>Kode Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Siswa</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $key => $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                        <td>
                            <strong>{{ $item->nama ?? '-' }}</strong>
                            @if(!empty($item->keterangan))
                                <br>
                                <small class="text-muted">{{ Str::limit($item->keterangan, 30) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <code>{{ $item->kode_kelas ?? '-' }}</code>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $item->tingkat ?? '-' }}</span>
                        </td>
                        <td>
                            @if($item->jurusan)
                                {{ $item->jurusan->nama }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->waliKelas)
                                {{ $item->waliKelas->nama_lengkap }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->kapasitas ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $item->siswa->count() ?? 0 }}</span>
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
                                <a href="{{ route('administrasi.kelas.show', $item->id) }}" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('administrasi.kelas.edit', $item->id) }}" class="btn btn-warning" title="Edit">
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
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-database fa-4x text-muted mb-3 d-block"></i>
                                <h5>Tidak ada data kelas</h5>
                                <p class="text-muted">Silakan tambah kelas terlebih dahulu</p>
                                <a href="{{ route('administrasi.kelas.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Kelas
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
                    Menampilkan {{ $kelas->firstItem() ?? 0 }} - {{ $kelas->lastItem() ?? 0 }} 
                    dari {{ $kelas->total() ?? 0 }} data
                </small>
            </div>
            <div>
                {{ $kelas->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- TAMBAHAN: MODAL IMPORT - TIDAK MERUBAH CODE ASLI LAINNYA -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('administrasi.kelas.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-file-csv me-2"></i>Import Kelas dari CSV</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <small><b>Format CSV:</b> nama, jurusan, tingkat <br>Contoh: <code>X A PEMASARAN,PEMASARAN,X</code><br>
                File <b>01_import_kelas.csv</b> yang tadi sudah sesuai.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Pilih File CSV</label>
                <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import</button>
        </div>
      </form>
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
                html: "Data kelas <strong>" + name + "</strong> akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menghapus data kelas',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: "{{ url('administrasi/kelas') }}/" + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: 'Kelas <strong>' + name + '</strong> berhasil dihapus',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            var errorMsg = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMsg
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