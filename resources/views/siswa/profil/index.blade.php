{{-- resources/views/siswa/profil/index.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <!-- Sidebar Profil -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-4">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ $siswa->nama_lengkap ?? $user->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="fas fa-id-card me-1"></i> NIS: {{ $siswa->nis ?? '-' }}
                </p>
                <hr>
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $user->email }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-phone me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $siswa->no_telepon ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $siswa->alamat ?? '-' }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-flag me-2 text-secondary" style="width: 20px;"></i> 
                        {{ $siswa->status ?? 'Aktif' }}
                    </p>
                </div>
                <a href="{{ route('siswa.profil.edit') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                    <i class="fas fa-edit me-1"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
    
    <!-- Detail Profil -->
    <div class="col-lg-8">
        <!-- Informasi Akademik -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-graduation-cap me-2 text-primary"></i> Informasi Akademik
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Kelas</td>
                                <td class="fw-bold">: {{ $siswa->kelas->nama_kelas ?? $siswa->kelas->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jurusan</td>
                                <td class="fw-bold">: {{ $siswa->kelas->jurusan ?? $siswa->kelas->nama_jurusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">NISN</td>
                                <td class="fw-bold">: {{ $siswa->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tahun Masuk</td>
                                <td class="fw-bold">: {{ $siswa->tahun_masuk ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Jenis Kelamin</td>
                                <td class="fw-bold">: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tempat, Tgl Lahir</td>
                                <td class="fw-bold">: {{ $siswa->tempat_lahir ?? '-' }}, 
                                    @if($siswa->tanggal_lahir)
                                        {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Agama</td>
                                <td class="fw-bold">: {{ $siswa->agama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td class="fw-bold">
                                    <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($siswa->status ?? 'Aktif') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Orang Tua -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-users me-2 text-success"></i> Informasi Orang Tua
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Nama Ayah</td>
                                <td class="fw-bold">: {{ $siswa->nama_ayah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Ibu</td>
                                <td class="fw-bold">: {{ $siswa->nama_ibu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Telepon</td>
                                <td class="fw-bold">: {{ $siswa->no_telepon_orangtua ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 120px;">Pekerjaan</td>
                                <td class="fw-bold">: {{ $siswa->pekerjaan_orangtua ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat</td>
                                <td class="fw-bold">: {{ $siswa->alamat_orangtua ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ubah Password -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-key me-2 text-warning"></i> Ubah Password
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.profil.change-password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="password" name="current_password" class="form-control" placeholder="Password Saat Ini" required>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="new_password" class="form-control" placeholder="Password Baru" required>
                            @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning rounded-pill px-4">
                                <i class="fas fa-save me-1"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection