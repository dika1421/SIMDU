@extends('kepala-sekolah.layouts.header')

@section('title', 'Manajemen Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-teacher me-2"></i>
        Manajemen Guru & Staf
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Guru
        </a>
        <a href="{{ route('kepala-sekolah.manajemen-guru.absensi') }}" class="btn btn-sm btn-info ms-2">
            <i class="fas fa-calendar-check"></i> Absensi Guru
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

<!-- Statistik Guru -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Guru</h6>
                <h3 class="mb-0">{{ $statistik['total'] ?? $guru->total() ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">PNS</h6>
                <h3 class="mb-0">{{ $statistik['pns'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Honorer</h6>
                <h3 class="mb-0">{{ $statistik['honorer'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Kontrak</h6>
                <h3 class="mb-0">{{ $statistik['kontrak'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Guru -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Guru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th width="60">Foto</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>No. Telepon</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $index => $g)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($guru->currentPage() - 1) * $guru->perPage() }}</td>
                        <td class="text-center">
                            @if($g->user && $g->user->foto)
                                <img src="{{ asset('storage/'.$g->user->foto) }}" class="rounded-circle" width="40" height="40" alt="foto">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:40px;height:40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge bg-primary">{{ $g->nip ?? '-' }}</span></td>
                        <td><strong>{{ $g->nama_lengkap ?? $g->user->name ?? '-' }}</strong></td>
                        <td>{{ $g->jabatan->nama_jabatan ?? $g->status_kepegawaian ?? '-' }}</td>
                        <td class="text-center">
                            @php
                                $statusColor = 'success';
                                if(($g->status_kepegawaian ?? '') == 'honorer') $statusColor = 'warning';
                                if(($g->status_kepegawaian ?? '') == 'kontrak') $statusColor = 'info';
                                if(($g->status ?? 'aktif') != 'aktif') $statusColor = 'danger';
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                {{ strtoupper($g->status_kepegawaian ?? 'AKTIF') }}
                            </span>
                        </td>
                        <td class="text-center">{{ $g->user->no_telepon ?? $g->no_telepon ?? '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('kepala-sekolah.manajemen-guru.show', $g->id) }}" 
                                   class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kepala-sekolah.manajemen-guru.edit', $g->id) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger" 
                                        title="Hapus" 
                                        onclick="confirmDelete({{ $g->id }}, '{{ addslashes($g->nama_lengkap ?? $g->user->name ?? 'Guru') }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x mb-2 d-block"></i>
                            Belum ada data guru. Silakan tambah guru terlebih dahulu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
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
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus guru <strong id="guruName"></strong>?</p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Data yang dihapus tidak dapat dikembalikan! Semua data terkait (user, jadwal, dll) juga akan terhapus.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, name) {
        // Set nama guru di modal
        document.getElementById('guruName').innerText = name;
        
        // Set action form dengan ID yang benar
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.action = "{{ route('kepala-sekolah.manajemen-guru.index') }}/" + id;
        
        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>
@endsection