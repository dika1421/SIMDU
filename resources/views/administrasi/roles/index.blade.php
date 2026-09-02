{{-- resources/views/administrasi/roles/index.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Manajemen Role')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-tag me-2 text-primary"></i> Manajemen Role
                    </h5>
                    @can('role.create')
                        <a href="{{ route('administrasi.roles.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-plus me-1"></i> Tambah Role
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
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

                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Role</th>
                                <th>Display Name</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                                <th>Jumlah Permission</th>
                                <th>Jumlah User</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $role->name }}</code></td>
                                <td>{{ $role->display_name ?? '-' }}</td>
                                <td>{{ $role->description ?? '-' }}</td>
                                <td>
                                    @if($role->is_default)
                                        <span class="badge bg-success">Default</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->users->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('role.permission')
                                            <a href="{{ route('administrasi.roles.permissions', $role->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Atur Permission">
                                                <i class="fas fa-key"></i>
                                            </a>
                                        @endcan

                                        @can('role.edit')
                                            <a href="{{ route('administrasi.roles.edit', $role->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit Role">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('role.delete')
                                            @if(!$role->is_default)
                                                <form action="{{ route('administrasi.roles.destroy', $role->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus role {{ $role->display_name ?? $role->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Role">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled title="Role default tidak bisa dihapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada role yang dibuat</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush