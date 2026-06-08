@extends('guru.layouts.header')

@section('title', 'Kalender Akademik')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Kalender Akademik
    </h1>
</div>

<!-- Filter Tahun -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tahun Akademik</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                            {{ $thn }}/{{ $thn + 1 }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Event -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Event</h5>
                <h2 class="mb-0">{{ $events->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Hari Libur</h5>
                <h2 class="mb-0">{{ $libur->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Jadwal Ujian</h5>
                <h2 class="mb-0">{{ $ujian->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Acara Sekolah</h5>
                <h2 class="mb-0">{{ $acara->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Event -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Kegiatan Akademik</h5>
    </div>
    <div class="card-body">
        @if($events->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Event</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        实例
                            <th width="20%">Tanggal</th>
                            <th width="30%">Event</th>
                            <th width="20%">Jenis</th>
                            <th width="20%">Target</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d/m/Y') }}
                                @if($event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai)
                                    - {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $event->judul }}</strong>
                                @if($event->deskripsi)
                                    <br><small class="text-muted">{{ Str::limit($event->deskripsi, 100) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeColor = [
                                        'libur' => 'warning',
                                        'ujian' => 'danger',
                                        'pendaftaran' => 'info',
                                        'acara' => 'success',
                                        'rapat' => 'primary',
                                        'ekstrakurikuler' => 'secondary',
                                        'lainnya' => 'secondary'
                                    ];
                                    $color = $badgeColor[$event->jenis] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($event->jenis) }}
                                </span>
                            </td>
                            <td>
                                @if($event->target == 'semua')
                                    <span class="badge bg-info">Semua</span>
                                @elseif($event->target == 'guru')
                                    <span class="badge bg-primary">Guru</span>
                                @elseif($event->target == 'siswa')
                                    <span class="badge bg-success">Siswa</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($event->target) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($event->isOngoing())
                                    <span class="badge bg-success">Berlangsung</span>
                                @elseif($event->isUpcoming())
                                    <span class="badge bg-warning">Akan Datang</span>
                                @else
                                    <span class="badge bg-secondary">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection