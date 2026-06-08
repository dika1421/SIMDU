@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Sholat Siswa')

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
    .search-highlight {
        background-color: #fff3cd !important;
    }
    .filter-row {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-graduate me-2"></i>
        Input Absensi Sholat Siswa
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
        <form method="GET" action="{{ route('administrasi.absensi-sholat.siswa') }}" class="row g-3" id="filterForm">
            <div class="col-md-2">
                <label class="form-label">Tanggal Absensi</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select" id="kelasSelect">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList ?? [] as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Cari NIS/Nama</label>
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="NIS / Nama Siswa" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('administrasi.absensi-sholat.siswa') }}" class="btn btn-secondary form-control">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-success form-control" onclick="setAllStatus('tepat_waktu')">
                    <i class="fas fa-check-circle"></i> Set Semua Hadir
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Live Search (Client Side) -->
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <i class="fas fa-search me-2"></i> Pencarian Cepat (Live Search)
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="liveSearch" class="form-control" placeholder="Ketik NIS atau Nama siswa...">
                <small class="text-muted">Pencarian langsung tanpa reload halaman</small>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary" onclick="clearSearch()">
                    <i class="fas fa-times"></i> Clear Search
                </button>
            </div>
            <div class="col-md-5 text-end">
                <span id="searchResultCount" class="badge bg-primary">0</span> siswa ditemukan
            </div>
        </div>
    </div>
</div>

<!-- Form Absensi -->
<form method="POST" action="{{ route('administrasi.absensi-sholat.manual-store') }}" id="absensiForm">
    @csrf
    <input type="hidden" name="role" value="siswa">
    <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-2"></i> 
            Input Absensi Sholat Tanggal: {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->format('d F Y') }}
            <span class="badge bg-light text-dark ms-2" id="totalSiswa">{{ $siswa->count() ?? 0 }}</span>
            <span class="badge bg-light text-dark ms-2" id="totalSiswaLabel">Total Siswa</span>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="tabelSiswa">
                <thead>
                    <tr class="table-light">
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="12%">Kelas</th>
                        <th width="9%">Subuh</th>
                        <th width="9%">Dzuhur</th>
                        <th width="9%">Ashar</th>
                        <th width="9%">Maghrib</th>
                        <th width="9%">Isya</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($siswa ?? [] as $index => $s)
                    @php
                        // Ambil data absensi dengan aman
                        $absensiSubuh = $s->absensi['subuh'] ?? null;
                        $absensiDzuhur = $s->absensi['dzuhur'] ?? null;
                        $absensiAshar = $s->absensi['ashar'] ?? null;
                        $absensiMaghrib = $s->absensi['maghrib'] ?? null;
                        $absensiIsya = $s->absensi['isya'] ?? null;
                    @endphp
                    <tr data-nis="{{ $s->nis ?? '' }}" data-nama="{{ strtolower($s->user->name ?? $s->nama ?? '') }}" data-id="{{ $s->id }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="nis-cell">{{ $s->nis ?? '-' }}</td>
                        <td>
                            <strong>{{ $s->user->name ?? $s->nama ?? '-' }}</strong>
                            <input type="hidden" name="absensi[{{ $s->id }}][user_id]" value="{{ $s->id }}">
                        </td>
                        <td>{{ $s->kelas->nama ?? '-' }}</td>
                        
                        <!-- Subuh -->
                        <td class="text-center">
                            <select name="absensi[{{ $s->id }}][subuh][status]" class="form-select form-select-sm status-select status-subuh-{{ $s->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiSubuh && $absensiSubuh->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $s->id }}][subuh][sholat]" value="subuh">
                            <input type="time" name="absensi[{{ $s->id }}][subuh][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiSubuh && $absensiSubuh->waktu_absen ? date('H:i', strtotime($absensiSubuh->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $s->id }}][subuh][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiSubuh && $absensiSubuh->keterangan ? $absensiSubuh->keterangan : '' }}">
                        </td>
                        
                        <!-- Dzuhur -->
                        <td class="text-center">
                            <select name="absensi[{{ $s->id }}][dzuhur][status]" class="form-select form-select-sm status-select status-dzuhur-{{ $s->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiDzuhur && $absensiDzuhur->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $s->id }}][dzuhur][sholat]" value="dzuhur">
                            <input type="time" name="absensi[{{ $s->id }}][dzuhur][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiDzuhur && $absensiDzuhur->waktu_absen ? date('H:i', strtotime($absensiDzuhur->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $s->id }}][dzuhur][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiDzuhur && $absensiDzuhur->keterangan ? $absensiDzuhur->keterangan : '' }}">
                        </td>
                        
                        <!-- Ashar -->
                        <td class="text-center">
                            <select name="absensi[{{ $s->id }}][ashar][status]" class="form-select form-select-sm status-select status-ashar-{{ $s->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiAshar && $absensiAshar->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $s->id }}][ashar][sholat]" value="ashar">
                            <input type="time" name="absensi[{{ $s->id }}][ashar][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiAshar && $absensiAshar->waktu_absen ? date('H:i', strtotime($absensiAshar->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $s->id }}][ashar][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiAshar && $absensiAshar->keterangan ? $absensiAshar->keterangan : '' }}">
                        </td>
                        
                        <!-- Maghrib -->
                        <td class="text-center">
                            <select name="absensi[{{ $s->id }}][maghrib][status]" class="form-select form-select-sm status-select status-maghrib-{{ $s->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiMaghrib && $absensiMaghrib->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $s->id }}][maghrib][sholat]" value="maghrib">
                            <input type="time" name="absensi[{{ $s->id }}][maghrib][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiMaghrib && $absensiMaghrib->waktu_absen ? date('H:i', strtotime($absensiMaghrib->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $s->id }}][maghrib][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiMaghrib && $absensiMaghrib->keterangan ? $absensiMaghrib->keterangan : '' }}">
                        </td>
                        
                        <!-- Isya -->
                        <td class="text-center">
                            <select name="absensi[{{ $s->id }}][isya][status]" class="form-select form-select-sm status-select status-isya-{{ $s->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ ($absensiIsya && $absensiIsya->status == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="absensi[{{ $s->id }}][isya][sholat]" value="isya">
                            <input type="time" name="absensi[{{ $s->id }}][isya][waktu_absen]" class="form-control form-control-sm mt-1" placeholder="Waktu" value="{{ $absensiIsya && $absensiIsya->waktu_absen ? date('H:i', strtotime($absensiIsya->waktu_absen)) : '' }}">
                            <input type="text" name="absensi[{{ $s->id }}][isya][keterangan]" class="form-control form-control-sm mt-1" placeholder="Keterangan" value="{{ $absensiIsya && $absensiIsya->keterangan ? $absensiIsya->keterangan : '' }}">
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(($siswa->count() ?? 0) > 0)
    <button type="submit" class="btn btn-primary btn-save-fixed" id="btnSubmit">
        <i class="fas fa-save me-2"></i> Simpan Semua Absensi
    </button>
    @endif
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        // Live Search functionality
        $('#liveSearch').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            var visibleCount = 0;
            
            $('#tableBody tr').each(function() {
                var nis = $(this).find('.nis-cell').text().toLowerCase();
                var nama = $(this).attr('data-nama') || '';
                var row = $(this);
                
                if (searchTerm === '') {
                    row.show();
                    visibleCount++;
                    row.removeClass('search-highlight');
                } else if (nis.indexOf(searchTerm) !== -1 || nama.indexOf(searchTerm) !== -1) {
                    row.show();
                    visibleCount++;
                    row.addClass('search-highlight');
                } else {
                    row.hide();
                    row.removeClass('search-highlight');
                }
            });
            
            $('#searchResultCount').text(visibleCount);
        });
    });
    
    function clearSearch() {
        $('#liveSearch').val('');
        $('#liveSearch').trigger('keyup');
        $('#searchResultCount').text($('#tableBody tr:visible').length);
    }
    
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
            text: `Apakah Anda yakin ingin mengatur SEMUA siswa dengan status "${statusText}"?`,
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