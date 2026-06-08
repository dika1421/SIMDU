@extends('siswa.layouts.header')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>Selamat Datang, {{ $siswa->nama_lengkap }}</h4>
                <p>Kelas: {{ $siswa->kelas->nama ?? '-' }}</p>
                <p>NIS: {{ $siswa->nis }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistik -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Rata-rata Nilai</h5>
                <h2>{{ number_format($rataNilai, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Kehadiran Bulan Ini</h5>
                <h2>{{ $persentaseKehadiran }}%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Absensi Hari Ini</h5>
                <h2>{{ $absensiHariIni->status ?? 'Belum' }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Nilai Terbaru -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Nilai Terbaru</h5>
            </div>
            <div class="card-body">
                @if($nilaiTerbaru->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            实例
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilaiTerbaru as $n)
                            <tr>
                                <td>{{ $n->mataPelajaran->nama_mapel ?? '-' }}</td>
                                <td>{{ number_format($n->nilai_akhir, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Belum ada nilai</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Event Mendatang -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Event Mendatang</h5>
            </div>
            <div class="card-body">
                @if($eventsMendatang->count() > 0)
                    @foreach($eventsMendatang as $event)
                        <div class="mb-2">
                            <strong>{{ $event->judul }}</strong><br>
                            <small class="text-muted">
                                {{ Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                            </small>
                            <p class="small">{{ $event->deskripsi }}</p>
                        </div>
                        @if(!$loop->last)<hr>@endif
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada event mendatang</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection