@extends('administrasi.layouts.header')

@section('title', 'Pembayaran SPP')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-money-bill-wave me-2"></i>
        Pembayaran SPP
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.keuangan.spp.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Pembayaran
        </a>
        <a href="{{ route('administrasi.keuangan.spp.laporan') }}" class="btn btn-sm btn-info ms-2">
            <i class="fas fa-chart-line"></i> Laporan
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.keuangan.spp') }}" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanList as $key => $nama)
                        <option value="{{ $key }}" {{ ($bulan ?? '') == $key ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ ($tahun ?? '') == $thn ? 'selected' : '' }}>
                            {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ (request('kelas') ?? '') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach($statusList as $key => $nama)
                        <option value="{{ $key }}" {{ (request('status') ?? '') == $key ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $key => $nama)
                        <option value="{{ $key }}" {{ (request('kategori') ?? '') == $key ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('administrasi.keuangan.spp') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Pembayaran SPP</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spp as $index => $item)
                    <tr>
                        <td class="text-center">{{ $spp->firstItem() + $index }}</td>
                        <td>{{ $item->no_transaksi ?? 'SPP-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $item->siswa->nis ?? '-' }}</td>
                        <td>
                            <strong>{{ $item->siswa->user->name ?? $item->siswa->nama_lengkap ?? $item->siswa->nama ?? '-' }}</strong>
                        </td>
                        <td>
                            {{ $item->siswa->kelas->nama_kelas ?? $item->siswa->kelas->nama ?? '-' }}
                            @if($item->siswa->kelas && $item->siswa->kelas->tingkat)
                                <br><small class="text-muted">({{ $item->siswa->kelas->tingkat }})</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $item->kategori ?? 'SPP Bulanan' }}</span>
                        </td>
                        <td class="text-center">{{ $bulanList[$item->bulan] ?? '-' }}</td>
                        <td class="text-center">{{ $item->tahun }}</td>
                        <td class="text-end">Rp {{ number_format($item->jumlah ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">{{ ucfirst($item->metode_bayar ?? '-') }}</td>
                        <td class="text-center">
                            @if($item->status == 'lunas')
                                <span class="badge bg-success">✅ Lunas</span>
                            @elseif($item->status == 'belum_bayar')
                                <span class="badge bg-danger">⏳ Belum Bayar</span>
                            @else
                                <span class="badge bg-warning">⚠️ Terlambat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('administrasi.keuangan.spp.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deletePayment({{ $item->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center py-4">
                            <i class="fas fa-database fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Belum ada data pembayaran SPP</p>
                            <a href="{{ route('administrasi.keuangan.spp.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Tambah Pembayaran
                            </a>
                        </td>
                    </tr>
                    @endforelse 
                </tbody>            
            </table>
        </div>
        <div class="mt-3">
            {{ $spp->links() }}
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data pembayaran ini?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deletePayment(id) {
        // Buat form action dengan id yang benar
        var form = document.getElementById('deleteForm');
        var url = "{{ route('administrasi.keuangan.spp.destroy', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;
        
        // Tampilkan modal
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
@endsection