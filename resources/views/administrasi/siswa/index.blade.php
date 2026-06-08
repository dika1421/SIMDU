@extends('administrasi.layouts.header')

@section('title', 'Data Siswa')

@section('content')
<style>
    .stat-card {
        transition: transform 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .table-actions {
        white-space: nowrap;
    }
    .badge-status {
        font-size: 0.75rem;
        padding: 5px 10px;
    }
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 15px;
    }
    .filter-card .card-body {
        padding: 20px;
    }
    .filter-card .form-label {
        color: white;
        font-weight: 500;
    }
    .filter-card .form-control, 
    .filter-card .form-select {
        border-radius: 10px;
        border: none;
        padding: 10px 15px;
    }
    .filter-card .btn-primary {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 10px;
        padding: 10px 15px;
        font-weight: 500;
    }
    .filter-card .btn-primary:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    .progress-sm {
        height: 8px;
        border-radius: 4px;
    }
    #importProgress {
        margin-top: 10px;
    }
    .alert ul {
        padding-left: 20px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
    .pagination {
        margin-bottom: 0;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-graduate me-2"></i>
        Data Siswa
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel"></i> Import Excel
        </button>
        <a href="{{ url('administrasi/siswa/download-template') }}" class="btn btn-sm btn-info me-2">
            <i class="fas fa-download"></i> Template
        </a>
        <a href="{{ url('administrasi/siswa/export') }}" class="btn btn-sm btn-secondary me-2">
            <i class="fas fa-file-export"></i> Export
        </a>
        <a href="{{ route('administrasi.siswa.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>
</div>

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
        @if(session('import_errors'))
            <hr>
            <strong>Detail Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Siswa</h6>
                        <h2 class="mb-0">{{ $totalSiswa ?? $siswa->total() }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Siswa Aktif</h6>
                        <h2 class="mb-0">{{ $siswaAktif ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Kelas</h6>
                        <h2 class="mb-0">{{ $kelas->count() }}</h2>
                    </div>
                    <i class="fas fa-school fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Siswa Lulus</h6>
                        <h2 class="mb-0">{{ $siswaLulus ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.siswa.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">
                    <i class="fas fa-school me-1"></i> Kelas
                </label>
                <select name="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="fas fa-tag me-1"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="dropout" {{ request('status') == 'dropout' ? 'selected' : '' }}>Drop Out</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-search me-1"></i> Cari
                </label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama / NIS / NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('administrasi.siswa.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>
            Daftar Siswa
        </h5>
        <span class="badge bg-primary">{{ $siswa->total() }} Siswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0" id="siswaTable">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th width="12%">NIS</th>
                        <th width="12%">NISN</th>
                        <th width="25%">Nama Lengkap</th>
                        <th width="15%">Kelas</th>
                        <th width="10%">JK</th>
                        <th width="10%">Status</th>
                        <th width="11%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                    <tr>
                        <td class="text-center">{{ $siswa->firstItem() + $index }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $s->nis ?? '-' }}</span>
                        </td>
                        <td class="text-center">{{ $s->nisn ?? '-' }}</td>
                        <td>
                            <strong>{{ $s->user->name ?? $s->nama_lengkap }}</strong>
                            <br>
                            <small class="text-muted">{{ $s->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            @if($s->kelas)
                                <span class="badge bg-secondary">{{ $s->kelas->nama }}</span>
                                <br>
                                <small class="text-muted">{{ $s->kelas->tingkat }}</small>
                            @else
                                <span class="badge bg-secondary">Belum ada kelas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($s->jenis_kelamin == 'L')
                                <span class="badge bg-primary">Laki-laki</span>
                            @elseif($s->jenis_kelamin == 'P')
                                <span class="badge bg-danger">Perempuan</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $statusColor = [
                                    'aktif' => 'success',
                                    'lulus' => 'info',
                                    'nonaktif' => 'danger',
                                    'dropout' => 'dark'
                                ];
                                $color = $statusColor[$s->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('administrasi.siswa.show', $s->id) }}" 
                                   class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('administrasi.siswa.edit', $s->id) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger" 
                                        onclick="confirmDelete({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-database fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada data siswa</p>
                            <a href="{{ route('administrasi.siswa.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Tambah Siswa Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($siswa->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small">
                    Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }} 
                    dari {{ $siswa->total() ?? 0 }} siswa
                </div>
                <div>
                    {{ $siswa->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    Import Data Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('administrasi/siswa/import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk Import:</strong>
                        <ul class="mb-0 mt-2">
                            <li>File harus berformat <strong>.CSV</strong> dengan separator koma (,)</li>
                            <li>Ukuran maksimal file: <strong>2 MB</strong></li>
                            <li>Download template terlebih dahulu</li>
                            <li>Kolom wajib: <strong>NIS, NAMA SISWA, JENIS KELAMIN</strong></li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
                        <small class="text-muted">Maksimal 2 MB, format .csv</small>
                    </div>

                    <div class="progress d-none" id="importProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="btnImport">
                        <i class="fas fa-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus siswa <strong id="siswaName" class="text-danger"></strong>?</p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        document.getElementById('siswaName').innerText = name;
        document.getElementById('deleteForm').action = "{{ url('administrasi/siswa') }}/" + id;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    $(document).ready(function() {
        // Progress bar untuk import
        $('#importForm').on('submit', function(e) {
            const fileInput = document.getElementById('file');
            const progressDiv = document.getElementById('importProgress');
            const btnImport = document.getElementById('btnImport');
            
            if (!fileInput.files.length) {
                e.preventDefault();
                Swal.fire('Error', 'Silakan pilih file terlebih dahulu!', 'error');
                return false;
            }
            
            const fileName = fileInput.files[0].name;
            const extension = fileName.split('.').pop().toLowerCase();
            if (extension !== 'csv') {
                e.preventDefault();
                Swal.fire('Error', 'Format file harus .csv!', 'error');
                return false;
            }
            
            progressDiv.classList.remove('d-none');
            btnImport.disabled = true;
            btnImport.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
        });
        
        // Submit delete form via AJAX
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var siswaName = $('#siswaName').text();
            
            $('#deleteModal').modal('hide');
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menghapus data',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data siswa berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { window.location.reload(); });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        });
        
        // Auto close alert
        setTimeout(function() { $('.alert').fadeOut('slow'); }, 3000);
    });
</script>
@endpush
@endsection