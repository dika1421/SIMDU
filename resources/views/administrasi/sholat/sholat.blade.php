@extends('administrasi.layouts.header')

@section('title', 'Dashboard Absensi Sholat')

@section('content')
<style>
    /* ========== ANIMASI ========== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes breathe {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.05); }
    }
    @keyframes shine {
        0%   { left: -100%; }
        100% { left: 100%; }
    }
    .animate-fade-in {
        animation: fadeInUp 0.5s ease-out both;
    }

    /* ========== PAGE HEADER ========== */
    .dash-header-sholat {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .dash-header-sholat::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .dash-header-sholat::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .dash-header-sholat .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .dash-header-sholat h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .dash-header-sholat p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
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
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }
    .live-clock-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        font-family: 'Courier New', monospace;
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 1px;
    }
    .btn-refresh-glass {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-refresh-glass:hover {
        background: rgba(255,255,255,0.35);
        transform: rotate(180deg);
    }

    /* ========== STAT CARDS ========== */
    .stat-modern {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .stat-modern::before {
        content: '';
        position: absolute;
        top: -50%; right: -30px;
        width: 140px; height: 140px;
        border-radius: 50%;
        opacity: 0.08;
        background: currentColor;
    }
    .stat-modern::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.6s;
    }
    .stat-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }
    .stat-modern:hover::after {
        left: 100%;
    }
    .stat-modern .stat-body {
        position: relative;
        z-index: 2;
    }
    .stat-modern .stat-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.15rem;
        margin-bottom: 12px;
    }
    .stat-modern .stat-label-m {
        font-size: 0.72rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .stat-modern .stat-value-m {
        font-size: 1.65rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 10px;
    }
    .stat-progress {
        height: 4px;
        background: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
    }
    .stat-progress .fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.8s ease;
    }

    .stat-total  .stat-icon-wrap { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-hadir  .stat-icon-wrap { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-late   .stat-icon-wrap { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-alfa   .stat-icon-wrap { background: linear-gradient(135deg, #ef4444, #f87171); }

    .stat-total .stat-progress .fill { background: linear-gradient(90deg, #667eea, #764ba2); }
    .stat-hadir .stat-progress .fill { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-late  .stat-progress .fill { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-alfa  .stat-progress .fill { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* ========== JADWAL SHOLAT ========== */
    .jadwal-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .jadwal-header {
        padding: 14px 20px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .jadwal-header h5 {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .jadwal-header h5 i { color: #0284c7; }

    .next-prayer-badge {
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: breathe 2.4s ease-in-out infinite;
    }

    .jadwal-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1px;
        background: #f1f5f9;
    }
    .jadwal-item {
        background: #fff;
        padding: 20px 12px;
        text-align: center;
        transition: all 0.3s;
    }
    .jadwal-item:hover {
        background: #f8fafc;
        transform: scale(1.02);
    }
    .jadwal-item .j-icon {
        font-size: 1.4rem;
        margin-bottom: 8px;
    }
    .jadwal-item .j-label {
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }
    .jadwal-item .j-time {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        font-family: 'Courier New', monospace;
    }
    .jadwal-item.subuh   .j-icon { color: #4338ca; }
    .jadwal-item.dzuhur  .j-icon { color: #d97706; }
    .jadwal-item.ashar   .j-icon { color: #ea580c; }
    .jadwal-item.maghrib .j-icon { color: #dc2626; }
    .jadwal-item.isya    .j-icon { color: #7c3aed; }

    .jadwal-item.kehadiran {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
    }
    .jadwal-item.kehadiran .j-label { color: rgba(255,255,255,0.85); }
    .jadwal-item.kehadiran .j-time  { color: #fff; }
    .jadwal-item.kehadiran .j-icon  { color: #fff; }

    /* ========== SHOLAT CARDS ========== */
    .sholat-card-modern {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.3s;
    }
    .sholat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.1);
    }
    .sholat-head-modern {
        padding: 14px 18px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .sholat-head-modern::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 140px; height: 140px;
        background: rgba(255,255,255,0.12);
        border-radius: 50%;
    }
    .sholat-head-modern .head-left {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 0.92rem;
    }
    .sholat-head-modern .head-left .ico-wrap {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: rgba(255,255,255,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    .sholat-head-modern .head-right {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sholat-time-badge {
        background: rgba(255,255,255,0.22);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        font-family: 'Courier New', monospace;
        backdrop-filter: blur(8px);
    }
    .sholat-count-badge {
        background: rgba(255,255,255,0.95);
        color: #1e293b;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .sholat-subuh   .sholat-head-modern { background: linear-gradient(135deg, #1e3799, #4a69bd); }
    .sholat-dzuhur  .sholat-head-modern { background: linear-gradient(135deg, #0a8f5e, #10b981); }
    .sholat-ashar   .sholat-head-modern { background: linear-gradient(135deg, #ea580c, #f59e0b); }
    .sholat-maghrib .sholat-head-modern { background: linear-gradient(135deg, #b91c1c, #ef4444); }
    .sholat-isya    .sholat-head-modern { background: linear-gradient(135deg, #6d28d9, #a855f7); }

    .sholat-body {
        max-height: 260px;
        overflow-y: auto;
    }
    .sholat-body::-webkit-scrollbar { width: 5px; }
    .sholat-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; }
    .sholat-body::-webkit-scrollbar-track { background: #f1f5f9; }

    .absen-item {
        padding: 11px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .absen-item:hover {
        background: #f8fafc;
        border-left-color: #0ea5e9;
    }
    .absen-item:last-child {
        border-bottom: none;
    }

    .absen-avatar {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .absen-name {
        font-weight: 600;
        font-size: 0.83rem;
        color: #1e293b;
    }
    .absen-time {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-badge.tepat     { background: #d1fae5; color: #065f46; }
    .status-badge.terlambat { background: #fef3c7; color: #92400e; }
    .status-badge.tidak     { background: #fee2e2; color: #991b1b; }
    .status-badge.izin      { background: #cffafe; color: #155e75; }

    .empty-absen {
        padding: 40px 20px;
        text-align: center;
    }
    .empty-absen i {
        font-size: 2rem;
        color: #cbd5e1;
        margin-bottom: 10px;
        display: block;
    }

    /* ========== MODAL MODERN ========== */
    .modal-modern .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-modern .modal-header {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border: none;
        padding: 18px 22px;
    }
    .modal-modern .modal-title {
        font-size: 1rem;
        font-weight: 700;
    }
    .modal-modern .modal-body { padding: 22px; }
    .modal-modern .modal-footer { padding: 16px 22px 20px; border: none; }
    .modal-modern .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }
    .modal-modern .form-control,
    .modal-modern .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.85rem;
    }
    .modal-modern .form-control:focus,
    .modal-modern .form-select:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .jadwal-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 576px) {
        .dash-header-sholat { padding: 18px; }
        .dash-header-sholat h1 { font-size: 1.15rem; }
        .jadwal-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="dash-header-sholat">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-mosque me-2"></i>
                Dashboard Absensi Sholat
            </h1>
            <p>
                <i class="fas fa-calendar-alt me-1"></i>
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="header-actions">
            <div class="live-clock-glass">
                <i class="fas fa-clock"></i>
                <span id="clock">--:--:--</span>
            </div>
            <a href="{{ route('administrasi.absensi-sholat.rekap-siswa') }}" class="btn-glass">
                <i class="fas fa-user-graduate"></i> Rekap Siswa
            </a>
            <a href="{{ route('administrasi.absensi-sholat.rekap-guru') }}" class="btn-glass">
                <i class="fas fa-chalkboard-user"></i> Rekap Guru
            </a>
            <button class="btn-refresh-glass" onclick="location.reload()" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn-glass" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>
</div>

{{-- ============ FILTER MODAL ============ --}}
<div class="modal fade modal-modern" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i> Filter Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ request('tanggal', date('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="siswa" {{ request('role', 'siswa') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ STAT CARDS ============ --}}
@php
    $totalUsers = $statistik['totalUsers'] ?? 0;
    $pctTepat   = $totalUsers > 0 ? ($statistik['tepatWaktu']  / $totalUsers) * 100 : 0;
    $pctLate    = $totalUsers > 0 ? ($statistik['terlambat']   / $totalUsers) * 100 : 0;
    $pctAlfa    = $totalUsers > 0 ? ($statistik['tidakHadir']  / $totalUsers) * 100 : 0;
    $roleLabel  = request('role', 'siswa') == 'siswa' ? 'Siswa' : 'Guru';
@endphp
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-modern stat-total animate-fade-in" style="animation-delay: 0.05s">
            <div class="stat-body">
                <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
                <div class="stat-label-m">Total {{ $roleLabel }}</div>
                <div class="stat-value-m">{{ number_format($totalUsers) }}</div>
                <div class="stat-progress"><div class="fill" style="width:100%"></div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-modern stat-hadir animate-fade-in" style="animation-delay: 0.1s">
            <div class="stat-body">
                <div class="stat-icon-wrap"><i class="fas fa-check-circle"></i></div>
                <div class="stat-label-m">Tepat Waktu</div>
                <div class="stat-value-m">{{ number_format($statistik['tepatWaktu'] ?? 0) }}</div>
                <div class="stat-progress"><div class="fill" style="width:{{ $pctTepat }}%"></div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-modern stat-late animate-fade-in" style="animation-delay: 0.15s">
            <div class="stat-body">
                <div class="stat-icon-wrap"><i class="fas fa-clock"></i></div>
                <div class="stat-label-m">Terlambat</div>
                <div class="stat-value-m">{{ number_format($statistik['terlambat'] ?? 0) }}</div>
                <div class="stat-progress"><div class="fill" style="width:{{ $pctLate }}%"></div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-modern stat-alfa animate-fade-in" style="animation-delay: 0.2s">
            <div class="stat-body">
                <div class="stat-icon-wrap"><i class="fas fa-times-circle"></i></div>
                <div class="stat-label-m">Tidak Hadir</div>
                <div class="stat-value-m">{{ number_format($statistik['tidakHadir'] ?? 0) }}</div>
                <div class="stat-progress"><div class="fill" style="width:{{ $pctAlfa }}%"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- ============ JADWAL SHOLAT ============ --}}
<div class="jadwal-card animate-fade-in" style="animation-delay: 0.25s">
    <div class="jadwal-header">
        <h5>
            <i class="fas fa-clock"></i>
            Jadwal Sholat Hari Ini
        </h5>
        <span class="next-prayer-badge" id="nextPrayer">
            <i class="fas fa-hourglass-half"></i> Memuat...
        </span>
    </div>
    <div class="jadwal-grid">
        <div class="jadwal-item subuh">
            <div class="j-icon"><i class="fas fa-cloud-moon"></i></div>
            <div class="j-label">Subuh</div>
            <div class="j-time">{{ $jadwal->subuh ?? '04:30' }}</div>
        </div>
        <div class="jadwal-item dzuhur">
            <div class="j-icon"><i class="fas fa-sun"></i></div>
            <div class="j-label">Dzuhur</div>
            <div class="j-time">{{ $jadwal->dzuhur ?? '12:00' }}</div>
        </div>
        <div class="jadwal-item ashar">
            <div class="j-icon"><i class="fas fa-cloud-sun"></i></div>
            <div class="j-label">Ashar</div>
            <div class="j-time">{{ $jadwal->ashar ?? '15:30' }}</div>
        </div>
        <div class="jadwal-item maghrib">
            <div class="j-icon"><i class="fas fa-cloud-moon"></i></div>
            <div class="j-label">Maghrib</div>
            <div class="j-time">{{ $jadwal->maghrib ?? '18:00' }}</div>
        </div>
        <div class="jadwal-item isya">
            <div class="j-icon"><i class="fas fa-moon"></i></div>
            <div class="j-label">Isya</div>
            <div class="j-time">{{ $jadwal->isya ?? '19:30' }}</div>
        </div>
        <div class="jadwal-item kehadiran">
            <div class="j-icon"><i class="fas fa-chart-line"></i></div>
            <div class="j-label">Kehadiran</div>
            <div class="j-time">
                {{ $totalUsers > 0 ? round((($statistik['tepatWaktu'] + $statistik['terlambat']) / $totalUsers) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
</div>

{{-- ============ ABSENSI PER SHOLAT ============ --}}
<div class="row g-3">
    @php $sholatList = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']; @endphp
    @foreach($sholatList as $index => $sholat)
    @php
        $jamSholat = $jadwal->$sholat ?? ($sholat == 'subuh' ? '04:30' : ($sholat == 'dzuhur' ? '12:00' : ($sholat == 'ashar' ? '15:30' : ($sholat == 'maghrib' ? '18:00' : '19:30'))));
        $jumlahAbsen = isset($absensi[$sholat]) ? $absensi[$sholat]->count() : 0;
        $sholatEmoji = ['subuh'=>'🌙','dzuhur'=>'☀️','ashar'=>'🌤️','maghrib'=>'🌆','isya'=>'🌟'][$sholat];
    @endphp
    <div class="col-lg-6 col-xl-4">
        <div class="sholat-card-modern sholat-{{ $sholat }} animate-fade-in" style="animation-delay: {{ 0.3 + ($index * 0.05) }}s">
            <div class="sholat-head-modern">
                <div class="head-left">
                    <div class="ico-wrap">{{ $sholatEmoji }}</div>
                    <span>{{ ucfirst($sholat) }}</span>
                </div>
                <div class="head-right">
                    <span class="sholat-time-badge">
                        <i class="fas fa-clock"></i> {{ $jamSholat }}
                    </span>
                    <span class="sholat-count-badge">{{ $jumlahAbsen }}/{{ $totalUsers }}</span>
                </div>
            </div>
            <div class="sholat-body">
                @forelse(($absensi[$sholat] ?? []) as $absen)
                @php
                    $nama  = $role == 'siswa'
                        ? ($absen->user->user->name ?? $absen->user->nama ?? '-')
                        : ($absen->user->user->name ?? $absen->user->nama_lengkap ?? '-');
                    $initial = strtoupper(substr($nama, 0, 1));
                    $statusMap = [
                        'tepat_waktu' => ['tepat', 'fa-check-circle', 'Tepat'],
                        'terlambat'   => ['terlambat', 'fa-clock', 'Terlambat'],
                        'izin'        => ['izin', 'fa-envelope-open-text', 'Izin'],
                    ];
                    $st = $statusMap[$absen->status] ?? ['tidak', 'fa-times-circle', 'Tidak Hadir'];
                @endphp
                <div class="absen-item">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        <div class="absen-avatar">{{ $initial }}</div>
                        <div class="min-w-0">
                            <div class="absen-name text-truncate" style="max-width:160px;">{{ $nama }}</div>
                            <div class="absen-time">
                                <i class="fas fa-clock me-1"></i>
                                {{ $absen->waktu_absen ? date('H:i:s', strtotime($absen->waktu_absen)) : '-' }}
                            </div>
                        </div>
                    </div>
                    <span class="status-badge {{ $st[0] }}">
                        <i class="fas {{ $st[1] }}"></i> {{ $st[2] }}
                    </span>
                </div>
                @empty
                <div class="empty-absen">
                    <i class="fas fa-inbox"></i>
                    <div class="text-muted small">Belum ada absensi</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endforeach
</div>

@push('scripts')
<script>
    // ========== LIVE CLOCK ==========
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
        });
        const el = document.getElementById('clock');
        if (el) el.textContent = timeString;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ========== NEXT PRAYER ==========
    function getNextPrayer() {
        const now = new Date();
        const currentTime = now.getHours() * 60 + now.getMinutes();

        const prayers = [
            { name: 'Subuh',   time: '{{ $jadwal->subuh ?? "04:30" }}' },
            { name: 'Dzuhur',  time: '{{ $jadwal->dzuhur ?? "12:00" }}' },
            { name: 'Ashar',   time: '{{ $jadwal->ashar ?? "15:30" }}' },
            { name: 'Maghrib', time: '{{ $jadwal->maghrib ?? "18:00" }}' },
            { name: 'Isya',    time: '{{ $jadwal->isya ?? "19:30" }}' },
        ];

        const badge = document.getElementById('nextPrayer');
        if (!badge) return;

        for (let p of prayers) {
            const [h, m] = p.time.split(':').map(Number);
            const min = h * 60 + m;
            if (currentTime < min) {
                badge.innerHTML = '<i class="fas fa-hourglass-half"></i> Berikutnya: ' + p.name + ' ' + p.time;
                return;
            }
        }
        badge.innerHTML = '<i class="fas fa-check-circle"></i> Semua sholat hari ini telah berlalu';
    }
    getNextPrayer();

    // ========== AUTO REFRESH 60 DETIK ==========
    setTimeout(function() { location.reload(); }, 60000);
</script>
@endpush
@endsection