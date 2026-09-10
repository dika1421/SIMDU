@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Siswa')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-rekap {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-rekap::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-rekap::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.05);
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
        opacity: 0.9;
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
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    /* ========== STAT CARDS ========== */
    .stats-rekap {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-rekap-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-rekap-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    .stat-rekap-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-rekap-card .stat-icon-s {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    .stat-rekap-card .stat-label-s {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-rekap-card .stat-value-s {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .stat-rekap-card.stat-hadir     .stat-icon-s { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-rekap-card.stat-sakit     .stat-icon-s { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-rekap-card.stat-izin      .stat-icon-s { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
    .stat-rekap-card.stat-alfa      .stat-icon-s { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-rekap-card.stat-terlambat .stat-icon-s { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
    .stat-rekap-card.stat-total     .stat-icon-s { background: linear-gradient(135deg, #667eea, #764ba2); }

    .stat-rekap-card.stat-hadir::before     { background: linear-gradient(180deg, #10b981, #34d399); }
    .stat-rekap-card.stat-sakit::before     { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .stat-rekap-card.stat-izin::before      { background: linear-gradient(180deg, #06b6d4, #22d3ee); }
    .stat-rekap-card.stat-alfa::before      { background: linear-gradient(180deg, #ef4444, #f87171); }
    .stat-rekap-card.stat-terlambat::before { background: linear-gradient(180deg, #8b5cf6, #a78bfa); }
    .stat-rekap-card.stat-total::before     { background: linear-gradient(180deg, #667eea, #764ba2); }

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
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
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
    }
    .period-badge {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: #4338ca;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #c7d2fe;
    }

    /* ========== TABLE ========== */
    .table-rekap {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-rekap thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 14px 10px;
        white-space: nowrap;
    }
    .table-rekap tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-rekap tbody tr { transition: all 0.2s; }
    .table-rekap tbody tr:hover { background: #f8fafc; }
    .table-rekap tbody tr:last-child td { border-bottom: none; }

    .avatar-siswa-small {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .nis-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .stat-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0 8px;
    }
    .stat-counter.hadir     { background: #d1fae5; color: #065f46; }
    .stat-counter.sakit     { background: #fef3c7; color: #92400e; }
    .stat-counter.izin      { background: #cffafe; color: #155e75; }
    .stat-counter.alfa      { background: #fee2e2; color: #991b1b; }
    .stat-counter.terlambat { background: #ede9fe; color: #5b21b6; }
    .stat-counter.total     { background: #f1f5f9; color: #334155; }

    /* ========== PERSENTASE ========== */
    .persentase-wrap { min-width: 80px; }
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
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 2.5rem;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-rekap { padding: 18px; }
        .page-header-rekap h1 { font-size: 1.15rem; }
        .table-rekap { font-size: 0.75rem; }
        .table-rekap tbody td { padding: 10px 6px; }
        .stats-rekap { grid-template-columns: repeat(2, 1fr); }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-rekap">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-chart-line me-2"></i>
                Rekap Absensi Siswa
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Statistik kehadiran siswa per bulan berdasarkan data absensi
            </p>
        </div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-calendar-alt me-1"></i> Bulan
            </label>
            <select name="bulan" class="form-select">
                @foreach($bulanList as $b => $nama)
                    <option value="{{ $b }}" {{ ($bulan ?? date('m')) == $b ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-calendar me-1"></i> Tahun
            </label>
            <select name="tahun" class="form-select">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ ($tahun ?? date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-school me-1"></i> Kelas
            </label>
            <select name="kelas_id" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ ($kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama ?? $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">
                <i class="fas fa-filter me-1"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

{{-- ============ STAT CARDS ========== --}}
@php
    $tHadir = 0; $tSakit = 0; $tIzin = 0; $tAlfa = 0; $tTerlambat = 0; $tTotal = 0;
    foreach(($statistik ?? []) as $st) {
        $tHadir     += $st['hadir'] ?? 0;
        $tSakit     += $st['sakit'] ?? 0;
        $tIzin      += $st['izin'] ?? 0;
        $tAlfa      += $st['alfa'] ?? 0;
        $tTerlambat += $st['terlambat'] ?? 0;
        $tTotal     += $st['total'] ?? 0;
    }
@endphp
<div class="stats-rekap">
    <div class="stat-rekap-card stat-hadir">
        <div class="stat-icon-s"><i class="fas fa-user-check"></i></div>
        <div class="stat-label-s">Total Hadir</div>
        <div class="stat-value-s">{{ $tHadir }}</div>
    </div>
    <div class="stat-rekap-card stat-sakit">
        <div class="stat-icon-s"><i class="fas fa-thermometer-half"></i></div>
        <div class="stat-label-s">Total Sakit</div>
        <div class="stat-value-s">{{ $tSakit }}</div>
    </div>
    <div class="stat-rekap-card stat-izin">
        <div class="stat-icon-s"><i class="fas fa-envelope-open-text"></i></div>
        <div class="stat-label-s">Total Izin</div>
        <div class="stat-value-s">{{ $tIzin }}</div>
    </div>
    <div class="stat-rekap-card stat-alfa">
        <div class="stat-icon-s"><i class="fas fa-user-times"></i></div>
        <div class="stat-label-s">Total Alfa</div>
        <div class="stat-value-s">{{ $tAlfa }}</div>
    </div>
    <div class="stat-rekap-card stat-terlambat">
        <div class="stat-icon-s"><i class="fas fa-clock"></i></div>
        <div class="stat-label-s">Terlambat</div>
        <div class="stat-value-s">{{ $tTerlambat }}</div>
    </div>
    <div class="stat-rekap-card stat-total">
        <div class="stat-icon-s"><i class="fas fa-database"></i></div>
        <div class="stat-label-s">Total Record</div>
        <div class="stat-value-s">{{ $tTotal }}</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <div>
            <h5>
                <i class="fas fa-table me-2 text-primary"></i>
                Tabel Rekap Kehadiran Siswa
            </h5>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <div class="period-badge">
                <i class="fas fa-calendar-week"></i>
                {{ $bulanList[$bulan ?? date('m')] }} {{ $tahun ?? date('Y') }}
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                <i class="fas fa-users me-1"></i> {{ $siswa->count() ?? 0 }} Siswa
            </span>
        </div>
    </div>

    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-rekap table-hover mb-0" id="rekapTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="22%">Nama</th>
                        <th width="10%">Kelas</th>
                        <th width="8%" class="text-center">Hadir</th>
                        <th width="8%" class="text-center">Sakit</th>
                        <th width="8%" class="text-center">Izin</th>
                        <th width="8%" class="text-center">Alfa</th>
                        <th width="8%" class="text-center">Terlambat</th>
                        <th width="8%" class="text-center">Total</th>
                        <th width="10%" class="text-center">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                        @php
                            $stats = $statistik[$s->id] ?? [
                                'hadir' => 0, 'sakit' => 0, 'izin' => 0,
                                'alfa' => 0, 'terlambat' => 0,
                                'total' => 0, 'persentase' => 0
                            ];
                            $persentase = $stats['persentase'];
                            $level = $persentase >= 90 ? 'high' : ($persentase >= 75 ? 'mid' : 'low');
                            $initial = strtoupper(substr($s->user->name ?? $s->nama_lengkap ?? 'S', 0, 1));
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="nis-badge">{{ $s->nis ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-siswa-small">{{ $initial }}</div>
                                    <span class="fw-semibold text-dark text-truncate" style="max-width: 180px;">
                                        {{ $s->user->name ?? $s->nama_lengkap ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                                    {{ $s->kelas->nama ?? $s->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter hadir">{{ $stats['hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter sakit">{{ $stats['sakit'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter izin">{{ $stats['izin'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter alfa">{{ $stats['alfa'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter terlambat">{{ $stats['terlambat'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="stat-counter total">{{ $stats['total'] }}</span>
                            </td>
                            <td>
                                <div class="persentase-wrap">
                                    <span class="persentase-value {{ $level }}">
                                        {{ number_format($persentase, 2) }}%
                                    </span>
                                    <div class="persentase-bar">
                                        <div class="fill {{ $level }}" style="width: {{ $persentase }}%;"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Belum ada data absensi</h5>
                                <p class="text-muted mb-0">
                                    Data absensi akan muncul setelah siswa melakukan absensi pada periode ini
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        if ($('#rekapTable tbody tr').length > 1) {
            $('#rekapTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [4,5,6,7,8,9,10] }
                ]
            });
        }
    });
</script>
@endpush
@endsection