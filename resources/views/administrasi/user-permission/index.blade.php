@extends('administrasi.layouts.header')

@section('title', 'Hak Akses per User')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="fas fa-users-cog me-2 text-primary"></i>
                Hak Akses per User
            </h1>
            <p class="text-muted small mb-0">Kelola permission khusus untuk setiap user</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-user-tag me-1"></i> Role
                    </label>
                    <select name="role" class="form-select rounded-3">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-search me-1"></i> Cari
                    </label>
                    <input type="text" name="search" class="form-control rounded-3"
                           placeholder="Nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-gradient-primary text-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list me-2"></i> Daftar User
                </h5>
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-semibold">
                    <i class="fas fa-users me-1"></i> {{ $users->total() }} User
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table">
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
                        <tr class="table-row">
                            <td class="text-center text-muted fw-semibold">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td><small class="text-muted">{{ $user->email }}</small></td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-primary bg-gradient rounded-pill">{{ ucfirst($role->name) }}</span>
                                @empty
                                    <span class="badge bg-secondary rounded-pill">No Role</span>
                                @endforelse
                            </td>
                            <td>
                                @php $overrideCount = $user->userPermissions->count(); @endphp
                                @if($overrideCount > 0)
                                    <span class="badge bg-warning text-dark bg-gradient rounded-pill">
                                        <i class="fas fa-star me-1"></i> {{ $overrideCount }} Custom
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted rounded-pill border">
                                        <i class="fas fa-check me-1"></i> Default Role
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('administrasi.user-permission.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold"
                                       title="Atur Hak Akses">
                                        <i class="fas fa-shield-alt me-1"></i> Atur
                                    </a>
                                    @if($overrideCount > 0)
                                    <form action="{{ route('administrasi.user-permission.reset', $user->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Reset hak akses user ini ke default role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Reset ke default">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash fa-4x text-muted mb-3 d-block opacity-50"></i>
                                    <h5 class="text-muted">Belum ada data user</h5>
                                    <p class="text-muted small mb-0">Data user akan muncul di sini setelah ditambahkan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                    dari <strong>{{ $users->total() }}</strong> user
                </div>
                <div>{{ $users->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .modern-table thead th {
        background-color: #f8f9ff;
        border-bottom: 2px solid #e9ecef;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        padding: 1rem;
    }
    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }
    .table-row {
        transition: all 0.2s ease;
    }
    .table-row:hover {
        background-color: #f8f9ff !important;
        transform: scale(1.002);
    }
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
    }
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: #fff;
    }
    .btn-warning:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    .empty-state {
        animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        border: 1px solid #e9ecef;
        color: #4f46e5;
        font-weight: 500;
    }
    .page-item.active .page-link {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-color: #4f46e5;
        color: #fff;
    }
    .page-link:hover {
        background-color: #eef2ff;
        border-color: #4f46e5;
        color: #4f46e5;
    }
</style>
@endpush
@endsection