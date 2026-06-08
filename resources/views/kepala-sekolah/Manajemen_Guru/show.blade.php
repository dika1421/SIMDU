@extends('kepala-sekolah.layouts.header')

@section('title', 'Detail Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>
        Detail Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;">
                    <i class="fas fa-user fa-5x text-white"></i>
                </div>
                <h4 class="mt-3">{{ $guru->nama_lengkap ?? '-' }}</h4>
                <p class="text-muted">{{ $guru->status_kepegawaian ?? '-' }}</p>
                <span class="badge bg-{{ ($guru->status ?? 'aktif') == 'aktif' ? 'success' : 'danger' }} p-2">
                    {{ ucfirst($guru->status ?? 'aktif') }}
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
                        <th width="200">NIP</th>
                        <td>{{ $guru->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NUPTK</th>
                        <td>{{ $guru->nuptk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $guru->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tempat/Tanggal Lahir</th>
                        <td>
                            {{ $guru->tempat_lahir ?? '-' }}, 
                            {{ isset($guru->tanggal_lahir) ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ ($guru->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan Terakhir</th>
                        <td>{{ $guru->pendidikan_terakhir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jurusan Pendidikan</th>
                        <td>{{ $guru->jurusan_pendidikan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Universitas</th>
                        <td>{{ $guru->universitas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Lulus</th>
                        <td>{{ $guru->tahun_lulus ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk (TMT)</th>
                        <td>{{ isset($guru->tmt_masuk) ? \Carbon\Carbon::parse($guru->tmt_masuk)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $guru->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $guru->no_telepon ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('kepala-sekolah.manajemen-guru.edit', $guru->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" action="{{ route('kepala-sekolah.manajemen-guru.destroy', $guru->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus guru ini? Data yang dihapus tidak dapat dikembalikan.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection