@extends('guru.layouts.header')

@section('title', 'Absensi Siswa')

@section('content')
<style>
    .abs-wrapper {
        --abs-primary: #667eea;
        --abs-purple: #764ba2;
        --abs-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --abs-text: #1e293b;
        --abs-text-muted: #64748b;
        --abs-text-light: #94a3b8;
        --abs-border: #e2e8f0;
        --abs-bg-soft: #f8fafc;
        --abs-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        color: var(--abs-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-bottom: 2rem;
    }
    .abs-wrapper * { box-sizing: border-box; }

    /* ===== Page Header ===== */
    .abs-wrapper .abs-page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--abs-border);
    }
    .abs-wrapper .abs-page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--abs-text);
        margin: 0 0 .35rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.5px;
    }
    .abs-wrapper .abs-page-title i { color: var(--abs-primary); }
    .abs-wrapper .abs-page-sub {
        color: var(--abs-text-light);
        font-size: .85rem;
        margin: 0;
    }

    /* ===== Buttons ===== */
    .abs-wrapper .abs-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: .82rem;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        font-family: inherit;
    }
    .abs-wrapper .abs-btn:hover { transform: translateY(-2px); }
    .abs-wrapper .abs-btn-primary {
        background: var(--abs-gradient);
        color: #fff;
        box-shadow: 0 4px 12px rgba(102,126,234,.25);
    }
    .abs-wrapper .abs-btn-primary:hover { color: #fff; box-shadow: 0 6px 18px rgba(102,126,234,.4); }
    .abs-wrapper .abs-btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(16,185,129,.25);
    }
    .abs-wrapper .abs-btn-success:hover { color: #fff; }
    .abs-wrapper .abs-btn-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(6,182,212,.25);
    }
    .abs-wrapper .abs-btn-info:hover { color: #fff; }

    /* ===== Info Card ===== */
    .abs-wrapper .abs-info-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: var(--abs-shadow);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 4px solid var(--abs-primary);
        flex-wrap: wrap;
    }
    .abs-wrapper .abs-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: var(--abs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .abs-wrapper .abs-info-body { flex-grow: 1; min-width: 200px; }
    .abs-wrapper .abs-info-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--abs-text);
        margin: 0 0 4px 0;
    }
    .abs-wrapper .abs-info-sub {
        font-size: .82rem;
        color: var(--abs-text-muted);
        margin: 0;
    }
    .abs-wrapper .abs-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        background: var(--abs-gradient);
        color: #fff;
    }

    /* ===== Stat Cards ===== */
    .abs-wrapper .abs-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 1.5rem;
    }
    .abs-wrapper .abs-stat {
        background: #fff;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        box-shadow: var(--abs-shadow);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .25s;
        border: 1px solid transparent;
    }
    .abs-wrapper .abs-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102,126,234,.15);
        border-color: #c7d2fe;
    }
    .abs-wrapper .abs-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .abs-wrapper .abs-stat-info { flex-grow: 1; min-width: 0; }
    .abs-wrapper .abs-stat-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--abs-text-light);
        margin-bottom: 2px;
    }
    .abs-wrapper .abs-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--abs-text);
        line-height: 1.1;
    }

    /* ===== Table Card ===== */
    .abs-wrapper .abs-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--abs-shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .abs-wrapper .abs-card-head {
        background: var(--abs-gradient);
        color: #fff;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .abs-wrapper .abs-card-head h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
    }
    .abs-wrapper .abs-card-head small {
        font-size: .75rem;
        opacity: .9;
        display: block;
        margin-top: 4px;
    }

    /* ===== Table ===== */
    .abs-wrapper table.abs-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }
    .abs-wrapper table.abs-table thead th {
        background: var(--abs-bg-soft) !important;
        color: var(--abs-text-muted) !important;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 12px 8px;
        text-align: center;
        border-bottom: 2px solid var(--abs-border);
        white-space: nowrap;
    }
    .abs-wrapper table.abs-table tbody td {
        padding: 10px 8px;
        vertical-align: middle;
        font-size: .875rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        background: #fff;
    }
    .abs-wrapper table.abs-table tbody tr:hover td {
        background: #f8faff;
    }
    .abs-wrapper table.abs-table tbody tr:nth-child(even) td {
        background: #fafbfc;
    }
    .abs-wrapper table.abs-table tbody tr:nth-child(even):hover td {
        background: #f1f5f9;
    }
    .abs-wrapper .abs-no {
        color: var(--abs-text-light);
        font-weight: 700;
        text-align: center;
    }
    .abs-wrapper .abs-nis {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
    }

    /* ===== Form Controls ===== */
    .abs-wrapper .abs-select,
    .abs-wrapper .abs-input {
        width: 100%;
        padding: 8px 12px;
        font-size: .82rem;
        border: 1.5px solid var(--abs-border);
        border-radius: 8px;
        outline: none;
        transition: all .2s;
        font-family: inherit;
        background: #fff;
        color: var(--abs-text);
    }
    .abs-wrapper .abs-select:focus,
    .abs-wrapper .abs-input:focus {
        border-color: var(--abs-primary);
        box-shadow: 0 0 0 3px rgba(102,126,234,.15);
    }
    .abs-wrapper .abs-select:disabled {
        background: #f8fafc;
        cursor: not-allowed;
        opacity: .6;
    }

    /* ===== Action Bar ===== */
    .abs-wrapper .abs-action-bar {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
        padding: 1.25rem 1.5rem;
        background: var(--abs-bg-soft);
        border-top: 1px solid var(--abs-border);
    }

    /* ===== Floating Button ===== */
    .abs-wrapper .abs-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 8px 24px rgba(16,185,129,.4);
        font-size: 1.3rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .25s;
        z-index: 1000;
    }
    .abs-wrapper .abs-fab:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 28px rgba(16,185,129,.5);
        color: #fff;
    }

    /* ===== Modal ===== */
    .abs-modal .modal-content {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.15);
    }
    .abs-modal .modal-header {
        background: var(--abs-gradient);
        color: #fff;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }
    .abs-modal .modal-header .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .abs-modal .modal-header .btn-close {
        filter: invert(1) brightness(2);
        opacity: .85;
    }
    .abs-modal .modal-body { padding: 1.5rem; }
    .abs-modal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }
    .abs-modal .form-label {
        font-weight: 700;
        font-size: .75rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .abs-modal .form-label i { color: var(--abs-primary); }
    .abs-modal .form-control,
    .abs-modal .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        font-size: .875rem;
        transition: all .2s;
    }
    .abs-modal .form-control:focus,
    .abs-modal .form-select:focus {
        border-color: var(--abs-primary);
        box-shadow: 0 0 0 3px rgba(102,126,234,.15);
        outline: none;
    }

    /* ===== Empty State ===== */
    .abs-wrapper .abs-empty {
        padding: 3rem 1rem;
        text-align: center;
    }
    .abs-wrapper .abs-empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .abs-wrapper .abs-empty h5 {
        color: #334155;
        font-weight: 700;
        margin: 0 0 6px;
    }
    .abs-wrapper .abs-empty p {
        color: #94a3b8;
        font-size: .875rem;
        margin: 0 0 1rem;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .abs-wrapper .abs-page-title { font-size: 1.2rem; }
        .abs-wrapper .abs-stat-value { font-size: 1.2rem; }
        .abs-wrapper .abs-stat-icon { width: 38px; height: 38px; font-size: .9rem; }
        .abs-wrapper .abs-fab { width: 50px; height: 50px; bottom: 20px; right: 20px; }
    }
</style>

<div class="abs-wrapper">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="abs-page-head">
        <div>
            <h1 class="abs-page-title">
                <i class="fas fa-calendar-check"></i>
                Absensi Siswa
            </h1>
            <p class="abs-page-sub">Kelola absensi siswa per kelas dan mata pelajaran</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('guru.absensi.scan') }}" class="abs-btn abs-btn-success">
                <i class="fas fa-rss"></i> Scan RFID
            </a>
            <a href="{{ route('guru.absensi.riwayat') }}" class="abs-btn abs-btn-info">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </div>
    </div>

    {{-- ================= FILTER CARD ================= --}}
    <div class="abs-card">
        <div class="abs-card-head">
            <div>
                <h5><i class="fas fa-filter"></i> Pilih Kelas & Mata Pelajaran</h5>
                <small>Pilih kelas lalu mata pelajaran untuk menampilkan daftar siswa</small>
            </div>
        </div>
        <div style="padding: 1.5rem;">
            <form method="GET" action="{{ route('guru.absensi.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:700; font-size:.75rem; color:#475569; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block;">
                            <i class="fas fa-users text-primary me-1"></i> Pilih Kelas <span class="text-danger">*</span>
                        </label>
                        <select name="kelas_id" class="abs-select" id="kelasSelect" required>
                            <option value="">— Pilih Kelas —</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:700; font-size:.75rem; color:#475569; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block;">
                            <i class="fas fa-book text-primary me-1"></i> Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <select name="mata_pelajaran_id" class="abs-select" id="mapelSelect" required>
                            <option value="">— Pilih Kelas Terlebih Dahulu —</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:700; font-size:.75rem; color:#475569; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block;">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal
                        </label>
                        <input type="date" name="tanggal" class="abs-input" value="{{ $tanggal }}">
                    </div>
                </div>
                <div style="margin-top:1rem; display:flex; justify-content:flex-end; gap:8px; padding-top:1rem; border-top:1px solid #e2e8f0;">
                    <a href="{{ route('guru.absensi.index') }}" class="abs-btn" style="background:#f1f5f9; color:#475569;">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                    <button type="submit" class="abs-btn abs-btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= INFO KELAS & MAPEL ================= --}}
    @if($kelasId && $mataPelajaranId)
        <div class="abs-info-card">
            <div class="abs-info-icon">
                <i class="fas fa-school"></i>
            </div>
            <div class="abs-info-body">
                <p class="abs-info-title">
                    {{ $kelas->firstWhere('id', $kelasId)->nama_kelas ?? $kelas->firstWhere('id', $kelasId)->nama ?? '-' }}
                    <span class="abs-badge ms-2"><i class="fas fa-user"></i> {{ $totalSiswa }} Siswa</span>
                </p>
                <p class="abs-info-sub">
                    <i class="fas fa-book me-1"></i> {{ $mataPelajaranList->firstWhere('id', $mataPelajaranId)->nama ?? '-' }}
                    <span class="mx-2">|</span>
                    <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
    @elseif($kelasId)
        <div class="alert alert-warning" style="border-radius:12px; border:none; border-left:4px solid #f59e0b;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Silakan pilih <strong>mata pelajaran</strong> terlebih dahulu.
        </div>
    @else
        <div class="alert alert-info" style="border-radius:12px; border:none; border-left:4px solid #3b82f6;">
            <i class="fas fa-info-circle me-2"></i>
            Silakan pilih <strong>kelas</strong> dan <strong>mata pelajaran</strong> terlebih dahulu.
        </div>
    @endif

    {{-- ================= STATISTIK ================= --}}
    @if($kelasId && $mataPelajaranId)
        <div class="abs-stats">
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#eef2ff; color:#4f46e5;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Total Siswa</div>
                    <div class="abs-stat-value">{{ $totalSiswa }}</div>
                </div>
            </div>
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#d1fae5; color:#059669;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Hadir</div>
                    <div class="abs-stat-value">{{ $hadir }}</div>
                </div>
            </div>
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#fef3c7; color:#d97706;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Terlambat</div>
                    <div class="abs-stat-value">{{ $terlambat }}</div>
                </div>
            </div>
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#cffafe; color:#0891b2;">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Sakit</div>
                    <div class="abs-stat-value">{{ $sakit }}</div>
                </div>
            </div>
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#f1f5f9; color:#475569;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Izin</div>
                    <div class="abs-stat-value">{{ $izin }}</div>
                </div>
            </div>
            <div class="abs-stat">
                <div class="abs-stat-icon" style="background:#fee2e2; color:#dc2626;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="abs-stat-info">
                    <div class="abs-stat-label">Belum Absen</div>
                    <div class="abs-stat-value">{{ $belumAbsen }}</div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= FORM ABSENSI ================= --}}
    @if($kelasId && $mataPelajaranId)
        <form method="POST" action="{{ route('guru.absensi.store') }}" id="absensiForm">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaranId }}">

            <div class="abs-card">
                <div class="abs-card-head">
                    <h5><i class="fas fa-edit"></i> Input Absensi</h5>
                </div>
                <div style="overflow-x:auto;">
                    <table class="abs-table">
                        <thead>
                            <tr>
                                <th style="width:5%;">No</th>
                                <th style="width:10%;">NIS</th>
                                <th style="width:20%; text-align:left;">Nama Siswa</th>
                                <th style="width:12%;">Kelas</th>
                                <th style="width:15%;">Status</th>
                                <th style="width:13%;">Jam Masuk</th>
                                <th style="width:25%; text-align:left;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                                <tr>
                                    <td class="abs-no">{{ $index + 1 }}</td>
                                    <td class="text-center"><span class="abs-nis">{{ $s->nis ?? '-' }}</span></td>
                                    <td>
                                        <strong>{{ $s->user->name ?? $s->nama ?? '-' }}</strong>
                                        <input type="hidden" name="absensi[{{ $s->id }}][siswa_id]" value="{{ $s->id }}">
                                    </td>
                                    <td class="text-center">{{ $s->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <select name="absensi[{{ $s->id }}][status]" class="abs-select status-select" data-id="{{ $s->id }}">
                                            <option value="">— Pilih —</option>
                                            @foreach($statusList as $key => $label)
                                                <option value="{{ $key }}" {{ $s->status_absensi == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" name="absensi[{{ $s->id }}][waktu_absen]"
                                               class="abs-input waktu-masuk-{{ $s->id }}"
                                               value="{{ $s->waktu_absensi ? date('H:i', strtotime($s->waktu_absensi)) : '' }}">
                                    </td>
                                    <td>
                                        <input type="text" name="absensi[{{ $s->id }}][keterangan]"
                                               class="abs-input"
                                               placeholder="Keterangan (opsional)"
                                               value="{{ $s->keterangan_absensi }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="abs-empty">
                                            <div class="abs-empty-icon">
                                                <i class="fas fa-users-slash"></i>
                                            </div>
                                            <h5>Tidak Ada Siswa</h5>
                                            <p>Belum ada siswa aktif di kelas ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($siswa->count() > 0)
                    <div class="abs-action-bar">
                        <button type="button" class="abs-btn abs-btn-success" onclick="setAllStatus('hadir')">
                            <i class="fas fa-check-circle"></i> Set Semua Hadir
                        </button>
                        <button type="button" class="abs-btn" style="background:#fef3c7; color:#d97706;" onclick="resetAllStatus()">
                            <i class="fas fa-undo-alt"></i> Reset Semua
                        </button>
                        <button type="submit" class="abs-btn abs-btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Simpan Absensi
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @endif
</div>

{{-- ================= FLOATING SCAN BUTTON ================= --}}
@if($kelasId && $mataPelajaranId)
    <a href="{{ route('guru.absensi.scan') }}?kelas_id={{ $kelasId }}&mata_pelajaran_id={{ $mataPelajaranId }}" class="abs-fab" title="Scan RFID">
        <i class="fas fa-rss"></i>
    </a>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ===== VARIABEL GLOBAL =====
    var SELECTED_KELAS_ID = '{{ $kelasId ?? "" }}';
    var SELECTED_MAPEL_ID = '{{ $mataPelajaranId ?? "" }}';

    document.addEventListener('DOMContentLoaded', function() {
        var kelasSelect = document.getElementById('kelasSelect');
        var mapelSelect = document.getElementById('mapelSelect');

        // ============================================
        // AUTO-LOAD MAPEL kalau kelas sudah dipilih
        // ============================================
        if (SELECTED_KELAS_ID) {
            loadMapel(SELECTED_KELAS_ID, SELECTED_MAPEL_ID);
        }

        // ============================================
        // EVENT: kelas berubah → load mapel
        // ============================================
        if (kelasSelect) {
            kelasSelect.addEventListener('change', function() {
                var kelasId = this.value;
                if (kelasId) {
                    loadMapel(kelasId);
                } else {
                    mapelSelect.innerHTML = '<option value="">— Pilih Kelas Terlebih Dahulu —</option>';
                }
            });
        }

        // ============================================
        // EVENT: mapel berubah → submit form
        // ============================================
        if (mapelSelect) {
            mapelSelect.addEventListener('change', function() {
                if (this.value !== '' && kelasSelect.value !== '') {
                    document.getElementById('filterForm').submit();
                }
            });
        }

        // ============================================
        // EVENT: status select → enable/disable waktu masuk
        // ============================================
        document.querySelectorAll('.status-select').forEach(function(sel) {
            sel.addEventListener('change', function() {
                var siswaId = this.getAttribute('data-id');
                var waktuMasuk = document.querySelector('.waktu-masuk-' + siswaId);

                if (!waktuMasuk) return;

                if (this.value === 'hadir' || this.value === 'terlambat') {
                    waktuMasuk.disabled = false;
                    if (!waktuMasuk.value) {
                        var now = new Date();
                        var h = String(now.getHours()).padStart(2, '0');
                        var m = String(now.getMinutes()).padStart(2, '0');
                        waktuMasuk.value = h + ':' + m;
                    }
                } else {
                    waktuMasuk.disabled = true;
                    waktuMasuk.value = '';
                }
            });
        });
    });

    // ============================================
    // FUNGSI: LOAD MAPEL via AJAX
    // ============================================
    function loadMapel(kelasId, selectedMapelId) {
        var mapelSelect = document.getElementById('mapelSelect');
        if (!mapelSelect) return;

        mapelSelect.innerHTML = '<option value="">⏳ Memuat...</option>';

        var url = '{{ route("guru.absensi.get-mata-pelajaran") }}?kelas_id=' + encodeURIComponent(kelasId);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.data && data.data.length > 0) {
                var opts = '<option value="">— Pilih Mata Pelajaran —</option>';
                data.data.forEach(function(m) {
                    var sel = (selectedMapelId && String(m.id) === String(selectedMapelId)) ? ' selected' : '';
                    opts += '<option value="' + m.id + '"' + sel + '>' + m.nama + '</option>';
                });
                mapelSelect.innerHTML = opts;
            } else {
                mapelSelect.innerHTML = '<option value="">— Tidak ada mata pelajaran —</option>';
            }
        })
        .catch(function(err) {
            console.error('Gagal load mapel:', err);
            mapelSelect.innerHTML = '<option value="">— Gagal memuat data —</option>';
        });
    }

    // ============================================
    // FUNGSI: SET ALL STATUS
    // ============================================
    function setAllStatus(status) {
        var labels = {
            'hadir': 'Hadir',
            'sakit': 'Sakit',
            'izin': 'Izin',
            'alfa': 'Alfa',
            'terlambat': 'Terlambat'
        };
        var label = labels[status] || status;

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Atur SEMUA siswa dengan status "' + label + '"?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Set Semua!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(function(sel) {
                    sel.value = status;
                    sel.dispatchEvent(new Event('change'));
                });
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Semua status telah diatur',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    // ============================================
    // FUNGSI: RESET ALL STATUS
    // ============================================
    function resetAllStatus() {
        Swal.fire({
            title: 'Konfirmasi Reset',
            text: 'Reset semua status ke kosong?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(function(sel) {
                    sel.value = '';
                    sel.dispatchEvent(new Event('change'));
                });
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Semua status telah direset',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    // ============================================
    // FORM SUBMIT — VALIDASI + LOADING
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('absensiForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            var hasData = false;
            document.querySelectorAll('.status-select').forEach(function(sel) {
                if (sel.value !== '') hasData = true;
            });

            if (!hasData) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Belum ada data absensi yang diisi',
                    confirmButtonColor: '#667eea'
                });
                return false;
            }

            var btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            btn.disabled = true;
        });
    });
</script>
@endpush
@endsection