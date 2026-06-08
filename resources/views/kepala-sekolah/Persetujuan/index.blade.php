@extends('kepala-sekolah.layouts.header')

@section('title', 'Persetujuan Pengajuan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-clipboard-list me-2"></i>
        Persetujuan & Pengawasan
    </h1>
</div>

<!-- Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Menunggu</h6>
                <h3>{{ $pengajuan->where('status', 'menunggu')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Disetujui</h6>
                <h3>{{ $pengajuan->where('status', 'disetujui')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h6>Ditolak</h6>
                <h3>{{ $pengajuan->where('status', 'ditolak')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Total Anggaran</h6>
                <h4>Rp {{ number_format($pengajuan->where('status', 'disetujui')->sum('jumlah_anggaran'), 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipe</label>
                <select name="tipe" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="kegiatan" {{ request('tipe') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="anggaran" {{ request('tipe') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                    <option value="izin" {{ request('tipe') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="pengadaan" {{ request('tipe') == 'pengadaan' ? 'selected' : '' }}>Pengadaan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Pengajuan -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Pengajuan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
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
                        <td><span class="badge bg-secondary">{{ $p->nomor_pengajuan }}</span></td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ $p->pengaju->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($p->tipe) }}</span>
                        </td>
                        <td>Rp {{ number_format($p->jumlah_anggaran, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status == 'menunggu')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
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
    </div>
</div>
@endsection