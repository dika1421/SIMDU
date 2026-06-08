@extends('kepala-sekolah.layouts.header')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt me-2"></i>
        Detail Pengajuan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nomor Pengajuan</th>
                        <td><span class="badge bg-secondary p-2">{{ $pengajuan->nomor_pengajuan }}</span></td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td><strong>{{ $pengajuan->judul }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td><span class="badge bg-info">{{ ucfirst($pengajuan->tipe) }}</span></td>
                    </tr>
                    <tr>
                        <th>Jumlah Anggaran</th>
                        <td><h4 class="text-primary">Rp {{ number_format($pengajuan->jumlah_anggaran, 0, ',', '.') }}</h4></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $pengajuan->deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Pengaju</th>
                        <td>{{ $pengajuan->pengaju->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($pengajuan->status == 'menunggu')
                                <span class="badge bg-warning p-2">Menunggu Persetujuan</span>
                            @elseif($pengajuan->status == 'disetujui')
                                <span class="badge bg-success p-2">Disetujui</span>
                            @elseif($pengajuan->status == 'ditolak')
                                <span class="badge bg-danger p-2">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @if($pengajuan->status != 'menunggu')
                    <tr>
                        <th>Diproses Oleh</th>
                        <td>{{ $pengajuan->penyetuju->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Diproses</th>
                        <td>{{ $pengajuan->tanggal_disetujui ? \Carbon\Carbon::parse($pengajuan->tanggal_disetujui)->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $pengajuan->catatan ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        @if($pengajuan->status == 'menunggu')
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aksi Persetujuan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.persetujuan.approve', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Setujui pengajuan ini?')">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </form>
                
                <form action="{{ route('kepala-sekolah.persetujuan.reject', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak pengajuan ini?')">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pengaju</h5>
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $pengajuan->pengaju->name }}</p>
                <p><strong>Email:</strong> {{ $pengajuan->pengaju->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($pengajuan->pengaju->role_login) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection