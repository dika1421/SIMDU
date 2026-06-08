@extends('administrasi.layouts.header')

@section('title', 'Pesan & Komunikasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope me-2"></i>
        Pesan & Komunikasi
        @if(isset($belumDibaca) && $belumDibaca > 0)
            <span class="badge bg-danger ms-2">{{ $belumDibaca }} Baru</span>
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.komunikasi.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Pesan Baru
        </a>
        <a href="{{ route('administrasi.komunikasi.broadcast') }}" class="btn btn-sm btn-info ms-2">
            <i class="fas fa-bullhorn"></i> Broadcast
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="pesanTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab">
            <i class="fas fa-inbox me-1"></i> Pesan Masuk
            @if(isset($belumDibaca) && $belumDibaca > 0)
                <span class="badge bg-danger ms-1">{{ $belumDibaca }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button" role="tab">
            <i class="fas fa-paper-plane me-1"></i> Pesan Terkirim
        </button>
    </li>
</ul>

<div class="tab-content" id="pesanTabContent">
    <!-- Tab Pesan Masuk -->
    <div class="tab-pane fade show active" id="masuk" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesan Masuk</h5>
                @if(isset($pesanDiterima))
                <span class="badge bg-info">{{ $pesanDiterima->total() ?? $pesanDiterima->count() }} Pesan</span>
                @endif
            </div>
            <div class="card-body">
                @if(isset($pesanDiterima) && $pesanDiterima->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                实例
                                    <th width="5%"></th>
                                    <th width="25%">Pengirim</th>
                                    <th width="40%">Judul</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanDiterima as $p)
                                    @php
                                        // Cek status baca dari pivot
                                        $penerimaData = $p->penerimaPesan->where('penerima_id', auth()->id())->first();
                                        $sudahDibaca = $penerimaData ? ($penerimaData->status == 'dibaca') : false;
                                        $rowClass = (!$sudahDibaca) ? 'table-info fw-bold' : '';
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            @if($p->is_urgent)
                                                <i class="fas fa-exclamation-circle text-danger" title="Penting"></i>
                                            @endif
                                            @if(!$sudahDibaca)
                                                <span class="badge bg-warning ms-1">Baru</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $p->pengirim->name ?? 'Tidak Diketahui' }}
                                            <br>
                                            <small class="text-muted">{{ ucfirst($p->pengirim->role ?? '') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($p->judul, 50) }}</strong>
                                            @if($p->jenis == 'broadcast')
                                                <span class="badge bg-info ms-2">Broadcast</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('administrasi.komunikasi.show', $p->id) }}" 
                                               class="btn btn-sm btn-info" title="Baca Pesan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($pesanDiterima, 'links'))
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $pesanDiterima->firstItem() ?? 0 }} - {{ $pesanDiterima->lastItem() ?? 0 }} 
                                dari {{ $pesanDiterima->total() ?? 0 }} pesan
                            </div>
                            <div>
                                {{ $pesanDiterima->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Pesan Masuk</h5>
                        <p class="text-muted">Anda belum memiliki pesan masuk</p>
                        <a href="{{ route('administrasi.komunikasi.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Pesan Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Tab Pesan Terkirim -->
    <div class="tab-pane fade" id="keluar" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesan Terkirim</h5>
                @if(isset($pesanDikirim))
                <span class="badge bg-info">{{ $pesanDikirim->total() ?? $pesanDikirim->count() }} Pesan</span>
                @endif
            </div>
            <div class="card-body">
                @if(isset($pesanDikirim) && $pesanDikirim->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="40%">Judul</th>
                                    <th width="15%">Jenis</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanDikirim as $p)
                                    <tr>
                                        <td>
                                            @if($p->is_urgent)
                                                <i class="fas fa-exclamation-circle text-danger" title="Penting"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($p->judul, 50) }}</strong>
                                            @if($p->jenis == 'broadcast')
                                                <span class="badge bg-info ms-2">Broadcast</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->jenis == 'broadcast')
                                                <span class="badge bg-info">Broadcast</span>
                                            @else
                                                <span class="badge bg-secondary">Personal</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Terkirim</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('administrasi.komunikasi.show', $p->id) }}" 
                                               class="btn btn-sm btn-info" title="Detail Pesan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($pesanDikirim, 'links'))
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $pesanDikirim->firstItem() ?? 0 }} - {{ $pesanDikirim->lastItem() ?? 0 }} 
                                dari {{ $pesanDikirim->total() ?? 0 }} pesan
                            </div>
                            <div>
                                {{ $pesanDikirim->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-paper-plane fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Pesan Terkirim</h5>
                        <p class="text-muted">Anda belum mengirim pesan apapun</p>
                        <a href="{{ route('administrasi.komunikasi.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Pesan Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistik Pesan -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-paper-plane fa-2x mb-2"></i>
                <h3>{{ isset($pesanDikirim) ? ($pesanDikirim->total() ?? $pesanDikirim->count()) : 0 }}</h3>
                <p class="mb-0 opacity-75">Pesan Terkirim</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <h3>{{ isset($pesanDiterima) ? ($pesanDiterima->total() ?? $pesanDiterima->count()) : 0 }}</h3>
                <p class="mb-0 opacity-75">Pesan Diterima</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-2x mb-2"></i>
                <h3>{{ $belumDibaca ?? 0 }}</h3>
                <p class="mb-0 opacity-75">Belum Dibaca</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-info {
        background-color: #cfe2ff !important;
    }
    .table-info td {
        font-weight: 500;
    }
    .badge {
        font-size: 0.75rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        padding: 0.375rem 0.75rem;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 500;
    }
</style>
@endpush
@endsection