@extends('kepala-sekolah.layouts.header')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Manajemen Tahun Ajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTahunAjaranModal">
            <i class="fas fa-plus"></i> Tambah Tahun Ajaran
        </button>
    </div>
</div>

<!-- Info Tahun Ajaran Aktif -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Tahun Ajaran Aktif Saat Ini:</strong>
    @php $aktif = $tahunAjaran->where('is_aktif', true)->first(); @endphp
    @if($aktif)
        {{ $aktif->nama }} (Semester {{ ucfirst($aktif->semester) }})
    @else
        Belum ada tahun ajaran aktif
    @endif
</div>

<!-- Daftar Tahun Ajaran -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Tahun Ajaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tahunAjaran as $ta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $ta->nama }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $ta->semester == 'ganjil' ? 'info' : 'success' }}">
                                {{ ucfirst($ta->semester) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ta->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ta->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>
                            @if($ta->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if(!$ta->is_aktif)
                            <form action="{{ route('kepala-sekolah.manajemen.tahun-ajaran.set-aktif', $ta->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Set sebagai tahun ajaran aktif?')">
                                    <i class="fas fa-check"></i> Set Aktif
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahTahunAjaranModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kepala-sekolah.manajemen.tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2024/2025" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_aktif" class="form-check-input" value="1">
                            <label class="form-check-label">
                                Jadikan Tahun Ajaran Aktif
                            </label>
                        </div>
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
@endsection