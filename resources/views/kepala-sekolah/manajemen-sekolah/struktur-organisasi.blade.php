@extends('kepala-sekolah.layouts.header')

@section('title', 'Struktur Organisasi')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    
    .card-modern .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 15px 20px;
        font-weight: 600;
        border-radius: 12px 12px 0 0;
    }
    
    .card-modern .card-body {
        padding: 20px;
    }
    
    .org-tree {
        padding: 20px 0;
        min-height: 300px;
    }
    
    .org-node {
        position: relative;
        text-align: center;
    }
    
    .org-node .org-box {
        display: inline-block;
        padding: 15px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        min-width: 220px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .org-node .org-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
    }
    
    .org-node .org-box h5 {
        margin-bottom: 2px;
        font-weight: 700;
    }
    
    .org-node .org-box small {
        opacity: 0.85;
    }
    
    .org-child {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .org-child-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px 20px;
        min-width: 180px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .org-child-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-color: #667eea;
    }
    
    .org-child-item h6 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 2px;
    }
    
    .org-child-item small {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .org-connector {
        color: #6c757d;
        font-size: 1.5rem;
        margin: 10px 0;
    }
    
    .form-control-modern {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }
    
    .form-label-modern {
        font-weight: 600;
        font-size: 0.85rem;
        color: #2c3e50;
    }
    
    .form-label-modern .text-danger {
        font-weight: 700;
    }
    
    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 30px;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-modern-secondary {
        border-radius: 10px;
        padding: 10px 30px;
        border: 2px solid #e9ecef;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .btn-modern-secondary:hover {
        background: #f8f9fa;
        border-color: #667eea;
        color: #2c3e50;
    }
    
    .table-modern {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table-modern thead th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
        padding: 12px 15px;
    }
    
    .table-modern tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    .badge-role {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-root {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .badge-child {
        background: #e3f2fd;
        color: #1565c0;
    }

    .modal-content-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .modal-content-modern .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 20px 25px;
    }
    
    .modal-content-modern .modal-header h5 {
        font-weight: 700;
        color: #2c3e50;
    }
    
    .modal-content-modern .modal-body {
        padding: 25px;
    }
    
    .modal-content-modern .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 25px;
    }
</style>

<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 mb-1">
            <i class="fas fa-sitemap me-2 text-primary"></i>
            Struktur Organisasi
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('kepala-sekolah.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Struktur Organisasi</li>
            </ol>
        </nav>
    </div>
    <div class="btn-toolbar">
        <button type="button" class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
            <i class="fas fa-plus me-2"></i>Tambah Struktur
        </button>
    </div>
</div>

<!-- Statistik -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-sitemap"></i>
            </div>
            <div class="stat-number">{{ $struktur->count() }}</div>
            <div class="stat-label">Total Struktur</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-number">{{ $struktur->whereNull('parent_id')->count() }}</div>
            <div class="stat-label">Root / Induk</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $struktur->whereNotNull('parent_id')->count() }}</div>
            <div class="stat-label">Sub Struktur</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="stat-number">{{ $struktur->whereNotNull('guru_id')->count() }}</div>
            <div class="stat-label">Penanggung Jawab</div>
        </div>
    </div>
</div>

<!-- Bagan Struktur -->
<div class="card-modern">
    <div class="card-header">
        <i class="fas fa-sitemap me-2 text-primary"></i>
        Bagan Struktur Organisasi
    </div>
    <div class="card-body">
        <div class="org-tree">
            @php
                $root = $struktur->whereNull('parent_id');
            @endphp
            
            @forelse($root as $item)
                <div class="org-node">
                    <div class="org-box">
                        <h5>{{ $item->nama }}</h5>
                        <p class="mb-0"><small>{{ $item->jabatan }}</small></p>
                        @if($item->guru)
                            <small class="text-white-50">
                                <i class="fas fa-user me-1"></i>{{ $item->guru->user->name ?? '-' }}
                            </small>
                        @endif
                    </div>
                    
                    @if($item->children->count() > 0)
                        <div class="org-connector">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="org-child">
                            @foreach($item->children->sortBy('urutan') as $child)
                                <div class="org-child-item">
                                    <h6>{{ $child->nama }}</h6>
                                    <small>{{ $child->jabatan }}</small>
                                    @if($child->guru)
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $child->guru->user->name ?? '-' }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-sitemap fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-3">Belum ada data struktur organisasi</p>
                    <button type="button" class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
                        <i class="fas fa-plus me-2"></i>Tambah Struktur Pertama
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Daftar Struktur -->
<div class="card-modern">
    <div class="card-header">
        <i class="fas fa-list me-2 text-primary"></i>
        Daftar Struktur Organisasi
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern table-hover datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Atasan</th>
                        <th>Penanggung Jawab</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($struktur->sortBy('urutan') as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $s->nama }}</strong></td>
                        <td>{{ $s->jabatan }}</td>
                        <td>
                            @if($s->parent)
                                <span class="badge-role badge-child">{{ $s->parent->nama }}</span>
                            @else
                                <span class="badge-role badge-root"><i class="fas fa-tree me-1"></i>Root</span>
                            @endif
                        </td>
                        <td>{{ $s->guru->user->name ?? '-' }}</td>
                        <td>{{ $s->urutan }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStrukturModal{{ $s->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('kepala-sekolah.manajemen.struktur.destroy', $s->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-database fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada data struktur</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL TAMBAH STRUKTUR (DIPERBAIKI) -->
<!-- ============================================ -->
<div class="modal fade" id="tambahStrukturModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <form action="{{ route('kepala-sekolah.manajemen.struktur.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Tambah Struktur Organisasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Struktur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-building me-1 text-primary"></i>
                                Nama Struktur <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control form-control-modern" 
                                   placeholder="Contoh: Kepala Sekolah, Wakil Kepala Sekolah" required>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-briefcase me-1 text-primary"></i>
                                Jabatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jabatan" class="form-control form-control-modern" 
                                   placeholder="Contoh: Kepala Sekolah, Wakil Kurikulum" required>
                        </div>
                        
                        <!-- Atasan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-arrow-up me-1 text-primary"></i>
                                Atasan
                            </label>
                            <select name="parent_id" class="form-control form-control-modern">
                                <option value="">Pilih Atasan (Kosongkan jika Root)</option>
                                @foreach($struktur as $s)
                                    <option value="{{ $s->id }}">
                                        {{ $s->nama }} ({{ $s->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Biarkan kosong jika ini adalah struktur induk (Root)
                            </small>
                        </div>
                        
                        <!-- Penanggung Jawab -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-user-tie me-1 text-primary"></i>
                                Penanggung Jawab
                            </label>
                            <select name="guru_id" class="form-control form-control-modern">
                                <option value="">Pilih Guru</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->user->name ?? $g->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-align-left me-1 text-primary"></i>
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" class="form-control form-control-modern" 
                                      rows="3" placeholder="Jelaskan tugas dan tanggung jawab ..."></textarea>
                        </div>
                        
                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-sort-numeric-down me-1 text-primary"></i>
                                Urutan
                            </label>
                            <input type="number" name="urutan" class="form-control form-control-modern" 
                                   value="0" min="0">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Semakin kecil angka, semakin atas posisinya
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL EDIT STRUKTUR (DIPERBAIKI) -->
<!-- ============================================ -->
@foreach($struktur as $s)
<div class="modal fade" id="editStrukturModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <form action="{{ route('kepala-sekolah.manajemen.struktur.update', $s->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Edit Struktur
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Struktur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-building me-1 text-primary"></i>
                                Nama Struktur <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control form-control-modern" 
                                   value="{{ $s->nama }}" required>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-briefcase me-1 text-primary"></i>
                                Jabatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jabatan" class="form-control form-control-modern" 
                                   value="{{ $s->jabatan }}" required>
                        </div>
                        
                        <!-- Atasan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-arrow-up me-1 text-primary"></i>
                                Atasan
                            </label>
                            <select name="parent_id" class="form-control form-control-modern">
                                <option value="">Pilih Atasan (Kosongkan jika Root)</option>
                                @foreach($struktur as $p)
                                    @if($p->id != $s->id)
                                        <option value="{{ $p->id }}" {{ $s->parent_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} ({{ $p->jabatan }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Biarkan kosong jika ini adalah struktur induk (Root)
                            </small>
                        </div>
                        
                        <!-- Penanggung Jawab -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-user-tie me-1 text-primary"></i>
                                Penanggung Jawab
                            </label>
                            <select name="guru_id" class="form-control form-control-modern">
                                <option value="">Pilih Guru</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}" {{ $s->guru_id == $g->id ? 'selected' : '' }}>
                                        {{ $g->user->name ?? $g->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-align-left me-1 text-primary"></i>
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" class="form-control form-control-modern" 
                                      rows="3">{{ $s->deskripsi }}</textarea>
                        </div>
                        
                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-sort-numeric-down me-1 text-primary"></i>
                                Urutan
                            </label>
                            <input type="number" name="urutan" class="form-control form-control-modern" 
                                   value="{{ $s->urutan }}" min="0">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Semakin kecil angka, semakin atas posisinya
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tambahStrukturModal, #editStrukturModal').on('shown.bs.modal', function() {
            $(this).find('select').trigger('change');
        });
    });
</script>
@endpush

@push('styles')
<style>
    .modal-content-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .modal-content-modern .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 20px 25px;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }
    
    .modal-content-modern .modal-header h5 {
        font-weight: 700;
        color: #2c3e50;
    }
    
    .modal-content-modern .modal-body {
        padding: 25px;
    }
    
    .modal-content-modern .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 25px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }
</style>
@endpush
@endsection