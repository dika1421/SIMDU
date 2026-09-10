@extends('administrasi.layouts.header')

@section('title', 'Atur Hak Akses User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shield-alt me-2"></i>
        Atur Hak Akses User
    </h1>
    <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Info User -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                <div class="mt-2">
                    <strong>Role:</strong>
                    @forelse($user->roles as $role)
                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                    @empty
                        <span class="badge bg-secondary">No Role</span>
                    @endforelse
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Permission default berasal dari <strong>Role</strong>.
                        Anda dapat menimpa (override) permission di sini khusus untuk user ini.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('administrasi.user-permission.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-key me-2"></i> Daftar Permission
            </h5>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll(true)">
                    <i class="fas fa-check-double"></i> Pilih Semua
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAll(false)">
                    <i class="fas fa-times"></i> Hapus Semua
                </button>
            </div>
        </div>
        <div class="card-body">
            @php
                $grouped = $allPermissions->groupBy(function($perm) {
                    $parts = explode('-', $perm->name);
                    return $parts[0] ?? 'lainnya';
                });
            @endphp

            <div class="row">
                @foreach($grouped as $group => $permissions)
                <div class="col-md-6 mb-4">
                    <div class="card border h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">
                                <i class="fas fa-folder me-2 text-primary"></i>
                                {{ ucfirst($group) }}
                                <span class="badge bg-secondary ms-2">{{ $permissions->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($permissions as $perm)
                                @php
                                    $fromRole = in_array($perm->name, $rolePermissions);
                                    $override = $userOverrides[$perm->name] ?? null;
                                    $checked  = ($override !== null) ? $override : $fromRole;
                                @endphp
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox"
                                           type="checkbox"
                                           name="permissions[]"
                                           value="{{ $perm->name }}"
                                           id="perm_{{ $perm->id }}"
                                           {{ $checked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $perm->id }}">
                                        {{ $perm->name }}
                                        @if($fromRole && $override === null)
                                            <span class="badge bg-success ms-1" style="font-size:0.6rem;">
                                                <i class="fas fa-check"></i> dari Role
                                            </span>
                                        @elseif($override !== null)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size:0.6rem;">
                                                <i class="fas fa-star"></i> Custom
                                            </span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Permission yang <strong>sama dengan role</strong> tidak akan disimpan sebagai override.
                </div>
                <div>
                    <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Hak Akses
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function selectAll(checked) {
        document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
    }
</script>
@endpush
@endsection