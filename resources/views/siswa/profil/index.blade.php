{{-- resources/views/siswa/profil/index.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">NIS: {{ $siswa->nis ?? '-' }}</p>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-envelope me-2 text-secondary"></i> {{ $user->email }}</p>
                    <p><i class="fas fa-phone me-2 text-secondary"></i> {{ $user->no_telepon ?? '-' }}</p>
                    <p><i class="fas fa-map-marker-alt me-2 text-secondary"></i> {{ $user->alamat ?? '-' }}</p>
                </div>
                <a href="{{ route('siswa.profil.edit') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Akademik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Kelas</th>
                                <td>: {{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jurusan</th>
                                <td>: {{ $siswa->kelas->jurusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Masuk</th>
                                <td>: {{ $siswa->tahun_masuk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($siswa->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">NISN</th>
                                <td>: {{ $siswa->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <td>: {{ $siswa->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Orang Tua</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Nama Ayah</th>
                                <td>: {{ $siswa->nama_ayah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama Ibu</th>
                                <td>: {{ $siswa->nama_ibu ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">No. Telepon</th>
                                <td>: {{ $siswa->no_telepon_orangtua ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td>: {{ $siswa->pekerjaan_orangtua ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-2">
                    <strong>Alamat Orang Tua:</strong>
                    <p>{{ $siswa->alamat_orangtua ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.profil.change-password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="password" name="current_password" class="form-control" placeholder="Password Saat Ini" required>
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="new_password" class="form-control" placeholder="Password Baru" required>
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection