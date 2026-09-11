@extends('guru.layouts.header')

@section('title', 'Raport Siswa')

@section('content')
<style>
    .rt-wrapper {
        --rt-primary: #667eea;
        --rt-purple: #764ba2;
        --rt-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --rt-text: #1e293b;
        --rt-text-muted: #64748b;
        --rt-text-light: #94a3b8;
        --rt-border: #e2e8f0;
        --rt-bg-soft: #f8fafc;
        --rt-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        color: var(--rt-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-bottom: 2rem;
    }
    .rt-wrapper * { box-sizing: border-box; }

    /* ===== Page Header ===== */
    .rt-wrapper .rt-page-head {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--rt-border);
    }
    .rt-wrapper .rt-page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--rt-text);
        margin: 0 0 .35rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
        line-height: 1.3;
        letter-spacing: -0.5px;
    }
    .rt-wrapper .rt-page-title i { color: var(--rt-primary); }
    .rt-wrapper .rt-page-sub {
        color: var(--rt-text-light);
        font-size: .85rem;
        margin: 0;
    }

    /* ===== Filter Card (Gradient) ===== */
    .rt-wrapper .rt-filter-card {
        background: var(--rt-gradient);
        color: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
        position: relative;
        overflow: hidden;
    }
    .rt-wrapper .rt-filter-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 40%;
        height: 200%;
        background: rgba(255,255,255,.05);
        transform: rotate(25deg);
    }
    .rt-wrapper .rt-filter-card .rt-filter-inner {
        position: relative;
        z-index: 1;
    }
    .rt-wrapper .rt-filter-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        opacity: .85;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .rt-wrapper .rt-filter-select {
        width: 100%;
        padding: 10px 14px;
        font-size: .875rem;
        font-weight: 600;
        color: #1e293b;
        background: #fff;
        border: none;
        border-radius: 10px;
        outline: none;
        cursor: pointer;
        transition: all .2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }
    .rt-wrapper .rt-filter-select:focus {
        box-shadow: 0 0 0 3px rgba(255,255,255,.4);
    }
    .rt-wrapper .rt-filter-btn {
        width: 100%;
        padding: 10px 20px;
        font-size: .875rem;
        font-weight: 700;
        color: var(--rt-primary);
        background: #fff;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .rt-wrapper .rt-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,.15);
        color: var(--rt-primary);
    }

    /* ===== Stat Cards ===== */
    .rt-wrapper .rt-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .rt-wrapper .rt-stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        box-shadow: var(--rt-shadow);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .25s;
        border: 1px solid transparent;
    }
    .rt-wrapper .rt-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102,126,234,.15);
        border-color: #c7d2fe;
    }
    .rt-wrapper .rt-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .rt-wrapper .rt-stat-info {
        flex-grow: 1;
        min-width: 0;
    }
    .rt-wrapper .rt-stat-label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--rt-text-light);
        margin-bottom: 2px;
    }
    .rt-wrapper .rt-stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--rt-text);
        line-height: 1.1;
    }

    /* ===== Main Card ===== */
    .rt-wrapper .rt-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--rt-shadow);
        overflow: hidden;
    }
    .rt-wrapper .rt-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: #fff;
    }
    .rt-wrapper .rt-card-head h5 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--rt-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rt-wrapper .rt-card-head h5 i { color: var(--rt-primary); }

    .rt-wrapper .rt-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
    }

    /* ===== Table ===== */
    .rt-wrapper .rt-table-wrap {
        border-radius: 0;
        overflow: hidden;
        background: #fff;
    }
    .rt-wrapper .rt-table-wrap .table-responsive {
        max-height: 600px;
    }
    .rt-wrapper table.rt-table {
        margin: 0;
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }
    .rt-wrapper table.rt-table thead th {
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 10px 6px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .rt-wrapper table.rt-table thead th.mapel-header {
        background: #eef2ff !important;
        color: #4f46e5 !important;
        font-weight: 700;
    }
    .rt-wrapper table.rt-table thead th.komponen-header {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        font-size: .62rem;
        font-weight: 600;
    }
    .rt-wrapper table.rt-table tbody td {
        padding: 10px 6px;
        vertical-align: middle;
        text-align: center;
        font-size: .82rem;
        border-bottom: 1px solid #f1f5f9;
        border-top: none;
        color: #334155;
        background: #fff;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .rt-wrapper table.rt-table tbody tr:hover td {
        background-color: #f8faff;
    }
    .rt-wrapper table.rt-table tbody tr:nth-child(even) td {
        background-color: #fafbfc;
    }
    .rt-wrapper table.rt-table tbody tr:nth-child(even):hover td {
        background-color: #f1f5f9;
    }
    .rt-wrapper table.rt-table .text-left { text-align: left !important; }

    /* Column widths */
    .rt-wrapper .rt-table .col-no { width: 45px; }
    .rt-wrapper .rt-table .col-nis { width: 80px; }
    .rt-wrapper .rt-table .col-nama { width: 160px; }
    .rt-wrapper .rt-table .col-nilai { width: 60px; }
    .rt-wrapper .rt-table .col-rata { width: 85px; }
    .rt-wrapper .rt-table .col-status { width: 85px; }
    .rt-wrapper .rt-table .col-aksi { width: 70px; }

    /* Nilai colors */
    .rt-wrapper .nilai-akhir {
        font-weight: 800;
        font-size: .95rem;
    }
    .rt-wrapper .nilai-tinggi { color: #10b981; }
    .rt-wrapper .nilai-sedang { color: #f59e0b; }
    .rt-wrapper .nilai-rendah { color: #ef4444; }

    /* Badges */
    .rt-wrapper .rt-badge-kkm {
        display: inline-block;
        font-size: .7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
    }
    .rt-wrapper .rt-badge-kkm.lulus {
        background: #d1fae5;
        color: #065f46;
    }
    .rt-wrapper .rt-badge-kkm.tidak {
        background: #fee2e2;
        color: #991b1b;
    }
    .rt-wrapper .rt-nis-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
    }

    /* Detail button */
    .rt-wrapper .rt-btn-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 12px;
        font-size: .75rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }
    .rt-wrapper .rt-btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, .35);
        color: #fff;
    }

    /* ===== Legend ===== */
    .rt-wrapper .rt-legend {
        padding: 1.25rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .rt-wrapper .rt-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .72rem;
        color: #64748b;
        font-weight: 600;
    }
    .rt-wrapper .rt-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    /* ===== Empty State ===== */
    .rt-wrapper .rt-empty {
        text-align: center;
        padding: 3rem 1rem;
    }
    .rt-wrapper .rt-empty-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1.25rem;
    }
    .rt-wrapper .rt-empty h5 {
        color: #334155;
        font-weight: 700;
        margin: 0 0 6px;
        font-size: 1.1rem;
    }
    .rt-wrapper .rt-empty p {
        color: #94a3b8;
        font-size: .875rem;
        margin: 0;
    }

    /* ===== Modal ===== */
    .rt-modal .modal-content {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.15);
    }
    .rt-modal .modal-header {
        background: var(--rt-gradient);
        color: #fff;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }
    .rt-modal .modal-header .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rt-modal .modal-header .btn-close {
        filter: invert(1) brightness(2);
        opacity: .8;
    }
    .rt-modal .modal-body {
        padding: 1.5rem;
    }
    .rt-modal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* DataTables fix */
    .rt-wrapper .dataTables_wrapper .dataTables_length,
    .rt-wrapper .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
        font-size: .8rem;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_length select,
    .rt-wrapper .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        padding: 6px 10px;
        font-size: .8rem;
        margin: 0 6px;
        outline: none;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_filter input:focus,
    .rt-wrapper .dataTables_wrapper .dataTables_length select:focus {
        border-color: var(--rt-primary);
        box-shadow: 0 0 0 3px rgba(102,126,234,.15);
    }
    .rt-wrapper .dataTables_wrapper .dataTables_info {
        font-size: .78rem;
        color: #94a3b8;
        padding-top: 1rem;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
        text-align: right;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #fff !important;
        color: #475569 !important;
        cursor: pointer !important;
        font-size: .78rem !important;
        font-weight: 600 !important;
        transition: all .15s;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #eef2ff !important;
        color: var(--rt-primary) !important;
        border-color: var(--rt-primary) !important;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--rt-gradient) !important;
        color: #fff !important;
        border-color: var(--rt-primary) !important;
    }
    .rt-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: .4 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .rt-wrapper .rt-page-title { font-size: 1.25rem; }
        .rt-wrapper .rt-filter-card { padding: 1.25rem; }
        .rt-wrapper .rt-stat-value { font-size: 1.1rem; }
        .rt-wrapper .rt-stat-icon { width: 42px; height: 42px; font-size: 1rem; }
    }
</style>

<div class="rt-wrapper">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="rt-page-head">
        <h1 class="rt-page-title">
            <i class="fas fa-file-alt"></i>
            Raport Siswa
        </h1>
        <p class="rt-page-sub">Lihat daftar nilai seluruh siswa berdasarkan kelas, tahun ajaran, dan semester</p>
    </div>

    {{-- ================= FILTER CARD ================= --}}
    <div class="rt-filter-card">
        <div class="rt-filter-inner">
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-6">
                    <div class="rt-filter-label"><i class="fas fa-users"></i> Pilih Kelas</div>
                    <select class="rt-filter-select" id="kelasSelector">
                        @forelse($kelasDiAjar as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama ?? $k->nama_kelas ?? 'Kelas' }}
                                @if($k->jurusan) — {{ $k->jurusan->nama }} @endif
                            </option>
                        @empty
                            <option value="">Tidak ada kelas yang diajar</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <div class="rt-filter-label"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</div>
                    <select class="rt-filter-select" id="tahunAjaran">
                        @foreach($tahunAjaranList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahunAjaran == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <div class="rt-filter-label"><i class="fas fa-clock"></i> Semester</div>
                    <select class="rt-filter-select" id="semester">
                        <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                        <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Semester Genap</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <button class="rt-filter-btn" id="filterBtn">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= ALERT ================= --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px; border:none; border-left:4px solid #ef4444;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ================= STAT CARDS ================= --}}
    <div class="rt-stats">
        <div class="rt-stat-card">
            <div class="rt-stat-icon" style="background:#eef2ff; color:#4f46e5;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="rt-stat-info">
                <div class="rt-stat-label">Total Siswa</div>
                <div class="rt-stat-value">{{ $siswa->count() }}</div>
            </div>
        </div>
        <div class="rt-stat-card">
            <div class="rt-stat-icon" style="background:#d1fae5; color:#059669;">
                <i class="fas fa-book"></i>
            </div>
            <div class="rt-stat-info">
                <div class="rt-stat-label">Mata Pelajaran</div>
                <div class="rt-stat-value">{{ $mapel->count() }}</div>
            </div>
        </div>
        <div class="rt-stat-card">
            <div class="rt-stat-icon" style="background:#fef3c7; color:#d97706;">
                <i class="fas fa-school"></i>
            </div>
            <div class="rt-stat-info">
                <div class="rt-stat-label">Total Kelas</div>
                <div class="rt-stat-value">{{ $kelasDiAjar->count() }}</div>
            </div>
        </div>
        <div class="rt-stat-card">
            <div class="rt-stat-icon" style="background:#dbeafe; color:#2563eb;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="rt-stat-info">
                <div class="rt-stat-label">Rata-rata Keseluruhan</div>
                <div class="rt-stat-value">
                    @php
                        $totalRata = 0; $countRata = 0;
                        foreach ($rataRataSiswa ?? [] as $r) {
                            if ($r > 0) { $totalRata += $r; $countRata++; }
                        }
                        echo $countRata > 0 ? number_format($totalRata / $countRata, 1) : '0';
                    @endphp
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MAIN CARD ================= --}}
    <div class="rt-card">
        <div class="rt-card-head">
            <h5>
                <i class="fas fa-table"></i>
                Daftar Nilai Siswa
            </h5>
            <div class="d-flex gap-2 flex-wrap">
                <span class="rt-badge">
                    <i class="fas fa-user"></i> {{ $siswa->count() }} Siswa
                </span>
                <span class="rt-badge">
                    <i class="fas fa-book"></i> {{ $mapel->count() }} Mapel
                </span>
            </div>
        </div>

        @if($mapel->isEmpty() || $siswa->isEmpty())
            {{-- ===== EMPTY STATE ===== --}}
            <div class="rt-empty">
                <div class="rt-empty-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h5>Belum Ada Data Raport</h5>
                <p>
                    @if($mapel->isEmpty())
                        Anda belum mengajar mata pelajaran apapun di kelas ini.
                    @elseif($siswa->isEmpty())
                        Tidak ada siswa di kelas ini.
                    @endif
                </p>
            </div>
        @else
            <div class="rt-table-wrap">
                <div class="table-responsive">
                    <table class="table rt-table" id="raportTable">
                        <thead>
                            <tr>
                                <th rowspan="2" class="col-no">No</th>
                                <th rowspan="2" class="col-nis">NIS</th>
                                <th rowspan="2" class="col-nama" style="text-align:left;">Nama Siswa</th>
                                @foreach($mapel as $m)
                                    <th colspan="3" class="mapel-header col-nilai">
                                        {{ $m->nama_mapel ?? $m->nama ?? 'Mapel' }}
                                        <br>
                                        <small style="font-weight:400;font-size:.6rem;opacity:.7;">KKM: {{ $m->kkm ?? 75 }}</small>
                                    </th>
                                @endforeach
                                <th rowspan="2" class="col-rata">Rata-rata</th>
                                <th rowspan="2" class="col-status">Status</th>
                                <th rowspan="2" class="col-aksi">Aksi</th>
                            </tr>
                            <tr>
                                @foreach($mapel as $m)
                                    <th class="komponen-header col-nilai">Tugas</th>
                                    <th class="komponen-header col-nilai">UTS</th>
                                    <th class="komponen-header col-nilai">Akhir</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                                @php
                                    $rataRata = $rataRataSiswa[$s->id] ?? 0;
                                    $statusClass = $rataRata >= 75 ? 'lulus' : 'tidak';
                                    $statusText = $rataRata >= 75 ? 'Lulus' : 'Tidak Lulus';
                                    $namaSiswa = $s->nama_lengkap ?? $s->user->name ?? $s->nama ?? '-';
                                    $nisSiswa = $s->nis ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold" style="color:#94a3b8;">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <span class="rt-nis-badge">{{ $nisSiswa }}</span>
                                    </td>
                                    <td class="text-left">
                                        <span class="fw-semibold">{{ $namaSiswa }}</span>
                                    </td>
                                    @foreach($mapel as $m)
                                        @php
                                            $nilai = $dataNilai[$s->id][$m->id] ?? ['tugas' => '-', 'uts' => '-', 'akhir' => '-'];
                                            $akhir = $nilai['akhir'];
                                            $isValid = $akhir !== '-' && $akhir !== null && $akhir > 0;
                                            $classNilai = '';
                                            if ($isValid) {
                                                if ($akhir >= 85) $classNilai = 'nilai-tinggi';
                                                elseif ($akhir >= 70) $classNilai = 'nilai-sedang';
                                                else $classNilai = 'nilai-rendah';
                                            }
                                            $tugas = $nilai['tugas'] ?? '-';
                                            $uts = $nilai['uts'] ?? '-';
                                        @endphp
                                        <td class="text-center">{{ $tugas !== '-' ? number_format($tugas, 1) : '—' }}</td>
                                        <td class="text-center">{{ $uts !== '-' ? number_format($uts, 1) : '—' }}</td>
                                        <td class="text-center nilai-akhir {{ $classNilai }}">
                                            {{ $isValid ? number_format($akhir, 2) : '—' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center">
                                        @if($rataRata > 0)
                                            <span class="rt-badge-kkm {{ $statusClass }}">
                                                {{ number_format($rataRata, 2) }}
                                            </span>
                                        @else
                                            <span class="rt-badge-kkm">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($rataRata > 0)
                                            <span class="rt-badge-kkm {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        @else
                                            <span class="rt-badge-kkm">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="rt-btn-detail"
                                                data-siswa="{{ $s->id }}"
                                                data-nama="{{ $namaSiswa }}"
                                                data-tahun="{{ $tahunAjaran }}"
                                                data-semester="{{ $semester }}"
                                                title="Lihat Detail Raport">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ===== LEGEND ===== --}}
            <div class="rt-legend">
                <span class="rt-legend-item">
                    <span class="rt-legend-dot" style="background:#10b981;"></span>
                    Nilai Tinggi (≥85)
                </span>
                <span class="rt-legend-item">
                    <span class="rt-legend-dot" style="background:#f59e0b;"></span>
                    Nilai Sedang (70-84)
                </span>
                <span class="rt-legend-item">
                    <span class="rt-legend-dot" style="background:#ef4444;"></span>
                    Nilai Rendah (&lt;70)
                </span>
                <span class="rt-legend-item">
                    <span class="rt-legend-dot" style="background:#065f46;"></span>
                    Lulus (≥75)
                </span>
                <span class="rt-legend-item">
                    <span class="rt-legend-dot" style="background:#991b1b;"></span>
                    Tidak Lulus (&lt;75)
                </span>
            </div>
        @endif
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
<div class="modal fade rt-modal" id="detailRaportModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt"></i>
                    Detail Raport — <span id="modalSiswaNama"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data raport...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // ============ DATATABLE ============
        var table = $('#raportTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            order: [[0, 'asc']],
            scrollX: true,
            scrollY: '450px',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3,
                rightColumns: 1
            },
            columnDefs: [
                { orderable: false, targets: '_all' },
                { className: 'text-center', targets: [0, 1, 4, 5, 6, 7, 8] },
                { className: 'text-left', targets: [2] }
            ],
            autoWidth: false,
            processing: true,
            stateSave: true,
            responsive: false
        });

        // ============ FILTER ============
        $('#filterBtn').on('click', function() {
            var kelasId = $('#kelasSelector').val();
            var tahunAjaran = $('#tahunAjaran').val();
            var semester = $('#semester').val();

            if (!kelasId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih kelas terlebih dahulu!',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            var url = '{{ route("guru.nilai.raport") }}' +
                     '?kelas_id=' + encodeURIComponent(kelasId) +
                     '&tahun_ajaran=' + encodeURIComponent(tahunAjaran) +
                     '&semester=' + encodeURIComponent(semester);

            window.location.href = url;
        });

        // ============ DETAIL MODAL ============
        $('.btn-detail').on('click', function() {
            var siswaId = $(this).data('siswa');
            var siswaNama = $(this).data('nama') || 'Siswa';
            var tahunAjaran = $(this).data('tahun') || $('#tahunAjaran').val() || '{{ date("Y") . "/" . (date("Y") + 1) }}';
            var semester = $(this).data('semester') || $('#semester').val() || 'ganjil';

            $('#modalSiswaNama').text(siswaNama);
            $('#modalBody').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data raport...</p>
                </div>
            `);
            $('#detailRaportModal').modal('show');

            var url = '{{ route("guru.nilai.raport.detail", ["siswaId" => ":siswaId"]) }}';
            url = url.replace(':siswaId', siswaId);

            $.ajax({
                url: url,
                data: { tahun_ajaran: tahunAjaran, semester: semester },
                success: function(response) {
                    $('#modalBody').html(generateDetailHtml(response));
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    $('#modalBody').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Gagal memuat data: ${errorMsg}
                        </div>
                    `);
                }
            });
        });
    });

    // ============ GENERATE DETAIL HTML ============
    function generateDetailHtml(data) {
        var siswa = data.siswa || {};
        var nilai = data.nilai || [];
        var rataRata = data.rataRata || 0;
        var totalNilai = data.totalNilai || 0;
        var jumlahMapel = data.jumlahMapel || 0;
        var predikatKeseluruhan = data.predikatKeseluruhan || '-';
        var tahunAjaran = data.tahunAjaran || '-';
        var semester = data.semester || '-';

        var namaSiswa = siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-');
        var nisSiswa = siswa.nis || '-';
        var namaKelas = siswa.kelas ? (siswa.kelas.nama_kelas || siswa.kelas.nama) : '-';

        var html = `
            <div class="row g-3 mb-4">
                <div class="col-md-7">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="100"><strong>Nama</strong></td>
                            <td>: ${namaSiswa}</td>
                        </tr>
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>: ${nisSiswa}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: ${namaKelas}</td>
                        </tr>
                        <tr>
                            <td><strong>Periode</strong></td>
                            <td>: ${tahunAjaran} — ${semester.charAt(0).toUpperCase() + semester.slice(1)}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-5">
                    <div class="card border-0" style="background:linear-gradient(135deg,#eef2ff,#f5f3ff);">
                        <div class="card-body text-center py-4">
                            <div style="font-size:.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">
                                Rata-rata Nilai
                            </div>
                            <h2 class="text-${rataRata >= 75 ? 'success' : 'danger'} mb-2 mt-1" style="font-weight:800;">
                                ${parseFloat(rataRata).toFixed(2)}
                            </h2>
                            <span class="badge bg-${rataRata >= 90 ? 'success' : (rataRata >= 75 ? 'primary' : 'danger')} rounded-pill">
                                ${predikatKeseluruhan}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" style="margin-bottom:0;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="font-size:.72rem;text-transform:uppercase;color:#64748b;">No</th>
                            <th style="font-size:.72rem;text-transform:uppercase;color:#64748b;">Mata Pelajaran</th>
                            <th style="font-size:.72rem;text-transform:uppercase;color:#64748b;text-align:center;">Nilai Akhir</th>
                            <th style="font-size:.72rem;text-transform:uppercase;color:#64748b;text-align:center;">Grade</th>
                            <th style="font-size:.72rem;text-transform:uppercase;color:#64748b;">Predikat</th>
                        </tr>
                    </thead>
                    <tbody>`;

        if (nilai && nilai.length > 0) {
            nilai.forEach(function(n, i) {
                var mapel = n.mapel || {};
                var grade = n.grade || { warna: 'secondary', grade: '-' };
                var predikat = n.predikat_label || '-';

                html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${mapel.nama_mapel || '-'}</td>
                        <td class="text-center fw-bold">${n.nilai_akhir || 0}</td>
                        <td class="text-center">
                            <span class="badge bg-${grade.warna}">${grade.grade}</span>
                        </td>
                        <td>${predikat}</td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada data nilai untuk siswa ini.
                    </td>
                </tr>
            `;
        }

        html += `
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td colspan="2" class="text-end fw-bold">Rata-rata</td>
                            <td class="text-center fw-bold" style="color:#4f46e5;">${parseFloat(rataRata).toFixed(2)}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Jumlah Mapel:</strong> ${jumlahMapel}</p>
                    <p class="mb-0"><strong>Total Nilai:</strong> ${parseFloat(totalNilai).toFixed(2)}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1"><strong>Tahun Ajaran:</strong> ${tahunAjaran}</p>
                    <p class="mb-0"><strong>Semester:</strong> ${semester.charAt(0).toUpperCase() + semester.slice(1)}</p>
                </div>
            </div>
        `;

        return html;
    }
</script>
@endpush
@endsection