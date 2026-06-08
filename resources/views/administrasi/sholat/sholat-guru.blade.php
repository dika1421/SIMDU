@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Sholat Guru')

@section('content')
<style>
    .table-absen th {
        background: #f8f9fa;
        vertical-align: middle;
    }
    .status-select {
        min-width: 120px;
    }
    .btn-save-fixed {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        padding: 12px 25px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-user me-2"></i>
        Input Absensi Sholat Guru
    </h1>
    <div class="btn-toolbar">
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-filter me-2"></i> Filter Data
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('administrasi.absensi-sholat.guru') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Absensi</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-success form-control" onclick="setAllStatus('tepat_waktu')">
                    <i class="fas fa-check-circle"></i> Set Semua Hadir
                </button>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-warning form-control" onclick="resetAllStatus()">
                    <i class="fas fa-undo-alt"></i> Reset Semua
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Absensi -->
<form method="POST" action="{{ route('administrasi.absensi-sholat.manual-store') }}" id="absensiForm">
    @csrf
    <input type="hidden" name="role" value="guru">
    <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-2"></i> 
            Input Absensi Sholat Tanggal: {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->format('d F Y') }}
            <span class="badge bg-light text-dark ms-2">Total Guru: {{ $guru->count() ?? 0 }}</span>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="table-light">
                        <th width="5%">No</th>
                        <th width="15%">NIP</th>
                        <th width="25%">Nama Guru</th>
                        <th width="10%">Subuh</th>
                        <th width="10%">Dzuhur</th>
                        <th width="10%">Ashar</th>
                        <th width="10%">Maghrib</th>
                        <th width="10%">Isya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru ?? [] as $index => $g)
                    @php
                        // Ambil data absensi dengan aman
                        $absensiSubuh = $g->absensi['subuh'] ?? null;
                        $absensiDzuhur = $g->absensi['dzuhur'] ?? null;
                        $absensiAshar = $g->absensi['ashar'] ?? null;
                        $absensiMaghrib = $g->absensi['maghrib'] ?? null;
                        $absensiIsya = $g->absensi['isya'] ?? null;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $g->nip ?? '-' }}</td>
                        <td>
                            <strong>{{ $g->user->name ?? $g->nama_lengkap ?? '-' }}</strong>
                            <input type="hidden" name="absensi[{{ $g->id }}][user_id]" value="{{ $g->id }}">
                        </td>
                        
                        <!-- Subuh -->
                        <td>
                            <select name="absensi[{{ $g->id }}][subuh][status]" class="form-select form-select-sm status-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiSubuh && $absensiSubuh->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $g->id }}][subuh][sholat]" value="subuh">
                            <input type="time" name="absensi[{{ $g->id }}][subuh][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiSubuh && $absensiSubuh->waktu_absen ? date('H:i', strtotime($absensiSubuh->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $g->id }}][subuh][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiSubuh && $absensiSubuh->keterangan ? $absensiSubuh->keterangan : '' }}">
                        </td>
                        
                        <!-- Dzuhur -->
                        <td>
                            <select name="absensi[{{ $g->id }}][dzuhur][status]" class="form-select form-select-sm status-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiDzuhur && $absensiDzuhur->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $g->id }}][dzuhur][sholat]" value="dzuhur">
                            <input type="time" name="absensi[{{ $g->id }}][dzuhur][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiDzuhur && $absensiDzuhur->waktu_absen ? date('H:i', strtotime($absensiDzuhur->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $g->id }}][dzuhur][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiDzuhur && $absensiDzuhur->keterangan ? $absensiDzuhur->keterangan : '' }}">
                        </td>
                        
                        <!-- Ashar -->
                        <td>
                            <select name="absensi[{{ $g->id }}][ashar][status]" class="form-select form-select-sm status-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiAshar && $absensiAshar->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $g->id }}][ashar][sholat]" value="ashar">
                            <input type="time" name="absensi[{{ $g->id }}][ashar][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiAshar && $absensiAshar->waktu_absen ? date('H:i', strtotime($absensiAshar->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $g->id }}][ashar][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiAshar && $absensiAshar->keterangan ? $absensiAshar->keterangan : '' }}">
                        </td>
                        
                        <!-- Maghrib -->
                        <td>
                            <select name="absensi[{{ $g->id }}][maghrib][status]" class="form-select form-select-sm status-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiMaghrib && $absensiMaghrib->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $g->id }}][maghrib][sholat]" value="maghrib">
                            <input type="time" name="absensi[{{ $g->id }}][maghrib][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiMaghrib && $absensiMaghrib->waktu_absen ? date('H:i', strtotime($absensiMaghrib->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $g->id }}][maghrib][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiMaghrib && $absensiMaghrib->keterangan ? $absensiMaghrib->keterangan : '' }}">
                        </td>
                        
                        <!-- Isya -->
                        <td>
                            <select name="absensi[{{ $g->id }}][isya][status]" class="form-select form-select-sm status-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiIsya && $absensiIsya->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $g->id }}][isya][sholat]" value="isya">
                            <input type="time" name="absensi[{{ $g->id }}][isya][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiIsya && $absensiIsya->waktu_absen ? date('H:i', strtotime($absensiIsya->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $g->id }}][isya][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiIsya && $absensiIsya->keterangan ? $absensiIsya->keterangan : '' }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                            Tidak ada data guru
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(($guru->count() ?? 0) > 0)
    <button type="submit" class="btn btn-primary btn-save-fixed" id="btnSubmit">
        <i class="fas fa-save me-2"></i> Simpan Semua Absensi
    </button>
    @endif
</form>

@push('scripts')
<script>
    function setAllStatus(status) {
        let statusText = '';
        switch(status) {
            case 'tepat_waktu': statusText = 'Tepat Waktu'; break;
            case 'terlambat': statusText = 'Terlambat'; break;
            case 'tidak_hadir': statusText = 'Tidak Hadir'; break;
            case 'izin': statusText = 'Izin'; break;
            default: return;
        }
        
        Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin mengatur SEMUA guru dengan status "${statusText}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Set Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = status;
                });
                Swal.fire('Berhasil!', 'Semua status telah diatur', 'success');
            }
        });
    }
    
    function resetAllStatus() {
        Swal.fire({
            title: 'Konfirmasi Reset',
            text: 'Apakah Anda yakin ingin mereset semua status ke kosong?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = '';
                });
                Swal.fire('Berhasil!', 'Semua status telah direset', 'success');
            }
        });
    }
    
    $('#absensiForm').on('submit', function(e) {
        e.preventDefault();
        
        let hasData = false;
        document.querySelectorAll('.status-select').forEach(select => {
            if (select.value !== '') hasData = true;
        });
        
        if (!hasData) {
            Swal.fire('Peringatan!', 'Belum ada data absensi yang diisi', 'warning');
            return false;
        }
        
        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: 'Apakah Anda yakin ingin menyimpan semua absensi?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop('disabled', true);
                $('#absensiForm')[0].submit();
            }
        });
    });
</script>
@endpush
@endsection