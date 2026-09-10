@extends('administrasi.layouts.header')

@section('title', 'Hak Akses per User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users-cog me-2"></i>
        Hak Akses per User
    </h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Role</label>
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
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama atau email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i> Daftar User
            <span class="badge bg-primary ms-2">{{ $users->total() }} User</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama</th>
                        <th width="20%">Email</th>
                        <th width="15%">Role</th>
                        <th width="15%">Override Permission</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $index }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td><small class="text-muted">{{ $user->email }}</small></td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="badge bg-secondary">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            @php $overrideCount = $user->userPermissions->count(); @endphp
                            @if($overrideCount > 0)
                                <span class="badge bg-warning text-dark">
                                    {{ $overrideCount }} Custom
                                </span>
                            @else
                                <span class="badge bg-light text-muted">Default Role</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('administrasi.user-permission.edit', $user->id) }}"
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-shield-alt"></i> Atur Hak Akses
                            </a>
                            @if($overrideCount > 0)
                            <form action="{{ route('administrasi.user-permission.reset', $user->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Reset hak akses user ini ke default role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset ke default">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-users-slash fa-3x mb-3 d-block"></i>
                            Belum ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                dari {{ $users->total() }} user
            </div>
            <div>{{ $users->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection