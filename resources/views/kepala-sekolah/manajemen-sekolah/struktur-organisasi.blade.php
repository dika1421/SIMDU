@extends('kepala-sekolah.layouts.header')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-sitemap me-2"></i>
        Struktur Organisasi Sekolah
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
            <i class="fas fa-plus"></i> Tambah Struktur
        </button>
    </div>
</div>

<!-- Tree View Struktur Organisasi -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Bagan Struktur Organisasi</h5>
    </div>
    <div class="card-body">
        <div class="org-tree">
            @php
                $root = $struktur->whereNull('parent_id');
            @endphp
            
            @forelse($root as $item)
                <div class="org-node text-center mb-4">
                    <div class="d-inline-block p-3 bg-primary text-white rounded-3 shadow" style="min-width: 250px;">
                        <h5 class="mb-1">{{ $item->nama }}</h5>
                        <p class="mb-0"><small>{{ $item->jabatan }}</small></p>
                        @if($item->guru)
                            <small class="text-white-50">{{ $item->guru->user->name }}</small>
                        @endif
                    </div>
                    
                    @if($item->children->count() > 0)
                        <div class="mt-3">
                            <i class="fas fa-chevron-down fa-2x text-muted"></i>
                        </div>
                        
                        <div class="row mt-3">
                            @foreach($item->children as $child)
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-3 shadow-sm">
                                        <h6>{{ $child->nama }}</h6>
                                        <p class="mb-0"><small>{{ $child->jabatan }}</small></p>
                                        @if($child->guru)
                                            <small class="text-muted">{{ $child->guru->user->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-sitemap fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data struktur organisasi</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahStrukturModal">
                        <i class="fas fa-plus"></i> Tambah Struktur Pertama
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Tabel Data Struktur -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Struktur Organisasi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Atasan</th>
                        <th>Penanggung Jawab</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($struktur as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $s->nama }}</strong></td>
                        <td>{{ $s->jabatan }}</td>
                        <td>{{ $s->parent->nama ?? '-' }}</td>
                        <td>{{ $s->guru->user->name ?? '-' }}</td>
                        <td>{{ $s->urutan }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStrukturModal{{ $s->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('kepala-sekolah.manajemen.struktur.destroy', $s->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahStrukturModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kepala-sekolah.manajemen.struktur.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Struktur Organisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Struktur</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atasan</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Tidak Ada (Root)</option>
                            @foreach($struktur as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->jabatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab (Guru)</label>
                        <select name="guru_id" class="form-control">
                            <option value="">Pilih Guru</option>
                            @foreach($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit untuk setiap data -->
@foreach($struktur as $s)
<div class="modal fade" id="editStrukturModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kepala-sekolah.manajemen.struktur.update', $s->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Struktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Struktur</label>
                        <input type="text" name="nama" class="form-control" value="{{ $s->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ $s->jabatan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atasan</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Tidak Ada</option>
                            @foreach($struktur as $p)
                            <option value="{{ $p->id }}" {{ $s->parent_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <select name="guru_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ $s->guru_id == $g->id ? 'selected' : '' }}>
                                {{ $g->user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $s->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="{{ $s->urutan }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
    .org-tree {
        min-height: 300px;
    }
    .org-node {
        position: relative;
    }
    .org-node .bg-primary {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
@endpush
@endsection