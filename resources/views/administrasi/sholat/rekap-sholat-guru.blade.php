@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Guru')

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
    .header-btn-group { display: flex; gap: 8px; flex-wrap: wrap; }
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

    /* ========== STAT CARDS ========== */
    .stats-rekap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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
        font-size: 0.68rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-rekap-modern .stat-value-m {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .stat-guru     .stat-icon-m { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }
    .stat-hadir    .stat-icon-m { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-tepat    .stat-icon-m { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-late     .stat-icon-m { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-izin     .stat-icon-m { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
    .stat-alfa     .stat-icon-m { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-pct      .stat-icon-m { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
    .stat-guru::before  { background: linear-gradient(180deg, #0ea5e9, #06b6d4); }
    .stat-hadir::before { background: linear-gradient(180deg, #10b981, #34d399); }
    .stat-tepat::before { background: linear-gradient(180deg, #059669, #10b981); }
    .stat-late::before  { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .stat-izin::before  { background: linear-gradient(180deg, #06b6d4, #22d3ee); }
    .stat-alfa::before  { background: linear-gradient(180deg, #ef4444, #f87171); }
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

    /* ========== TABLE ========== */
    .table-rekap-guru {
        margin: 0;
        font-size: 0.82rem;
    }
    .table-rekap-guru thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 13px 10px;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }
    .table-rekap-guru thead th.text-start { text-align: left; }
    .table-rekap-guru tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-rekap-guru tbody tr:hover {
        background: #f8fafc;
    }

    .avatar-guru-sm {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.82rem;
        flex-shrink: 0;
    }
    .nip-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .stat-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 30px;
        border-radius: 9px;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 0 10px;
    }
    .stat-counter.hadir  { background: #dbeafe; color: #1e40af; }
    .stat-counter.tepat  { background: #d1fae5; color: #065f46; }
    .stat-counter.late   { background: #fef3c7; color: #92400e; }
    .stat-counter.izin   { background: #cffafe; color: #155e75; }
    .stat-counter.alfa   { background: #fee2e2; color: #991b1b; }

    /* ========== PERSENTASE ========== */
    .persentase-wrap { min-width: 110px; }
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
        .table-rekap-guru { font-size: 0.72rem; }
        .table-rekap-guru tbody td { padding: 9px 6px; }
    }
</style>

@php
    $totalGuru = $guru->count() ?? 0;
    $sumHadir = $guru->sum('total_hadir') ?? 0;
    $sumTepat = $guru->sum('total_tepat_waktu') ?? 0;
    $sumLate  = $guru->sum('total_terlambat') ?? 0;
    $sumIzin  = $guru->sum('total_izin') ?? 0;
    $sumAlfa  = $guru->sum('total_tidak_hadir') ?? 0;

    $avgPct = 0;
    if ($totalGuru > 0) {
        $totalPct = 0;
        foreach ($guru as $item) {
            $tot = ($item->total_hadir ?? 0) + ($item->total_izin ?? 0) + ($item->total_tidak_hadir ?? 0);
            $totalPct += $tot > 0 ? (($item->total_hadir ?? 0) / $tot * 100) : 0;
        }
        $avgPct = $totalPct / $totalGuru;
    }
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-rekap">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-chart-bar me-2"></i>
                Rekap Absensi Sholat Guru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Statistik kehadiran sholat 5 waktu untuk guru
            </p>
        </div>
        <div class="header-btn-group">
            <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn-glass">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn-glass">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

{{-- ============ STAT CARDS ============ --}}
<div class="stats-rekap">
    <div class="stat-rekap-modern stat-guru">
        <div class="stat-icon-m"><i class="fas fa-chalkboard-user"></i></div>
        <div class="stat-label-m">Total Guru</div>
        <div class="stat-value-m">{{ $totalGuru }}</div>
    </div>
    <div class="stat-rekap-modern stat-hadir">
        <div class="stat-icon-m"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label-m">Total Hadir</div>
        <div class="stat-value-m">{{ $sumHadir }}</div>
    </div>
    <div class="stat-rekap-modern stat-tepat">
        <div class="stat-icon-m"><i class="fas fa-star"></i></div>
        <div class="stat-label-m">Tepat Waktu</div>
        <div class="stat-value-m">{{ $sumTepat }}</div>
    </div>
    <div class="stat-rekap-modern stat-late">
        <div class="stat-icon-m"><i class="fas fa-clock"></i></div>
        <div class="stat-label-m">Terlambat</div>
        <div class="stat-value-m">{{ $sumLate }}</div>
    </div>
    <div class="stat-rekap-modern stat-izin">
        <div class="stat-icon-m"><i class="fas fa-envelope-open-text"></i></div>
        <div class="stat-label-m">Izin</div>
        <div class="stat-value-m">{{ $sumIzin }}</div>
    </div>
    <div class="stat-rekap-modern stat-alfa">
        <div class="stat-icon-m"><i class="fas fa-user-times"></i></div>
        <div class="stat-label-m">Tidak Hadir</div>
        <div class="stat-value-m">{{ $sumAlfa }}</div>
    </div>
    <div class="stat-rekap-modern stat-pct">
        <div class="stat-icon-m"><i class="fas fa-percentage"></i></div>
        <div class="stat-label-m">Rata-rata Kehadiran</div>
        <div class="stat-value-m">{{ number_format($avgPct, 1) }}%</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-table"></i>
            Tabel Rekap Absensi Sholat Guru
        </h5>
        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
            <i class="fas fa-users me-1"></i> {{ $totalGuru }} Guru
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-rekap-guru mb-0">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="20%" class="text-start">Nama Guru</th>
                    <th width="12%">NIP</th>
                    <th width="8%">Hadir</th>
                    <th width="9%">Tepat Waktu</th>
                    <th width="9%">Terlambat</th>
                    <th width="8%">Izin</th>
                    <th width="9%">Tidak Hadir</th>
                    <th width="21%">Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guru as $index => $item)
                @php
                    $nama = $item->user->name ?? $item->nama_lengkap ?? '-';
                    $initial = strtoupper(substr($nama, 0, 1));

                    $total = ($item->total_hadir ?? 0) + ($item->total_izin ?? 0) + ($item->total_tidak_hadir ?? 0);
                    $persen = $total > 0 ? round(($item->total_hadir ?? 0) / $total * 100, 1) : 0;
                    $level = $persen >= 80 ? 'high' : ($persen >= 60 ? 'mid' : 'low');
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-guru-sm">{{ $initial }}</div>
                            <span class="fw-semibold text-dark text-truncate" style="max-width:200px;">
                                {{ $nama }}
                            </span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="nip-badge">{{ $item->nip ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stat-counter hadir">{{ $item->total_hadir ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stat-counter tepat">{{ $item->total_tepat_waktu ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stat-counter late">{{ $item->total_terlambat ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stat-counter izin">{{ $item->total_izin ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stat-counter alfa">{{ $item->total_tidak_hadir ?? 0 }}</span>
                    </td>
                    <td>
                        <div class="persentase-wrap">
                            <span class="persentase-value {{ $level }}">{{ number_format($persen, 1) }}%</span>
                            <div class="persentase-bar">
                                <div class="fill {{ $level }}" style="width: {{ $persen }}%;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chalkboard-user"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data absensi sholat guru</h5>
                            <p class="text-muted mb-0">
                                Data akan muncul setelah guru melakukan absensi sholat
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection