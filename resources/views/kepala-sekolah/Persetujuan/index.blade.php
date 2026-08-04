@extends('kepala-sekolah.layouts.header')

@section('title', 'Daftar Persetujuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-check-double me-2"></i> Daftar Persetujuan</h4>
                <div>
                    <a href="{{ route('kepala-sekolah.persetujuan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
            </div>

            <!-- Statistik -->
            <div class="row mb-3">
                <div class="col-md-3 col-6">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">{{ $menunggu ?? 0 }}</h5>
                            <p class="card-text">⏳ Menunggu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $disetujui ?? 0 }}</h5>
                            <p class="card-text">✅ Disetujui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ditolak ?? 0 }}</h5>
                            <p class="card-text">❌ Ditolak</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $revisi ?? 0 }}</h5>
                            <p class="card-text">📝 Revisi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('kepala-sekolah.persetujuan.index') }}" class="row g-2">
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
                                   placeholder="Cari..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Pengaju</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuan ?? [] as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->judul ?? '-' }}</td>
                                    <td>{{ $item->pengaju->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->tipe ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($item->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">⏳ Menunggu</span>
                                        @elseif($item->status == 'disetujui')
                                            <span class="badge bg-success">✅ Disetujui</span>
                                        @elseif($item->status == 'ditolak')
                                            <span class="badge bg-danger">❌ Ditolak</span>
                                        @elseif($item->status == 'revisi')
                                            <span class="badge bg-info">📝 Revisi</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
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
                                                  onsubmit="return confirm('⚠️ Yakin ingin menghapus data ini?')">
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
                    {{ $pengajuan->links() ?? '' }}
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
        // 🔥 PERBAIKAN: Gunakan route helper, bukan hardcode
        form.action = "{{ url('kepala-sekolah/persetujuan') }}/" + id + "/approve";
        var modal = new bootstrap.Modal(document.getElementById('approveModal'));
        modal.show();
    }

    function rejectPengajuan(id) {
        var form = document.getElementById('rejectForm');
        // 🔥 PERBAIKAN: Gunakan route helper, bukan hardcode
        form.action = "{{ url('kepala-sekolah/persetujuan') }}/" + id + "/reject";
        var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>
@endpush