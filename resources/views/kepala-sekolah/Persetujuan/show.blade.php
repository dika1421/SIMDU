@extends('kepala-sekolah.layouts.header')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-file-alt me-2"></i> Detail Pengajuan
                <div class="float-end">
                    <a href="{{ route('kepala-sekolah.persetujuan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($pengajuan->status == 'menunggu')
                        <a href="{{ route('kepala-sekolah.persetujuan.edit', $pengajuan->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="200">Judul</th><td>{{ $pengajuan->judul }}</td></tr>
                    <tr><th>Pengaju</th><td>{{ $pengajuan->pengaju->name ?? 'Tidak Diketahui' }}</td></tr>
                    <tr><th>Tipe</th><td>{{ ucfirst($pengajuan->tipe) }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            <span class="badge bg-{{ $pengajuan->status == 'menunggu' ? 'warning' : ($pengajuan->status == 'disetujui' ? 'success' : 'danger') }}">
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Prioritas</th>
                        <td>
                            <span class="badge bg-{{ $pengajuan->prioritas == 'tinggi' ? 'danger' : ($pengajuan->prioritas == 'sedang' ? 'warning' : 'info') }}">
                                {{ ucfirst($pengajuan->prioritas ?? 'Normal') }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Jumlah Anggaran</th>
                        <td>Rp {{ number_format($pengajuan->jumlah_anggaran ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr><th>Deskripsi</th><td>{{ $pengajuan->deskripsi }}</td></tr>
                    <tr><th>Catatan</th><td>{{ $pengajuan->catatan ?? '-' }}</td></tr>
                    <tr><th>Lampiran</th>
                        <td>
                            @if($pengajuan->lampiran)
                                <a href="{{ asset('storage/' . $pengajuan->lampiran) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> Lihat Lampiran
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr><th>Tanggal Pengajuan</th><td>{{ $pengajuan->created_at->format('d/m/Y H:i') }}</td></tr>
                    @if($pengajuan->status == 'disetujui')
                        <tr><th>Disetujui Oleh</th><td>{{ $pengajuan->disetujuiOleh->name ?? 'Tidak Diketahui' }}</td></tr>
                        <tr><th>Tanggal Disetujui</th>
                            <td>{{ $pengajuan->tanggal_disetujui ? date('d/m/Y H:i', strtotime($pengajuan->tanggal_disetujui)) : '-' }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($pengajuan->status == 'menunggu')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cog me-2"></i> Aksi
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.persetujuan.approve', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </form>

                <form action="{{ route('kepala-sekolah.persetujuan.reject', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Catatan (Wajib)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 mb-2">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </form>

                <form action="{{ route('kepala-sekolah.persetujuan.revise', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Catatan Revisi (Wajib)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-info w-100">
                        <i class="fas fa-undo"></i> Revisi
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection