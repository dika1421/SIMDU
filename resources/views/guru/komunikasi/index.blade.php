@extends('guru.layouts.header')

@section('title', 'Pesan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope me-2"></i>
        Pesan
        @if(isset($belumDibaca) && $belumDibaca > 0)
            <span class="badge bg-danger ms-2">{{ $belumDibaca }} Baru</span>
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.komunikasi.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Pesan Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Tabel Pesan -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Pesan</h5>
    </div>
    <div class="card-body">
        @if($pesan->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Pesan</h5>
                <a href="{{ route('guru.komunikasi.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Buat Pesan Baru
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        实例
                            <th width="10%">Status</th>
                            <th width="35%">Judul</th>
                            <th width="20%">Pengirim</th>
                            <th width="20%">Tanggal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesan as $p)
                        @php
                            // Cek apakah user adalah penerima
                            $penerimaData = $p->penerimaPesan->where('penerima_id', auth()->id())->first();
                            $sudahDibaca = $penerimaData ? ($penerimaData->status == 'dibaca') : true;
                            $isPenting = $p->is_urgent ?? false;
                        @endphp
                        <tr class="{{ !$sudahDibaca ? 'table-info fw-bold' : '' }}">
                            <td>
                                @if($isPenting)
                                    <span class="badge bg-danger">Penting</span>
                                @endif
                                @if(!$sudahDibaca)
                                    <span class="badge bg-warning">Baru</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($p->judul, 50) }}</td>
                            <td>
                                {{ $p->pengirim->name ?? 'Tidak Diketahui' }}
                                <br>
                                <small class="text-muted">{{ ucfirst($p->pengirim->role ?? '') }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('guru.komunikasi.show', $p->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Baca
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $p->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pesan->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pesan ini?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = '/guru/komunikasi/' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
@endsection