@extends('administrasi.layouts.header')

@section('title', 'Atur Role User')

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
    color: rgba(255,255,255,.9);
    font-size: .8rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.up-edit-wrapper .up-card-body {
    padding: 2rem 1.5rem;
    background: var(--up-bg-soft);
}

/* Role Checkbox Grid */
.up-edit-wrapper .up-role-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1rem;
}
.up-edit-wrapper .up-role-option {
    position: relative;
}
.up-edit-wrapper .up-role-option input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.up-edit-wrapper .up-role-label {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 20px;
    background: #fff;
    border: 2px solid var(--up-border);
    border-radius: 14px;
    cursor: pointer;
    transition: all .2s ease;
    margin: 0;
    width: 100%;
}
.up-edit-wrapper .up-role-label:hover {
    border-color: var(--up-primary);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79,70,229,.12);
}
.up-edit-wrapper .up-role-option input[type="checkbox"]:checked + .up-role-label {
    border-color: var(--up-primary);
    background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
    box-shadow: 0 6px 18px rgba(79,70,229,.18);
}
.up-edit-wrapper .up-role-option input[type="checkbox"]:checked + .up-role-label .up-role-icon {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
}
.up-edit-wrapper .up-role-option input[type="checkbox"]:checked + .up-role-label .up-role-check {
    opacity: 1;
    transform: scale(1);
}
.up-edit-wrapper .up-role-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #f1f5f9;
    color: var(--up-text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
    transition: all .2s ease;
}
.up-edit-wrapper .up-role-info {
    flex-grow: 1;
    min-width: 0;
}
.up-edit-wrapper .up-role-name {
    font-size: .95rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0 0 2px 0;
    text-transform: capitalize;
}
.up-edit-wrapper .up-role-desc {
    font-size: .75rem;
    color: var(--up-text-light);
    margin: 0;
}
.up-edit-wrapper .up-role-check {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    opacity: 0;
    transform: scale(.5);
    transition: all .2s ease;
    flex-shrink: 0;
}

/* Footer */
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
.up-edit-wrapper .up-foot-info {
    color: var(--up-text-muted);
    font-size: .85rem;
    display: flex;
    align-items: center;
    gap: 6px;
}
.up-edit-wrapper .up-foot-buttons {
    display: flex;
    gap: 8px;
}

/* Empty state */
.up-edit-wrapper .up-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--up-text-muted);
}

/* Alert */
.up-edit-wrapper .up-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: .9rem;
}
.up-edit-wrapper .up-alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
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
                <i class="fas fa-user-shield"></i>
                Atur Role User
            </h1>
            <p class="up-page-sub">Pilih satu atau lebih role — permission akan digabung dari semua role yang dipilih</p>
        </div>
        <a href="{{ route('administrasi.user-permission.index') }}" class="up-btn up-btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin:4px 0 0 0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
                    <span style="font-size:.75rem; color:var(--up-text-muted); font-weight:600;">ROLE SAAT INI:</span>
                    @forelse($currentRoles as $cr)
                        <span class="up-badge up-badge-role">{{ ucfirst($cr->name) }}</span>
                    @empty
                        <span class="up-badge up-badge-norole">Belum ada role</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Form Pilih Role (Multi) --}}
    <form action="{{ route('administrasi.user-permission.update', ['id' => $user->id]) }}"
          method="POST" id="roleForm">
        @csrf
        @method('PUT')

        <div class="up-card">
            <div class="up-card-head">
                <div>
                    <h5><i class="fas fa-user-tag"></i> Pilih Role</h5>
                    <small>
                        <i class="fas fa-check-circle"></i>
                        <span id="selectedCount">0</span> role dipilih
                    </small>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="button" class="up-btn up-btn-light" onclick="selectAllRole(true)">
                        <i class="fas fa-check-double"></i> Pilih Semua
                    </button>
                    <button type="button" class="up-btn up-btn-light-outline" onclick="selectAllRole(false)">
                        <i class="fas fa-times"></i> Hapus Semua
                    </button>
                </div>
            </div>

            <div class="up-card-body">
                @if($roles->isEmpty())
                    <div class="up-empty">
                        <i class="fas fa-folder-open fa-3x mb-3" style="opacity:.4;"></i>
                        <h5>Belum ada role</h5>
                        <p>Silakan buat role terlebih dahulu di menu Data Role.</p>
                    </div>
                @else
                    <div class="up-role-grid">
                        @foreach($roles as $r)
                            @php
                                $isChecked = in_array((int) $r->id, $selectedRoleIds, true);
                            @endphp
                            <div class="up-role-option">
                                <input type="checkbox"
                                       name="role_ids[]"
                                       id="role_{{ $r->id }}"
                                       value="{{ $r->id }}"
                                       class="role-checkbox"
                                       {{ $isChecked ? 'checked' : '' }}>
                                <label class="up-role-label" for="role_{{ $r->id }}">
                                    <div class="up-role-icon">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="up-role-info">
                                        <p class="up-role-name">{{ ucfirst($r->name) }}</p>
                                        <p class="up-role-desc">
                                            {{ $r->display_name ?? 'Role ' . ucfirst($r->name) }}
                                        </p>
                                    </div>
                                    <div class="up-role-check">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="up-card-foot">
                <div class="up-foot-info">
                    <i class="fas fa-info-circle"></i>
                    Bisa pilih lebih dari 1 role. Permission akan digabung & override user akan direset.
                </div>
                <div class="up-foot-buttons">
                    <a href="{{ route('administrasi.user-permission.index') }}" class="up-btn up-btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="up-btn up-btn-primary"
                            {{ $roles->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-save"></i> Simpan Role
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Update counter
    function updateRoleCount() {
        const total = document.querySelectorAll('.role-checkbox:checked').length;
        const el = document.getElementById('selectedCount');
        if (el) el.textContent = total;
    }

    // Pilih semua / hapus semua
    function selectAllRole(checked) {
        document.querySelectorAll('.role-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
        updateRoleCount();
    }

    // Event listener tiap checkbox
    document.querySelectorAll('.role-checkbox').forEach(function(cb) {
        cb.addEventListener('change', updateRoleCount);
    });

    // Init
    document.addEventListener('DOMContentLoaded', updateRoleCount);

    // Konfirmasi sebelum submit
    document.getElementById('roleForm')?.addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.role-checkbox:checked');
        if (selected.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 role.');
            return false;
        }

        const currentCount = {{ count($selectedRoleIds) }};
        if (selected.length !== currentCount) {
            if (!confirm('Ubah role user ini? Semua override permission akan direset.')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endsection