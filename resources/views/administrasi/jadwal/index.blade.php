@extends('administrasi.layouts.header')

@section('title', 'Daftar Jadwal')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-jadwal {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-jadwal::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-jadwal::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-jadwal .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-jadwal h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-jadwal p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
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

    /* ========== STAT CARDS ========== */
    .stats-jadwal {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-jadwal-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-jadwal-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-jadwal-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-jadwal-card .stat-icon-s {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .stat-jadwal-card .stat-label-s {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-jadwal-card .stat-value-s {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }
    .stat-total   .stat-icon-s { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .stat-hari    .stat-icon-s { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-mapel   .stat-icon-s { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-kelas   .stat-icon-s { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-total::before   { background: linear-gradient(180deg, #0ea5e9, #0284c7); }
    .stat-hari::before    { background: linear-gradient(180deg, #10b981, #059669); }
    .stat-mapel::before   { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .stat-kelas::before   { background: linear-gradient(180deg, #8b5cf6, #6d28d9); }

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
    .filter-card .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .filter-card .form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
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

    /* ========== TABLE ========== */
    .table-jadwal {
        margin: 0;
        font-size: 0.83rem;
    }
    .table-jadwal thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 13px 12px;
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-jadwal tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-jadwal tbody tr { transition: all 0.2s; }
    .table-jadwal tbody tr:hover { background: #f8fafc; }

    .badge-hari {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .badge-hari.senin  { background: #dbeafe; color: #1e40af; }
    .badge-hari.selasa { background: #d1fae5; color: #065f46; }
    .badge-hari.rabu   { background: #fef3c7; color: #92400e; }
    .badge-hari.kamis  { background: #ede9fe; color: #5b21b6; }
    .badge-hari.jumat  { background: #fce7f3; color: #be185d; }
    .badge-hari.sabtu  { background: #cffafe; color: #155e75; }

    .jam-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #334155;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .mapel-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid #93c5fd;
    }
    .ruang-badge {
        background: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        font-family: 'Courier New', monospace;
    }
    .semester-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .semester-badge.ganjil { background: #d1fae5; color: #065f46; }
    .semester-badge.genap  { background: #fef3c7; color: #92400e; }
    .semester-badge.other  { background: #f1f5f9; color: #64748b; }

    .btn-action {
        width: 32px; height: 32px;
        padding: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: none;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .btn-action.btn-edit {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: #fff;
    }
    .btn-action.btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }

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

    /* ========== MODAL MODERN ========== */
    .modal-modern .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-modern .modal-header {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border: none;
        padding: 20px 24px;
    }
    .modal-modern .modal-title { font-size: 1rem; font-weight: 700; }
    .modal-modern .modal-body { padding: 26px; text-align: center; }
    .modal-modern .modal-footer { border: none; padding: 16px 24px 22px; }
    .modal-icon-big {
        width: 70px; height: 70px;
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #fee2e2;
        color: #dc2626;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header-jadwal { padding: 18px; }
        .page-header-jadwal h1 { font-size: 1.15rem; }
        .table-jadwal { font-size: 0.72rem; }
        .table-jadwal tbody td { padding: 9px 6px; }
    }
</style>

@php
    $totalJadwal = $jadwal->count();
    $totalHari   = $jadwal->pluck('hari')->unique()->count();
    $totalMapel  = $jadwal->pluck('mapel_id')->unique()->count();
    $totalKelas  = $jadwal->pluck('kelas_id')->unique()->count();
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-jadwal">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-calendar-alt me-2"></i>
                Daftar Jadwal Pelajaran
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola jadwal pelajaran per kelas, guru, dan mata pelajaran
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.jadwal.kalender') }}" class="btn-glass">
                <i class="fas fa-calendar-week"></i> Kalender
            </a>
            <a href="{{ route('administrasi.jadwal.create') }}" class="btn-glass">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        </div>
    </div>
</div>

{{-- ============ ALERT ============ --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {!! session('error') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ STAT CARDS ============ --}}
<div class="stats-jadwal">
    <div class="stat-jadwal-card stat-total">
        <div class="stat-icon-s"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-label-s">Total Jadwal</div>
        <div class="stat-value-s">{{ $totalJadwal }}</div>
    </div>
    <div class="stat-jadwal-card stat-hari">
        <div class="stat-icon-s"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-label-s">Hari Aktif</div>
        <div class="stat-value-s">{{ $totalHari }}</div>
    </div>
    <div class="stat-jadwal-card stat-mapel">
        <div class="stat-icon-s"><i class="fas fa-book"></i></div>
        <div class="stat-label-s">Mata Pelajaran</div>
        <div class="stat-value-s">{{ $totalMapel }}</div>
    </div>
    <div class="stat-jadwal-card stat-kelas">
        <div class="stat-icon-s"><i class="fas fa-school"></i></div>
        <div class="stat-label-s">Kelas Terlibat</div>
        <div class="stat-value-s">{{ $totalKelas }}</div>
    </div>
</div>

{{-- ============ FILTER CARD ============ --}}
<div class="filter-card">
    <form method="GET" action="{{ route('administrasi.jadwal.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">
                <i class="fas fa-calendar-day"></i> Hari
            </label>
            <select name="hari" class="form-control">
                <option value="">-- Semua Hari --</option>
                @foreach($hariList as $h)
                    <option value="{{ $h }}" {{ isset($selectedHari) && $selectedHari == $h ? 'selected' : '' }}>
                        {{ ucfirst($h) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">
                <i class="fas fa-school"></i> Kelas
            </label>
            <select name="kelas_id" class="form-control">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ isset($selectedKelasId) && $selectedKelasId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama ?? $k->nama_kelas ?? $k->kelas ?? 'Kelas' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-3 fw-semibold flex-grow-1" style="padding:9px">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-secondary rounded-3" style="padding:9px" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ============ MAIN CARD ============ --}}
<div class="main-card">
    <div class="main-card-header">
        <h5>
            <i class="fas fa-table"></i>
            Data Jadwal Pelajaran
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
            <i class="fas fa-database me-1"></i> {{ $jadwal->count() }} Jadwal
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-jadwal table-hover mb-0" id="jadwalTable">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="10%">Hari</th>
                    <th width="13%">Jam</th>
                    <th width="15%">Kelas</th>
                    <th width="18%">Mata Pelajaran</th>
                    <th width="15%">Guru</th>
                    <th width="10%">Ruangan</th>
                    <th width="10%">Tahun Ajaran</th>
                    <th width="8%">Semester</th>
                    <th width="8%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $key => $j)
                @php
                    $namaMapel = 'Mapel tidak ditemukan';
                    if(isset($j->mapel) && $j->mapel) {
                        $namaMapel = $j->mapel->nama ?? 'Mapel tidak ditemukan';
                    } elseif(isset($j->mataPelajaran) && $j->mataPelajaran) {
                        $namaMapel = $j->mataPelajaran->nama ?? 'Mapel tidak ditemukan';
                    } else {
                        $mapelId = $j->mata_pelajaran_id ?? null;
                        if($mapelId) {
                            $mapel = DB::table('mapel')->where('id', $mapelId)->first();
                            $namaMapel = $mapel ? $mapel->nama : 'ID: ' . $mapelId;
                        }
                    }

                    $namaKelas = 'Kelas tidak ditemukan';
                    if(isset($j->kelas) && $j->kelas) {
                        $namaKelas = $j->kelas->nama ?? $j->kelas->nama_kelas ?? $j->kelas->kelas ?? 'Kelas tidak ditemukan';
                    } else {
                        $kelasId = $j->kelas_id ?? null;
                        if($kelasId) {
                            $kelasData = DB::table('kelas')->where('id', $kelasId)->first();
                            $namaKelas = $kelasData ? ($kelasData->nama ?? 'ID: ' . $kelasId) : 'ID: ' . $kelasId;
                        }
                    }

                    $namaGuru = 'Guru tidak ditemukan';
                    if(isset($j->guru) && $j->guru) {
                        $namaGuru = $j->guru->user->name ?? $j->guru->nama_lengkap ?? $j->guru->nama ?? 'Guru tidak ditemukan';
                    } else {
                        $guruId = $j->guru_id ?? null;
                        if($guruId) {
                            $guruData = DB::table('guru')->where('id', $guruId)->first();
                            if($guruData) {
                                $namaGuru = $guruData->nama_lengkap ?? $guruData->nama ?? 'ID: ' . $guruId;
                            }
                        }
                    }

                    $hariClass = strtolower($j->hari);
                    $initialGuru = strtoupper(substr($namaGuru, 0, 1));
                @endphp
                <tr>
                    <td class="text-center fw-bold text-muted">{{ $key + 1 }}</td>
                    <td>
                        <span class="badge-hari {{ $hariClass }}">
                            <i class="fas fa-calendar-day"></i>
                            {{ ucfirst($j->hari) }}
                        </span>
                    </td>
                    <td>
                        <span class="jam-badge">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $namaKelas }}</div>
                        @if(isset($j->kelas) && $j->kelas && $j->kelas->jurusan)
                            <small class="text-muted">{{ $j->kelas->jurusan->nama ?? '' }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="mapel-badge">
                            <i class="fas fa-book"></i>
                            {{ Str::limit($namaMapel, 30) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                                {{ $initialGuru }}
                            </div>
                            <span class="fw-semibold text-dark text-truncate" style="max-width:140px;">
                                {{ $namaGuru }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="ruang-badge">
                            <i class="fas fa-door-open me-1"></i>
                            {{ $j->ruangan ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                            {{ $j->tahun_ajaran ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if($j->semester == 'ganjil')
                            <span class="semester-badge ganjil">Ganjil</span>
                        @elseif($j->semester == 'genap')
                            <span class="semester-badge genap">Genap</span>
                        @else
                            <span class="semester-badge other">{{ $j->semester ?? '-' }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('administrasi.jadwal.edit', $j->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn-action btn-delete" title="Hapus"
                                    onclick="deleteJadwal({{ $j->id }}, '{{ ucfirst($j->hari) }} - {{ addslashes($namaMapel) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Belum ada data jadwal</h5>
                            <p class="text-muted mb-3">Silakan tambahkan jadwal pelajaran terlebih dahulu</p>
                            <a href="{{ route('administrasi.jadwal.create') }}" class="btn btn-primary rounded-3">
                                <i class="fas fa-plus me-1"></i> Tambah Jadwal
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ============ DELETE MODAL ============ --}}
<div class="modal fade modal-modern" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-big">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="fw-bold mb-2">Yakin ingin menghapus jadwal?</h5>
                <p class="text-muted mb-0" id="deleteInfo">-</p>
                <div class="alert alert-warning mb-0 mt-3 rounded-3 text-start small">
                    <i class="fas fa-info-circle me-2"></i>
                    Data yang dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary flex-fill rounded-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger flex-fill rounded-3">
                        <i class="fas fa-trash-alt me-1"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        if ($('#jadwalTable').length && $('#jadwalTable tbody tr').length > 1) {
            if ($.fn.DataTable) {
                $('#jadwalTable').DataTable({
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                    pageLength: 10,
                    order: [[1, 'asc']],
                    responsive: true,
                    columnDefs: [{ orderable: false, targets: [9] }]
                });
            }
        }
    });

    function deleteJadwal(id, info) {
        document.getElementById('deleteInfo').innerText = info;
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.jadwal.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
@endsection