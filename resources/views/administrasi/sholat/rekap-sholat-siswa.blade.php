@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Siswa')

@section('content')
<style>
    .progress-custom {
        height: 8px;
        border-radius: 4px;
    }
    .badge-count {
        font-size: 13px;
        padding: 5px 10px;
    }
    .table-rekap th {
        background: #f8f9fa;
        vertical-align: middle;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        transition: transform 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-number {
        font-size: 32px;
        font-weight: bold;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        Rekap Absensi Sholat Siswa
    </h1>
    <div class="btn-toolbar">
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-success ms-2">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="exportToExcel()" class="btn btn-sm btn-primary ms-2">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-filter me-2"></i> Filter Data
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList ?? [] as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id', $kelasId ?? '') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                    @php
                        $bulanOptions = \Carbon\Carbon::create(null, $i, 1);
                    @endphp
                    <option value="{{ $i }}" {{ request('bulan', $bulan ?? date('m')) == $i ? 'selected' : '' }}>
                        {{ $bulanOptions->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($i = date('Y')-2; $i <= date('Y')+1; $i++)
                    <option value="{{ $i }}" {{ request('tahun', $tahun ?? date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Cari NIS/Nama</label>
                <input type="text" name="search" class="form-control" placeholder="NIS / Nama" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-users fa-2x mb-2"></i>
            <div class="stats-number">{{ $siswa->count() ?? 0 }}</div>
            <small>Total Siswa</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <i class="fas fa-check-circle fa-2x mb-2"></i>
            <div class="stats-number">{{ $siswa->sum('total_hadir') ?? 0 }}</div>
            <small>Total Kehadiran</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
            <i class="fas fa-clock fa-2x mb-2"></i>
            <div class="stats-number">{{ $siswa->sum('total_terlambat') ?? 0 }}</div>
            <small>Total Terlambat</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #138496);">
            <i class="fas fa-calendar-check fa-2x mb-2"></i>
            <div class="stats-number">{{ number_format(($siswa->avg('persentase') ?? 0), 1) }}%</div>
            <small>Rata-rata Kehadiran</small>
        </div>
    </div>
</div>

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-table me-2"></i> 
        Rekap Absensi Sholat 
        Bulan 
        @php
            $bulanName = '';
            $bulanInt = (int)(request('bulan', $bulan ?? date('m')));
            if($bulanInt == 1) $bulanName = 'Januari';
            elseif($bulanInt == 2) $bulanName = 'Februari';
            elseif($bulanInt == 3) $bulanName = 'Maret';
            elseif($bulanInt == 4) $bulanName = 'April';
            elseif($bulanInt == 5) $bulanName = 'Mei';
            elseif($bulanInt == 6) $bulanName = 'Juni';
            elseif($bulanInt == 7) $bulanName = 'Juli';
            elseif($bulanInt == 8) $bulanName = 'Agustus';
            elseif($bulanInt == 9) $bulanName = 'September';
            elseif($bulanInt == 10) $bulanName = 'Oktober';
            elseif($bulanInt == 11) $bulanName = 'November';
            else $bulanName = 'Desember';
        @endphp
        {{ $bulanName }}
        {{ request('tahun', $tahun ?? date('Y')) }}
        @if(request('kelas_id'))
            - Kelas {{ \App\Models\Kelas::find(request('kelas_id'))->nama ?? '' }}
        @endif
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-rekap" id="rekapTable">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" width="5%">No</th>
                    <th rowspan="2" width="10%">NIS</th>
                    <th rowspan="2" width="20%">Nama Siswa</th>
                    <th rowspan="2" width="15%">Kelas</th>
                    <th colspan="5" class="text-center">Status Kehadiran Sholat</th>
                    <th rowspan="2" class="text-center" width="10%">Total Hadir</th>
                    <th rowspan="2" class="text-center" width="10%">Persentase</th>
                    <th rowspan="2" class="text-center" width="10%">Keterangan</th>
                </tr>
                <tr>
                    <th class="text-center">Subuh</th>
                    <th class="text-center">Dzuhur</th>
                    <th class="text-center">Ashar</th>
                    <th class="text-center">Maghrib</th>
                    <th class="text-center">Isya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa ?? [] as $index => $s)
                @php
                    $persentase = $s->persentase ?? 0;
                    $warna = $persentase >= 80 ? 'success' : ($persentase >= 60 ? 'warning' : 'danger');
                    $keterangan = $persentase >= 80 ? 'Baik' : ($persentase >= 60 ? 'Cukup' : 'Kurang');
                    
                    // Ambil status per sholat
                    $statusSubuh = $s->absensi->where('sholat', 'subuh')->first();
                    $statusDzuhur = $s->absensi->where('sholat', 'dzuhur')->first();
                    $statusAshar = $s->absensi->where('sholat', 'ashar')->first();
                    $statusMaghrib = $s->absensi->where('sholat', 'maghrib')->first();
                    $statusIsya = $s->absensi->where('sholat', 'isya')->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $s->nis ?? '-' }}</td>
                    <td>
                        <strong>{{ $s->user->name ?? $s->nama ?? '-' }}</strong>
                        <br>
                        <small class="text-muted">{{ $s->nisn ?? '-' }}</small>
                    </td>
                    <td>{{ $s->kelas->nama ?? '-' }}</td>
                    
                    <!-- Subuh -->
                    <td class="text-center">
                        @if($statusSubuh)
                            <span class="badge {{ $statusSubuh->status == 'tepat_waktu' ? 'bg-success' : ($statusSubuh->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $statusSubuh->status == 'tepat_waktu' ? '✓' : ($statusSubuh->status == 'terlambat' ? '⚠' : '✗') }}
                            </span>
                            <br>
                            <small>{{ $statusSubuh->waktu_absen ? date('H:i', strtotime($statusSubuh->waktu_absen)) : '-' }}</small>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    
                    <!-- Dzuhur -->
                    <td class="text-center">
                        @if($statusDzuhur)
                            <span class="badge {{ $statusDzuhur->status == 'tepat_waktu' ? 'bg-success' : ($statusDzuhur->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $statusDzuhur->status == 'tepat_waktu' ? '✓' : ($statusDzuhur->status == 'terlambat' ? '⚠' : '✗') }}
                            </span>
                            <br>
                            <small>{{ $statusDzuhur->waktu_absen ? date('H:i', strtotime($statusDzuhur->waktu_absen)) : '-' }}</small>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    
                    <!-- Ashar -->
                    <td class="text-center">
                        @if($statusAshar)
                            <span class="badge {{ $statusAshar->status == 'tepat_waktu' ? 'bg-success' : ($statusAshar->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $statusAshar->status == 'tepat_waktu' ? '✓' : ($statusAshar->status == 'terlambat' ? '⚠' : '✗') }}
                            </span>
                            <br>
                            <small>{{ $statusAshar->waktu_absen ? date('H:i', strtotime($statusAshar->waktu_absen)) : '-' }}</small>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    
                    <!-- Maghrib -->
                    <td class="text-center">
                        @if($statusMaghrib)
                            <span class="badge {{ $statusMaghrib->status == 'tepat_waktu' ? 'bg-success' : ($statusMaghrib->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $statusMaghrib->status == 'tepat_waktu' ? '✓' : ($statusMaghrib->status == 'terlambat' ? '⚠' : '✗') }}
                            </span>
                            <br>
                            <small>{{ $statusMaghrib->waktu_absen ? date('H:i', strtotime($statusMaghrib->waktu_absen)) : '-' }}</small>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    
                    <!-- Isya -->
                    <td class="text-center">
                        @if($statusIsya)
                            <span class="badge {{ $statusIsya->status == 'tepat_waktu' ? 'bg-success' : ($statusIsya->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $statusIsya->status == 'tepat_waktu' ? '✓' : ($statusIsya->status == 'terlambat' ? '⚠' : '✗') }}
                            </span>
                            <br>
                            <small>{{ $statusIsya->waktu_absen ? date('H:i', strtotime($statusIsya->waktu_absen)) : '-' }}</small>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        <span class="badge bg-primary badge-count">{{ $s->total_hadir ?? 0 }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2 progress-custom">
                                <div class="progress-bar bg-{{ $warna }}" style="width: {{ $persentase }}%"></div>
                            </div>
                            <small>{{ $persentase }}%</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $warna }}">{{ $keterangan }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                        Belum ada data absensi sholat untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function exportToExcel() {
        let params = new URLSearchParams(window.location.search);
        window.location.href = "{{ route('administrasi.absensi-sholat.export-siswa') }}?" + params.toString();
    }
</script>
@endpush
@endsection