@extends('administrasi.layouts.header')

@section('title', 'Data Pembayaran Lain')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-money-bill-wave me-2"></i>
        Data Pembayaran Lain
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- PERBAIKAN: Gunakan route create -->
        <a href="{{ route('administrasi.keuangan.pembayaran-lain.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Pembayaran
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Tanggal Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $key => $item)
                    <tr>
                        <td>{{ $key + $pembayaran->firstItem() }}</td>
                        <td>{{ $item->no_transaksi }}</td>
                        <td>{{ $item->siswa->nis ?? '-' }}</td>
                        <td>{{ $item->siswa->user->name ?? $item->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $item->siswa->kelas->nama_kelas ?? $item->siswa->kelas->nama ?? $item->siswa->kelas->kelas ?? '-' }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $item->metode_bayar }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('administrasi.keuangan.pembayaran-lain.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('administrasi.keuangan.pembayaran-lain.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $pembayaran->links() }}
        </div>
    </div>
</div>
@endsection