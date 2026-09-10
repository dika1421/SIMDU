{{-- resources/views/administrasi/permissions/index.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Manajemen Permission')

@section('content')
<style>
.up-perm-wrapper {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-warning: #f59e0b;
    --up-warning-dark: #d97706;
    --up-info: #0ea5e9;
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
.up-perm-wrapper * { box-sizing: border-box; }

.up-perm-wrapper .up-page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-perm-wrapper .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-perm-wrapper .up-page-title i { color: var(--up-warning); }
.up-perm-wrapper .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}

/* Card */
.up-perm-wrapper .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}
.up-perm-wrapper .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.up-perm-wrapper .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-perm-wrapper .up-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: var(--up-primary);
    padding: 8px 16px;
    border-radius: 999px;
    font-weight: 700;
    font-size: .8rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}

/* Buttons */
.up-perm-wrapper .up-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 20px;
    font-size: .85rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    white-space: nowrap;
    font-family: inherit;
}
.up-perm-wrapper .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25);
}
.up-perm-wrapper .up-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, .4);
    color: #fff;
}
.up-perm-wrapper .up-btn-light {
    background: #fff;
    color: var(--up-primary);
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}
.up-perm-wrapper .up-btn-light:hover {
    transform: translateY(-2px);
    color: var(--up-primary);
}
.up-perm-wrapper .up-btn-danger {
    background: #fff;
    color: var(--up-danger);
    border: 1.5px solid #fecaca;
    padding: 8px 12px;
}
.up-perm-wrapper .up-btn-danger:hover {
    background: #fef2f2;
    border-color: var(--up-danger);
    color: var(--up-danger);
    transform: translateY(-2px);
}

/* Table */
.up-perm-wrapper .up-table-wrap {
    width: 100%;
    overflow-x: auto;
}
.up-perm-wrapper table.up-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    background: #fff;
}
.up-perm-wrapper table.up-table thead th {
    background: var(--up-bg-soft);
    color: var(--up-text-muted);
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 16px;
    text-align: left;
    border-bottom: 2px solid var(--up-border);
    white-space: nowrap;
}
.up-perm-wrapper table.up-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: .875rem;
    color: #334155;
    background: #fff;
}
.up-perm-wrapper table.up-table tbody tr:last-child td { border-bottom: none; }
.up-perm-wrapper table.up-table tbody tr:hover td { background: #f8faff; }
.up-perm-wrapper .up-no {
    color: var(--up-text-light);
    font-weight: 700;
    text-align: center;
}
.up-perm-wrapper .up-code {
    display: inline-block;
    background: #f1f5f9;
    color: var(--up-primary);
    padding: 4px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: .8rem;
    font-weight: 600;
    border: 1px solid #e2e8f0;
}
.up-perm-wrapper .up-role-badge-stack {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

/* Badges */
.up-perm-wrapper .up-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.2;
}
.up-perm-wrapper .up-badge-group {
    background: #e0f2fe;
    color: #075985;
    border: 1px solid #bae6fd;
}
.up-perm-wrapper .up-badge-role {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
}
.up-perm-wrapper .up-badge-muted {
    color: var(--up-text-light);
    font-size: .8rem;
}

/* Alerts */
.up-perm-wrapper .up-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: .9rem;
}
.up-perm-wrapper .up-alert-success {
    background: #ecfdf5;
    color: #065f46;
    border-left: 4px solid #10b981;
}
.up-perm-wrapper .up-alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

/* Empty */
.up-perm-wrapper .up-empty {
    padding: 3rem 1rem;
    text-align: center;
}
.up-perm-wrapper .up-empty i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 12px;
    display: block;
}
.up-perm-wrapper .up-empty p {
    color: var(--up-text-light);
    margin: 0;
    font-size: .9rem;
}

/* DataTables fix */
.up-perm-wrapper .dataTables_wrapper .dataTables_filter input {
    border-radius: 10px;
    border: 1.5px solid var(--up-border);
    padding: 6px 12px;
    outline: none;
}
.up-perm-wrapper .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--up-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.up-perm-wrapper .dataTables_wrapper .dataTables_length select {
    border-radius: 10px;
    border: 1.5px solid var(--up-border);
    padding: 4px 8px;
    outline: none;
}
.up-perm-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    margin: 0 2px !important;
    border: 1.5px solid var(--up-border) !important;
    color: var(--up-primary) !important;
    font-weight: 600 !important;
}
.up-perm-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%) !important;
    border-color: var(--up-primary) !important;
    color: #fff !important;
}
</style>

<div class="up-perm-wrapper">

    {{-- Page Header --}}
    <div class="up-page-head">
        <div>
            <h1 class="up-page-title">
                <i class="fas fa-lock"></i>
                Manajemen Permission
            </h1>
            <p class="up-page-sub">Kelola daftar permission yang tersedia di sistem</p>
        </div>
    </div>

    {{-- Alerts --}}
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

    {{-- Card --}}
    <div class="up-card">
        <div class="up-card-head">
            <h5><i class="fas fa-list"></i> Daftar Permission</h5>
            <div style="display:flex; gap:8px; align-items:center;">
                <span class="up-count">
                    <i class="fas fa-key"></i> {{ $permissions->count() }} Permission
                </span>
                @can('permission.create')
                    <a href="{{ route('administrasi.permissions.create') }}" class="up-btn up-btn-light">
                        <i class="fas fa-plus"></i> Tambah Permission
                    </a>
                @endcan
            </div>
        </div>

        <div class="up-table-wrap">
            <table class="table up-table datatable">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Deskripsi</th>
                        <th>Group</th>
                        <th>Roles</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $index => $permission)
                    <tr>
                        <td class="up-no">{{ $index + 1 }}</td>
                        <td><span class="up-code">{{ $permission->name }}</span></td>
                        <td><strong>{{ $permission->display_name ?? '-' }}</strong></td>
                        <td>{{ $permission->description ?? '-' }}</td>
                        <td>
                            <span class="up-badge up-badge-group">
                                <i class="fas fa-folder"></i>
                                {{ $permission->group ?? 'General' }}
                            </span>
                        </td>
                        <td>
                            <div class="up-role-badge-stack">
                                @forelse($permission->roles as $role)
                                    <span class="up-badge up-badge-role">
                                        {{ $role->display_name ?? $role->name }}
                                    </span>
                                @empty
                                    <span class="up-badge-muted">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="text-center">
                            @can('permission.delete')
                                <form action="{{ route('administrasi.permissions.destroy', $permission->id) }}"
                                      method="POST"
                                      style="display:inline; margin:0;"
                                      onsubmit="return confirm('Yakin ingin menghapus permission {{ $permission->display_name ?? $permission->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="up-btn up-btn-danger" title="Hapus Permission">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="up-empty">
                                <i class="fas fa-inbox"></i>
                                <p>Belum ada permission yang dibuat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [6] }
                ]
            });
        }
    });
</script>
@endpush