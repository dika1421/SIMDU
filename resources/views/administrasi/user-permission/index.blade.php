@extends('administrasi.layouts.header')

@section('title', 'Hak Akses per User')

@section('content')
{{-- ================= STYLE ================= --}}
<style>
    /* Force modern look, override template bawaan */
    .user-perm-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .user-perm-wrapper .header-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem !important;
    }
    .user-perm-wrapper .header-card h5,
    .user-perm-wrapper .header-card i {
        color: #fff !important;
    }
    .user-perm-wrapper .modern-card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08) !important;
        overflow: hidden !important;
        background: #fff !important;
        margin-bottom: 1.5rem !important;
    }
    .user-perm-wrapper .filter-card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06) !important;
        background: #fff !important;
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }
    .user-perm-wrapper .filter-card label {
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 0.5rem !important;
    }
    .user-perm-wrapper .filter-card .form-control,
    .user-perm-wrapper .filter-card .form-select {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        padding: 0.6rem 1rem !important;
        font-size: 0.9rem !important;
        transition: all 0.2s !important;
    }
    .user-perm-wrapper .filter-card .form-control:focus,
    .user-perm-wrapper .filter-card .form-select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    }

    /* Table */
    .user-perm-wrapper .modern-table {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .user-perm-wrapper .modern-table thead th {
        background: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0 !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        padding: 1rem !important;
        white-space: nowrap !important;
    }
    .user-perm-wrapper .modern-table tbody td {
        padding: 1rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        background: #fff !important;
    }
    .user-perm-wrapper .modern-table tbody tr {
        transition: background-color 0.15s ease !important;
    }
    .user-perm-wrapper .modern-table tbody tr:hover td {
        background: #f8faff !important;
    }
    .user-perm-wrapper .modern-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Avatar */
    .user-perm-wrapper .avatar-sm {
        width: 38px !important;
        height: 38px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: #fff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        flex-shrink: 0 !important;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3) !important;
    }

    /* Badges */
    .user-perm-wrapper .badge-modern {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        padding: 0.4em 0.85em !important;
        border-radius: 999px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        line-height: 1 !important;
    }
    .user-perm-wrapper .badge-role {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: #fff !important;
    }
    .user-perm-wrapper .badge-custom {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: #fff !important;
    }
    .user-perm-wrapper .badge-default {
        background: #f1f5f9 !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }
    .user-perm-wrapper .badge-norole {
        background: #94a3b8 !important;
        color: #fff !important;
    }
    .user-perm-wrapper .badge-count {
        background: #fff !important;
        color: #4f46e5 !important;
        padding: 0.5em 1em !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    }

    /* Buttons */
    .user-perm-wrapper .btn-modern {
        border-radius: 999px !important;
        padding: 0.5rem 1.25rem !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        border: none !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
    }
    .user-perm-wrapper .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    .user-perm-wrapper .btn-primary-modern:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4) !important;
        color: #fff !important;
    }
    .user-perm-wrapper .btn-outline-modern {
        background: #fff !important;
        color: #475569 !important;
        border: 1.5px solid #e2e8f0 !important;
    }
    .user-perm-wrapper .btn-outline-modern:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
    }
    .user-perm-wrapper .btn-warning-modern {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25) !important;
    }
    .user-perm-wrapper .btn-warning-modern:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4) !important;
        color: #fff !important;
    }
    .user-perm-wrapper .btn-danger-outline {
        background: #fff !important;
        color: #dc2626 !important;
        border: 1.5px solid #fecaca !important;
        padding: 0.5rem 0.9rem !important;
    }
    .user-perm-wrapper .btn-danger-outline:hover {
        background: #fef2f2 !important;
        border-color: #dc2626 !important;
        color: #dc2626 !important;
        transform: translateY(-2px) !important;
    }

    /* Alerts */
    .user-perm-wrapper .alert-modern {
        border: none !important;
        border-radius: 12px !important;
        padding: 1rem 1.25rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        margin-bottom: 1rem !important;
    }
    .user-perm-wrapper .alert-success-modern {
        background: #ecfdf5 !important;
        color: #065f46 !important;
        border-left: 4px solid #10b981 !important;
    }
    .user-perm-wrapper .alert-danger-modern {
        background: #fef2f2 !important;
        color: #991b1b !important;
        border-left: 4px solid #ef4444 !important;
    }

    /* Empty state */
    .user-perm-wrapper .empty-state {
        padding: 4rem 2rem !important;
        text-align: center !important;
        animation: fadeInUp 0.5s ease !important;
    }
    .user-perm-wrapper .empty-state i {
        font-size: 4rem !important;
        color: #cbd5e1 !important;
        margin-bottom: 1rem !important;
        display: block !important;
    }
    .user-perm-wrapper .empty-state h5 {
        color: #475569 !important;
        font-weight: 700 !important;
        margin-bottom: 0.5rem !important;
    }
    .user-perm-wrapper .empty-state p {
        color: #94a3b8 !important;
        font-size: 0.9rem !important;
        margin: 0 !important;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Footer */
    .user-perm-wrapper .card-footer-modern {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 1.25rem 1.5rem !important;
        border-radius: 0 0 16px 16px !important;
    }

    /* Pagination */
    .user-perm-wrapper .pagination {
        margin-bottom: 0 !important;
        gap: 4px !important;
    }
    .user-perm-wrapper .pagination .page-link {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        color: #4f46e5 !important;
        font-weight: 600 !important;
        padding: 0.5rem 0.85rem !important;
        font-size: 0.85rem !important;
        transition: all 0.15s ease !important;
        background: #fff !important;
    }
    .user-perm-wrapper .pagination .page-link:hover {
        background: #eef2ff !important;
        border-color: #4f46e5 !important;
        color: #4f46e5 !important;
        transform: translateY(-1px) !important;
    }
    .user-perm-wrapper .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3) !important;
    }
    .user-perm-wrapper .pagination .page-item.disabled .page-link {
        color: #cbd5e1 !important;
        background: #f8fafc !important;
    }

    /* Header page */
    .user-perm-wrapper .page-title {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        margin-bottom: 0.25rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    .user-perm-wrapper .page-title i {
        color: #4f46e5 !important;
    }
    .user-perm-wrapper .page-subtitle {
        color: #94a3b8 !important;
        font-size: 0.9rem !important;
        margin: 0 !important;
    }
</style>

<div class="container-fluid px-4 user-perm-wrapper">
    {{-- Header --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-3 mb-3">
        <div>
            <h1 class="page-title">
                <i class="fas fa-users-cog"></i>
                Hak Akses per User
            </h1>
            <p class="page-subtitle">Kelola permission khusus untuk setiap user</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert-modern alert-success-modern alert-dismissible fade show">
            <i class="fas fa-check-circle fa-lg"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern alert-danger-modern alert-dismissible fade show">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label>
                    <i class="fas fa-user-tag me-1"></i> Role
                </label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label>
                    <i class="fas fa-search me-1"></i> Cari
                </label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama atau email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-modern btn-primary-modern flex-grow-1 justify-content-center">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('administrasi.user-permission.index') }}" class="btn-modern btn-outline-modern">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="modern-card">
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list me-2"></i> Daftar User
                </h5>
                <span class="badge-modern badge-count">
                    <i class="fas fa-users"></i> {{ $users->total() }} User
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="25%">Nama</th>
                        <th width="20%">Email</th>
                        <th width="15%">Role</th>
                        <th width="15%">Override Permission</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong style="color:#1e293b;">{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $user->email }}</small></td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge-modern badge-role">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="badge-modern badge-norole">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            @php $overrideCount = $user->userPermissions->count(); @endphp
                            @if($overrideCount > 0)
                                <span class="badge-modern badge-custom">
                                    <i class="fas fa-star"></i> {{ $overrideCount }} Custom
                                </span>
                            @else
                                <span class="badge-modern badge-default">
                                    <i class="fas fa-check"></i> Default Role
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('administrasi.user-permission.edit', $user->id) }}"
                                   class="btn-modern btn-warning-modern"
                                   title="Atur Hak Akses">
                                    <i class="fas fa-shield-alt"></i> Atur
                                </a>
                                @if($overrideCount > 0)
                                <form action="{{ route('administrasi.user-permission.reset', $user->id) }}"
                                      method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Reset hak akses user ini ke default role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-modern btn-danger-outline" title="Reset ke default">
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
                            <div class="empty-state">
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

        <div class="card-footer-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> -
                    <strong>{{ $users->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $users->total() }}</strong> user
                </div>
                <div>{{ $users->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection