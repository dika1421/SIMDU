@extends('administrasi.layouts.header')

@section('title', 'Daftar Guru')

@section('content')
<style>
    .card-stats {
        transition: all 0.3s ease;
    }
    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    }
    .table-guru th {
        background-color: #2c3e50 !important;
        color: white !important;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-guru td {
        vertical-align: middle;
    }
    .search-box {
        position: relative;
    }
    .search-box .form-control {
        padding-left: 40px;
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }
    .badge-jk-l {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }
    .badge-jk-p {
        background: linear-gradient(135deg, #e91e63, #c2185b);
    }
    .badge-mapel {
        background: linear-gradient(135deg, #667eea, #764ba2);
        font-size: 0.7rem;
        padding: 4px 8px;
        margin: 2px;
        display: inline-block;
    }
    .mapel-container {
        max-width: 250px;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-user me-2 text-primary"></i>
        Daftar Guru
    </h1>
    <div class="btn-toolbar">
        <button type="button" class="btn btn-sm btn-success me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-csv me-1"></i> Import CSV
        </button>
        
        <a href="{{ route('administrasi.guru.download-template') }}" class="btn btn-sm btn-info me-2 shadow-sm text-white">
            <i class="fas fa-download me-1"></i> Template CSV
        </a>
        
        <a href="{{ route('administrasi.guru.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Guru
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Guru</h6>
                        <h3 class="mb-0 fw-bold">{{ $guru->total() ?? 0 }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Guru Laki-laki</h6>
                        <h3 class="mb-0 fw-bold text-primary">{{ $guruLaki ?? 0 }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-mars fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Guru Perempuan</h6>
                        <h3 class="mb-0 fw-bold text-danger">{{ $guruPerempuan ?? 0 }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-venus fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Rata-rata Usia</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $rataUsia ?? 0 }} <small class="fs-6">thn</small></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-calendar-alt fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('administrasi.guru.index') }}" method="GET" class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama guru, NUPTK, NIP, atau jabatan..." 
                           value="{{ request('search') }}" style="padding-left: 40px;">
                </form>
            </div>
            <div class="col-md-6 text-end">
                @if(request('search'))
                    <a href="{{ route('administrasi.guru.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Reset Filter
                    </a>
                @endif
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
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

        <!-- Tabel Data Guru -->
        <div class="table-responsive">
            <table class="table table-guru table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th width="30">NO.</th>
                        <th width="150">NAMA GURU</th>
                        <th width="40">JK</th>
                        <th width="120">TEMPAT TANGGAL LAHIR</th>
                        <th width="180">ALAMAT LENGKAP</th>
                        <th width="100">NUPTK/NIP</th>
                        <th width="100">JABATAN</th>
                        <th width="180">MATA PELAJARAN</th>
                        <th width="120">PENDIDIKAN</th>
                        <th width="80">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $key => $g)
                    <tr>
                        <td class="text-center fw-bold">{{ $key + $guru->firstItem() }}</td>
                        <td>
                            <strong>{{ $g->nama_lengkap ?? '-' }}</strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-envelope fa-xs"></i> {{ $g->user->email ?? '-' }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if(($g->jenis_kelamin ?? '') == 'L')
                                <span class="badge badge-jk-l px-3 py-2 rounded-pill">
                                    <i class="fas fa-mars me-1"></i> L
                                </span>
                            @elseif(($g->jenis_kelamin ?? '') == 'P')
                                <span class="badge badge-jk-p px-3 py-2 rounded-pill">
                                    <i class="fas fa-venus me-1"></i> P
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">-</span>
                            @endif
                        </td>
                        <td>
                            <div><strong>{{ $g->tempat_lahir ?? '-' }}</strong></div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt fa-xs me-1"></i>
                                {{ $g->tanggal_lahir ? \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </small>
                        </td>
                        <td style="max-width: 180px;">
                            <small>{{ Str::limit($g->alamat ?? '-', 50) }}</small>
                        </td>
                        <td>
                            <div><strong>NUPTK:</strong> <code>{{ $g->nuptk ?? '-' }}</code></div>
                            <small class="text-muted"><strong>NIP:</strong> {{ $g->nip ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-25 text-dark px-3 py-2 rounded-pill">
                                {{ $g->status_kepegawaian ?? '-' }}
                            </span>
                        </td>
                        <td class="mapel-container">
                            @if($g->mataPelajaran && $g->mataPelajaran->count() > 0)
                                <div style="max-height: 80px; overflow-y: auto;">
                                    @foreach($g->mataPelajaran as $mapel)
                                        <span class="badge badge-mapel rounded-pill mb-1 d-inline-block">
                                            <i class="fas fa-book-open me-1"></i>
                                            {{ Str::limit($mapel->nama_mapel, 25) }}
                                        </span>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-layer-group"></i> {{ $g->mataPelajaran->count() }} Mata Pelajaran
                                </small>
                            @else
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="fas fa-minus"></i> Belum ada
                                </span>
                            @endif
                        </td>
                        <td>
                            <div><strong>{{ $g->pendidikan_terakhir ?? '-' }}</strong></div>
                            <small class="text-muted">{{ $g->jurusan_pendidikan ?? '-' }}</small>
                            <br>
                            <small>{{ $g->universitas ?? '-' }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('administrasi.guru.edit', $g->id) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" title="Hapus" 
                                        onclick="confirmDelete({{ $g->id }}, '{{ addslashes($g->nama_lengkap ?? '') }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fas fa-users-slash fa-4x mb-3 d-block"></i>
                            <h5>Belum ada data guru</h5>
                            <p>Silakan tambah guru melalui tombol "Tambah Guru" atau "Import CSV"</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
            <div>
                <small class="text-muted">
                    <i class="fas fa-table me-1"></i>
                    Menampilkan <strong>{{ $guru->firstItem() ?? 0 }}</strong> - <strong>{{ $guru->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $guru->total() ?? 0 }}</strong> data
                </small>
            </div>
            <div>
                {{ $guru->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash-alt me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus guru <strong id="guruName" class="text-danger"></strong>?</p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Perhatian! Data yang dihapus tidak dapat dikembalikan.</small>
                </div>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import CSV -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-csv me-2"></i>
                    Import Data Guru dari CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('administrasi.guru.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk Import CSV:</strong>
                        <ul class="mb-0 mt-2">
                            <li>File harus berformat <strong>.CSV</strong> dengan separator <strong>koma (,)</strong></li>
                            <li>Download template CSV terlebih dahulu dengan klik tombol "Template CSV"</li>
                            <li>Mata pelajaran dipisahkan dengan koma, contoh: Matematika, Fisika, Kimia</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
                        <small class="text-muted">Maksimal 2 MB, format .csv</small>
                    </div>

                    <div class="progress d-none" id="importProgress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        // Set nama guru di modal
        document.getElementById('guruName').innerText = name;
        
        // Set action form dengan ID yang benar - PERBAIKAN UTAMA
        var deleteForm = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.guru.destroy', ':id') }}";
        url = url.replace(':id', id);
        deleteForm.action = url;
        
        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }

    // Submit form on search (Enter key)
    document.querySelector('.search-box input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });

    // Progress bar untuk import
    document.getElementById('importForm')?.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file');
        const progressDiv = document.getElementById('importProgress');
        const btnImport = document.getElementById('btnImport');
        
        if (!fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih file CSV terlebih dahulu!'
            });
            return false;
        }
        
        // Validasi ekstensi file
        const fileName = fileInput.files[0].name;
        const extension = fileName.split('.').pop().toLowerCase();
        if (extension !== 'csv') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Format file harus .csv!'
            });
            return false;
        }
        
        progressDiv.classList.remove('d-none');
        btnImport.disabled = true;
        btnImport.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
        
        let progress = 0;
        const interval = setInterval(function() {
            progress += 10;
            const progressBar = document.querySelector('#importProgress .progress-bar');
            if (progressBar) {
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
            }
            if (progress >= 100) clearInterval(interval);
        }, 200);
    });
</script>
@endpush