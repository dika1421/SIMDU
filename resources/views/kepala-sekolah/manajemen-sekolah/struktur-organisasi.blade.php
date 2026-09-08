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
        padding: 18px 20px;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 8px;
    }
    
    .stat-number {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.8rem;
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
        padding: 14px 20px;
        font-weight: 600;
        border-radius: 12px 12px 0 0;
        font-size: 0.95rem;
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
    
    .org-box {
        display: inline-block;
        padding: 14px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        min-width: 200px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .org-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
    }
    
    .org-box h5 {
        margin-bottom: 2px;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .org-box small {
        opacity: 0.85;
        font-size: 0.75rem;
    }
    
    .org-connector {
        color: #6c757d;
        font-size: 1.2rem;
        margin: 8px 0;
    }
    
    .org-child {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    
    .org-child-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 12px 18px;
        min-width: 160px;
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
        font-size: 0.85rem;
    }
    
    .org-child-item small {
        color: #6c757d;
        font-size: 0.7rem;
    }
    
    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .table-modern {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table-modern thead th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
        padding: 10px 14px;
    }
    
    .table-modern tbody td {
        padding: 10px 14px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    .badge-root {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-child {
        background: #e3f2fd;
        color: #1565c0;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
</style>

<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h4 mb-0">
            <i class="fas fa-sitemap me-2 text-primary"></i>
            Struktur Organisasi
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('kepala-sekolah.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Struktur Organisasi</li>
            </ol>
        </nav>
    </div>
    <div>
        <button type="button" class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
            <i class="fas fa-plus me-2"></i>Tambah Struktur
        </button>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
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
                <i class="fas fa-tree"></i>
            </div>
            <div class="stat-number">{{ $struktur->whereNull('parent_id')->count() }}</div>
            <div class="stat-label">Induk / Root</div>
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
                <i class="fas fa-user-tie"></i>
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
                        <small>{{ $item->jabatan }}</small>
                        @if($item->guru)
                            <div><small><i class="fas fa-user me-1"></i>{{ $item->guru->user->name ?? '-' }}</small></div>
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
                                        <div><small class="text-muted"><i class="fas fa-user me-1"></i>{{ $child->guru->user->name ?? '-' }}</small></div>
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
                                <span class="badge-child">{{ $s->parent->nama }}</span>
                            @else
                                <span class="badge-root"><i class="fas fa-tree me-1"></i>Root</span>
                            @endif
                        </td>
                        <td>{{ $s->guru->user->name ?? '-' }}</td>
                        <td>{{ $s->urutan }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editStrukturModal{{ $s->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('kepala-sekolah.manajemen.struktur.destroy', $s->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
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
<!-- MODAL TAMBAH STRUKTUR -->
<!-- ============================================ -->
<div class="modal fade" id="tambahStrukturModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
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
                            <label class="form-label fw-bold">
                                Nama Struktur <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Kepala Sekolah" required>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Jabatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Kepala Sekolah" required>
                        </div>
                        
                        <!-- Atasan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Atasan</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Tidak Ada (Root)</option>
                                @foreach($struktur as $s)
                                    <option value="{{ $s->id }}">
                                        {{ $s->nama }} ({{ $s->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Kosongkan jika ini adalah struktur induk</small>
                        </div>
                        
                        <!-- Penanggung Jawab -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Penanggung Jawab</label>
                            <select name="guru_id" class="form-control">
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
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan tugas dan tanggung jawab..."></textarea>
                        </div>
                        
                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" min="0">
                            <small class="text-muted">Semakin kecil angka, semakin atas posisinya</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL EDIT STRUKTUR -->
<!-- ============================================ -->
@foreach($struktur as $s)
<div class="modal fade" id="editStrukturModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
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
                            <label class="form-label fw-bold">
                                Nama Struktur <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control" value="{{ $s->nama }}" required>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Jabatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $s->jabatan }}" required>
                        </div>
                        
                        <!-- Atasan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Atasan</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Tidak Ada (Root)</option>
                                @foreach($struktur as $p)
                                    @if($p->id != $s->id)
                                        <option value="{{ $p->id }}" {{ $s->parent_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} ({{ $p->jabatan }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">Kosongkan jika ini adalah struktur induk</small>
                        </div>
                        
                        <!-- Penanggung Jawab -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Penanggung Jawab</label>
                            <select name="guru_id" class="form-control">
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
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $s->deskripsi }}</textarea>
                        </div>
                        
                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="{{ $s->urutan }}" min="0">
                            <small class="text-muted">Semakin kecil angka, semakin atas posisinya</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
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
@endsection