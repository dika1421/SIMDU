@extends('kepala-sekolah.layouts.header')

@section('title', 'Daftar Persetujuan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">
                                @php
                                    try {
                                        $menunggu = \App\Models\Pengajuan::where('status', 'menunggu')->count();
                                    } catch (\Exception $e) {
                                        $menunggu = 0;
                                    }
                                @endphp
                                {{ $menunggu }}
                            </div>
                            <div class="stat-label">Menunggu Persetujuan</div>
                        </div>
                        <div style="font-size: 2rem; color: #f39c12;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">
                                @php
                                    try {
                                        $disetujui = \App\Models\Pengajuan::where('status', 'disetujui')->count();
                                    } catch (\Exception $e) {
                                        $disetujui = 0;
                                    }
                                @endphp
                                {{ $disetujui }}
                            </div>
                            <div class="stat-label">Disetujui</div>
                        </div>
                        <div style="font-size: 2rem; color: #27ae60;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">
                                @php
                                    try {
                                        $ditolak = \App\Models\Pengajuan::where('status', 'ditolak')->count();
                                    } catch (\Exception $e) {
                                        $ditolak = 0;
                                    }
                                @endphp
                                {{ $ditolak }}
                            </div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                        <div style="font-size: 2rem; color: #e74c3c;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">
                                @php
                                    try {
                                        $revisi = \App\Models\Pengajuan::where('status', 'revisi')->count();
                                    } catch (\Exception $e) {
                                        $revisi = 0;
                                    }
                                @endphp
                                {{ $revisi }}
                            </div>
                            <div class="stat-label">Revisi</div>
                        </div>
                        <div style="font-size: 2rem; color: #3498db;">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Daftar Pengajuan -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Daftar Pengajuan
                <div class="float-end">
                    <a href="{{ route('kepala-sekolah.persetujuan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pengajuan
                    </a>
                    <a href="{{ route('kepala-sekolah.persetujuan.dashboard') }}" class="btn btn-info btn-sm text-white">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Form Filter -->
                <form method="GET" action="{{ route('kepala-sekolah.persetujuan.index') }}" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="tipe" class="form-select form-select-sm">
                                <option value="">Semua Tipe</option>
                                <option value="anggaran" {{ request('tipe') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                                <option value="izin" {{ request('tipe') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="proyek" {{ request('tipe') == 'proyek' ? 'selected' : '' }}>Proyek</option>
                                <option value="lainnya" {{ request('tipe') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari judul atau pengaju..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="40">No</th>
                                <th>Judul</th>
                                <th>Pengaju</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $key => $item)
                            <tr>
                                <td>{{ $pengajuan->firstItem() + $key }}</td>
                                <td>{{ Str::limit($item->judul, 40) }}</td>
                                <td>{{ $item->pengaju->name ?? 'Tidak Diketahui' }}</td>
                                <td>
                                    @php
                                        $tipeColors = [
                                            'anggaran' => 'primary',
                                            'izin' => 'warning',
                                            'proyek' => 'info',
                                            'lainnya' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $tipeColors[$item->tipe] ?? 'secondary' }}">
                                        {{ ucfirst($item->tipe) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'menunggu' => 'warning',
                                            'disetujui' => 'success',
                                            'ditolak' => 'danger',
                                            'revisi' => 'info'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('kepala-sekolah.persetujuan.show', $item->id) }}" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($item->status == 'menunggu')
                                            <a href="{{ route('kepala-sekolah.persetujuan.edit', $item->id) }}" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-success" 
                                                    onclick="approvePengajuan({{ $item->id }})" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger" 
                                                    onclick="rejectPengajuan({{ $item->id }})" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        
                                        <form action="{{ route('kepala-sekolah.persetujuan.destroy', $item->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada data pengajuan</p>
                                    <a href="{{ route('kepala-sekolah.persetujuan.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Buat Pengajuan
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $pengajuan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Setujui Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui pengajuan ini?</p>
                    <div class="mb-3">
                        <label for="approveCatatan" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" id="approveCatatan" class="form-control" rows="3" 
                                  placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-times-circle text-danger me-2"></i>Tolak Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak pengajuan ini?</p>
                    <div class="mb-3">
                        <label for="rejectCatatan" class="form-label">Catatan Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="rejectCatatan" class="form-control" rows="3" 
                                  placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function approvePengajuan(id) {
        var form = document.getElementById('approveForm');
        form.action = "{{ url('kepala-sekolah/persetujuan') }}/" + id + "/approve";
        var modal = new bootstrap.Modal(document.getElementById('approveModal'));
        modal.show();
    }

    function rejectPengajuan(id) {
        var form = document.getElementById('rejectForm');
        form.action = "{{ url('kepala-sekolah/persetujuan') }}/" + id + "/reject";
        var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>
@endpush