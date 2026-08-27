@extends('administrasi.layouts.header')

@section('title', 'Detail Jadwal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Detail Jadwal
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('administrasi.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width: 200px;">Hari</th>
                <td><span class="badge bg-info">{{ ucfirst($jadwal->hari) }}</span></td>
            </tr>
            <tr>
                <th>Jam</th>
                <td>
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>
                    {{ $jadwal->kelas->kelas ?? 'Kelas tidak ditemukan' }}
                    @if(isset($jadwal->kelas) && $jadwal->kelas && $jadwal->kelas->jurusan)
                        <br><small class="text-muted">{{ $jadwal->kelas->jurusan->nama ?? '' }}</small>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Guru</th>
                <td>
                    @if(isset($jadwal->guru) && $jadwal->guru)
                        {{ $jadwal->guru->user->name ?? $jadwal->guru->nama_lengkap ?? $jadwal->guru->nama ?? 'Guru tidak ditemukan' }}
                    @else
                        Guru tidak ditemukan
                    @endif
                </td>
            </tr>
            <tr>
                <th>Mata Pelajaran</th>
                <td>{{ $jadwal->mapel->nama ?? $jadwal->mapel->nama_mapel ?? '-' }}</td>
            </tr>
            <tr>
                <th>Ruangan</th>
                <td>{{ $jadwal->ruangan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tahun Ajaran</th>
                <td>{{ $jadwal->tahun_ajaran ?? '-' }}</td>
            </tr>
            <tr>
                <th>Semester</th>
                <td>{{ $jadwal->semester ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $jadwal->status ?? '-' }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $jadwal->keterangan ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection