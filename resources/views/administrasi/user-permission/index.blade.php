@extends('administrasi.layouts.header')

@section('title', 'Hak Akses per User')

@section('content')
<style>
.up-wrapper {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-warning: #f59e0b;
    --up-warning-dark: #d97706;
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
.up-wrapper .up-page-head {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-wrapper .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}
.up-wrapper .up-page-title i {
    color: var(--up-primary) !important;
}
.up-wrapper .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}
.up-wrapper .up-alert {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 14px 20px !important;
    border-radius: 12px !important;
    margin-bottom: 1rem !important;
    font-weight: 600 !important;
    font-size: .9rem !important;
    border: none !important;
}
.up-wrapper .up-alert-success {
    background: #ecfdf5 !important;
    color: #065f46 !important;
    border-left: 4px solid #10b981 !important;
}
.up-wrapper .up-alert-danger {
    background: #fef2f2 !important;
    color: #991b1b !important;
    border-left: 4px solid #ef4444 !important;
}
.up-wrapper .up-filter {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    padding: 1.5rem !important;
    margin-bottom: 1.5rem !important;
}
.up-wrapper .up-filter-row {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 1rem !important;
    align-items: flex-end !important;
}
.up-wrapper .up-filter-col {
    flex: 1 1 200px !important;
    min-width: 0 !important;
}
.up-wrapper .up-filter-col.up-grow {
    flex: 2 1 300px !important;
}
.up-wrapper .up-filter label {
    display: block !important;
    font-size: .72rem !important;
    font-weight: 700 !important;
    color: var(--up-text-muted) !important;
    text-transform: uppercase !important;
    letter-spacing: .5px !important;
    margin-bottom: .5rem !important;
}
.up-wrapper .up-filter .form-control,
.up-wrapper .up-filter .form-select {
    width: 100% !important;
    padding: 10px 14px !important;
    font-size: .9rem !important;
    color: var(--up-text) !important;
    background-color: #fff !important;
    border: 1.5px solid var(--up-border) !important;
    border-radius: 10px !important;
    outline: none !important;
}
.up-wrapper .up-filter .form-control:focus,
.up-wrapper .up-filter .form-select:focus {
    border-color: var(--up-primary) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
}
.up-wrapper .up-btn {
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
.up-wrapper .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25) !important;
}
.up-wrapper .up-btn-primary:hover {
    transform: translateY(-2px) !important;
    color: #fff !important;
}
.up-wrapper .up-btn-outline {
    background: #fff !important;
    color: var(--up-text-muted) !important;
    border: 1.5px solid var(--up-border) !important;
}
.up-wrapper .up-btn-outline:hover {
    background: var(--up-bg-soft) !important;
    color: var(--up-text) !important;
}
.up-wrapper .up-btn-warning {
    background: linear-gradient(135deg, var(--up-warning) 0%, var(--up-warning-dark) 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, .25) !important;
}
.up-wrapper .up-btn-warning:hover {
    transform: translateY(-2px) !important;
    color: #fff !important;
}
.up-wrapper .up-btn-danger {
    background: #fff !important;
    color: var(--up-danger) !important;
    border: 1.5px solid #fecaca !important;
    padding: 10px 14px !important;
}
.up-wrapper .up-btn-danger:hover {
    background: #fef2f2 !important;
    color: var(--up-danger) !important;
}
.up-wrapper .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}
.up-wrapper .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    color: #fff !important;
    padding: 1.5rem !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
}
.up-wrapper .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}
.up-wrapper .up-count {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    background: #fff !important;
    color: var(--up-primary) !important;
    padding: 8px 16px !important;
    border-radius: 999px !important;
    font-weight: 700 !important;
    font-size: .8rem !important;
}
.up-wrapper .up-table-wrap {
    width: 100% !important;
    overflow-x: auto !important;
}
.up-wrapper table.up-table {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    margin: 0 !important;
    background: #fff !important;
}
.up-wrapper table.up-table thead th {
    background: var(--up-bg-soft) !important;
    color: var(--up-text-muted) !important;
    font-size: .72rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: .6px !important;
    padding: 16px !important;
    text-align: left !important;
    border-bottom: 2px solid var(--up-border) !important;
    white-space: nowrap !important;
}
.up-wrapper table.up-table tbody td {
    padding: 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    vertical-align: middle !important;
    font-size: .875rem !important;
    color: #334155 !important;
    background: #fff !important;
}
.up-wrapper table.up-table tbody tr:last-child td {
    border-bottom: none !important;
}
.up-wrapper table.up-table tbody tr:hover td {
    background: #f8faff !important;
}
.up-wrapper .up-no {
    color: var(--up-text-light) !important;
    font-weight: 700 !important;
    text-align: center !important;
}
.up-wrapper .up-avatar {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 38px !important;
    height: 38px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    color: #fff !important;
    font-weight: 700 !important;
    font-size: .85rem !important;
    flex-shrink: 0 !important;
    text-transform: uppercase !important;
}
.up-wrapper .up-user {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}
.up-wrapper .up-user strong {
    color: var(--up-text) !important;
    font-weight: 700 !important;
}
.up-wrapper .up-email {
    color: var(--up-text-light) !important;
    font-size: .8rem !important;
}
.up-wrapper .up-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    padding: 5px 12px !important;
    border-radius: 999px !important;
    font-size: .72rem !important;
    font-weight: 700 !important;
    white-space: nowrap !important;
}
.up-wrapper .up-badge-role {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    color: #fff !important;
}
.up-wrapper .up-badge-custom {
    background: linear-gradient(135deg, var(--up-warning) 0%, var(--up-warning-dark) 100%) !important;
    color: #fff !important;
}
.up-wrapper .up-badge-default {
    background: #f1f5f9 !important;
    color: var(--up-text-muted) !important;
    border: 1px solid var(--up-border) !important;
}
.up-wrapper .up-badge-norole {
    background: #94a3b8 !important;
    color: #fff !important;
}
.up-wrapper .up-actions {
    display: flex !important;
    justify-content: center !important;
    gap: 8px !important;
    flex-wrap: wrap !important;
}
.up-wrapper .up-actions form {
    margin: 0 !important;
    display: inline-block !important;
}
.up-wrapper .up-card-foot {
    background: var(--up-bg-soft) !important;
    border-top: 1px solid var(--up-border) !important;
    padding: 20px 24px !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
    font-size: .85rem !important;
    color: var(--up-text-muted) !important;
}
.up-wrapper .up-empty {
    padding: 60px 20px !important;
    text-align: center !important;
}
.up-wrapper .up-empty i {
    font-size: 4rem !important;
    color: #cbd5e1 !important;
    margin-bottom: 16px !important;
    display: block !important;
}
.up-wrapper .up-empty h5 {
    color: #475569 !important;
    font-weight: 700 !important;
    margin: 0 0 6px 0 !important;
}
.up-wrapper .up-empty p {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}
.up-wrapper .pagination {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 4px !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}
.up-wrapper .pagination .page-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 12px !important;
    font-size: .82rem !important;
    font-weight: 600 !important;
    color: var(--up-primary) !important;
    background: #fff !important;
    border: 1.5px solid var(--up-border) !important;
    border-radius: 10px !important;
    text-decoration: none !important;
}
.up-wrapper .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    border-color: var(--up-primary) !important;
    color: #fff !important;
}
.up-wrapper .pagination svg {
    width: 14px !important;
    height: 14px !important;
}
@media (max-width: 768px) {
    .up-wrapper .up-filter-row { flex-direction: column !important; }
    .up-wrapper .up-filter-col { width: 100% !important; flex: 1 1 100% !important; }
    .up-wrapper .up-card-foot { flex-direction: column !important; align-items: flex-start !important; }
}
</style>

<div class="up-wrapper">
    <div class="up-page-head">
        <h1 class="up-page-title">
            <i class="fas fa-users-cog"></i>
            Hak Akses per User
        </h1>
        <p class="up-page-sub">Kelola Role khusus untuk setiap user di sistem</p>
    </div>

    @if(session('success'))
        <div class="up-alert up-alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="up-filter">
        <form method="GET" action="{{ route('administrasi.user-permission.index') }}">
            <div class="up-filter-row">
                <div class="up-filter-col">
                    <label for="filter_role"><i class="fas fa-user-tag me-1"></i> Role</label>
                    <select name="role" id="filter_role" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="up-filter-col up-grow">
                    <label for="filter_search"><i class="fas fa-search me-1"></i> Cari</label>
                    <input type="text" name="search" id="filter_search" class="form-control"
                           placeholder="Cari berdasarkan nama atau email..."
                           value="{{ request('search') }}">
                </div>
                <div class="up-filter-col" style="flex:0 1 auto; display:flex; gap:8px;">
                    <button type="submit" class="up-btn up-btn-primary" style="flex:1;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('administrasi.user-permission.index') }}" class="up-btn up-btn-outline">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="up-card">
        <div class="up-card-head">
            <h5><i class="fas fa-list"></i> Daftar User</h5>
            <span class="up-count">
                <i class="fas fa-users"></i> {{ $users->total() }} User
            </span>
        </div>

        <div class="up-table-wrap">
            <table class="up-table">
                <thead>
                    <tr>
                        <th style="width:60px; text-align:center;">No</th>
                        <th style="width:25%;">Nama</th>
                        <th style="width:22%;">Email</th>
                        <th style="width:14%;">Role</th>
                        <th style="width:14%;">Override</th>
                        <th style="width:15%; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        @php
                            $overrideCount = $user->userPermissions->count();

                            // ✅ FIX: Tentukan role yang ditampilkan tanpa akses $user->role (yang string)
                            $displayRoles = collect();
                            if ($user->roles->count() > 0) {
                                $displayRoles = $user->roles->pluck('name');
                            } elseif ($user->role_id) {
                                $r = \App\Models\Role::find($user->role_id);
                                if ($r) $displayRoles = collect([$r->name]);
                            } elseif (!empty($user->role)) {
                                $displayRoles = collect([$user->role]);
                            }
                        @endphp
                        <tr>
                            <td class="up-no">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="up-user">
                                    <div class="up-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td><span class="up-email">{{ $user->email }}</span></td>
                            <td>
                                @forelse($displayRoles as $roleName)
                                    <span class="up-badge up-badge-role">{{ ucfirst($roleName) }}</span>
                                @empty
                                    <span class="up-badge up-badge-norole">No Role</span>
                                @endforelse
                            </td>
                            <td>
                                @if($overrideCount > 0)
                                    <span class="up-badge up-badge-custom">
                                        <i class="fas fa-star"></i> {{ $overrideCount }} Custom
                                    </span>
                                @else
                                    <span class="up-badge up-badge-default">
                                        <i class="fas fa-check"></i> Default Role
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="up-actions">
                                    <a href="{{ route('administrasi.user-permission.edit', ['id' => $user->id]) }}"
                                       class="up-btn up-btn-warning">
                                        <i class="fas fa-shield-alt"></i> Atur
                                    </a>
                                    @if($overrideCount > 0)
                                        <form action="{{ route('administrasi.user-permission.reset', ['id' => $user->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Reset hak akses user ini ke default role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="up-btn up-btn-danger" title="Reset ke default">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="up-empty">
                                    <i class="fas fa-users-slash"></i>
                                    <h5>Belum ada data user</h5>
                                    <p>Data user akan muncul di sini setelah ditambahkan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="up-card-foot">
            <div>
                <i class="fas fa-info-circle me-1"></i>
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> -
                <strong>{{ $users->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $users->total() }}</strong> user
            </div>
            <div>{{ $users->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection