@extends('guru.layouts.header')

@section('title', 'Raport Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt me-2"></i>
        Raport Siswa - {{ $kelas->nama }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.nilai.raport') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn btn-sm btn-success ms-2" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
</div>

<!-- Informasi Kelas -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Kelas:</strong> {{ $kelas->nama }} | 
    <strong>Jurusan:</strong> {{ $kelas->jurusan->nama ?? '-' }} | 
    <strong>Wali Kelas:</strong> {{ $kelas->waliKelas->user->name ?? '-' }}
</div>

<!-- Tabel Raport -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Nilai Siswa</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">NIS</th>
                        <th rowspan="2">Nama Siswa</th>
                        @foreach($mapel as $m)
                        <th colspan="4" class="text-center">{{ $m->nama }}</th>
                        @endforeach
                        <th rowspan="2">Rata-rata</th>
                    </tr>
                    <tr>
                        @foreach($mapel as $m)
                        <th>Tgs</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Akhir</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $s->nis }}</td>
                        <td><strong>{{ $s->user->name }}</strong></td>
                        @php
                            $totalNilai = 0;
                            $jumlahMapel = 0;
                        @endphp
                        @foreach($mapel as $m)
                            @php
                                $nilai = $dataNilai[$s->id][$m->id] ?? ['tugas' => '-', 'uts' => '-', 'uas' => '-', 'akhir' => '-'];
                                if ($nilai['akhir'] !== '-') {
                                    $totalNilai += $nilai['akhir'];
                                    $jumlahMapel++;
                                }
                            @endphp
                            <td class="text-center">{{ $nilai['tugas'] }}</td>
                            <td class="text-center">{{ $nilai['uts'] }}</td>
                            <td class="text-center">{{ $nilai['uas'] }}</td>
                            <td class="text-center"><strong>{{ $nilai['akhir'] }}</strong></td>
                        @endforeach
                        <td class="text-center">
                            <strong>
                                @if($jumlahMapel > 0)
                                    {{ round($totalNilai / $jumlahMapel, 2) }}
                                @else
                                    -
                                @endif
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection