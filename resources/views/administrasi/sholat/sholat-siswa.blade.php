@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Sholat Siswa')

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

    /* ========== LIVE SEARCH ========== */
    .live-search-card {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #bae6fd;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .live-search-card .search-icon-big {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .live-search-input {
        flex: 1;
        min-width: 240px;
        border-radius: 12px;
        border: 1.5px solid #bae6fd;
        padding: 11px 16px;
        font-size: 0.88rem;
        background: #fff;
    }
    .live-search-input:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        outline: none;
    }
    .live-search-count {
        background: #fff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.8rem;
        color: #0369a1;
        font-weight: 600;
        white-space: nowrap;
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
    .table-sholat tbody tr.search-highlight {
        background: #fef3c7 !important;
    }

    .avatar-siswa-small {
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
        background: #d1fae5; border-color: #6ee7b7; color: #065f46;
    }
    .sholat-cell .form-select-sm[data-status="terlambat"] {
        background: #fef3c7; border-color: #fcd34d; color: #92400e;
    }
    .sholat-cell .form-select-sm[data-status="tidak_hadir"] {
        background: #fee2e2; border-color: #fca5a5; color: #991b1b;
    }
    .sholat-cell .form-select-sm[data-status="izin"] {
        background: #cffafe; border-color: #67e8f9; color: #155e75;
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
                <i class="fas fa-user-graduate me-2"></i>
                Input Absensi Sholat Siswa
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Catat kehadiran sholat 5 waktu untuk siswa
            </p>
        </div>
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn-glass">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.absensi-sholat.siswa') }}" class="row g-3 align-items-end" id="filterForm">
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Tanggal
            </label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ request('tanggal', date('Y-m-d')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">
                <i class="fas fa-school"></i> Kelas
            </label>
            <select name="kelas_id" class="form-select" id="kelasSelect">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList ?? [] as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari NIS/Nama
            </label>
            <input type="text" name="search" class="form-control"
                   placeholder="NIS / Nama"
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold" style="padding:9px">
                <i class="fas fa-search me-1"></i> Tampilkan
            </button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('administrasi.absensi-sholat.siswa') }}"
               class="btn btn-secondary w-100 rounded-3" style="padding:9px">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-success w-100 rounded-3 fw-semibold" style="padding:9px"
                    onclick="setAllStatus('tepat_waktu')">
                <i class="fas fa-check-double me-1"></i> Semua Hadir
            </button>
        </div>
    </form>
</div>

{{-- ============ LIVE SEARCH ============ --}}
<div class="live-search-card">
    <div class="search-icon-big">
        <i class="fas fa-search"></i>
    </div>
    <input type="text" id="liveSearch" class="live-search-input"
           placeholder="Ketik NIS atau Nama siswa untuk pencarian cepat...">
    <button type="button" class="btn btn-outline-secondary rounded-3" onclick="clearSearch()">
        <i class="fas fa-times me-1"></i> Clear
    </button>
    <span class="live-search-count">
        <i class="fas fa-user-check me-1"></i>
        <span id="searchResultCount">{{ $siswa->count() ?? 0 }}</span> ditemukan
    </span>
</div>

{{-- ============ MAIN CARD ============ --}}
<form method="POST" action="{{ route('administrasi.absensi-sholat.manual-store') }}" id="absensiForm">
    @csrf
    <input type="hidden" name="role" value="siswa">
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
            <table class="table table-sholat mb-0" id="tabelSiswa">
                <thead>
                    <tr>
                        <th width="4%" class="text-center">No</th>
                        <th width="9%">NIS</th>
                        <th width="18%">Nama Siswa</th>
                        <th width="9%">Kelas</th>
                        <th width="12%" class="sholat-head"><i class="fas fa-moon me-1"></i> Subuh</th>
                        <th width="12%" class="sholat-head"><i class="fas fa-sun me-1"></i> Dzuhur</th>
                        <th width="12%" class="sholat-head"><i class="fas fa-cloud-sun me-1"></i> Ashar</th>
                        <th width="12%" class="sholat-head"><i class="fas fa-cloud-moon me-1"></i> Maghrib</th>
                        <th width="12%" class="sholat-head"><i class="fas fa-star me-1"></i> Isya</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($siswa ?? [] as $index => $s)
                    @php
                        $absensiSubuh   = $s->absensi['subuh']   ?? null;
                        $absensiDzuhur  = $s->absensi['dzuhur']  ?? null;
                        $absensiAshar   = $s->absensi['ashar']   ?? null;
                        $absensiMaghrib = $s->absensi['maghrib'] ?? null;
                        $absensiIsya    = $s->absensi['isya']    ?? null;
                        $initial = strtoupper(substr($s->user->name ?? $s->nama ?? 'S', 0, 1));
                    @endphp
                    <tr data-nis="{{ $s->nis ?? '' }}"
                        data-nama="{{ strtolower($s->user->name ?? $s->nama ?? '') }}"
                        data-id="{{ $s->id }}">
                        <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="nis-badge">{{ $s->nis ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-siswa-small">{{ $initial }}</div>
                                <span class="fw-semibold text-dark text-truncate" style="max-width:150px;">
                                    {{ $s->user->name ?? $s->nama ?? '-' }}
                                </span>
                            </div>
                            <input type="hidden" name="absensi[{{ $s->id }}][user_id]" value="{{ $s->id }}">
                        </td>
                        <td>
                            <span class="kelas-badge">
                                <i class="fas fa-school"></i>
                                {{ $s->kelas->nama ?? '-' }}
                            </span>
                        </td>

                        @foreach(['subuh' => $absensiSubuh, 'dzuhur' => $absensiDzuhur,
                                  'ashar' => $absensiAshar, 'maghrib' => $absensiMaghrib,
                                  'isya' => $absensiIsya] as $sholat => $ab)
                        <td>
                            <div class="sholat-cell">
                                <select name="absensi[{{ $s->id }}][{{ $sholat }}][status]"
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
                                <input type="hidden" name="absensi[{{ $s->id }}][{{ $sholat }}][sholat]" value="{{ $sholat }}">
                                <input type="time" name="absensi[{{ $s->id }}][{{ $sholat }}][waktu_absen]"
                                       class="input-mini"
                                       value="{{ $ab && $ab->waktu_absen ? date('H:i', strtotime($ab->waktu_absen)) : '' }}">
                                <input type="text" name="absensi[{{ $s->id }}][{{ $sholat }}][keterangan]"
                                       class="input-mini" placeholder="Ket..."
                                       value="{{ $ab && $ab->keterangan ? $ab->keterangan : '' }}">
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Belum ada data siswa</h5>
                                <p class="text-muted mb-0">
                                    Silakan pilih kelas lain atau ubah tanggal untuk menampilkan data
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(($siswa->count() ?? 0) > 0)
    <button type="submit" class="btn-save-floating" id="btnSubmit">
        <i class="fas fa-save"></i>
        <span>Simpan Semua Absensi</span>
    </button>
    @endif
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============ LIVE SEARCH ============
    $('#liveSearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        $('#tableBody tr').each(function() {
            if ($(this).attr('id') === 'emptyRow') return;

            const nis  = $(this).find('.nis-badge').text().toLowerCase();
            const nama = $(this).attr('data-nama') || '';
            const row  = $(this);

            if (searchTerm === '') {
                row.show().removeClass('search-highlight');
                visibleCount++;
            } else if (nis.indexOf(searchTerm) !== -1 || nama.indexOf(searchTerm) !== -1) {
                row.show().addClass('search-highlight');
                visibleCount++;
            } else {
                row.hide().removeClass('search-highlight');
            }
        });

        $('#searchResultCount').text(visibleCount);
    });

    function clearSearch() {
        $('#liveSearch').val('');
        $('#liveSearch').trigger('keyup');
    }

    // ============ CELL COLOR ============
    function updateCellColor(select) {
        select.setAttribute('data-status', select.value);
    }

    // ============ SET ALL ============
    function setAllStatus(status) {
        const labels = {
            'tepat_waktu': 'Tepat Waktu',
            'terlambat':   'Terlambat',
            'tidak_hadir':'Tidak Hadir',
            'izin':        'Izin'
        };

        Swal.fire({
            title: 'Konfirmasi',
            html: `Atur <strong>SEMUA</strong> siswa dengan status <strong>"${labels[status]}"</strong>?`,
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
                Swal.fire({ icon:'success', title:'Berhasil!', timer:1200, showConfirmButton:false });
            }
        });
    }

    // ============ SUBMIT ============
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