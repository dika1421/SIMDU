@extends('kepala-sekolah.layouts.header')

@section('title', 'Hapus Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-trash-alt me-2 text-danger"></i>
        Hapus Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Penghapusan
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-user-times fa-5x text-danger mb-3"></i>
                    <h4>Apakah Anda yakin?</h4>
                    <p>Anda akan menghapus guru <strong>{{ $guru->nama_lengkap ?? $guru->user->name ?? '-' }}</strong></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Data yang dihapus tidak dapat dikembalikan!<br>
                        Semua data terkait (user, jadwal mengajar, dll) juga akan terhapus.</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('kepala-sekolah.manajemen-guru.destroy', $guru->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt"></i> Ya, Hapus!
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Detail Guru yang Akan Dihapus -->
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Detail Guru yang Akan Dihapus</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="120">NIP</th>
                        <td>: {{ $guru->nip ?? '-' }}</td
                    </tr>
                    <tr>
                        <th>NUPTK</th>
                        <td>: {{ $guru->nuptk ?? '-' }}</td
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>: {{ $guru->nama_lengkap ?? $guru->user->name ?? '-' }}</td
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $guru->user->email ?? '-' }}</td
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>: {{ $guru->jabatan->nama_jabatan ?? $guru->status_kepegawaian ?? '-' }}</td
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            @if(($guru->status ?? 'aktif') == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection