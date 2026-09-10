@extends('administrasi.layouts.header')

@section('title', 'Detail Pesan')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-show {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
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
    .page-header-show h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-show p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 9px 18px;
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
        margin-bottom: 20px;
    }
    .detail-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .detail-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-card-header h5 i { color: #0284c7; }
    .detail-card-body { padding: 24px; }

    /* ========== MESSAGE HERO ========== */
    .msg-hero {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 14px;
        border: 1px solid #bae6fd;
        margin-bottom: 20px;
    }
    .msg-hero-avatar {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 6px 16px rgba(14, 165, 233, 0.3);
    }
    .msg-hero-info { flex: 1; min-width: 0; }
    .msg-hero-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0c4a6e;
        margin-bottom: 6px;
        word-break: break-word;
    }
    .msg-hero-meta {
        font-size: 0.8rem;
        color: #0369a1;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .msg-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .msg-hero-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    /* ========== BADGES ========== */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-modern.urgent {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
    .badge-modern.broadcast {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
    }
    .badge-modern.personal {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    .badge-modern.success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    .badge-modern.info {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
    }
    .badge-modern.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }

    /* ========== MESSAGE CONTENT ========== */
    .msg-content {
        padding: 20px;
        background: #f8fafc;
        border-radius: 14px;
        border-left: 4px solid #0ea5e9;
        font-size: 0.9rem;
        color: #334155;
        line-height: 1.7;
    }

    /* ========== PENERIMA TABLE ========== */
    .penerima-table {
        margin: 0;
        font-size: 0.82rem;
    }
    .penerima-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 12px 14px;
        white-space: nowrap;
    }
    .penerima-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .penerima-table tbody tr:hover {
        background: #f8fafc;
    }
    .penerima-avatar {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        flex-shrink: 0;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
    }
    .status-chip.dibaca {
        background: #d1fae5;
        color: #065f46;
    }
    .status-chip.belum {
        background: #fef3c7;
        color: #92400e;
    }

    /* ========== ACTION FOOTER ========== */
    .detail-footer {
        padding: 16px 22px;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .detail-footer .meta-info {
        font-size: 0.78rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-delete-modern {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 0.82rem;
        font-weight: 700;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-delete-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        color: #fff;
    }

    .info-box-blue {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 1px solid #93c5fd;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e40af;
        font-size: 0.85rem;
    }
    .info-box-blue i { font-size: 1.2rem; }

    @media (max-width: 768px) {
        .page-header-show { padding: 18px; }
        .page-header-show h1 { font-size: 1.15rem; }
        .detail-card-body { padding: 18px; }
        .msg-hero { flex-direction: column; }
    }
</style>

@php
    $pengirimName = $pesan->pengirim->name ?? 'Tidak diketahui';
    $pengirimInitial = strtoupper(substr($pengirimName, 0, 1));
    $isBroadcast = isset($pesan->jenis) && $pesan->jenis == 'broadcast';
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-show">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-envelope-open me-2"></i>
                Detail Pesan
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Informasi lengkap pesan
            </p>
        </div>
        <a href="{{ route('administrasi.komunikasi.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ MAIN CARD ========== --}}
<div class="detail-card">
    <div class="detail-card-header">
        <h5>
            <i class="fas fa-envelope"></i>
            Isi Pesan
        </h5>
        <div class="d-flex gap-2 flex-wrap">
            @if(isset($pesan->is_urgent) && $pesan->is_urgent)
                <span class="badge-modern urgent"><i class="fas fa-exclamation"></i> Penting</span>
            @endif
            @if($isBroadcast)
                <span class="badge-modern broadcast"><i class="fas fa-bullhorn"></i> Broadcast</span>
            @else
                <span class="badge-modern personal"><i class="fas fa-user"></i> Personal</span>
            @endif
        </div>
    </div>
    <div class="detail-card-body">

        {{-- Hero Pengirim --}}
        <div class="msg-hero">
            <div class="msg-hero-avatar">{{ $pengirimInitial }}</div>
            <div class="msg-hero-info">
                <div class="msg-hero-title">{{ $pesan->judul ?? 'Tanpa Judul' }}</div>
                <div class="msg-hero-meta">
                    <span><i class="fas fa-user"></i> {{ $pengirimName }}</span>
                    <span><i class="fas fa-tag"></i> {{ ucfirst($pesan->pengirim->role ?? '-') }}</span>
                    <span><i class="fas fa-clock"></i> {{ $pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="msg-hero-badges">
                    @if(isset($pesan->status))
                        @if($pesan->status == 'terkirim')
                            <span class="badge-modern success"><i class="fas fa-check"></i> Terkirim</span>
                        @elseif($pesan->status == 'dibaca')
                            <span class="badge-modern info"><i class="fas fa-eye"></i> Dibaca</span>
                        @else
                            <span class="badge-modern warning">{{ ucfirst($pesan->status) }}</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Isi Pesan --}}
        <div class="form-section-title" style="font-size:0.78rem;font-weight:700;color:#0284c7;text-transform:uppercase;letter-spacing:0.6px;padding-bottom:10px;margin-bottom:14px;border-bottom:2px solid #e0f2fe;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-comment"></i> Isi Pesan
        </div>
        <div class="msg-content">
            {!! nl2br(e($pesan->isi ?? '-')) !!}
        </div>

        {{-- Info Broadcast --}}
        @if($isBroadcast)
            <div class="info-box-blue mt-4">
                <i class="fas fa-bullhorn"></i>
                <div>
                    Pesan ini dikirim sebagai <strong>Broadcast</strong> ke semua pengguna.
                    @if(isset($pesan->penerimaPesan))
                        <br>Total penerima: <strong>{{ $pesan->penerimaPesan->count() }}</strong> orang
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ============ PENERIMA (untuk personal) ========== --}}
    @if(!$isBroadcast)
    <div style="padding:0 24px 24px;">
        <div class="form-section-title" style="font-size:0.78rem;font-weight:700;color:#0284c7;text-transform:uppercase;letter-spacing:0.6px;padding-bottom:10px;margin-bottom:14px;border-bottom:2px solid #e0f2fe;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-users"></i> Daftar Penerima
        </div>

        @php
            $penerimaList = $pesan->penerimaPesan ?? $pesan->penerima ?? [];
        @endphp

        @if(count($penerimaList) > 0)
            <div class="table-responsive">
                <table class="penerima-table table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama</th>
                            <th width="15%">Role</th>
                            <th width="20%">Status</th>
                            <th width="20%">Waktu Dibaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penerimaList as $index => $penerimaItem)
                        @php
                            $penerimaUser = $penerimaItem->penerima ?? $penerimaItem->user ?? null;
                            $penerimaName = $penerimaUser->name ?? 'Tidak diketahui';
                            $initial = strtoupper(substr($penerimaName, 0, 1));
                            $status = $penerimaItem->status ?? '-';
                            $tanggalBaca = $penerimaItem->tanggal_baca ?? $penerimaItem->dibaca_at ?? null;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="penerima-avatar">{{ $initial }}</div>
                                    <span class="fw-semibold">{{ $penerimaName }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ ucfirst($penerimaUser->role ?? '-') }}
                                </span>
                            </td>
                            <td>
                                @if($status == 'dibaca')
                                    <span class="status-chip dibaca"><i class="fas fa-check-circle"></i> Sudah Dibaca</span>
                                @elseif($status == 'terkirim')
                                    <span class="status-chip belum"><i class="fas fa-clock"></i> Belum Dibaca</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($tanggalBaca)
                                    <small>{{ \Carbon\Carbon::parse($tanggalBaca)->format('d/m/Y H:i') }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info rounded-3">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada data penerima
            </div>
        @endif
    </div>
    @endif

    {{-- ============ FOOTER ========== --}}
    <div class="detail-footer">
        <div class="meta-info">
            <i class="fas fa-clock"></i>
            Dikirim: {{ $pesan->created_at ? \Carbon\Carbon::parse($pesan->created_at)->diffForHumans() : '-' }}
        </div>
        <div>
            @if(auth()->id() == ($pesan->pengirim_id ?? null))
                <button type="button" class="btn-delete-modern" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Hapus Pesan
                </button>
            @endif
        </div>
    </div>
</div>

{{-- HIDDEN DELETE FORM --}}
<form id="deleteForm" action="{{ route('administrasi.komunikasi.destroy', $pesan->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Pesan?',
            text: 'Pesan ini akan dihapus permanen dan tidak dapat dikembalikan.',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endpush
@endsection