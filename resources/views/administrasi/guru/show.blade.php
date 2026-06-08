@extends('administrasi.layouts.header')

@section('title', 'Detail Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Detail Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.guru.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('administrasi.guru.edit', $guru->id) }}" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chalkboard-user fa-5x text-primary mb-3"></i>
                <h4>{{ $guru->nama_lengkap }}</h4>
                <p class="text-muted">{{ $guru->nip }}</p>
                <hr>
                <p>
                    <span class="badge bg-{{ $guru->status == 'aktif' ? 'success' : 'danger' }}">
                        {{ ucfirst($guru->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Lengkap</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">NIP</th>
                                <td>: {{ $guru->nip ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>NUPTK</th>
                                <td>: {{ $guru->nuptk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                              <tr>
                                <th>Mata Pelajaran</th>
                                <table>: {{ $guru->mata_pelajaran_utama ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Tempat, Tanggal Lahir</th>
                                <td>: {{ $guru->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pendidikan Terakhir</th>
                                <td>: {{ $guru->pendidikan_terakhir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No Telepon</th>
                                <td>: {{ $guru->no_telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $guru->user->email ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <hr>
                        <strong>Alamat:</strong>
                        <p>{{ $guru->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection