@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Guru')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-abs {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(17, 153, 142, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-abs::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-abs::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-abs .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-abs h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-abs p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.22);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
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
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .filter-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .filter-card .form-control:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.1);
    }

    /* ========== STATS MINI ========== */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }
    .stat-mini-card {
        background: #fff;
        border-radius: 12px;
        padding: 14px 16px;
        border: 1px solid #f1f5f9;
        border-left: 4px solid;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        transition: all 0.25s;
    }
    .stat-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }
    .stat-mini-card .label {
        font-size: 0.68rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 4px;
    }
    .stat-mini-card .value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .stat-mini-card.border-hadir  { border-left-color: #10b981; }
    .stat-mini-card.border-sakit  { border-left-color: #f59e0b; }
    .stat-mini-card.border-izin   { border-left-color: #06b6d4; }
    .stat-mini-card.border-alfa   { border-left-color: #ef4444; }
    .stat-mini-card.border-total  { border-left-color: #11998e; }

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
    .date-badge {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #6ee7b7;
    }

    /* ========== QUICK ACTIONS ========== */
    .quick-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .quick-actions .btn-quick {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.72rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .quick-actions .btn-quick:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .quick-actions .btn-quick.btn-all-hadir {
        background: #d1fae5;
        border-color: #a7f3d0;
        color: #065f46;
    }
    .quick-actions .btn-quick.btn-all-hadir:hover { background: #a7f3d0; }
    .quick-actions .btn-quick.btn-reset {
        background: #fee2e2;
        border-color: #fecaca;
        color: #991b1b;
    }
    .quick-actions .btn-quick.btn-reset:hover { background: #fecaca; }

    /* ========== TABLE MODERN ========== */
    .table-absensi {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-absensi thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 14px 12px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .table-absensi tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-absensi tbody tr {
        transition: all 0.2s;
    }
    .table-absensi tbody tr:hover {
        background: #f8fafc;
    }
    .table-absensi tbody tr.row-filled {
        background: #f0fdf4;
    }
    .table-absensi tbody tr.row-filled:hover { background: #ecfdf5; }
    .table-absensi tbody tr.row-filled.status-sakit     { background: #fffbeb; }
    .table-absensi tbody tr.row-filled.status-izin      { background: #ecfeff; }
    .table-absensi tbody tr.row-filled.status-alfa      { background: #fef2f2; }
    .table-absensi tbody tr.row-filled.status-terlambat { background: #fffbeb; }

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .teacher-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .teacher-nip {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .mapel-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #e0f2fe;
        color: #0369a1;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 600;
        border: 1px solid #bae6fd;
    }

    /* ========== STATUS SELECT ========== */
    .status-select {
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 7px 10px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s;
        cursor: pointer;
        background: #fff;
    }
    .status-select:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.12);
        outline: none;
    }
    .status-select[data-status="hadir"]     { background: #d1fae5; border-color: #a7f3d0; color: #065f46; }
    .status-select[data-status="sakit"]     { background: #fef3c7; border-color: #fde68a; color: #92400e; }
    .status-select[data-status="izin"]      { background: #cffafe; border-color: #a5f3fc; color: #155e75; }
    .status-select[data-status="alfa"]      { background: #fee2e2; border-color: #fecaca; color: #991b1b; }
    .status-select[data-status="terlambat"] { background: #fef3c7; border-color: #fde68a; color: #92400e; }
    .status-select[data-status="dinas_luar"]{ background: #e0e7ff; border-color: #c7d2fe; color: #3730a3; }
    .status-select[data-status="cuti"]      { background: #f3e8ff; border-color: #e9d5ff; color: #6b21a8; }

    .input-ket {
        border-radius: 8px;
        font-size: 0.78rem;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
    }
    .input-ket:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.1);
    }

    /* ========== SUBMIT BAR ========== */
    .submit-bar {
        padding: 16px 22px;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .submit-bar .info-text {
        font-size: 0.8rem;
        color: #64748b;
    }
    .submit-bar .info-text strong {
        color: #1e293b;
    }
    .btn-save-absensi {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 11px 26px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
    }
    .btn-save-absensi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(17, 153, 142, 0.4);
        color: #fff;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-abs { padding: 18px; }
        .page-header-abs h1 { font-size: 1.15rem; }
        .table-absensi { font-size: 0.75rem; }
        .table-absensi tbody td { padding: 10px 8px; }
        .submit-bar { padding: 14px 16px; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-abs">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-chalkboard-user me-2"></i>
                Input Absensi Guru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Catat kehadiran guru harian dengan cepat dan mudah
            </p>
        </div>
        <a href="{{ route('administrasi.absensi.rekap-guru') }}" class="btn-glass">
            <i class="fas fa-chart-line me-1"></i> Rekap Absensi
        </a>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">
                <i class="fas fa-calendar-alt me-1"></i> Tanggal
            </label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ $tanggal }}" onchange="this.form.submit()">
        </div>
        <div class="col-md-8 text-md-end">
            <label class="form-label d-none d-md-block">&nbsp;</label>
            <div class="badge bg-light text-dark rounded-3 px-3 py-2 border">
                <i class="fas fa-clock me-1"></i>
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </form>
</div>

{{-- ============ STATS MINI ============ --}}
@php
    $totalGuru   = $guru->count();
    $hadirCount  = 0; $sakitCount = 0; $izinCount = 0; $alfaCount = 0;
    foreach($guru as $g) {
        $st = $g->absensi_hari_ini->status ?? null;
        if ($st == 'hadir' || $st == 'terlambat') $hadirCount++;
        elseif ($st == 'sakit') $sakitCount++;
        elseif ($st == 'izin')  $izinCount++;
        elseif ($st == 'alfa')  $alfaCount++;
    }
@endphp
<div class="stats-mini">
    <div class="stat-mini-card border-total">
        <div class="label">Total Guru</div>
        <div class="value">{{ $totalGuru }}</div>
    </div>
    <div class="stat-mini-card border-hadir">
        <div class="label">Hadir</div>
        <div class="value text-success">{{ $hadirCount }}</div>
    </div>
    <div class="stat-mini-card border-sakit">
        <div class="label">Sakit</div>
        <div class="value text-warning">{{ $sakitCount }}</div>
    </div>
    <div class="stat-mini-card border-izin">
        <div class="label">Izin</div>
        <div class="value" style="color:#06b6d4;">{{ $izinCount }}</div>
    </div>
    <div class="stat-mini-card border-alfa">
        <div class="label">Alfa</div>
        <div class="value text-danger">{{ $alfaCount }}</div>
    </div>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <div>
            <h5>
                <i class="fas fa-list-check me-2 text-success"></i>
                Daftar Guru
            </h5>
            <div class="date-badge mt-2">
                <i class="fas fa-calendar-day"></i>
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </div>
        </div>
        @if($guru->count() > 0)
        <div class="quick-actions">
            <button type="button" class="btn-quick btn-all-hadir" onclick="setAllStatus('hadir')">
                <i class="fas fa-check-double"></i> Semua Hadir
            </button>
            <button type="button" class="btn-quick btn-reset" onclick="resetAll()">
                <i class="fas fa-times-circle"></i> Reset
            </button>
        </div>
        @endif
    </div>

    <form action="{{ route('administrasi.absensi.store-guru') }}" method="POST" id="formAbsensi">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="table-responsive">
            <table class="table table-absensi mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="13%">NIP</th>
                        <th width="22%">Nama Guru</th>
                        <th width="18%">Mata Pelajaran</th>
                        <th width="14%">Status</th>
                        <th width="28%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $index => $g)
                    @php
                        $status = $g->absensi_hari_ini->status ?? null;
                        $rowClass = $status ? 'row-filled status-' . $status : '';
                        $initial = strtoupper(substr($g->nama_lengkap ?? 'G', 0, 1));
                    @endphp
                    <tr class="{{ $rowClass }}" data-row>
                        <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="teacher-nip">{{ $g->nip ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="teacher-info">
                                <div class="teacher-avatar">{{ $initial }}</div>
                                <div>
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 180px;">
                                        {{ $g->nama_lengkap ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope fa-xs me-1"></i>
                                        {{ Str::limit($g->user->email ?? '-', 30) }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($g->mata_pelajaran_utama)
                                <span class="mapel-badge">
                                    <i class="fas fa-book-open"></i>
                                    {{ Str::limit($g->mata_pelajaran_utama, 22) }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <select name="absensi[{{ $g->id }}][status]"
                                    class="form-select status-select"
                                    data-status="{{ $status ?? '' }}"
                                    onchange="updateRowColor(this)">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList as $key => $label)
                                    <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="absensi[{{ $g->id }}][keterangan]"
                                   class="form-control input-ket"
                                   placeholder="Keterangan..."
                                   value="{{ $g->absensi_hari_ini->keterangan ?? '' }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-5">
                                <div class="empty-state-icon mx-auto" style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;color:#11998e;font-size:2.5rem;margin-bottom:20px;">
                                    <i class="fas fa-chalkboard-user"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Belum ada data guru</h5>
                                <p class="text-muted mb-0">
                                    Silakan tambahkan data guru terlebih dahulu
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guru->count() > 0)
        <div class="submit-bar">
            <div class="info-text">
                <i class="fas fa-info-circle me-1"></i>
                Total <strong>{{ $totalGuru }}</strong> guru akan disimpan
            </div>
            <button type="submit" class="btn-save-absensi">
                <i class="fas fa-save me-1"></i> Simpan Absensi
            </button>
        </div>
        @endif
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateRowColor(select) {
        const row = select.closest('tr');
        const val = select.value;

        select.setAttribute('data-status', val);
        row.classList.remove('row-filled', 'status-hadir', 'status-sakit', 'status-izin', 'status-alfa', 'status-terlambat', 'status-dinas_luar', 'status-cuti');

        if (val) {
            row.classList.add('row-filled');
            row.classList.add('status-' + val);
        }
    }

    function setAllStatus(status) {
        document.querySelectorAll('.status-select').forEach(function(s) {
            s.value = status;
            updateRowColor(s);
        });
    }

    function resetAll() {
        Swal.fire({
            title: 'Reset Semua Status?',
            text: 'Semua status kehadiran akan dikosongkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(function(s) {
                    s.value = '';
                    updateRowColor(s);
                });
                document.querySelectorAll('.input-ket').forEach(i => i.value = '');
                Swal.fire({
                    icon: 'success',
                    title: 'Direset',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });
    }
</script>
@endpush
@endsection