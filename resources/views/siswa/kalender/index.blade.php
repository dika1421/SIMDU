{{-- resources/views/siswa/kalender/index.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Kalender Akademik')

@section('content')
<style>
    .event-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .event-card:hover {
        transform: translateX(5px);
    }
    .event-ujian { border-left-color: #dc3545; }
    .event-libur { border-left-color: #ffc107; }
    .event-acara { border-left-color: #28a745; }
    .event-lainnya { border-left-color: #6c757d; }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                            @foreach($bulanList as $key => $nama)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                <h4>{{ $eventsBulanIni->count() }}</h4>
                <p class="text-muted">Total Event</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-pen fa-2x text-danger mb-2"></i>
                <h4>{{ $ujian->count() }}</h4>
                <p class="text-muted">Jadwal Ujian</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-glass-cheers fa-2x text-warning mb-2"></i>
                <h4>{{ $libur->count() }}</h4>
                <p class="text-muted">Hari Libur</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-futbol fa-2x text-success mb-2"></i>
                <h4>{{ $kegiatan->count() }}</h4>
                <p class="text-muted">Kegiatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Event Bulan Ini -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Event {{ $bulanList[$bulan] }} {{ $tahun }}
                </h5>
            </div>
            <div class="card-body">
                @if($eventsBulanIni->count() > 0)
                    <div class="row">
                        @foreach($eventsBulanIni as $event)
                            <div class="col-md-6 mb-3">
                                <div class="event-card event-{{ $event['jenis'] }} p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $event['judul'] }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                {{ \Carbon\Carbon::parse($event['tanggal_mulai'])->translatedFormat('d F Y') }}
                                                @if($event['tanggal_selesai'] && $event['tanggal_selesai'] != $event['tanggal_mulai'])
                                                    - {{ \Carbon\Carbon::parse($event['tanggal_selesai'])->translatedFormat('d F Y') }}
                                                @endif
                                            </small>
                                            @if($event['deskripsi'])
                                                <p class="small mt-2 mb-0">{{ $event['deskripsi'] }}</p>
                                            @endif
                                        </div>
                                        <span class="badge bg-{{ 
                                            $event['jenis'] == 'ujian' ? 'danger' : 
                                            ($event['jenis'] == 'libur' ? 'warning' : 
                                            ($event['jenis'] == 'acara' ? 'success' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($event['jenis']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada event pada bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ujian Mendatang -->
@if($ujian->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-pen-fancy me-2 text-danger"></i>Jadwal Ujian</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ujian as $u)
                                <tr>
                                    <td>{{ $u->judul }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($u->tanggal_mulai)->translatedFormat('d F Y') }}
                                        @if($u->tanggal_selesai && $u->tanggal_selesai != $u->tanggal_mulai)
                                            - {{ \Carbon\Carbon::parse($u->tanggal_selesai)->translatedFormat('d F Y') }}
                                        @endif
                                    </td>
                                    <td>{{ $u->deskripsi ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi DataTable jika ada
        if ($('#ujianTable').length) {
            $('#ujianTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                pageLength: 10
            });
        }
    });
</script>
@endpush
@endsection