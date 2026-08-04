@extends('kepala-sekolah.layouts.header')

@section('title', 'Daftar Persetujuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-check-double me-2"></i> Daftar Persetujuan</h4>
                <div>
                    <a href="{{ route('kepala-sekolah.persetujuan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
            </div>

            <!-- Statistik -->
            <div class="row mb-3">
                <div class="col-md-3 col-6">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">{{ $menunggu ?? 0 }}</h5>
                            <p class="card-text">⏳ Menunggu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $disetujui ?? 0 }}</h5>
                            <p class="card-text">✅ Disetujui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ditolak ?? 0 }}</h5>
                            <p class="card-text">❌ Ditolak</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $revisi ?? 0 }}</h5>
                            <p class="card-text">📝 Revisi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('kepala-sekolah.persetujuan.index') }}" class="row g-2">
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="tipe" class="form-select form-select-sm">
                                <option value="">Semua Tipe</option>
                                <option value="anggaran" {{ request('tipe') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                                <option value="izin" {{ request('tipe') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="proyek" {{ request('tipe') == 'proyek' ? 'selected' : '' }}>Proyek</option>
                                <option value="lainnya" {{ request('tipe') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Pengaju</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuan ?? [] as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->judul ?? '-' }}</td>
                                    <td>{{ $item->pengaju->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->tipe ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($item->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">⏳ Menunggu</span>
                                        @elseif($item->status == 'disetujui')
                                            <span class="badge bg-success">✅ Disetujui</span>
                                        @elseif($item->status == 'ditolak')
                                            <span class="badge bg-danger">❌ Ditolak</span>
                                        @elseif($item->status == 'revisi')
                                            <span class="badge bg-info">📝 Revisi</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('kepala-sekolah.persetujuan.show', $item->id) }}" 
                                           class="btn btn-sm btn-info">Detail</a>
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
                    {{ $pengajuan->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection