@extends('layouts.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>📋 Daftar Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Halaman Persetujuan Pengajuan untuk Kepala Sekolah.
                    </div>
                    
                    @if(isset($pengajuan) && $pengajuan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Pengaju</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->judul ?? '-' }}</td>
                                        <td>{{ $item->pengaju->name ?? '-' }}</td>
                                        <td>
                                            @if($item->status == 'menunggu')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($item->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('kepala-sekolah.persetujuan.show', $item->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pengajuan->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data pengajuan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection