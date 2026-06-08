@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Siswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-check me-2"></i>
        Input Absensi Siswa
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.absensi.rekap-siswa') }}" class="btn btn-sm btn-info">
            <i class="fas fa-chart-line"></i> Lihat Rekap
        </a>
    </div>
</div>

<!-- Form Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ ($kelas_id ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama ?? $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Absensi -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Siswa - Tanggal {{ \Carbon\Carbon::parse($tanggal ?? date('Y-m-d'))->format('d/m/Y') }}</h5>
        <span class="badge bg-info">{{ $siswa->count() ?? 0 }} Siswa</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('administrasi.absensi.store-siswa') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">NIS</th>
                            <th width="20%">Nama</th>
                            <th width="10%">Kelas</th>
                            <th width="15%">Status Kehadiran</th>
                            <th width="12%">Waktu Masuk</th>
                            <th width="12%">Waktu Keluar</th>
                            <th width="16%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa ?? [] as $index => $s)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td><strong>{{ $s->user->name ?? $s->nama_lengkap ?? '-' }}</strong></td>
                            <td>{{ $s->kelas->nama ?? $s->kelas->nama_kelas ?? '-' }}</td>
                            <td>
                                <select name="absensi[{{ $s->id }}][status]" class="form-select">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="hadir" {{ optional($s->absensi_hari_ini)->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="sakit" {{ optional($s->absensi_hari_ini)->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="izin" {{ optional($s->absensi_hari_ini)->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alfa" {{ optional($s->absensi_hari_ini)->status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                    <option value="terlambat" {{ optional($s->absensi_hari_ini)->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                </select>
                            </td>
                            <td>
                                <input type="time" name="absensi[{{ $s->id }}][waktu_masuk]" class="form-control" 
                                       value="{{ optional($s->absensi_hari_ini)->waktu_masuk ? \Carbon\Carbon::parse($s->absensi_hari_ini->waktu_masuk)->format('H:i') : '' }}">
                            </td>
                            <td>
                                <input type="time" name="absensi[{{ $s->id }}][waktu_keluar]" class="form-control"
                                       value="{{ optional($s->absensi_hari_ini)->waktu_keluar ? \Carbon\Carbon::parse($s->absensi_hari_ini->waktu_keluar)->format('H:i') : '' }}">
                            </td>
                            <td>
                                <input type="text" name="absensi[{{ $s->id }}][keterangan]" class="form-control" 
                                       value="{{ optional($s->absensi_hari_ini)->keterangan }}">
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Tidak ada data siswa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(($siswa ?? collect())->count() > 0)
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto submit filter ketika kelas berubah
    document.querySelector('select[name="kelas_id"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
@endsection