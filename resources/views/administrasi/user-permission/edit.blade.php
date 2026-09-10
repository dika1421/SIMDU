@extends('administrasi.layouts.header')

@section('title', 'Atur Hak Akses User')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="fas fa-shield-alt me-2 text-primary"></i>
                Atur Hak Akses User
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Administrasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administrasi.user-permission.index') }}" class="text-decoration-none">Hak Akses User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Atur</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alert --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
            <strong><i class="fas fa-exclamation-triangle me-1"></i> Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info User --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                            <p class="text-muted mb-1 small">
                                <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                            </p>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <span class="text-muted small me-1">Role:</span>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-primary bg-gradient rounded-pill">{{ ucfirst($role->name) }}</span>
                                @empty
                                    <span class="badge bg-secondary rounded-pill">No Role</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="alert alert-info border-0 rounded-3 mb-0 d-flex align-items-start">
                        <i class="fas fa-info-circle fa-lg me-2 mt-1"></i>
                        <div class="small">
                            Permission default berasal dari <strong>Role</strong>.
                            Anda dapat menimpa (<em>override</em>) permission khusus untuk user ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('administrasi.user-permission.update', $user->id) }}" method="POST" id="permissionForm">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            {{-- Card Header --}}
            <div class="card-header bg-gradient-primary text-white border-0 p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            <i class="fas fa-key me-2"></i> Daftar Permission
                        </h5>
                        <small class="opacity-75">
                            <i class="fas fa-check-circle me-1"></i>
                            <span id="selectedCount">0</span> permission dipilih
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold" onclick="selectAll(true)">
                            <i class="fas fa-check-double me-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold" onclick="selectAll(false)">
                            <i class="fas fa-times me-1"></i> Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-4 bg-light">
                @php
                    $grouped = $allPermissions->groupBy(function($perm) {
                        $parts = explode('-', $perm->name);
                        return $parts[0] ?? 'lainnya';
                    });
                @endphp

                <div class="row g-3">
                    @foreach($grouped as $group => $permissions)
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 permission-group-card">
                            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-dark">
                                    <span class="group-icon me-2">
                                        <i class="fas fa-folder"></i>
                                    </span>
                                    {{ ucfirst($group) }}
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-gradient rounded-pill">{{ $permissions->count() }}</span>
                                    <div class="form-check form-switch mb-0" title="Pilih semua di grup ini">
                                        <input class="form-check-input group-toggle" type="checkbox"
                                               data-group="{{ $group }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @foreach($permissions as $perm)
                                    @php
                                        $fromRole = in_array($perm->name, $rolePermissions);
                                        $override = $userOverrides[$perm->name] ?? null;
                                        $checked  = ($override !== null) ? $override : $fromRole;
                                    @endphp
                                    <div class="permission-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div class="d-flex align-items-center flex-grow-1 me-2">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input permission-checkbox"
                                                       type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $perm->name }}"
                                                       id="perm_{{ $perm->id }}"
                                                       data-group="{{ $group }}"
                                                       {{ $checked ? 'checked' : '' }}>
                                            </div>
                                            <label class="form-check-label ms-2 small flex-grow-1" for="perm_{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                        <div>
                                            @if($fromRole && $override === null)
                                                <span class="badge bg-success bg-gradient rounded-pill" style="font-size:0.65rem;">
                                                    <i class="fas fa-check me-1"></i> Role
                                                </span>
                                            @elseif($override !== null)
                                                <span class="badge bg-warning text-dark bg-gradient rounded-pill" style="font-size:0.65rem;">
                                                    <i class="fas fa-star me-1"></i> Custom
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Card Footer (Sticky) --}}
            <div class="card-footer bg-white border-top sticky-footer p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1 text-primary"></i>
                        Permission yang <strong>sama dengan role</strong> tidak akan disimpan sebagai override.
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('administrasi.user-permission.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-save me-1"></i> Simpan Hak Akses
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .permission-group-card {
        transition: all 0.25s ease;
        border: 1px solid #e9ecef !important;
    }
    .permission-group-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        border-color: #4f46e5 !important;
    }
    .group-icon {
        display: inline-flex;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #4f46e5;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    .permission-item:last-child {
        border-bottom: none !important;
    }
    .permission-item {
        transition: background-color 0.15s ease;
    }
    .permission-item:hover {
        background-color: #f8f9ff;
        border-radius: 8px;
        padding-left: 8px;
        padding-right: 8px;
    }
    .form-check-input {
        cursor: pointer;
        width: 2.4em;
        height: 1.2em;
    }
    .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.05);
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
    .breadcrumb-item a {
        color: #6c757d;
    }
    .breadcrumb-item a:hover {
        color: #4f46e5;
    }
    @media (max-width: 768px) {
        .sticky-footer {
            position: static;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Select All / None
    function selectAll(checked) {
        document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
        document.querySelectorAll('.group-toggle').forEach(function(gt) {
            gt.checked = checked;
        });
        updateSelectedCount();
    }

    // Update counter
    function updateSelectedCount() {
        const total = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = total;
    }

    // Group toggle
    document.querySelectorAll('.group-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]').forEach(function(cb) {
                cb.checked = toggle.checked;
            });
            updateSelectedCount();
        });
    });

    // Individual checkbox -> update group toggle & counter
    document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
        cb.addEventListener('change', function() {
            const group = this.dataset.group;
            const groupCheckboxes = document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]');
            const checkedCount = document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]:checked').length;
            const groupToggle = document.querySelector('.group-toggle[data-group="' + group + '"]');
            if (groupToggle) {
                groupToggle.checked = (checkedCount === groupCheckboxes.length);
                groupToggle.indeterminate = (checkedCount > 0 && checkedCount < groupCheckboxes.length);
            }
            updateSelectedCount();
        });
    });

    // Init
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
        // Set initial group toggle state
        document.querySelectorAll('.group-toggle').forEach(function(toggle) {
            const group = toggle.dataset.group;
            const groupCheckboxes = document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]');
            const checkedCount = document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]:checked').length;
            toggle.checked = (checkedCount === groupCheckboxes.length);
            toggle.indeterminate = (checkedCount > 0 && checkedCount < groupCheckboxes.length);
        });
    });
</script>
@endpush
@endsection