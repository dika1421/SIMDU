@extends('administrasi.layouts.header')

@section('title', 'Detail Pesan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope-open me-2"></i>
        Detail Pesan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.komunikasi.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $pesan->judul ?? 'Tanpa Judul' }}</h5>
        @if(isset($pesan->is_urgent) && $pesan->is_urgent)
            <span class="badge bg-danger">Penting</span>
        @endif
        @if(isset($pesan->jenis) && $pesan->jenis == 'broadcast')
            <span class="badge bg-info">Broadcast</span>
        @endif
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Pengirim:</strong> 
            @if($pesan->pengirim)
                {{ $pesan->pengirim->name ?? 'Tidak diketahui' }}
            @else
                <span class="text-muted">Pengirim tidak ditemukan</span>
            @endif
            <span class="text-muted ms-2">
                ({{ $pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->format('d/m/Y H:i') : '-' }})
            </span>
        </div>
        
        <div class="mb-3">
            <strong>Tipe:</strong> 
            @if(isset($pesan->jenis) && $pesan->jenis == 'broadcast')
                <span class="badge bg-info">Broadcast</span>
            @else
                <span class="badge bg-secondary">Individual</span>
            @endif
        </div>

        <div class="mb-3">
            <strong>Status:</strong>
            @if(isset($pesan->status))
                @if($pesan->status == 'terkirim')
                    <span class="badge bg-success">Terkirim</span>
                @elseif($pesan->status == 'dibaca')
                    <span class="badge bg-primary">Dibaca</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($pesan->status) }}</span>
                @endif
            @else
                <span class="badge bg-secondary">-</span>
            @endif
        </div>
        
        <hr>
        
        <div class="mb-4">
            <strong>Isi Pesan:</strong>
            <div class="mt-2 p-3 bg-light rounded">
                {!! nl2br(e($pesan->isi ?? '-')) !!}
            </div>
        </div>
        
        <!-- Tampilkan daftar penerima untuk pesan individual -->
        @if(isset($pesan->jenis) && $pesan->jenis != 'broadcast')
        <hr>
        <div class="mt-3">
            <strong>Daftar Penerima:</strong>
            <div class="table-responsive mt-2">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Waktu Dibaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $penerimaList = $pesan->penerimaPesan ?? $pesan->penerima ?? []; @endphp
                        @forelse($penerimaList as $index => $penerimaItem)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                @if($penerimaItem->penerima)
                                    {{ $penerimaItem->penerima->name ?? 'Tidak diketahui' }}
                                @elseif($penerimaItem->user)
                                    {{ $penerimaItem->user->name ?? 'Tidak diketahui' }}
                                @else
                                    <span class="text-muted">Penerima tidak ditemukan</span>
                                @endif
                            </td>
                            <td>
                                @if($penerimaItem->penerima)
                                    <span class="badge bg-secondary">{{ ucfirst($penerimaItem->penerima->role ?? '-') }}</span>
                                @elseif($penerimaItem->user)
                                    <span class="badge bg-secondary">{{ ucfirst($penerimaItem->user->role ?? '-') }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($penerimaItem->status))
                                    @if($penerimaItem->status == 'dibaca')
                                        <span class="badge bg-success">Sudah Dibaca</span>
                                    @elseif($penerimaItem->status == 'terkirim')
                                        <span class="badge bg-warning">Belum Dibaca</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($penerimaItem->status) }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($penerimaItem->tanggal_baca) && $penerimaItem->tanggal_baca)
                                    {{ \Carbon\Carbon::parse($penerimaItem->tanggal_baca)->format('d/m/Y H:i') }}
                                @elseif(isset($penerimaItem->dibaca_at) && $penerimaItem->dibaca_at)
                                    {{ \Carbon\Carbon::parse($penerimaItem->dibaca_at)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data penerima</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Informasi tambahan untuk broadcast -->
        @if(isset($pesan->jenis) && $pesan->jenis == 'broadcast')
        <hr>
        <div class="mt-3">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Pesan ini dikirim sebagai <strong>Broadcast</strong> ke semua pengguna.
                @if(isset($pesan->penerimaPesan))
                    <br>Total penerima: <strong>{{ $pesan->penerimaPesan->count() }}</strong> orang
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between">
            <div>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> Dikirim: {{ $pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->diffForHumans() : '-' }}
                </small>
            </div>
            <div>
                @if(auth()->id() == ($pesan->pengirim_id ?? null))
                <form action="{{ route('administrasi.komunikasi.destroy', $pesan->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .table-sm td, .table-sm th {
        padding: 0.5rem;
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush