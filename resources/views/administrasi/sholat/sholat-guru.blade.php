@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Sholat Guru')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-sholat {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-sholat::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-sholat .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-sholat h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-sholat p {
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
        text-decoration: none;
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
        padding: 18px 22px;
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
    .filter-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
    }
    .filter-card .form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }
    .btn-quick-action {
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 9px 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.25s;
        border: none;
        width: 100%;
    }
    .btn-set-all {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    .btn-set-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        color: #fff;
    }
    .btn-reset-all {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
    .btn-reset-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
        color: #fff;
    }

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
    .table-sholat {
        margin: 0;
        font-size: 0.8rem;
    }
    .table-sholat thead th {
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
    }
    .table-sholat thead th.sholat-head {
        background: #f0f9ff;
        color: #0369a1;
        text-align: center;
    }
    .table-sholat tbody td {
        padding: 12px 10px;
        vertical-align: top;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-sholat tbody tr:hover {
        background: #f8fafc;
    }

    .avatar-guru-small {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
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

    /* ========== SHOLAT CELL ========== */
    .sholat-cell {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .sholat-cell .form-select-sm {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 6px 8px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s;
        cursor: pointer;
    }
    .sholat-cell .form-select-sm:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    .sholat-cell .form-select-sm[data-status="tepat_waktu"] {
        background: #d1fae5;
        border-color: #6ee7b7;
        color: #065f46;
    }
    .sholat-cell .form-select-sm[data-status="terlambat"] {
        background: #fef3c7;
        border-color: #fcd34d;
        color: #92400e;
    }
    .sholat-cell .form-select-sm[data-status="tidak_hadir"] {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #991b1b;
    }
    .sholat-cell .form-select-sm[data-status="izin"] {
        background: #cffafe;
        border-color: #67e8f9;
        color: #155e75;
    }
    .sholat-cell .input-mini {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .sholat-cell .input-mini:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1);
    }

    /* ========== FLOATING SAVE BUTTON ========== */
    .btn-save-floating {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border: none;
        padding: 14px 26px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.88rem;
        box-shadow: 0 8px 24px rgba(14, 165, 233, 0.4);
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save-floating:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(14, 165, 233, 0.5);
        color: #fff;
    }

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
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        font-size: 2.5rem;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-sholat { padding: 18px; }
        .page-header-sholat h1 { font-size: 1.15rem; }
        .btn-save-floating { bottom: 20px; right: 20px; padding: 12px 20px; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-sholat">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-chalkboard-user me-2"></i>
                Input Absensi Sholat Guru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Catat kehadiran sholat 5 waktu untuk guru
            </p>
        </div>
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn-glass">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.absensi-sholat.guru') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Tanggal Absensi
            </label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ request('tanggal', date('Y-m-d')) }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold" style="padding:9px">
                <i class="fas fa-search me-1"></i> Tampilkan
            </button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn-quick-action btn-set-all" onclick="setAllStatus('tepat_waktu')">
                <i class="fas fa-check-double"></i> Set Semua Tepat Waktu
            </button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn-quick-action btn-reset-all" onclick="resetAllStatus()">
                <i class="fas fa-undo-alt"></i> Reset Semua
            </button>
        </div>
        <div class="col-md-1 text-md-end">
            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 w-100">
                <i class="fas fa-users me-1"></i> {{ $guru->count() ?? 0 }}
            </span>
        </div>
    </form>
</div>

{{-- ============ MAIN CARD ============ --}}
<form method="POST" action="{{ route('administrasi.absensi-sholat.manual-store') }}" id="absensiForm">
    @csrf
    <input type="hidden" name="role" value="guru">
    <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">

    <div class="main-card">
        <div class="main-card-header">
            <h5>
                <i class="fas fa-mosque"></i>
                Input Absensi Sholat 5 Waktu
            </h5>
            <div class="period-badge">
                <i class="fas fa-calendar-day"></i>
                {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sholat mb-0">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">Guru</th>
                        <th width="8%" class="text-center">NIP</th>
                        <th width="14%" class="sholat-head">
                            <i class="fas fa-moon me-1"></i> Subuh
                        </th>
                        <th width="14%" class="sholat-head">
                            <i class="fas fa-sun me-1"></i> Dzuhur
                        </th>
                        <th width="14%" class="sholat-head">
                            <i class="fas fa-cloud-sun me-1"></i> Ashar
                        </th>
                        <th width="14%" class="sholat-head">
                            <i class="fas fa-cloud-moon me-1"></i> Maghrib
                        </th>
                        <th width="16%" class="sholat-head">
                            <i class="fas fa-star me-1"></i> Isya
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru ?? [] as $index => $g)
                    @php
                        $absensiSubuh   = $g->absensi['subuh']   ?? null;
                        $absensiDzuhur  = $g->absensi['dzuhur']  ?? null;
                        $absensiAshar   = $g->absensi['ashar']   ?? null;
                        $absensiMaghrib = $g->absensi['maghrib'] ?? null;
                        $absensiIsya    = $g->absensi['isya']    ?? null;
                        $initial = strtoupper(substr($g->user->name ?? $g->nama_lengkap ?? 'G', 0, 1));
                    @endphp
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-guru-small">{{ $initial }}</div>
                                <div>
                                    <div class="fw-semibold text-dark text-truncate" style="max-width:140px;">
                                        {{ $g->user->name ?? $g->nama_lengkap ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope fa-xs me-1"></i>
                                        {{ Str::limit($g->user->email ?? '-', 22) }}
                                    </small>
                                </div>
                            </div>
                            <input type="hidden" name="absensi[{{ $g->id }}][user_id]" value="{{ $g->id }}">
                        </td>
                        <td class="text-center">
                            <span class="nip-badge">{{ $g->nip ?? '-' }}</span>
                        </td>

                        {{-- Macro: cell sholat --}}
                        @foreach(['subuh' => $absensiSubuh, 'dzuhur' => $absensiDzuhur,
                                  'ashar' => $absensiAshar, 'maghrib' => $absensiMaghrib,
                                  'isya' => $absensiIsya] as $sholat => $ab)
                        <td>
                            <div class="sholat-cell">
                                <select name="absensi[{{ $g->id }}][{{ $sholat }}][status]"
                                        class="form-select form-select-sm status-select"
                                        data-status="{{ $ab->status ?? '' }}"
                                        onchange="updateCellColor(this)">
                                    <option value="">--</option>
                                    @foreach($statusList ?? [] as $key => $label)
                                        <option value="{{ $key }}" {{ ($ab && $ab->status == $key) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="absensi[{{ $g->id }}][{{ $sholat }}][sholat]" value="{{ $sholat }}">
                                <input type="time" name="absensi[{{ $g->id }}][{{ $sholat }}][waktu_absen]"
                                       class="input-mini"
                                       value="{{ $ab && $ab->waktu_absen ? date('H:i', strtotime($ab->waktu_absen)) : '' }}">
                                <input type="text" name="absensi[{{ $g->id }}][{{ $sholat }}][keterangan]"
                                       class="input-mini" placeholder="Ket..."
                                       value="{{ $ab && $ab->keterangan ? $ab->keterangan : '' }}">
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chalkboard-user"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Belum ada data guru</h5>
                                <p class="text-muted mb-0">
                                    Tambahkan data guru terlebih dahulu di menu Manajemen Guru
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(($guru->count() ?? 0) > 0)
    <button type="submit" class="btn-save-floating" id="btnSubmit">
        <i class="fas fa-save"></i>
        <span>Simpan Semua Absensi</span>
    </button>
    @endif
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateCellColor(select) {
        select.setAttribute('data-status', select.value);
    }

    function setAllStatus(status) {
        const labels = {
            'tepat_waktu': 'Tepat Waktu',
            'terlambat':   'Terlambat',
            'tidak_hadir':'Tidak Hadir',
            'izin':        'Izin'
        };

        Swal.fire({
            title: 'Konfirmasi',
            html: `Atur <strong>SEMUA</strong> guru dengan status <strong>"${labels[status]}"</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Set Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = status;
                    updateCellColor(select);
                });
                Swal.fire({ icon:'success', title:'Berhasil!', text:'Semua status telah diatur', timer:1200, showConfirmButton:false });
            }
        });
    }

    function resetAllStatus() {
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
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = '';
                    updateCellColor(select);
                });
                document.querySelectorAll('.input-mini').forEach(i => i.value = '');
                Swal.fire({ icon:'success', title:'Direset', timer:1000, showConfirmButton:false });
            }
        });
    }

    $('#absensiForm').on('submit', function(e) {
        e.preventDefault();

        let hasData = false;
        document.querySelectorAll('.status-select').forEach(select => {
            if (select.value !== '') hasData = true;
        });

        if (!hasData) {
            Swal.fire('Peringatan!', 'Belum ada data absensi yang diisi', 'warning');
            return false;
        }

        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: 'Yakin ingin menyimpan semua absensi?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop('disabled', true);
                $('#absensiForm')[0].submit();
            }
        });
    });
</script>
@endpush
@endsection