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

    .form-control-modern {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 8px 14px;
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
        margin-bottom: 4px;
    }
    
    .form-label-modern .text-danger {
        color: #dc3545;
    }

    /* Style untuk Live Search Result */
    .guru-search-result {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .guru-search-result .list-group-item {
        cursor: pointer;
        border-left: none;
        border-right: none;
        border-radius: 0;
        padding: 10px 15px;
    }
    
    .guru-search-result .list-group-item:first-child {
        border-top: none;
    }
    
    .guru-search-result .list-group-item:hover {
        background-color: #f0f2ff;
    }
    
    .guru-search-result .list-group-item:last-child {
        border-bottom: none;
    }

    .guru-info-card {
        background: #f0f2ff;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #667eea;
    }
    
    .guru-info-card .info-label {
        font-size: 0.7rem;
        color: #888;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .guru-info-card .info-value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }
</style>

<!-- Header dengan Breadcrumb -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h4 mb-1">
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
                        <h5>{{ $item->nama_jabatan ?? $item->nama }}</h5>
                        <small>{{ $item->nama_pejabat ?? $item->jabatan }}</small>
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
                                    <h6>{{ $child->nama_jabatan ?? $child->nama }}</h6>
                                    <small>{{ $child->nama_pejabat ?? $child->jabatan }}</small>
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
                        <th>Nama Jabatan</th>
                        <th>Nama Pejabat</th>
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
                        <td><strong>{{ $s->nama_jabatan ?? $s->nama }}</strong></td>
                        <td>{{ $s->nama_pejabat ?? $s->jabatan }}</td>
                        <td>
                            @if($s->parent)
                                <span class="badge-child">{{ $s->parent->nama_jabatan ?? $s->parent->nama }}</span>
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
<!-- MODAL TAMBAH STRUKTUR (LIVE SEARCH + MAPEL) -->
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
                        <!-- Cari Guru dengan Live Search -->
                        <div class="col-md-12 mb-3 position-relative">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search me-1"></i>Cari Guru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-user-graduate text-muted"></i></span>
                                <input type="text" id="searchGuru" class="form-control" 
                                       placeholder="Ketik nama atau NUPTK guru..." autocomplete="off">
                                <button class="btn btn-secondary" type="button" id="btnClearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <!-- Hasil pencarian muncul melayang -->
                            <div id="searchResult" class="guru-search-result" style="display: none; position: absolute; z-index: 1000; width: 95%;">
                                <div class="list-group" id="guruList"></div>
                            </div>
                            <input type="hidden" name="guru_id" id="selectedGuruId">
                            <input type="hidden" name="nuptk" id="selectedNuptk">
                        </div>

                        <!-- Auto Detect Result -->
                        <div class="col-md-12 mb-3" id="guruInfo" style="display: none;">
                            <div class="guru-info-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-user me-1"></i>Nama Guru</div>
                                        <div class="info-value" id="guruNama">-</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-id-card me-1"></i>NUPTK</div>
                                        <div class="info-value" id="guruNuptk">-</div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="info-label"><i class="fas fa-briefcase me-1"></i>Jabatan</div>
                                        <div class="info-value" id="guruJabatan">-</div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="info-label"><i class="fas fa-book me-1"></i>Mata Pelajaran</div>
                                        <div class="info-value" id="guruMapel">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Nama Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Nama Jabatan <span class="text-danger">*</span>
                            </label>
                            <select name="nama_jabatan" id="namaJabatan" class="form-control" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Kepala Sekolah">Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah Kurikulum">Wakil Kepala Sekolah Kurikulum</option>
                                <option value="Wakil Kepala Sekolah Kesiswaan">Wakil Kepala Sekolah Kesiswaan</option>
                                <option value="Kaprog Pemasaran">Kaprog Pemasaran</option>
                                <option value="Kaprog Tata Boga">Kaprog Tata Boga</option>
                                <option value="Pembina OSIS">Pembina OSIS</option>
                                <option value="BP/BKK">BP/BKK</option>
                                <option value="Operator Sekolah">Operator Sekolah</option>
                                <option value="Tata Usaha">Tata Usaha</option>
                                <option value="Kepala Laboratorium">Kepala Laboratorium</option>
                                <option value="Guru Tahsin/Tadarus">Guru Tahsin/Tadarus</option>
                                <option value="Guru Mapel">Guru Mapel</option>
                                <option value="Wali Kelas">Wali Kelas</option>
                                <option value="Staf TU">Staf TU</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Nama Pejabat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-tie me-1"></i>Nama Pejabat <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_pejabat" id="namaPejabat" class="form-control" 
                                   placeholder="Akan terisi otomatis" readonly style="background-color: #f0f0f0;">
                        </div>

                        <!-- FIELD BARU: Mata Pelajaran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-book-open me-1"></i>Mata Pelajaran
                            </label>
                            <input type="text" name="mata_pelajaran" id="mataPelajaran" class="form-control" 
                                   placeholder="Akan terisi otomatis" readonly style="background-color: #f0f0f0;">
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-layer-group me-1"></i>Kategori
                            </label>
                            <select name="kategori" id="kategori" class="form-control">
                                <option value="pimpinan">Pimpinan</option>
                                <option value="staf">Staf</option>
                                <option value="guru" selected>Guru</option>
                            </select>
                        </div>

                        <!-- Atasan (Parent) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-arrow-up me-1"></i>Atasan
                            </label>
                            <select name="parent_id" class="form-control">
                                <option value="">Tidak Ada (Root - Pimpinan Tertinggi)</option>
                                <optgroup label="Pimpinan">
                                    @foreach($struktur->whereNull('parent_id') as $st)
                                        <option value="{{ $st->id }}">📌 {{ $st->nama_jabatan ?? $st->nama }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Staf">
                                    @foreach($struktur->whereNotNull('parent_id') as $st)
                                        <option value="{{ $st->id }}">📋 {{ $st->nama_jabatan ?? $st->nama }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                <i class="fas fa-info-circle me-1"></i>Pilih atasan langsung dari struktur ini
                            </small>
                        </div>

                        <!-- Deskripsi Tugas -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-alt me-1"></i>Deskripsi Tugas
                            </label>
                            <textarea name="deskripsi_tugas" class="form-control" rows="3" 
                                      placeholder="Jelaskan tugas dan tanggung jawab..."></textarea>
                        </div>

                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-sort-numeric-down me-1"></i>Urutan
                            </label>
                            <input type="number" name="urutan" class="form-control" value="1" min="1">
                            <small class="text-muted" style="font-size: 0.7rem;">Semakin kecil angka, semakin atas posisinya</small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-toggle-on me-1"></i>Status
                            </label>
                            <select name="status" class="form-control">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
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
                        <!-- Nama Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Jabatan <span class="text-danger">*</span></label>
                            <select name="nama_jabatan" class="form-control" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Kepala Sekolah" {{ $s->nama_jabatan == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah" {{ $s->nama_jabatan == 'Wakil Kepala Sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah Kurikulum" {{ $s->nama_jabatan == 'Wakil Kepala Sekolah Kurikulum' ? 'selected' : '' }}>Wakil Kepala Sekolah Kurikulum</option>
                                <option value="Wakil Kepala Sekolah Kesiswaan" {{ $s->nama_jabatan == 'Wakil Kepala Sekolah Kesiswaan' ? 'selected' : '' }}>Wakil Kepala Sekolah Kesiswaan</option>
                                <option value="Kaprog Pemasaran" {{ $s->nama_jabatan == 'Kaprog Pemasaran' ? 'selected' : '' }}>Kaprog Pemasaran</option>
                                <option value="Kaprog Tata Boga" {{ $s->nama_jabatan == 'Kaprog Tata Boga' ? 'selected' : '' }}>Kaprog Tata Boga</option>
                                <option value="Pembina OSIS" {{ $s->nama_jabatan == 'Pembina OSIS' ? 'selected' : '' }}>Pembina OSIS</option>
                                <option value="BP/BKK" {{ $s->nama_jabatan == 'BP/BKK' ? 'selected' : '' }}>BP/BKK</option>
                                <option value="Operator Sekolah" {{ $s->nama_jabatan == 'Operator Sekolah' ? 'selected' : '' }}>Operator Sekolah</option>
                                <option value="Tata Usaha" {{ $s->nama_jabatan == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                <option value="Kepala Laboratorium" {{ $s->nama_jabatan == 'Kepala Laboratorium' ? 'selected' : '' }}>Kepala Laboratorium</option>
                                <option value="Guru Tahsin/Tadarus" {{ $s->nama_jabatan == 'Guru Tahsin/Tadarus' ? 'selected' : '' }}>Guru Tahsin/Tadarus</option>
                                <option value="Guru Mapel" {{ $s->nama_jabatan == 'Guru Mapel' ? 'selected' : '' }}>Guru Mapel</option>
                                <option value="Wali Kelas" {{ $s->nama_jabatan == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="Staf TU" {{ $s->nama_jabatan == 'Staf TU' ? 'selected' : '' }}>Staf TU</option>
                                <option value="Lainnya" {{ $s->nama_jabatan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Nama Pejabat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Pejabat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pejabat" class="form-control" 
                                   value="{{ $s->nama_pejabat ?? $s->jabatan }}" required>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-control">
                                <option value="pimpinan" {{ $s->kategori == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="staf" {{ $s->kategori == 'staf' ? 'selected' : '' }}>Staf</option>
                                <option value="guru" {{ $s->kategori == 'guru' ? 'selected' : '' }}>Guru</option>
                            </select>
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

                        <!-- Atasan (Parent) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Atasan</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Tidak Ada (Root - Pimpinan Tertinggi)</option>
                                <optgroup label="Pimpinan">
                                    @foreach($struktur->whereNull('parent_id') as $st)
                                        <option value="{{ $st->id }}" {{ $s->parent_id == $st->id ? 'selected' : '' }}>
                                            📌 {{ $st->nama_jabatan ?? $st->nama }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Staf">
                                    @foreach($struktur->whereNotNull('parent_id') as $st)
                                        <option value="{{ $st->id }}" {{ $s->parent_id == $st->id ? 'selected' : '' }}>
                                            📋 {{ $st->nama_jabatan ?? $st->nama }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                <i class="fas fa-info-circle me-1"></i>Pilih atasan langsung dari struktur ini
                            </small>
                        </div>

                        <!-- Deskripsi Tugas -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Deskripsi Tugas</label>
                            <textarea name="deskripsi_tugas" class="form-control" rows="3">{{ $s->deskripsi_tugas ?? $s->deskripsi }}</textarea>
                        </div>

                        <!-- Urutan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" 
                                   value="{{ $s->urutan }}" min="1">
                            <small class="text-muted" style="font-size: 0.7rem;">Semakin kecil angka, semakin atas posisinya</small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="aktif" {{ $s->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $s->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
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
    // ===== LIVE SEARCH GURU (Debounce) =====
    var searchTimeout;
    
    $('#searchGuru').on('keyup', function() {
        var keyword = $(this).val().trim();
        
        // Clear timeout sebelumnya
        clearTimeout(searchTimeout);
        
        // Jika kosong, sembunyikan hasil
        if (keyword.length < 2) {
            $('#searchResult').hide();
            return;
        }
        
        // Debounce: Tunggu 300ms setelah user berhenti mengetik
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("administrasi.api.guru.search") }}',
                type: 'GET',
                data: { q: keyword },
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        var html = '';
                        $.each(response.data, function(index, guru) {
                            // Simpan data mapel di atribut data-mapel
                            html += '<a href="#" class="list-group-item list-group-item-action" ' +
                                    'data-id="' + guru.id + '" ' +
                                    'data-nuptk="' + guru.nuptk + '" ' +
                                    'data-nama="' + guru.nama + '" ' +
                                    'data-jabatan="' + (guru.jabatan || 'Guru') + '" ' +
                                    'data-mapel="' + (guru.mata_pelajaran || '-') + '">';
                            html += '<div class="d-flex justify-content-between align-items-center">';
                            html += '<div><strong>' + guru.nama + '</strong></div>';
                            html += '<span class="badge bg-primary">' + (guru.jabatan || 'Guru') + '</span>';
                            html += '</div>';
                            html += '<small class="text-muted">NUPTK: ' + guru.nuptk + ' | Mapel: ' + (guru.mata_pelajaran || '-') + '</small>';
                            html += '</a>';
                        });
                        $('#guruList').html(html);
                        $('#searchResult').show();
                    } else {
                        $('#guruList').html('<div class="list-group-item text-muted text-center py-3"><i class="fas fa-user-slash me-2"></i>Guru tidak ditemukan</div>');
                        $('#searchResult').show();
                    }
                },
                error: function() {
                    $('#guruList').html('<div class="list-group-item text-danger text-center py-3"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan</div>');
                    $('#searchResult').show();
                }
            });
        }, 300); // Delay 300ms
    });

    // Sembunyikan hasil jika klik di luar area search
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#searchGuru, #searchResult').length) {
            $('#searchResult').hide();
        }
    });

    // ===== PILIH GURU =====
    $(document).on('click', '#guruList .list-group-item', function(e) {
        e.preventDefault();
        
        var id = $(this).data('id');
        var nuptk = $(this).data('nuptk');
        var nama = $(this).data('nama');
        var jabatan = $(this).data('jabatan') || '-';
        var mapel = $(this).data('mapel') || '-';
        
        // Set value ke hidden input dan tampilan info card
        $('#selectedGuruId').val(id);
        $('#selectedNuptk').val(nuptk);
        $('#guruNama').text(nama);
        $('#guruNuptk').text(nuptk);
        $('#guruJabatan').text(jabatan);
        $('#guruMapel').text(mapel);
        
        // Auto fill nama pejabat
        $('#namaPejabat').val(nama);
        
        // Auto fill mata pelajaran (FIELD BARU)
        $('#mataPelajaran').val(mapel);
        
        // Jika jabatan ada, auto pilih di dropdown
        if (jabatan && jabatan !== '-') {
            $('#namaJabatan').val(jabatan);
        }
        
        // Tampilkan info card & sembunyikan hasil search
        $('#guruInfo').fadeIn();
        $('#searchResult').hide();
        $('#searchGuru').val(nama);
    });

    // ===== CLEAR SEARCH =====
    $('#btnClearSearch').on('click', function() {
        $('#searchGuru').val('');
        $('#searchResult').hide();
        $('#guruInfo').hide();
        $('#selectedGuruId').val('');
        $('#selectedNuptk').val('');
        $('#namaJabatan').val('');
        $('#namaPejabat').val('');
        $('#mataPelajaran').val(''); // Clear field mapel
        $('#guruNama').text('-');
        $('#guruNuptk').text('-');
        $('#guruJabatan').text('-');
        $('#guruMapel').text('-');
    });

    // ===== RESET MODAL =====
    $('#tambahStrukturModal').on('hidden.bs.modal', function() {
        $('#searchGuru').val('');
        $('#searchResult').hide();
        $('#guruInfo').hide();
        $('#selectedGuruId').val('');
        $('#selectedNuptk').val('');
        $('#namaJabatan').val('');
        $('#namaPejabat').val('');
        $('#mataPelajaran').val(''); // Reset field mapel
        $('#guruNama').text('-');
        $('#guruNuptk').text('-');
        $('#guruJabatan').text('-');
        $('#guruMapel').text('-');
        $('form').find('.is-invalid').removeClass('is-invalid');
    });

    // ===== VALIDASI =====
    $('form').on('submit', function(e) {
        var guruId = $('#selectedGuruId').val();
        var namaJabatan = $('#namaJabatan').val();
        var namaPejabat = $('#namaPejabat').val();
        var isValid = true;
        
        if (!guruId) {
            $('#searchGuru').addClass('is-invalid');
            isValid = false;
        } else {
            $('#searchGuru').removeClass('is-invalid');
        }
        
        if (!namaJabatan) {
            $('#namaJabatan').addClass('is-invalid');
            isValid = false;
        } else {
            $('#namaJabatan').removeClass('is-invalid');
        }
        
        if (!namaPejabat) {
            $('#namaPejabat').addClass('is-invalid');
            isValid = false;
        } else {
            $('#namaPejabat').removeClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Silakan lengkapi data yang wajib diisi');
            return false;
        }
    });
});
</script>
@endpush
@endsection