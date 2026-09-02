{{-- resources/views/administrasi/permissions/index.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Manajemen Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-lock me-2 text-warning"></i> Manajemen Permission
                    </h5>
                    @can('permission.create')
                        <a href="{{ route('administrasi.permissions.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-plus me-1"></i> Tambah Permission
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
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Deskripsi</th>
                                <th>Group</th>
                                <th>Roles</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $index => $permission)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $permission->name }}</code></td>
                                <td>{{ $permission->display_name ?? '-' }}</td>
                                <td>{{ $permission->description ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $permission->group ?? 'General' }}</span>
                                </td>
                                <td>
                                    @foreach($permission->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->display_name ?? $role->name }}</span>
                                    @endforeach
                                    @if($permission->roles->isEmpty())
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('permission.delete')
                                            <form action="{{ route('administrasi.permissions.destroy', $permission->id) }}" 
                                                  method="POST" 
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus permission {{ $permission->display_name ?? $permission->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permission">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada permission yang dibuat</p>
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