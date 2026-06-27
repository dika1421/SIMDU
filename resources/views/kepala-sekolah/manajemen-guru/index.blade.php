{{-- resources/views/kepala-sekolah/manajemen-guru/index.blade.php --}}
@extends('kepala-sekolah.layouts.header')

@section('title', 'Manajemen Guru')

@section('content')
<style>
    .btn-action {
        transition: all 0.3s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .table-hover tbody tr:hover {
        background-color: #f0f8ff;
        cursor: pointer;
    }
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
        Manajemen Guru & Staf
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.create') }}" class="btn btn-sm btn-primary btn-action">
            <i class="fas fa-plus"></i> Tambah Guru
        </a>
        <a href="{{ route('kepala-sekolah.manajemen-guru.absensi') }}" class="btn btn-sm btn-info btn-action ms-2">
            <i class="fas fa-chart-bar"></i> Rekap Absensi
        </a>
    </div>
</div>

<!-- Alert Messages -->
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

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-1"></i> Cari
                </label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama, NIP, atau NUPTK..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-1"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100 btn-action">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-secondary w-100 btn-action">
                    <i class="fas fa-sync"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Total Guru</h6>
                        <h3 class="mb-0">{{ $totalGuru ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Guru Aktif</h6>
                        <h3 class="mb-0">{{ $guruAktif ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-dark-50">PNS</h6>
                        <h3 class="mb-0">{{ $guruPNS ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-user-tie fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Honorer/Kontrak</h6>
                        <h3 class="mb-0">{{ ($guruHonorer ?? 0) + ($guruKontrak ?? 0) }}</h3>
                    </div>
                    <i class="fas fa-handshake fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Guru -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-list me-2 text-primary"></i>
            Daftar Guru
        </h5>
        <div>
            <span class="badge bg-secondary me-2">{{ isset($guru) ? $guru->total() : 0 }} Data</span>
            <span class="badge bg-success">
                <i class="fas fa-user-check me-1"></i> {{ $guruAktif ?? 0 }} Aktif
            </span>
        </div>
    </div>
    <div class="card-body">
        @if(isset($guru) && $guru->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="guruTable">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>NUPTK</th>
                        <th>Jenis Kelamin</th>
                        <th>Status Kepegawaian</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru as $index => $g)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($guru->currentPage() - 1) * $guru->perPage() }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $g->nip ?? '-' }}</span>
                        </td>
                        <td>
                            <strong>{{ $g->nama_lengkap ?? $g->user->name ?? '-' }}</strong>
                            @if($g->jabatan)
                                <br>
                                <small class="text-muted"><i class="fas fa-briefcase me-1"></i>{{ $g->jabatan->nama ?? '-' }}</small>
                            @endif
                            @if($g->mata_pelajaran_utama)
                                <br>
                                <small class="text-info"><i class="fas fa-book me-1"></i>{{ $g->mata_pelajaran_utama }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $g->nuptk ?? '-' }}</td>
                        <td class="text-center">
                            @if($g->jenis_kelamin == 'L')
                                <span class="badge bg-info">Laki-laki</span>
                            @elseif($g->jenis_kelamin == 'P')
                                <span class="badge bg-danger">Perempuan</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $statusColor = 'success';
                                $statusText = strtoupper($g->status_kepegawaian ?? 'AKTIF');
                                if(strtolower($g->status_kepegawaian ?? '') == 'honorer') {
                                    $statusColor = 'warning';
                                    $statusText = 'HONORER';
                                } elseif(strtolower($g->status_kepegawaian ?? '') == 'kontrak') {
                                    $statusColor = 'info';
                                    $statusText = 'KONTRAK';
                                } elseif(($g->status ?? 'aktif') != 'aktif') {
                                    $statusColor = 'danger';
                                    $statusText = 'NONAKTIF';
                                }
                            @endphp
                            <span class="badge badge-status bg-{{ $statusColor }} text-white">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('kepala-sekolah.manajemen-guru.show', $g->id) }}" 
                                   class="btn btn-info btn-action" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kepala-sekolah.manajemen-guru.edit', $g->id) }}" 
                                   class="btn btn-warning btn-action" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger btn-action" 
                                        title="Hapus" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $g->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-secondary btn-action" 
                                        title="Reset Password" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#resetPasswordModal{{ $g->id }}">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="deleteModal{{ $g->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus guru <strong>{{ $g->nama_lengkap }}</strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <small>Data yang dihapus tidak dapat dikembalikan!</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('kepala-sekolah.manajemen-guru.destroy', $g->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Ya, Hapus!
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Reset Password -->
                            <div class="modal fade" id="resetPasswordModal{{ $g->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">
                                                <i class="fas fa-key me-2"></i> Reset Password
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Reset password untuk guru <strong>{{ $g->nama_lengkap }}</strong>?</p>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <small>Password akan direset menjadi: <strong>password123</strong></small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('kepala-sekolah.manajemen-guru.reset-password', $g->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-key"></i> Reset Password
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $guru->firstItem() ?? 0 }} - {{ $guru->lastItem() ?? 0 }} 
                    dari {{ $guru->total() ?? 0 }} data
                </small>
            </div>
            <div>
                {{ $guru->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users-slash fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Belum ada data guru</h5>
            <p class="text-muted">Silakan tambah guru terlebih dahulu.</p>
            <a href="{{ route('kepala-sekolah.manajemen-guru.create') }}" class="btn btn-primary mt-2 btn-action">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<script>
    $(document).ready(function() {
        $('#guruTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    });
</script>
@endpush