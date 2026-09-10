@extends('administrasi.layouts.header')

@section('title', 'Pesan & Komunikasi')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-kom {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-kom::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-kom .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-kom h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-kom p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    /* ========== STATS ========== */
    .stats-kom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card-k {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-card-k::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
    }
    .stat-card-k:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-card-k .stat-icon-k {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-card-k .stat-info-k { flex: 1; min-width: 0; }
    .stat-card-k .stat-label-k {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }
    .stat-card-k .stat-value-k {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .sk-inbox .stat-icon-k { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .sk-sent .stat-icon-k  { background: linear-gradient(135deg, #10b981, #059669); }
    .sk-unread .stat-icon-k{ background: linear-gradient(135deg, #f59e0b, #d97706); }
    .sk-inbox::before { background: linear-gradient(180deg, #6366f1, #4f46e5); }
    .sk-sent::before  { background: linear-gradient(180deg, #10b981, #059669); }
    .sk-unread::before{ background: linear-gradient(180deg, #f59e0b, #d97706); }

    /* ========== TABS ========== */
    .nav-tabs-modern {
        border: none;
        background: #f1f5f9;
        border-radius: 14px;
        padding: 6px;
        display: inline-flex;
        gap: 4px;
        margin-bottom: 20px;
    }
    .nav-tabs-modern .nav-link {
        border: none;
        border-radius: 10px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 20px;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .nav-tabs-modern .nav-link:hover {
        color: #334155;
        background: rgba(255,255,255,0.6);
    }
    .nav-tabs-modern .nav-link.active {
        background: #fff;
        color: #4f46e5;
        box-shadow: 0 3px 8px rgba(99, 102, 241, 0.15);
    }
    .nav-tabs-modern .nav-link .badge {
        font-size: 0.65rem;
        padding: 3px 7px;
    }

    /* ========== MESSAGE LIST ========== */
    .msg-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .msg-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .msg-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .msg-card-header h5 i { color: #4f46e5; }

    .msg-item {
        display: flex;
        gap: 14px;
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        align-items: flex-start;
        position: relative;
    }
    .msg-item:last-child { border-bottom: none; }
    .msg-item:hover {
        background: #f8fafc;
    }
    .msg-item.unread {
        background: linear-gradient(90deg, #eef2ff 0%, #ffffff 100%);
        border-left: 3px solid #6366f1;
    }
    .msg-item.unread:hover {
        background: linear-gradient(90deg, #e0e7ff 0%, #f8fafc 100%);
    }

    .msg-avatar {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .msg-avatar.siswa  { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .msg-avatar.guru   { background: linear-gradient(135deg, #10b981, #059669); }
    .msg-avatar.admin  { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .msg-avatar.default{ background: linear-gradient(135deg, #6366f1, #4f46e5); }

    .msg-body { flex: 1; min-width: 0; }
    .msg-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 6px;
    }
    .msg-sender {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .msg-sender .role-tag {
        font-size: 0.62rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .msg-time {
        font-size: 0.7rem;
        color: #94a3b8;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .msg-title {
        font-size: 0.88rem;
        color: #334155;
        font-weight: 600;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .msg-item.unread .msg-title {
        color: #1e293b;
    }
    .msg-preview {
        font-size: 0.78rem;
        color: #94a3b8;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .badge-new {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-urgent {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-broadcast {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-personal {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-sent {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #d1fae5;
        color: #065f46;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .msg-arrow {
        color: #cbd5e1;
        font-size: 0.9rem;
        align-self: center;
        transition: all 0.2s;
    }
    .msg-item:hover .msg-arrow {
        color: #4f46e5;
        transform: translateX(3px);
    }

    /* ========== EMPTY STATE ========== */
    .empty-state-k {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-k-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-size: 2.5rem;
    }

    /* ========== PAGINATION ========== */
    .pagination-wrapper {
        padding: 16px 22px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .pagination-wrapper .text-info-pg {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .page-header-kom { padding: 18px; }
        .page-header-kom h1 { font-size: 1.15rem; }
        .msg-item { padding: 14px 16px; }
        .msg-avatar { width: 40px; height: 40px; font-size: 0.9rem; }
    }
</style>

@php
    $totalMasuk     = isset($pesanDiterima) ? ($pesanDiterima->total() ?? $pesanDiterima->count()) : 0;
    $totalKeluar    = isset($pesanDikirim) ? ($pesanDikirim->total() ?? $pesanDikirim->count()) : 0;
    $totalBelumBaca = $belumDibaca ?? 0;
@endphp

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-kom">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-envelope me-2"></i>
                Pesan & Komunikasi
                @if($totalBelumBaca > 0)
                    <span class="badge" style="background:rgba(255,255,255,0.25);font-size:0.7rem;vertical-align:middle;">{{ $totalBelumBaca }} Baru</span>
                @endif
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kelola pesan masuk dan terkirim dengan mudah
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('administrasi.komunikasi.broadcast') }}" class="btn-glass">
                <i class="fas fa-bullhorn"></i> Broadcast
            </a>
            <a href="{{ route('administrasi.komunikasi.create') }}" class="btn-glass">
                <i class="fas fa-plus"></i> Pesan Baru
            </a>
        </div>
    </div>
</div>

{{-- ============ ALERT ========== --}}
@foreach(['success', 'error', 'info'] as $type)
    @if(session($type))
        <div class="alert alert-{{ $type == 'error' ? 'danger' : $type }} alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="fas fa-{{ $type == 'success' ? 'check-circle' : ($type == 'error' ? 'exclamation-circle' : 'info-circle') }} me-2"></i>
            {{ session($type) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

{{-- ============ STATS ========== --}}
<div class="stats-kom">
    <div class="stat-card-k sk-inbox">
        <div class="stat-icon-k"><i class="fas fa-inbox"></i></div>
        <div class="stat-info-k">
            <div class="stat-label-k">Pesan Masuk</div>
            <div class="stat-value-k">{{ $totalMasuk }}</div>
        </div>
    </div>
    <div class="stat-card-k sk-sent">
        <div class="stat-icon-k"><i class="fas fa-paper-plane"></i></div>
        <div class="stat-info-k">
            <div class="stat-label-k">Terkirim</div>
            <div class="stat-value-k">{{ $totalKeluar }}</div>
        </div>
    </div>
    <div class="stat-card-k sk-unread">
        <div class="stat-icon-k"><i class="fas fa-envelope"></i></div>
        <div class="stat-info-k">
            <div class="stat-label-k">Belum Dibaca</div>
            <div class="stat-value-k">{{ $totalBelumBaca }}</div>
        </div>
    </div>
</div>

{{-- ============ TABS ========== --}}
<ul class="nav nav-tabs-modern" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#masuk" type="button">
            <i class="fas fa-inbox"></i> Pesan Masuk
            @if($totalBelumBaca > 0)
                <span class="badge bg-danger">{{ $totalBelumBaca }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#keluar" type="button">
            <i class="fas fa-paper-plane"></i> Pesan Terkirim
            @if($totalKeluar > 0)
                <span class="badge bg-secondary">{{ $totalKeluar }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content">
    {{-- ============ TAB PESAN MASUK ========== --}}
    <div class="tab-pane fade show active" id="masuk">
        <div class="msg-card">
            <div class="msg-card-header">
                <h5>
                    <i class="fas fa-inbox"></i>
                    Kotak Masuk
                </h5>
                @if($totalMasuk > 0)
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                        <i class="fas fa-envelope me-1"></i> {{ $totalMasuk }} Pesan
                    </span>
                @endif
            </div>

            @if(isset($pesanDiterima) && $pesanDiterima->count() > 0)
                <div>
                    @foreach($pesanDiterima as $p)
                        @php
                            $penerimaData = $p->penerimaPesan->where('penerima_id', auth()->id())->first();
                            $sudahDibaca = $penerimaData ? ($penerimaData->status == 'dibaca') : false;
                            $senderRole = $p->pengirim->role ?? 'default';
                            $avatarClass = in_array($senderRole, ['siswa', 'guru', 'administrasi']) ? $senderRole : 'default';
                            $initial = strtoupper(substr($p->pengirim->name ?? 'U', 0, 1));
                            $preview = \Illuminate\Support\Str::limit(strip_tags($p->isi ?? ''), 100);
                        @endphp
                        <a href="{{ route('administrasi.komunikasi.show', $p->id) }}"
                           class="msg-item {{ !$sudahDibaca ? 'unread' : '' }}">
                            <div class="msg-avatar {{ $avatarClass }}">{{ $initial }}</div>
                            <div class="msg-body">
                                <div class="msg-top">
                                    <div class="msg-sender">
                                        {{ $p->pengirim->name ?? 'Tidak Diketahui' }}
                                        <span class="role-tag">{{ ucfirst($p->pengirim->role ?? '-') }}</span>
                                        @if(!$sudahDibaca)
                                            <span class="badge-new"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Baru</span>
                                        @endif
                                        @if($p->is_urgent)
                                            <span class="badge-urgent"><i class="fas fa-exclamation"></i> Penting</span>
                                        @endif
                                        @if($p->jenis == 'broadcast')
                                            <span class="badge-broadcast"><i class="fas fa-bullhorn"></i> Broadcast</span>
                                        @endif
                                    </div>
                                    <div class="msg-time">
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="msg-title">
                                    <i class="fas fa-file-alt text-muted" style="font-size:0.75rem;"></i>
                                    {{ Str::limit($p->judul, 70) }}
                                </div>
                                @if($preview)
                                    <div class="msg-preview">{{ $preview }}</div>
                                @endif
                            </div>
                            <div class="msg-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if(method_exists($pesanDiterima, 'links'))
                    <div class="pagination-wrapper">
                        <div class="text-info-pg">
                            <i class="fas fa-list me-1"></i>
                            Menampilkan {{ $pesanDiterima->firstItem() ?? 0 }} - {{ $pesanDiterima->lastItem() ?? 0 }}
                            dari {{ $pesanDiterima->total() ?? 0 }}
                        </div>
                        <div>{{ $pesanDiterima->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            @else
                <div class="empty-state-k">
                    <div class="empty-state-k-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Belum Ada Pesan Masuk</h5>
                    <p class="text-muted mb-3">Anda belum memiliki pesan masuk</p>
                    <a href="{{ route('administrasi.komunikasi.create') }}" class="btn btn-primary rounded-3">
                        <i class="fas fa-plus me-1"></i> Buat Pesan Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ============ TAB PESAN TERKIRIM ========== --}}
    <div class="tab-pane fade" id="keluar">
        <div class="msg-card">
            <div class="msg-card-header">
                <h5>
                    <i class="fas fa-paper-plane"></i>
                    Pesan Terkirim
                </h5>
                @if($totalKeluar > 0)
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                        <i class="fas fa-paper-plane me-1"></i> {{ $totalKeluar }} Pesan
                    </span>
                @endif
            </div>

            @if(isset($pesanDikirim) && $pesanDikirim->count() > 0)
                <div>
                    @foreach($pesanDikirim as $p)
                        @php
                            $preview = \Illuminate\Support\Str::limit(strip_tags($p->isi ?? ''), 100);
                        @endphp
                        <a href="{{ route('administrasi.komunikasi.show', $p->id) }}" class="msg-item">
                            <div class="msg-avatar default">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="msg-body">
                                <div class="msg-top">
                                    <div class="msg-sender">
                                        {{ Str::limit($p->judul, 60) }}
                                        @if($p->is_urgent)
                                            <span class="badge-urgent"><i class="fas fa-exclamation"></i> Penting</span>
                                        @endif
                                        @if($p->jenis == 'broadcast')
                                            <span class="badge-broadcast"><i class="fas fa-bullhorn"></i> Broadcast</span>
                                        @else
                                            <span class="badge-personal"><i class="fas fa-user"></i> Personal</span>
                                        @endif
                                    </div>
                                    <div class="msg-time">
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="msg-title">
                                    <span class="badge-sent"><i class="fas fa-check"></i> Terkirim</span>
                                    <span style="font-size:0.75rem;color:#94a3b8;font-weight:500;">
                                        {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                @if($preview)
                                    <div class="msg-preview">{{ $preview }}</div>
                                @endif
                            </div>
                            <div class="msg-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if(method_exists($pesanDikirim, 'links'))
                    <div class="pagination-wrapper">
                        <div class="text-info-pg">
                            <i class="fas fa-list me-1"></i>
                            Menampilkan {{ $pesanDikirim->firstItem() ?? 0 }} - {{ $pesanDikirim->lastItem() ?? 0 }}
                            dari {{ $pesanDikirim->total() ?? 0 }}
                        </div>
                        <div>{{ $pesanDikirim->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            @else
                <div class="empty-state-k">
                    <div class="empty-state-k-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Belum Ada Pesan Terkirim</h5>
                    <p class="text-muted mb-3">Anda belum mengirim pesan apapun</p>
                    <a href="{{ route('administrasi.komunikasi.create') }}" class="btn btn-primary rounded-3">
                        <i class="fas fa-plus me-1"></i> Buat Pesan Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection