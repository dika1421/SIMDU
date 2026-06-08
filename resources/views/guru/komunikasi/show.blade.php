@extends('guru.layouts.header')

@section('title', 'Detail Pesan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope-open me-2"></i>
        Detail Pesan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.komunikasi.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $pesan->judul }}</h5>
        @if($pesan->is_urgent)
            <span class="badge bg-danger mt-2">Penting</span>
        @endif
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <small class="text-muted">Pengirim:</small>
                <p><strong>{{ $pesan->pengirim->name ?? 'Tidak Diketahui' }}</strong><br>
                <small class="text-muted">{{ ucfirst($pesan->pengirim->role ?? '') }}</small></p>
            </div>
            <div class="col-md-6">
                <small class="text-muted">Tanggal Kirim:</small>
                <p><strong>{{ \Carbon\Carbon::parse($pesan->created_at)->format('d F Y H:i') }}</strong></p>
            </div>
        </div>
        
        <div class="mb-4">
            <small class="text-muted">Isi Pesan:</small>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($pesan->isi)) !!}
            </div>
        </div>
        
        @if($pesan->penerimaPesan->count() > 0)
            <div class="mb-4">
                <small class="text-muted">Penerima:</small>
                <div class="mt-2">
                    @foreach($pesan->penerimaPesan as $penerima)
                        <span class="badge bg-secondary me-1">
                            {{ $penerima->penerima->name ?? 'Tidak Diketahui' }}
                            @if($penerima->status == 'dibaca')
                                <i class="fas fa-check-circle text-success ms-1"></i>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div class="text-end">
            <a href="{{ route('guru.komunikasi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection