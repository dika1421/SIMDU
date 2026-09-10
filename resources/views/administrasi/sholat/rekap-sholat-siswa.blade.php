@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Siswa')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-rekap {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-rekap::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-rekap .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-rekap h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-rekap p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .header-btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ========== FILTER CARD ========== */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .filter-card .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    /* ========== STAT CARDS ========== */
    .stats-rekap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-rekap-modern {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-rekap-modern::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-rekap-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-rekap-modern .stat-icon-m {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-rekap-modern .stat-label-m {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-rekap-modern .stat-value-m {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .stat-siswa   .stat-icon-m { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }
    .stat-hadir   .stat-icon-m { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-late    .stat-icon-m { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-pct     .stat-icon-m { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
    .stat-siswa::before { background: linear-gradient(180deg, #0ea5e9, #06b6d4); }
    .stat-hadir::before { background: linear-gradient(180deg, #10b981, #34d399); }
    .stat-late::before  { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .stat-pct::before   { background: linear-gradient(180deg, #8b5cf6, #a78bfa); }

    /* ========== MAIN CARD ========== */
    .main-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .main-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .main-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .main-card-header h5 i { color: #0284c7; }
    .period-badge {
        background: linear-gradient(135deg, #cffafe, #a5f3fc);
        color: #155e75;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #67e8f9;
    }

    /* ========== TABLE ========== */
    .table-rekap-sholat {
        margin: 0;
        font-size: 0.8rem;
    }
    .table-rekap-sholat thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 12px 10px;
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
    }
    .table-rekap-sholat thead th.sholat-head {
        background: #f0f9ff;
        color: #0369a1;
    }
    .table-rekap-sholat tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-rekap-sholat tbody tr:hover {
        background: #f8fafc;
    }

    .avatar-siswa-sm {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .nis-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .kelas-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #f1f5f9;
        color: #334155;
        padding: 3px 9px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* ========== STATUS CHIP ========== */
    .status-chip {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 5px 10px;
        border-radius: 10px;
        min-width: 46px;
        font-weight: 700;
        font-size: 0.78rem;
    }
    .status-chip.tepat     { background: #d1fae5; color: #065f46; }
    .status-chip.terlambat { background: #fef3c7; color: #92400e; }
    .status-chip.tidak     { background: #fee2e2; color: #991b1b; }
    .status-chip.izin      { background: #cffafe; color: #155e75; }
    .status-chip.kosong    { background: #f1f5f9; color: #94a3b8; }
    .status-chip small {
        font-size: 0.62rem;
        font-weight: 500;
        opacity: 0.75;
    }

    /* ========== PERSENTASE ========== */
    .persentase-wrap { min-width: 100px; }
    .persentase-value {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 3px;
        display: block;
    }
    .persentase-value.high { color: #059669; }
    .persentase-value.mid  { color: #d97706; }
    .persentase-value.low  { color: #dc2626; }
    .persentase-bar {
        height: 5px;
        background: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
    }
    .persentase-bar .fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    .persentase-bar .fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .persentase-bar .fill.mid  { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .persentase-bar .fill.low  { background: linear-gradient(90deg, #ef4444, #f87171); }

    .ket-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        min-width: 60px;
    }
    .ket-badge.high { background: #d1fae5; color: #065f46; }
    .ket-badge.mid  { background: #fef3c7; color: #92400e; }
    .ket-badge.low  { background: #fee2e2; color: #991b1b; }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        font-size: 2.5rem;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-rekap { padding: 18px; }
        .page-header-rekap h1 { font-size: 1.15rem; }
        .table-rekap-sholat { font-size: 0.72rem; }
        .table-rekap-sholat tbody td { padding: 9px 6px; }
    }
</style>

@php
    $bulanInt = (int)(request('bulan', $bulan ?? date('m')));
    $bulanName = \Carbon\Carbon::create(null, $bulanInt, 1)->translatedFormat('F');
    $tahunVal = request('tahun', $tahun ?? date('Y'));
    $kelasFilter = request('kelas_id') ? (\App\Models\Kelas::find(request('kelas_id'))->nama ?? '') : null;
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-rekap">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-chart-bar me-2"></i>
                Rekap Absensi Sholat Siswa
            </h1>
            <p>
                <i class="fas fa-calendar-alt me-1"></i>
                Periode {{ $bulanName }} {{ $tahunVal }}
                @if($kelasFilter) · Kelas {{ $kelasFilter }} @endif
            </p>
        </div>
        <div class="header-btn-group">
            <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn-glass">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn-glass">
                <i class="fas fa-print"></i> Print
            </button>
            <button onclick="exportToExcel()" class="btn-glass">
                <i class="fas fa-file-excel"></i> Excel
            </button>
        </div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" class="row g-3 align-items-end" id="filterForm">
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-school"></i> Kelas
            </label>
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
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Bulan
            </label>
            <select name="bulan" class="form-select">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulanInt == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-calendar"></i> Tahun
            </label>
            <select name="tahun" class="form-select">
                @for($i = date('Y')-2; $i <= date('Y')+1; $i++)
                    <option value="{{ $i }}" {{ $tahunVal == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari NIS/Nama
            </label>
            <input type="text" name="search" class="form-control" placeholder="NIS / Nama" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-3 fw-semibold flex-grow-1" style="padding:9px">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
                <a href="{{ route('administrasi.absensi-sholat.rekap-siswa') }}"
                   class="btn btn-secondary rounded-3" style="padding:9px" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ============ STAT CARDS ============ --}}
<div class="stats-rekap">
    <div class="stat-rekap-modern stat-siswa">
        <div class="stat-icon-m"><i class="fas fa-users"></i></div>
        <div class="stat-label-m">Total Siswa</div>
        <div class="stat-value-m">{{ $siswa->count() ?? 0 }}</div>
    </div>
    <div class="stat-rekap-modern stat-hadir">
        <div class="stat-icon-m"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label-m">Total Kehadiran</div>
        <div class="stat-value-m">{{ $siswa->sum('total_hadir') ?? 0 }}</div>
    </div>
    <div class="stat-rekap-modern stat-late">
        <div class="stat-icon-m"><i class="fas fa-clock"></i></div>
        <div class="stat-label-m">Total Terlambat</div>
        <div class="stat-value-m">{{ $siswa->sum('total_terlambat') ?? 0 }}</div>
    </div>
    <div class="stat-rekap-modern stat-pct">
        <div class="stat-icon-m"><i class="fas fa-percentage"></i></div>
        <div class="stat-label-m">Rata-rata Kehadiran</div>
        <div class="stat-value-m">{{ number_format(($siswa->avg('persentase') ?? 0), 1) }}%</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-table"></i>
            Tabel Rekap Absensi Sholat
        </h5>
        <div class="period-badge">
            <i class="fas fa-calendar-week"></i>
            {{ $bulanName }} {{ $tahunVal }}
            @if($kelasFilter) · {{ $kelasFilter }} @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-rekap-sholat mb-0" id="rekapTable">
            <thead>
                <tr>
                    <th rowspan="2" width="4%">No</th>
                    <th rowspan="2" width="9%">NIS</th>
                    <th rowspan="2" width="18%">Nama Siswa</th>
                    <th rowspan="2" width="10%">Kelas</th>
                    <th colspan="5" class="sholat-head">Status Kehadiran Sholat</th>
                    <th rowspan="2" width="8%">Hadir</th>
                    <th rowspan="2" width="12%">Persentase</th>
                    <th rowspan="2" width="9%">Keterangan</th>
                </tr>
                <tr>
                    <th class="sholat-head" width="7%">🌙 Subuh</th>
                    <th class="sholat-head" width="7%">☀️ Dzuhur</th>
                    <th class="sholat-head" width="7%">🌤️ Ashar</th>
                    <th class="sholat-head" width="7%">🌆 Maghrib</th>
                    <th class="sholat-head" width="7%">🌟 Isya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa ?? [] as $index => $s)
                @php
                    $persentase = $s->persentase ?? 0;
                    $level = $persentase >= 80 ? 'high' : ($persentase >= 60 ? 'mid' : 'low');
                    $keterangan = $persentase >= 80 ? 'Baik' : ($persentase >= 60 ? 'Cukup' : 'Kurang');
                    $nama = $s->user->name ?? $s->nama ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));

                    $statuses = [
                        'subuh'   => $s->absensi->where('sholat', 'subuh')->first(),
                        'dzuhur'  => $s->absensi->where('sholat', 'dzuhur')->first(),
                        'ashar'   => $s->absensi->where('sholat', 'ashar')->first(),
                        'maghrib' => $s->absensi->where('sholat', 'maghrib')->first(),
                        'isya'    => $s->absensi->where('sholat', 'isya')->first(),
                    ];
                    $map = [
                        'tepat_waktu' => ['tepat',     '✓', 'Tepat'],
                        'terlambat'   => ['terlambat', '⚠', 'Telat'],
                        'izin'        => ['izin',      '📩', 'Izin'],
                    ];
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <span class="nis-badge">{{ $s->nis ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-siswa-sm">{{ $initial }}</div>
                            <div>
                                <div class="fw-semibold text-dark text-truncate" style="max-width:170px;">
                                    {{ $nama }}
                                </div>
                                <small class="text-muted">{{ $s->nisn ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="kelas-badge">
                            <i class="fas fa-school"></i>
                            {{ $s->kelas->nama ?? '-' }}
                        </span>
                    </td>

                    @foreach($statuses as $sholat => $ab)
                    <td class="text-center">
                        @if($ab)
                            @php $st = $map[$ab->status] ?? ['tidak', '✗', 'Absen']; @endphp
                            <span class="status-chip {{ $st[0] }}">
                                <span>{{ $st[1] }}</span>
                                <small>{{ $ab->waktu_absen ? date('H:i', strtotime($ab->waktu_absen)) : '' }}</small>
                            </span>
                        @else
                            <span class="status-chip kosong">—</span>
                        @endif
                    </td>
                    @endforeach

                    <td class="text-center">
                        <span class="status-chip tepat" style="background:#dbeafe;color:#1e40af;">
                            {{ $s->total_hadir ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <div class="persentase-wrap">
                            <span class="persentase-value {{ $level }}">
                                {{ number_format($persentase, 1) }}%
                            </span>
                            <div class="persentase-bar">
                                <div class="fill {{ $level }}" style="width: {{ $persentase }}%;"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="ket-badge {{ $level }}">{{ $keterangan }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data absensi</h5>
                            <p class="text-muted mb-0">
                                Data absensi sholat akan muncul setelah siswa melakukan absensi pada periode ini
                            </p>
                        </div>
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
        const params = new URLSearchParams(window.location.search);
        window.location.href = "{{ route('administrasi.absensi-sholat.export-siswa') }}?" + params.toString();
    }
</script>
@endpush
@endsection