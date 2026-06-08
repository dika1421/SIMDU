@extends('administrasi.layouts.header')

@section('title', 'Arsip Dokumen')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-archive me-2"></i>
        Arsip Dokumen Sekolah
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <a href="{{ route('administrasi.arsip.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-upload"></i> Upload Dokumen
            </a>
            <a href="{{ route('administrasi.arsip.trash') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-trash-restore"></i> Tempat Sampah
            </a>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.arsip.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $key => $value)
                    <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @if($tahunList->isNotEmpty())
                        @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                        @endforeach
                    @else
                        @foreach(range(now()->year, now()->year-10) as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Kode arsip, judul atau deskripsi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('administrasi.arsip.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-sync"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Arsip -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Dokumen</h5>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">Tampilkan:</label>
            <select name="per_page" class="form-select form-select-sm w-auto" onchange="window.location.href='{{ route('administrasi.arsip.index') }}?per_page='+this.value+'&kategori={{ request('kategori') }}&tahun={{ request('tahun') }}&search={{ request('search') }}'">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Arsip</th>
                        <th width="25%">Judul Dokumen</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">Tahun</th>
                        <th width="10%">Uploader</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsip as $index => $a)
                    <tr>
                        <td class="text-center">{{ $arsip->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $a->kode_arsip ?? '-' }}</span>
                        </td>
                        <td>
                            <strong>{{ $a->judul ?? '-' }}</strong>
                            @if($a->deskripsi)
                                <br>
                                <small class="text-muted">{{ Str::limit($a->deskripsi, 50) }}</small>
                            @endif
                            @if($a->nama_file)
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-file"></i> {{ $a->nama_file }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $kategoriList[$a->kategori] ?? $a->kategori }}
                            </span>
                        </td>
                        <td>{{ $a->tanggal_dokumen ? \Carbon\Carbon::parse($a->tanggal_dokumen)->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-dark">{{ $a->tahun ?? '-' }}</span>
                        </td>
                        <td>{{ $a->uploader ? $a->uploader->name : '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('administrasi.arsip.show', $a->id) }}" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('administrasi.arsip.download', $a->id) }}" class="btn btn-success" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('administrasi.arsip.edit', $a->id) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $a->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="deleteModal{{ $a->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus dokumen:</p>
                                            <p class="fw-bold">{{ $a->judul }}</p>
                                            <p class="text-muted small">Dokumen akan dipindahkan ke tempat sampah dan dapat direstore kembali.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('administrasi.arsip.destroy', $a->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Belum ada data arsip</h5>
                            <p class="text-muted">Silakan upload dokumen pertama Anda</p>
                            <a href="{{ route('administrasi.arsip.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-upload"></i> Upload Dokumen
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Informasi dan Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan {{ $arsip->firstItem() ?? 0 }} sampai {{ $arsip->lastItem() ?? 0 }} dari {{ $arsip->total() }} entri
            </div>
            <div>
                {{ $arsip->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Statistik Ringkas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Dokumen</h6>
                        <h3 class="mb-0">{{ $arsip->total() }}</h3>
                    </div>
                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Kategori</h6>
                        <h3 class="mb-0">{{ count($kategoriList) }}</h3>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Tahun Terbaru</h6>
                        <h3 class="mb-0">{{ $tahunList->first() ?? date('Y') }}</h3>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Bulan Ini</h6>
                        <h3 class="mb-0">{{ $arsip->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-weight: 500;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
</style>
@endpush