@extends('administrasi.layouts.header')

@section('title', 'Atur Hak Akses User')

@section('content')
<style>
.up-edit-wrapper {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-warning: #f59e0b;
    --up-warning-dark: #d97706;
    --up-success: #10b981;
    --up-danger: #dc2626;
    --up-text: #1e293b;
    --up-text-muted: #64748b;
    --up-text-light: #94a3b8;
    --up-bg-soft: #f8fafc;
    --up-border: #e2e8f0;
    --up-radius: 16px;
    --up-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    color: var(--up-text);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-bottom: 2rem;
}
.up-edit-wrapper .up-page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-edit-wrapper .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-edit-wrapper .up-page-title i { color: var(--up-primary); }
.up-edit-wrapper .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}
.up-edit-wrapper .up-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    padding: 10px 20px !important;
    font-size: .85rem !important;
    font-weight: 700 !important;
    border: none !important;
    border-radius: 999px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: transform .2s, box-shadow .2s !important;
    white-space: nowrap !important;
}
.up-edit-wrapper .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25) !important;
}
.up-edit-wrapper .up-btn-primary:hover {
    transform: translateY(-2px) !important;
    color: #fff !important;
}
.up-edit-wrapper .up-btn-outline {
    background: #fff !important;
    color: var(--up-text-muted) !important;
    border: 1.5px solid var(--up-border) !important;
}
.up-edit-wrapper .up-btn-outline:hover {
    background: var(--up-bg-soft) !important;
    color: var(--up-text) !important;
}
.up-edit-wrapper .up-btn-light {
    background: #fff !important;
    color: var(--up-primary) !important;
    font-weight: 700 !important;
}
.up-edit-wrapper .up-btn-light-outline {
    background: transparent !important;
    color: #fff !important;
    border: 1.5px solid rgba(255,255,255,.5) !important;
}
.up-edit-wrapper .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}
.up-edit-wrapper .up-info-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    padding: 1.5rem !important;
    margin-bottom: 1.5rem !important;
}
.up-edit-wrapper .up-avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    flex-shrink: 0;
    text-transform: uppercase;
}
.up-edit-wrapper .up-info-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.up-edit-wrapper .up-info-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0 0 4px 0;
}
.up-edit-wrapper .up-info-email {
    color: var(--up-text-light);
    font-size: .85rem;
    margin: 0 0 8px 0;
}
.up-edit-wrapper .up-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}
.up-edit-wrapper .up-badge-role {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
}
.up-edit-wrapper .up-badge-norole {
    background: #94a3b8;
    color: #fff;
}
.up-edit-wrapper .up-info-note {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: .85rem;
    color: #1e40af;
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.up-edit-wrapper .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.up-edit-wrapper .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-edit-wrapper .up-card-head small {
    color: rgba(255,255,255,.85);
    font-size: .8rem;
}
.up-edit-wrapper .up-card-body {
    padding: 1.5rem;
    background: var(--up-bg-soft);
}
.up-edit-wrapper .up-group-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}
.up-edit-wrapper .up-group {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(15,23,42,.06);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.up-edit-wrapper .up-group:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15,23,42,.1);
}
.up-edit-wrapper .up-group-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
}
.up-edit-wrapper .up-group-title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-edit-wrapper .up-group-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #eef2ff;
    color: var(--up-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
}
.up-edit-wrapper .up-group-count {
    background: var(--up-primary);
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
}
.up-edit-wrapper .up-group-body {
    padding: 8px 0;
}
.up-edit-wrapper .up-perm-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 18px;
    transition: background .15s;
}
.up-edit-wrapper .up-perm-item:hover {
    background: #f8faff;
}
.up-edit-wrapper .up-perm-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-grow: 1;
    min-width: 0;
}
.up-edit-wrapper .up-perm-label {
    font-size: .82rem;
    color: #334155;
    cursor: pointer;
    word-break: break-word;
}
.up-edit-wrapper .form-check-input {
    cursor: pointer;
    width: 2.4em;
    height: 1.2em;
    margin: 0;
}
.up-edit-wrapper .form-check-input:checked {
    background-color: var(--up-primary);
    border-color: var(--up-primary);
}
.up-edit-wrapper .up-perm-badges {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}
.up-edit-wrapper .up-badge-role-tag {
    background: #dcfce7;
    color: #166534;
    font-size: .65rem;
    padding: 2px 8px;
    border-radius: 999px;
    font-weight: 700;
}
.up-edit-wrapper .up-badge-custom-tag {
    background: #fef3c7;
    color: #92400e;
    font-size: .65rem;
    padding: 2px 8px;
    border-radius: 999px;
    font-weight: 700;
}
.up-edit-wrapper .up-card-foot {
    background: #fff;
    border-top: 1px solid var(--up-border);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.up-edit-wrapper .up-card-foot .up-foot-info {
    color: var(--up-text-muted);
    font-size: .85rem;
}
.up-edit-wrapper .up-foot-buttons {
    display: flex;
    gap: 8px;
}
@media (max-width: 768px) {
    .up-edit-wrapper .up-card-foot {
        flex-direction: column;
        align-items: stretch;
    }
    .up-edit-wrapper .up-foot-buttons {
        justify-content: stretch;
    }
    .up-edit-wrapper .up-foot-buttons .up-btn {
        flex: 1;
    }
}
</style>

<div class="up-edit-wrapper">

    {{-- Page Header --}}
    <div class="up-page-head">
        <div>
            <h1 class="up-page-title">
                <i class="fas fa-shield-alt"></i>
                Atur Hak Akses User
            </h1>
            <p class="up-page-sub">Override permission khusus untuk user ini</p>
        </div>
        <a href="{{ route('administrasi.user-permission.index') }}" class="up-btn up-btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Info User --}}
    <div class="up-info-card">
        <div class="up-info-row">
            <div class="up-avatar-circle">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:200px;">
                <h5 class="up-info-name">{{ $user->name }}</h5>
                <p class="up-info-email">
                    <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                </p>
                <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                    <span style="font-size:.75rem; color:var(--up-text-muted); font-weight:600;">ROLE:</span>
                    @php
                        $displayRoles = collect();
                        if ($user->roles->count() > 0) {
                            $displayRoles = $user->roles->pluck('name');
                        } elseif ($role) {
                            $displayRoles = collect([$role->name]);
                        } elseif (!empty($user->role)) {
                            $displayRoles = collect([$user->role]);
                        }
                    @endphp

                    @forelse($displayRoles as $roleName)
                        <span class="up-badge up-badge-role">{{ ucfirst($roleName) }}</span>
                    @empty
                        <span class="up-badge up-badge-norole">No Role</span>
                    @endforelse
                </div>
            </div>
            <div style="flex:1 1 300px; min-width:280px;">
                <div class="up-info-note">
                    <i class="fas fa-info-circle" style="margin-top:2px;"></i>
                    <div>
                        Permission default berasal dari <strong>Role</strong>.
                        Kamu bisa <em>override</em> permission khusus untuk user ini —
                        permission yang <strong>sama dengan role</strong> tidak akan disimpan sebagai override.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('administrasi.user-permission.update', ['id' => $user->id]) }}"
          method="POST" id="permissionForm">
        @csrf
        @method('PUT')

        <div class="up-card">
            <div class="up-card-head">
                <div>
                    <h5><i class="fas fa-key"></i> Daftar Permission</h5>
                    <small><i class="fas fa-check-circle me-1"></i>
                        <span id="selectedCount">0</span> permission dipilih
                    </small>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="button" class="up-btn up-btn-light" onclick="selectAll(true)">
                        <i class="fas fa-check-double"></i> Pilih Semua
                    </button>
                    <button type="button" class="up-btn up-btn-light-outline" onclick="selectAll(false)">
                        <i class="fas fa-times"></i> Hapus Semua
                    </button>
                </div>
            </div>

            <div class="up-card-body">
                @php
                    // Group permission berdasarkan prefix nama (sebelum tanda '-')
                    $grouped = $allPermissions->groupBy(function($perm) {
                        $parts = explode('-', $perm->name);
                        return $parts[0] ?? 'lainnya';
                    });
                @endphp

                <div class="up-group-grid">
                    @foreach($grouped as $group => $permissions)
                        <div class="up-group">
                            <div class="up-group-head">
                                <h6 class="up-group-title">
                                    <span class="up-group-icon">
                                        <i class="fas fa-folder"></i>
                                    </span>
                                    {{ ucfirst($group) }}
                                </h6>
                                <span class="up-group-count">{{ $permissions->count() }}</span>
                            </div>
                            <div class="up-group-body">
                                @foreach($permissions as $perm)
                                    @php
                                        $fromRole = in_array($perm->name, $rolePermissions);
                                        $override = $userOverrides[$perm->id] ?? null;
                                        $checked  = ($override !== null) ? $override : $fromRole;
                                    @endphp
                                    <div class="up-perm-item">
                                        <div class="up-perm-left">
                                            <div class="form-check form-switch" style="margin:0;">
                                                <input class="form-check-input permission-checkbox"
                                                       type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $perm->name }}"
                                                       id="perm_{{ $perm->id }}"
                                                       {{ $checked ? 'checked' : '' }}>
                                            </div>
                                            <label class="up-perm-label" for="perm_{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                        <div class="up-perm-badges">
                                            @if($fromRole && $override === null)
                                                <span class="up-badge-role-tag">
                                                    <i class="fas fa-check"></i> Role
                                                </span>
                                            @elseif($override !== null)
                                                <span class="up-badge-custom-tag">
                                                    <i class="fas fa-star"></i> Custom
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="up-card-foot">
                <div class="up-foot-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Permission yang <strong>sama dengan role</strong> tidak akan disimpan sebagai override.
                </div>
                <div class="up-foot-buttons">
                    <a href="{{ route('administrasi.user-permission.index') }}" class="up-btn up-btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="up-btn up-btn-primary">
                        <i class="fas fa-save"></i> Simpan Hak Akses
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function selectAll(checked) {
        document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
        updateCount();
    }

    function updateCount() {
        const total = document.querySelectorAll('.permission-checkbox:checked').length;
        const el = document.getElementById('selectedCount');
        if (el) el.textContent = total;
    }

    document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
        cb.addEventListener('change', updateCount);
    });

    document.addEventListener('DOMContentLoaded', updateCount);
</script>
@endsection