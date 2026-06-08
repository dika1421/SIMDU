@extends('administrasi.layouts.header')

@section('title', 'Detail Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>
        Detail Siswa
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.siswa.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('administrasi.siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#mutasiModal">
            <i class="fas fa-exchange-alt"></i> Mutasi
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;">
                    <i class="fas fa-user-graduate fa-5x text-white"></i>
                </div>
                <h4 class="mt-3">{{ $siswa->user->name }}</h4>
                <p class="text-muted">NIS: {{ $siswa->nis }}</p>
                <p class="text-muted">NISN: {{ $siswa->nisn }}</p>
                <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : ($siswa->status == 'lulus' ? 'info' : 'danger') }} p-2">
                    {{ ucfirst($siswa->status) }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Lengkap</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Email</th>
                        <td>{{ $siswa->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Tempat/Tanggal Lahir</th>
                        <td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $siswa->agama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $siswa->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ayah</th>
                        <td>{{ $siswa->nama_ayah }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $siswa->nama_ibu }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon Orang Tua</th>
                        <td>{{ $siswa->no_telp_ortu }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan Orang Tua</th>
                        <td>{{ $siswa->pekerjaan_ortu ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Masuk</th>
                        <td>{{ $siswa->tahun_masuk }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mutasi -->
<div class="modal fade" id="mutasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('administrasi.siswa.mutasi', $siswa->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Mutasi Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kelas Asal</label>
                        <input type="text" class="form-control" value="{{ $siswa->kelas->nama ?? '-' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas Tujuan <span class="text-danger">*</span></label>
                        <select name="kelas_tujuan" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Mutasi <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Mutasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection