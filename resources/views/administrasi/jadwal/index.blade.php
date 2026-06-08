@extends('administrasi.layouts.header')

@section('title', 'Daftar Jadwal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Daftar Jadwal Pelajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jadwal.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {!! session('success') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i> {!! session('error') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Form Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.jadwal.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Hari</label>
                <select name="hari" class="form-control">
                    <option value="">-- Semua Hari --</option>
                    @foreach($hariList as $h)
                    <option value="{{ $h }}" {{ isset($selectedHari) && $selectedHari == $h ? 'selected' : '' }}>
                        {{ ucfirst($h) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-control">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ isset($selectedKelasId) && $selectedKelasId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama ?? $k->nama_kelas ?? $k->kelas ?? 'Kelas' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Jadwal -->
<div class="card">
    <div class="card-header bg-white">
        <i class="fas fa-table me-2 text-primary"></i>
        <strong>Data Jadwal</strong>
        <span class="badge bg-primary ms-2">{{ $jadwal->count() }} Jadwal</span>
    </div>
    <div class="card-body">
        @if($jadwal->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="jadwalTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Ruangan</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $key => $j)
                    @php
                        // ========== AMBIL NAMA MATA PELAJARAN ==========
                        $namaMapel = 'Mata Pelajaran tidak ditemukan';
                        
                        // Cek dari relasi mapel yang sudah di-load
                        if(isset($j->mapel) && $j->mapel) {
                            $namaMapel = $j->mapel->nama ?? 'Mata Pelajaran tidak ditemukan';
                        } 
                        // Cek dari relasi mataPelajaran
                        elseif(isset($j->mataPelajaran) && $j->mataPelajaran) {
                            $namaMapel = $j->mataPelajaran->nama ?? 'Mata Pelajaran tidak ditemukan';
                        }
                        // Jika relasi tidak berfungsi, ambil dari database langsung
                        else {
                            $mapelId = $j->mata_pelajaran_id ?? null;
                            if($mapelId) {
                                $mapel = DB::table('mapel')->where('id', $mapelId)->first();
                                $namaMapel = $mapel ? $mapel->nama : 'ID: ' . $mapelId . ' (tidak ditemukan)';
                            } else {
                                $namaMapel = 'Tidak ada data mapel';
                            }
                        }
                        
                        // ========== AMBIL NAMA KELAS ==========
                        $namaKelas = 'Kelas tidak ditemukan';
                        if(isset($j->kelas) && $j->kelas) {
                            $namaKelas = $j->kelas->nama ?? $j->kelas->nama_kelas ?? $j->kelas->kelas ?? 'Kelas tidak ditemukan';
                        } else {
                            $kelasId = $j->kelas_id ?? null;
                            if($kelasId) {
                                $kelasData = DB::table('kelas')->where('id', $kelasId)->first();
                                $namaKelas = $kelasData ? ($kelasData->nama ?? 'ID: ' . $kelasId) : 'ID: ' . $kelasId;
                            }
                        }
                        
                        // ========== AMBIL NAMA GURU ==========
                        $namaGuru = 'Guru tidak ditemukan';
                        if(isset($j->guru) && $j->guru) {
                            $namaGuru = $j->guru->user->name ?? $j->guru->nama_lengkap ?? $j->guru->nama ?? 'Guru tidak ditemukan';
                        } else {
                            $guruId = $j->guru_id ?? null;
                            if($guruId) {
                                $guruData = DB::table('guru')->where('id', $guruId)->first();
                                if($guruData) {
                                    $namaGuru = $guruData->nama_lengkap ?? $guruData->nama ?? 'ID: ' . $guruId;
                                } else {
                                    $namaGuru = 'ID: ' . $guruId . ' (tidak ditemukan)';
                                }
                            }
                        }
                        
                        // ========== DATA LAINNYA ==========
                        $ruangan = $j->ruangan ?? '-';
                        $tahunAjaran = $j->tahun_ajaran ?? '-';
                        $semester = $j->semester ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($j->hari) }}</span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                        </td>
                        <td>
                            <strong>{{ $namaKelas }}</strong>
                            @if(isset($j->kelas) && $j->kelas && $j->kelas->jurusan)
                                <br><small class="text-muted">{{ $j->kelas->jurusan->nama ?? '' }}</small>
                            @endif
                        </td>
                        <td style="min-width: 200px;">
                            <span class="badge bg-primary" style="font-size: 14px; padding: 8px 12px;">
                                <i class="fas fa-book me-1"></i> {{ $namaMapel }}
                            </span>
                        </td>
                        <td>{{ $namaGuru }}</td>
                        <td><span class="badge bg-secondary">{{ $ruangan }}</span></td>
                        <td>{{ $tahunAjaran }}</td>
                        <td>
                            @if($semester == 'ganjil')
                                <span class="badge bg-success">Ganjil</span>
                            @elseif($semester == 'genap')
                                <span class="badge bg-warning">Genap</span>
                            @else
                                <span class="badge bg-secondary">{{ $semester }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('administrasi.jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('administrasi.jadwal.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
            <strong>Belum ada data jadwal</strong><br>
            <small>Silakan tambah jadwal terlebih dahulu</small>
            <div class="mt-3">
                <a href="{{ route('administrasi.jadwal.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    if ($('#jadwalTable').length && $('#jadwalTable tbody tr').length > 0) {
        $('#jadwalTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                processing: "Sedang memproses...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Terakhir"
                }
            },
            pageLength: 10,
            order: [[1, 'asc']],
            responsive: true
        });
    }
});
</script>
@endpush
@endsection