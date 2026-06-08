@extends('guru.layouts.header')

@section('title', 'Absensi Siswa')

@section('content')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        transition: transform 0.3s;
    }
    .stats-card:hover { transform: translateY(-5px); }
    .stats-number { font-size: 32px; font-weight: bold; }
    .btn-scan-fixed {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .btn-scan-fixed:hover { transform: scale(1.1); color: white; }
    .kelas-info {
        background: #e8f4fd;
        border-left: 4px solid #3498db;
        padding: 10px 15px;
        margin-bottom: 15px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-check me-2"></i>
        Absensi Siswa
    </h1>
    <div class="btn-toolbar">
        <a href="{{ route('guru.absensi.scan') }}" class="btn btn-sm btn-success me-2">
            <i class="fas fa-rss"></i> Scan RFID
        </a>
        <a href="{{ route('guru.absensi.riwayat') }}" class="btn btn-sm btn-info">
            <i class="fas fa-history"></i> Riwayat Absensi
        </a>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i> Filter Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('guru.absensi.index') }}" id="filterForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" class="form-select" id="kelasSelect" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? $k->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <!-- PERBAIKAN: Hapus atribut disabled, tambahkan semua mata pelajaran -->
                            <select name="mata_pelajaran_id" class="form-select" id="mapelSelect" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mataPelajaranList ?? [] as $mp)
                                <option value="{{ $mp->id }}" {{ $mataPelajaranId == $mp->id ? 'selected' : '' }}>
                                    {{ $mp->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cari NIS/Nama</label>
                            <input type="text" name="search" class="form-control" placeholder="NIS / Nama" value="{{ $search }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('guru.absensi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Info Kelas & Mapel Terpilih -->
@if($kelasId && $mataPelajaranId)
<div class="kelas-info">
    <div class="row">
        <div class="col-md-6">
            <i class="fas fa-school me-2"></i>
            <strong>Kelas:</strong> 
            {{ $kelas->firstWhere('id', $kelasId)->nama_kelas ?? $kelas->firstWhere('id', $kelasId)->nama ?? '-' }}
            <span class="badge bg-primary ms-2">{{ $totalSiswa }} Siswa</span>
        </div>
        <div class="col-md-6">
            <i class="fas fa-book me-2"></i>
            <strong>Mata Pelajaran:</strong> 
            {{ $mataPelajaranList->firstWhere('id', $mataPelajaranId)->nama ?? '-' }}
        </div>
    </div>
</div>
@elseif($kelasId && !$mataPelajaranId)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Silakan pilih mata pelajaran terlebih dahulu!
</div>
@elseif(!$kelasId)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Silakan pilih kelas dan mata pelajaran terlebih dahulu!
</div>
@endif

<!-- Statistik Cards (hanya tampil jika kelas dan mapel dipilih) -->
@if($kelasId && $mataPelajaranId)
<div class="row mb-4">
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card bg-primary">
            <i class="fas fa-users fa-2x mb-2"></i>
            <div class="stats-number">{{ $totalSiswa }}</div>
            <small>Total Siswa</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <i class="fas fa-check-circle fa-2x mb-2"></i>
            <div class="stats-number">{{ $hadir }}</div>
            <small>Hadir</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
            <i class="fas fa-clock fa-2x mb-2"></i>
            <div class="stats-number">{{ $terlambat }}</div>
            <small>Terlambat</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #138496);">
            <i class="fas fa-notes-medical fa-2x mb-2"></i>
            <div class="stats-number">{{ $sakit }}</div>
            <small>Sakit</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card" style="background: linear-gradient(135deg, #6c757d, #5a6268);">
            <i class="fas fa-file-alt fa-2x mb-2"></i>
            <div class="stats-number">{{ $izin }}</div>
            <small>Izin</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #c82333);">
            <i class="fas fa-times-circle fa-2x mb-2"></i>
            <div class="stats-number">{{ $belumAbsen }}</div>
            <small>Belum Absen</small>
        </div>
    </div>
</div>

<!-- Form Absensi -->
<form method="POST" action="{{ route('guru.absensi.store') }}" id="absensiForm">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
    <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaranId }}">
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-2"></i> 
            Input Absensi 
            - Kelas: {{ $kelas->firstWhere('id', $kelasId)->nama_kelas ?? $kelas->firstWhere('id', $kelasId)->nama ?? '-' }}
            - Mata Pelajaran: {{ $mataPelajaranList->firstWhere('id', $mataPelajaranId)->nama ?? '-' }}
            - Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">NIS</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="20%">Status</th>
                        <th width="30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $s->nis ?? '-' }}</td>
                        <td>
                            <strong>{{ $s->user->name ?? $s->nama ?? '-' }}</strong>
                            <input type="hidden" name="absensi[{{ $s->id }}][siswa_id]" value="{{ $s->id }}">
                        </td>
                        <td>{{ $s->kelas->nama ?? '-' }}</td>
                        <td>
                            <select name="absensi[{{ $s->id }}][status]" class="form-select status-select" data-id="{{ $s->id }}">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statusList as $key => $label)
                                    <option value="{{ $key }}" {{ $s->status_absensi == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="time" name="absensi[{{ $s->id }}][waktu_absen]" class="form-control form-control-sm mt-1 waktu-masuk-{{ $s->id }}" 
                                   placeholder="Jam Masuk" value="{{ $s->waktu_absensi ? date('H:i', strtotime($s->waktu_absensi)) : '' }}">
                        </td>
                        <td>
                            <input type="text" name="absensi[{{ $s->id }}][keterangan]" class="form-control" 
                                   placeholder="Keterangan (opsional)" value="{{ $s->keterangan_absensi }}">
                        </td>
                    </tr>
                    @empty
                    <td>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                            Tidak ada data siswa di kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($siswa->count() > 0)
    <div class="text-end mt-3">
        <button type="button" class="btn btn-success me-2" onclick="setAllStatus('hadir')">
            <i class="fas fa-check-circle"></i> Set Semua Hadir
        </button>
        <button type="button" class="btn btn-warning me-2" onclick="resetAllStatus()">
            <i class="fas fa-undo-alt"></i> Reset Semua
        </button>
        <button type="submit" class="btn btn-primary" id="btnSubmit">
            <i class="fas fa-save me-2"></i> Simpan Absensi
        </button>
    </div>
    @endif
</form>
@endif

<!-- Floating Scan Button -->
@if($kelasId && $mataPelajaranId)
<a href="{{ route('guru.absensi.scan') }}?kelas_id={{ $kelasId }}&mata_pelajaran_id={{ $mataPelajaranId }}" class="btn-scan-fixed">
    <i class="fas fa-rss"></i>
</a>
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        // Event ketika kelas berubah - update mata pelajaran berdasarkan kelas
        $('#kelasSelect').on('change', function() {
            var kelasId = $(this).val();
            var mapelSelect = $('#mapelSelect');
            
            if (kelasId) {
                // AJAX request untuk mendapatkan mata pelajaran berdasarkan kelas
                $.ajax({
                    url: '{{ route("guru.absensi.get-mata-pelajaran") }}',
                    method: 'GET',
                    data: { kelas_id: kelasId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            var options = '<option value="">-- Pilih Mata Pelajaran --</option>';
                            $.each(response.data, function(key, mapel) {
                                options += '<option value="' + mapel.id + '">' + mapel.nama + '</option>';
                            });
                            mapelSelect.html(options);
                        } else {
                            mapelSelect.html('<option value="">-- Tidak ada mata pelajaran --</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        mapelSelect.html('<option value="">-- Gagal memuat data --</option>');
                    }
                });
            }
        });
        
        // Auto submit ketika mapel dipilih
        $('#mapelSelect').on('change', function() {
            if ($(this).val() !== '' && $('#kelasSelect').val() !== '') {
                $('#filterForm').submit();
            }
        });
        
        $('.status-select').change(function() {
            var siswaId = $(this).data('id');
            var waktuMasuk = $('.waktu-masuk-' + siswaId);
            
            if ($(this).val() === 'hadir') {
                waktuMasuk.prop('disabled', false);
                if (!waktuMasuk.val()) {
                    var now = new Date();
                    var hours = now.getHours().toString().padStart(2, '0');
                    var minutes = now.getMinutes().toString().padStart(2, '0');
                    waktuMasuk.val(hours + ':' + minutes);
                }
            } else {
                waktuMasuk.prop('disabled', true);
                waktuMasuk.val('');
            }
        });
    });
    
    function setAllStatus(status) {
        let statusText = '';
        switch(status) {
            case 'hadir': statusText = 'Hadir'; break;
            case 'sakit': statusText = 'Sakit'; break;
            case 'izin': statusText = 'Izin'; break;
            case 'alfa': statusText = 'Alfa'; break;
            case 'terlambat': statusText = 'Terlambat'; break;
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
                $('.status-select').each(function() {
                    $(this).val(status).trigger('change');
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
                $('.status-select').each(function() {
                    $(this).val('').trigger('change');
                });
                Swal.fire('Berhasil!', 'Semua status telah direset', 'success');
            }
        });
    }
    
    $('#absensiForm').on('submit', function(e) {
        let hasData = false;
        $('.status-select').each(function() {
            if ($(this).val() !== '') hasData = true;
        });
        
        if (!hasData) {
            e.preventDefault();
            Swal.fire('Peringatan!', 'Belum ada data absensi yang diisi', 'warning');
            return false;
        }
        
        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop('disabled', true);
    });
</script>
@endpush
@endsection