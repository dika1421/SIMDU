@extends('administrasi.layouts.header')

@section('title', 'Detail Jadwal')

@section('content')
<style>
    .page-header-show {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-show::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-show .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-show h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-show p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .detail-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-card-header i { color: #7c3aed; font-size: 1.05rem; }
    .detail-card-header h5 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .detail-card-body { padding: 24px; }

    .info-row {
        display: flex;
        padding: 14px 0;
        border-bottom: 1px dashed #f1f5f9;
        align-items: center;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label {
        width: 180px;
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .info-row .label i { color: #8b5cf6; font-size: 1rem; }
    .info-row .value {
        flex: 1;
        font-size: 0.92rem;
        color: #1e293b;
        font-weight: 600;
    }
    .info-row .value.text-normal { font-weight: 500; }
    .info-row .value .sub {
        font-weight: 400;
        color: #94a3b8;
        font-size: 0.78rem;
        display: block;
        margin-top: 2px;
    }

    .badge-hari {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #dbeafe;
        color: #1e40af;
    }
    .jam-badge {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mapel-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid #93c5fd;
    }
    .ruang-badge {
        font-family: 'Courier New', monospace;
        background: #f0fdf4;
        color: #065f46;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .semester-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .semester-badge.ganjil { background: #d1fae5; color: #065f46; }
    .semester-badge.genap  { background: #fef3c7; color: #92400e; }

    .guru-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .guru-avatar-lg {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);
    }
</style>

@php
    $namaKelas = $jadwal->kelas->nama ?? $jadwal->kelas->nama_kelas ?? $jadwal->kelas->kelas ?? '-';
    $namaGuru  = $jadwal->guru->user->name ?? $jadwal->guru->nama_lengkap ?? '-';
    $namaMapel = $jadwal->mapel->nama ?? $jadwal->mataPelajaran->nama ?? '-';
    $initialGuru = strtoupper(substr($namaGuru, 0, 1));
@endphp

<div class="page-header-show">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-calendar-alt me-2"></i>
                Detail Jadwal Pelajaran
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                {{ ucfirst($jadwal->hari) }} · {{ $namaMapel }} · {{ $namaKelas }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.jadwal.index') }}" class="btn-glass">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('administrasi.jadwal.edit', $jadwal->id) }}" class="btn-glass">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="detail-card">
    <div class="detail-card-header">
        <i class="fas fa-info-circle"></i>
        <h5>Informasi Lengkap Jadwal</h5>
    </div>
    <div class="detail-card-body">

        <div class="info-row">
            <div class="label"><i class="fas fa-calendar-day"></i> Hari</div>
            <div class="value">
                <span class="badge-hari">
                    <i class="fas fa-calendar-day"></i>
                    {{ ucfirst($jadwal->hari) }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-clock"></i> Waktu</div>
            <div class="value">
                <span class="jam-badge">
                    <i class="fas fa-clock"></i>
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-school"></i> Kelas</div>
            <div class="value">
                {{ $namaKelas }}
                @if($jadwal->kelas && $jadwal->kelas->jurusan)
                    <span class="sub">{{ $jadwal->kelas->jurusan->nama ?? '' }}</span>
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-book"></i> Mata Pelajaran</div>
            <div class="value">
                <span class="mapel-badge">
                    <i class="fas fa-book"></i>
                    {{ $namaMapel }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-chalkboard-user"></i> Guru Pengajar</div>
            <div class="value">
                <div class="guru-info">
                    <div class="guru-avatar-lg">{{ $initialGuru }}</div>
                    <div>
                        {{ $namaGuru }}
                        @if($jadwal->guru && $jadwal->guru->nip)
                            <span class="sub">NIP: {{ $jadwal->guru->nip }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-door-open"></i> Ruangan</div>
            <div class="value">
                <span class="ruang-badge">
                    <i class="fas fa-door-open"></i>
                    {{ $jadwal->ruangan ?? '-' }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</div>
            <div class="value text-normal">{{ $jadwal->tahun_ajaran ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-calendar-week"></i> Semester</div>
            <div class="value">
                @if($jadwal->semester == 'ganjil')
                    <span class="semester-badge ganjil">Ganjil</span>
                @elseif($jadwal->semester == 'genap')
                    <span class="semester-badge genap">Genap</span>
                @else
                    <span class="semester-badge" style="background:#f1f5f9;color:#64748b;">{{ $jadwal->semester ?? '-' }}</span>
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-toggle-on"></i> Status</div>
            <div class="value text-normal">{{ $jadwal->status ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="label"><i class="fas fa-comment"></i> Keterangan</div>
            <div class="value text-normal">{{ $jadwal->keterangan ?? '-' }}</div>
        </div>

    </div>
</div>
@endsection