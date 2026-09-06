@extends('administrasi.layouts.header')

@section('title', 'Edit Jadwal')

@section('content')
<style>
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 15px;
        color: white;
        margin-top: 10px;
    }
    .auto-fill-badge {
        background: #e8f5e9;
        color: #2e7d32;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
        margin-left: 8px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Jadwal Pelajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan!</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header bg-white">
        <i class="fas fa-edit me-2 text-primary"></i>
        <strong>Form Edit Jadwal Pelajaran</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('administrasi.jadwal.update', $jadwal->id) }}" method="POST" id="jadwalForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Pilih Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school"></i> Kelas <span class="text-danger">*</span>
                        <span class="auto-fill-badge"><i class="fas fa-magic"></i> Pilih dulu untuk isi ruangan</span>
                    </label>
                    <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" 
                                data-kode="{{ $k->kode_kelas ?? $k->kode ?? '' }}"
                                data-nama="{{ $k->nama ?? $k->nama_kelas ?? '' }}"
                                data-tingkat="{{ $k->tingkat ?? '' }}"
                                data-jurusan="{{ $k->jurusan->nama ?? '' }}"
                                {{ old('kelas_id', $jadwal->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama ?? $k->nama_kelas ?? $k->kelas }} 
                            ({{ $k->kode_kelas ?? $k->kode ?? 'Kode tidak tersedia' }})
                        </option>
                        @endforeach
                    </select>
                    @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Preview Informasi Kelas -->
                <div class="col-md-6 mb-3" id="kelasPreview" style="display: {{ old('kelas_id', $jadwal->kelas_id) ? 'block' : 'none' }};">
                    <label class="form-label"><i class="fas fa-info-circle"></i> Informasi Kelas</label>
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6"><small><i class="fas fa-tag"></i> Kode Kelas:</small><br><strong id="previewKode">-</strong></div>
                            <div class="col-6"><small><i class="fas fa-layer-group"></i> Tingkat:</small><br><strong id="previewTingkat">-</strong></div>
                            <div class="col-12 mt-2"><small><i class="fas fa-graduation-cap"></i> Jurusan:</small><br><strong id="previewJurusan">-</strong></div>
                        </div>
                    </div>
                </div>
                
                <!-- Mata Pelajaran -->
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-book"></i> Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="mapel_id" id="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @if(isset($mapel) && (is_object($mapel) ? $mapel->count() > 0 : count($mapel) > 0))
                            @foreach($mapel as $m)
                            <option value="{{ $m->id }}" {{ old('mapel_id', $jadwal->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                            @endforeach
                        @else
                            <option value="" disabled class="text-danger">⚠️ Belum ada data mata pelajaran</option>
                        @endif
                    </select>
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Jika tidak ada pilihan, tambahkan data mata pelajaran terlebih dahulu</small>
                    @error('mapel_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Guru Pengajar -->
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-chalkboard-user"></i> Guru Pengajar <span class="text-danger">*</span></label>
                    <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ old('guru_id', $jadwal->guru_id) == $g->id ? 'selected' : '' }}>
                            {{ $g->user->name ?? $g->nama_lengkap ?? $g->nama ?? 'Guru' }}
                        </option>
                        @endforeach
                    </select>
                    @error('guru_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Hari -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-day"></i> Hari <span class="text-danger">*</span></label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="senin" {{ old('hari', $jadwal->hari) == 'senin' ? 'selected' : '' }}>Senin</option>
                        <option value="selasa" {{ old('hari', $jadwal->hari) == 'selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="rabu" {{ old('hari', $jadwal->hari) == 'rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="kamis" {{ old('hari', $jadwal->hari) == 'kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="jumat" {{ old('hari', $jadwal->hari) == 'jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="sabtu" {{ old('hari', $jadwal->hari) == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Jam Mulai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-clock"></i> Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" required>
                    @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Jam Selesai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-clock"></i> Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" required>
                    @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Ruangan -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-building"></i> Ruangan <span class="text-danger">*</span></label>
                    <input type="text" name="ruang" id="ruang" class="form-control" value="{{ old('ruang', $jadwal->ruangan) }}" placeholder="Akan terisi otomatis" readonly style="background-color: #e8f5e9;">
                    <small class="text-muted" id="ruangHint"><i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis dari kode kelas</small>
                    @error('ruang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Tahun Ajaran (DIPERBAIKI) -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</label>
                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @if(isset($tahunAjaranList) && $tahunAjaranList->count() > 0)
                            @foreach($tahunAjaranList as $ta)
                                @php
                                    $namaTa = is_object($ta) ? $ta->nama_tahun : $ta['nama_tahun'];
                                    $isAktif = is_object($ta) ? $ta->is_aktif : $ta['is_aktif'];
                                @endphp
                                <option value="{{ $namaTa }}" 
                                    {{ old('tahun_ajaran', $jadwal->tahun_ajaran) == $namaTa ? 'selected' : '' }}>
                                    {{ $namaTa }}
                                    @if($isAktif)
                                        (Aktif)
                                    @endif
                                </option>
                            @endforeach
                        @else
                            <option value="{{ date('Y') . '/' . (date('Y') + 1) }}" selected>
                                {{ date('Y') . '/' . (date('Y') + 1) }}
                            </option>
                        @endif
                    </select>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Pilih tahun ajaran
                    </small>
                    @error('tahun_ajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Semester -->
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-week"></i> Semester</label>
                    <select name="semester" class="form-control @error('semester') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>
                        <option value="ganjil" {{ old('semester', $jadwal->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ old('semester', $jadwal->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Informasi Tambahan -->
                <div class="col-md-12 mb-3">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <small>Pastikan tidak ada jadwal yang bentrok untuk kelas, guru, dan ruangan yang sama.</small>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary" id="resetBtn">Reset</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Update Jadwal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill ruangan dari kelas
    $('#kelas_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kodeKelas = selectedOption.data('kode');
        var namaKelas = selectedOption.data('nama');
        var tingkatKelas = selectedOption.data('tingkat');
        var jurusanKelas = selectedOption.data('jurusan');
        
        var ruangan = kodeKelas || (namaKelas ? namaKelas.substring(0, 5).toUpperCase().replace(/\s/g, '') : '');
        
        if ($(this).val() !== '') {
            $('#kelasPreview').fadeIn();
            $('#previewKode').text(kodeKelas || '-');
            $('#previewTingkat').text(tingkatKelas || '-');
            $('#previewJurusan').text(jurusanKelas || '-');
            $('#ruangHint').html('<i class="fas fa-check-circle text-success"></i> Ruangan terisi otomatis: <strong>' + ruangan + '</strong>');
            $('#ruang').css('background-color', '#e8f5e9');
        } else {
            $('#kelasPreview').fadeOut();
            $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
            $('#ruang').css('background-color', '#f5f5f5');
        }
        $('#ruang').val(ruangan);
    });
    
    if ($('#kelas_id').val()) {
        $('#kelas_id').trigger('change');
    }
    
    // Validasi jam
    function validateTime() {
        var jamMulai = $('#jam_mulai').val();
        var jamSelesai = $('#jam_selesai').val();
        if (jamMulai && jamSelesai && jamMulai >= jamSelesai) {
            $('#jam_selesai').addClass('is-invalid');
            $('#jam_selesai').next('.invalid-feedback').remove();
            $('#jam_selesai').after('<div class="invalid-feedback">Jam selesai harus lebih besar dari jam mulai</div>');
            return false;
        } else {
            $('#jam_selesai').removeClass('is-invalid');
            $('#jam_selesai').next('.invalid-feedback').remove();
            return true;
        }
        return true;
    }
    
    $('#jam_mulai, #jam_selesai').on('change', validateTime);
    
    // Reset form
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        $('#jadwalForm')[0].reset();
        $('#kelasPreview').hide();
        $('#ruang').val('');
        $('#ruang').css('background-color', '#f5f5f5');
        $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
        $('#kelas_id').css('border-color', '#ddd');
        
        @if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
        $('#tahun_ajaran').val('{{ $tahunAjaranAktif->nama_tahun }}');
        @else
        $('#tahun_ajaran').val('');
        @endif
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });
    
    // Validasi form
    $('#jadwalForm').on('submit', function(e) {
        var isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        
        if (!$('#kelas_id').val()) { $('#kelas_id').addClass('is-invalid'); isValid = false; }
        if (!$('#mapel_id').val()) { $('#mapel_id').addClass('is-invalid'); isValid = false; }
        if (!$('#guru_id').val()) { $('#guru_id').addClass('is-invalid'); isValid = false; }
        if (!$('#hari').val()) { $('#hari').addClass('is-invalid'); isValid = false; }
        if (!$('#jam_mulai').val()) { $('#jam_mulai').addClass('is-invalid'); isValid = false; }
        if (!$('#jam_selesai').val()) { $('#jam_selesai').addClass('is-invalid'); isValid = false; }
        if (!$('#ruang').val()) { $('#ruang').addClass('is-invalid'); isValid = false; }
        
        if (!isValid) {
            e.preventDefault();
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        if (!validateTime()) {
            e.preventDefault();
            alert('Periksa kembali jam mulai dan jam selesai');
            return false;
        }
        
        // Loading state
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $('#submitBtn').prop('disabled', true);
        
        return true;
    });
});
</script>
@endpush
@endsection