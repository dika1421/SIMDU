@extends('siswa.layouts.header')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-credit-card me-2"></i>
        Detail Pembayaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('siswa.pembayaran.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Tagihan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">No. Transaksi</th>
                        <td>: {{ $pembayaran->no_transaksi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Pembayaran</th>
                        <td>: <strong>{{ $pembayaran->jenis_pembayaran_label }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: {!! $pembayaran->status_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td>: {{ $pembayaran->formatted_tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Jatuh Tempo</th>
                        <td>: {{ $pembayaran->formatted_jatuh_tempo }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>: {{ $pembayaran->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Rincian Keuangan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Total Tagihan</th>
                        <td>: <span class="fw-bold fs-5">{{ number_format($pembayaran->tagihan, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <th>Sudah Dibayar</th>
                        <td>: <span class="text-success fw-bold">{{ number_format($pembayaran->terbayar, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <th>Sisa Tagihan</th>
                        <td>: <span class="text-danger fw-bold">{{ number_format($pembayaran->sisa, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>: {{ $pembayaran->metode_bayar ? ucfirst($pembayaran->metode_bayar) : '-' }}</td>
                    </tr>
                </table>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Progress Pembayaran</span>
                        <span class="fw-bold">{{ $pembayaran->persentase }}%</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $pembayaran->persentase }}%">
                            {{ $pembayaran->persentase }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($riwayatLain->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Pembayaran Lainnya ({{ $pembayaran->jenis_pembayaran_label }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Transaksi</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatLain as $item)
                            <tr>
                                <td>{{ $item->formatted_tanggal }}</td>
                                <td><small>{{ $item->no_transaksi ?? '-' }}</small></td>
                                <td>{{ number_format($item->tagihan, 0, ',', '.') }}</td>
                                <td class="text-success">{{ number_format($item->terbayar, 0, ',', '.') }}</td>
                                <td>{!! $item->status_badge !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-3">
    <div class="col-12 text-center">
        <a href="{{ route('siswa.pembayaran.cetak-struk', $pembayaran->id) }}" 
           class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Cetak Struk
        </a>
    </div>
</div>
@endsection