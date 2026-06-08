@extends('administrasi.layouts.header')

@section('title', 'Detail Jurusan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-info-circle me-2"></i>
        Detail Jurusan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jurusan.edit', $jurusan->id) }}" class="btn btn-sm btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('administrasi.jurusan.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-graduation-cap me-2"></i> Informasi Jurusan
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">Kode Jurusan</th>
                        <td>: <strong>{{ $jurusan->kode_jurusan }}</strong></td>
                    </tr>
                    <tr>
                        <th>Nama Jurusan</th>
                        <td>: {{ $jurusan->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            @if($jurusan->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>: {{ $jurusan->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>: {{ $jurusan->created_at ? $jurusan->created_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>: {{ $jurusan->updated_at ? $jurusan->updated_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i> Statistik
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="card bg-primary text-white p-3">
                            <h3>{{ $jumlahKelas ?? $jurusan->kelas->count() }}</h3>
                            <p class="mb-0">Jumlah Kelas</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-success text-white p-3">
                            <h3>{{ $jumlahSiswa ?? 0 }}</h3>
                            <p class="mb-0">Jumlah Siswa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Daftar Kelas
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Kapasitas</th>
                                <th>Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurusan->kelas as $key => $kelas)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $kelas->nama }}</td>
                                <td>{{ $kelas->waliKelas->user->name ?? $kelas->waliKelas->nama_lengkap ?? '-' }}</td>
                                <td>{{ $kelas->kapasitas ?? '-' }}</td>
                                <td>{{ $kelas->siswa->count() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada kelas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection