@extends('layouts.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-clipboard-list me-2"></i>
            Persetujuan & Pengawasan
        </h1>
    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Menunggu</h6>
                    <h3 class="mb-0">{{ isset($pengajuan) ? $pengajuan->where('status', 'menunggu')->count() : 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Disetujui</h6>
                    <h3 class="mb-0">{{ isset($pengajuan) ? $pengajuan->where('status', 'disetujui')->count() : 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Ditolak</h6>
                    <h3 class="mb-0">{{ isset($pengajuan) ? $pengajuan->where('status', 'ditolak')->count() : 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Total Anggaran</h6>
                    <h4 class="mb-0">Rp {{ isset($pengajuan) ? number_format($pengajuan->where('status', 'disetujui')->sum('jumlah_anggaran'), 0, ',', '.') : 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="">Semua Tipe</option>
                        <option value="kegiatan" {{ request('tipe') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="anggaran" {{ request('tipe') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                        <option value="izin" {{ request('tipe') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="pengadaan" {{ request('tipe') == 'pengadaan' ? 'selected' : '' }}>Pengadaan</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Pengajuan -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pengajuan</h5>
        </div>
        <div class="card-body">
            @if(isset($pengajuan) && $pengajuan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered datatable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>No. Pengajuan</th>
                                <th>Judul</th>
                                <th>Pengaju</th>
                                <th>Tipe</th>
                                <th>Anggaran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengajuan as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge bg-secondary">{{ $p->nomor_pengajuan ?? '-' }}</span></td>
                                <td>{{ $p->judul ?? '-' }}</td>
                                <td>{{ $p->pengaju->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($p->tipe ?? '-') }}</span>
                                </td>
                                <td>Rp {{ number_format($p->jumlah_anggaran ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->status == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->status ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>{{ isset($p->created_at) ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('kepala-sekolah.persetujuan.show', $p->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($pengajuan, 'links'))
                    <div class="d-flex justify-content-end mt-3">
                        {{ $pengajuan->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data pengajuan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection