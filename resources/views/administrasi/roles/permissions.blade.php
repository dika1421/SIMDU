{{-- resources/views/administrasi/roles/permissions.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Atur Permission Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-key me-2 text-warning"></i> Atur Permission
                        <small class="text-muted">- {{ $role->display_name ?? $role->name }}</small>
                    </h5>
                    <a href="{{ route('administrasi.roles.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administrasi.roles.assign-permissions', $role->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        @php
                            $permissionGroups = \App\Models\Permission::all()->groupBy('group');
                            $rolePermissions = $role->permissions->pluck('id')->toArray();
                        @endphp
                        
                        @forelse($permissionGroups as $group => $permissions)
                            <div class="col-md-6 mb-4">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold text-primary">{{ $group ?? 'General' }}</h6>
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input select-all" 
                                                       data-group="{{ $group }}"
                                                       id="select_all_{{ Str::slug($group) }}">
                                                <label for="select_all_{{ Str::slug($group) }}" class="form-check-label small">
                                                    Pilih Semua
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="perm_{{ $permission->id }}"
                                                       class="form-check-input permission-checkbox"
                                                       data-group="{{ $group }}"
                                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                <label for="perm_{{ $permission->id }}" class="form-check-label small">
                                                    {{ $permission->display_name ?? $permission->name }}
                                                    <br>
                                                    <small class="text-muted">{{ $permission->name }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada permission yang tersedia</p>
                                    @can('permission.create')
                                        <a href="{{ route('administrasi.permissions.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Permission
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Permission
                            </button>
                            <a href="{{ route('administrasi.roles.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 ms-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All per group
        $('.select-all').on('change', function() {
            const group = $(this).data('group');
            const isChecked = $(this).prop('checked');
            $(`.permission-checkbox[data-group="${group}"]`).prop('checked', isChecked);
        });

        // Uncheck "Select All" jika ada checkbox yang di-uncheck
        $('.permission-checkbox').on('change', function() {
            const group = $(this).data('group');
            const allChecked = $(`.permission-checkbox[data-group="${group}"]:checked`).length;
            const total = $(`.permission-checkbox[data-group="${group}"]`).length;
            const groupSlug = group.replace(/\s/g, '_');
            const selectAll = $(`#select_all_${groupSlug}`);
            
            if (allChecked === total) {
                selectAll.prop('checked', true);
            } else {
                selectAll.prop('checked', false);
            }
        });
    });
</script>
@endpush