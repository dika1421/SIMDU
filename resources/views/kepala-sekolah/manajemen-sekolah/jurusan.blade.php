@extends('kepala-sekolah.layouts.header')

@section('title', 'Manajemen Jurusan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-code-branch me-2"></i>
        Manajemen Jurusan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahJurusanModal">
            <i class="fas fa-plus"></i> Tambah Jurusan
        </button>
    </div>
</div>

<!-- Statistik Jurusan -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Jurusan</h6>
                <h3>{{ $jurusan->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Kelas</h6>
                <h3>{{ \App\Models\Kelas::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Siswa</h6>
                <h3>{{ \App\Models\Siswa::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Jurusan -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Jurusan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Deskripsi</th>
                        <th>Kepala Jurusan</th>
                        <th>Jumlah Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jurusan as $j)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary">{{ $j->kode }}</span></td>
                        <td><strong>{{ $j->nama }}</strong></td>
                        <td>{{ Str::limit($j->deskripsi, 50) }}</td>
                        <td>{{ $j->kepalaJurusan->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $j->kelas->count() }}</td>
                        <td class="text-center">{{ $j->siswa->count() }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editJurusanModal{{ $j->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('kepala-sekolah.manajemen.jurusan.destroy', $j->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahJurusanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kepala-sekolah.manajemen.jurusan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jurusan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kepala Jurusan</label>
                        <select name="kepala_jurusan_id" class="form-control">
                            <option value="">Pilih Guru</option>
                            @foreach($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach($jurusan as $j)
<div class="modal fade" id="editJurusanModal{{ $j->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kepala-sekolah.manajemen.jurusan.update', $j->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" class="form-control" value="{{ $j->kode }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" value="{{ $j->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $j->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kepala Jurusan</label>
                        <select name="kepala_jurusan_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ $j->kepala_jurusan_id == $g->id ? 'selected' : '' }}>
                                {{ $g->user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection