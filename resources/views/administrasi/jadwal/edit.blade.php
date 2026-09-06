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

    .form-section-title {
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #667eea;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px;
    }

    .card-header i {
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4292 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        border-radius: 8px;
        padding: 10px 30px;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 8px;
    }

    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 15px 20px;
        color: white;
        margin-top: 5px;
    }

    .info-card small {
        opacity: 0.85;
        font-size: 11px;
    }

    .info-card strong {
        font-size: 14px;
    }

    .badge-auto {
        background: rgba(255,255,255,0.2);
        color: #fff;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 12px;
        margin-left: 8px;
    }

    .form-label {
        font-weight: 500;
        color: #34495e;
        font-size: 13px;
    }

    .form-label .text-danger {
        font-weight: 700;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 35px;
        cursor: pointer;
    }

    .select-wrapper select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-header h5 {
            font-size: 16px;
        }
        .app-content {
            padding: 10px !important;
        }
        .info-card {
            padding: 10px 15px;
        }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2 text-primary"></i>
        Edit Jadwal Pelajaran
    </h1>
    <div class="btn-toolbar">
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
            <strong>Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>
            Form Edit Jadwal Pelajaran
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('administrasi.jadwal.update', $jadwal->id) }}" method="POST" id="jadwalForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- ========== BAGIAN 1: KELAS & INFORMASI ========== -->
                <div class="col-12 mb-3">
                    <h6 class="form-section-title">
                        <i class="fas fa-school me-2 text-primary"></i>
                        Informasi Kelas & Pengajar
                    </h6>
                </div>

                <!-- Pilih Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school me-1"></i> Kelas
                        <span class="text-danger">*</span>
                        <span class="badge bg-success text-white ms-1" style="font-size: 10px;">
                            <i class="fas fa-magic"></i> Auto-fill
                        </span>
                    </label>
                    <div class="select-wrapper">
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
                    </div>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Preview Informasi Kelas -->
                <div class="col-md-6 mb-3" id="kelasPreview" style="display: {{ old('kelas_id', $jadwal->kelas_id) ? 'block' : 'none' }};">
                    <label class="form-label">
                        <i class="fas fa-info-circle me-1"></i> Informasi Kelas
                    </label>
                    <div class="info-card">
                        <div class="row">
                            <div class="col-4">
                                <small><i class="fas fa-tag"></i> Kode Kelas</small><br>
                                <strong id="previewKode">-</strong>
                            </div>
                            <div class="col-4">
                                <small><i class="fas fa-layer-group"></i> Tingkat</small><br>
                                <strong id="previewTingkat">-</strong>
                            </div>
                            <div class="col-4">
                                <small><i class="fas fa-graduation-cap"></i> Jurusan</small><br>
                                <strong id="previewJurusan">-</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mata Pelajaran -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-book me-1"></i> Mata Pelajaran
                        <span class="text-danger">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select name="mapel_id" id="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @if(isset($mapel) && (is_object($mapel) ? $mapel->count() > 0 : count($mapel) > 0))
                                @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_id', $jadwal->mapel_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }}
                                </option>
                                @endforeach
                            @else
                                <option value="" disabled class="text-danger">⚠️ Belum ada data mata pelajaran</option>
                            @endif
                        </select>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Jika tidak ada pilihan, tambahkan data mata pelajaran terlebih dahulu
                    </small>
                    @error('mapel_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Guru Pengajar -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-chalkboard-user me-1"></i> Guru Pengajar
                        <span class="text-danger">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ old('guru_id', $jadwal->guru_id) == $g->id ? 'selected' : '' }}>
                                {{ $g->user->name ?? $g->nama_lengkap ?? $g->nama ?? 'Guru' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ========== BAGIAN 2: JADWAL ========== -->
                <div class="col-12 mt-3 mb-3">
                    <h6 class="form-section-title">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        Waktu & Ruangan
                    </h6>
                </div>

                <!-- Hari -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-day me-1"></i> Hari
                        <span class="text-danger">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="senin" {{ old('hari', $jadwal->hari) == 'senin' ? 'selected' : '' }}>Senin</option>
                            <option value="selasa" {{ old('hari', $jadwal->hari) == 'selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="rabu" {{ old('hari', $jadwal->hari) == 'rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="kamis" {{ old('hari', $jadwal->hari) == 'kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="jumat" {{ old('hari', $jadwal->hari) == 'jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="sabtu" {{ old('hari', $jadwal->hari) == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                    </div>
                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jam Mulai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-play-circle me-1"></i> Jam Mulai
                        <span class="text-danger">*</span>
                    </label>
                    <input type="time" name="jam_mulai" id="jam_mulai" 
                           class="form-control @error('jam_mulai') is-invalid @enderror" 
                           value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                           required>
                    @error('jam_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-stop-circle me-1"></i> Jam Selesai
                        <span class="text-danger">*</span>
                    </label>
                    <input type="time" name="jam_selesai" id="jam_selesai" 
                           class="form-control @error('jam_selesai') is-invalid @enderror" 
                           value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                           required>
                    @error('jam_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ruangan -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-door-open me-1"></i> Ruangan
                        <span class="text-danger">*</span>
                        <span class="badge bg-info text-white ms-1" style="font-size: 10px;">
                            <i class="fas fa-sync-alt"></i> Auto
                        </span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-door-open text-primary"></i>
                        </span>
                        <input type="text" name="ruang" id="ruang" 
                               class="form-control @error('ruang') is-invalid @enderror" 
                               value="{{ old('ruang', $jadwal->ruangan) }}"
                               placeholder="Akan terisi otomatis" 
                               readonly style="background-color: {{ old('ruang', $jadwal->ruangan) ? '#e8f5e9' : '#f8f9fa' }}; font-weight: 600; color: #2c3e50;">
                    </div>
                    <small class="text-muted" id="ruangHint">
                        <i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis dari kode kelas
                    </small>
                    @error('ruang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ========== BAGIAN 3: TAHUN AJARAN & SEMESTER ========== -->
                <div class="col-12 mt-3 mb-3">
                    <h6 class="form-section-title">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        Tahun Ajaran & Semester
                    </h6>
                </div>

                <!-- Tahun Ajaran -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i> Tahun Ajaran
                    </label>
                    <div class="select-wrapper">
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
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Pilih tahun ajaran
                    </small>
                    @error('tahun_ajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Semester -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-week me-1"></i> Semester
                    </label>
                    <div class="select-wrapper">
                        <select name="semester" class="form-control @error('semester') is-invalid @enderror">
                            <option value="">-- Pilih Semester --</option>
                            <option value="ganjil" {{ old('semester', $jadwal->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester', $jadwal->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Pilih semester
                    </small>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ========== NOTIFIKASI ========== -->
                <div class="col-12 mt-3">
                    <div class="alert alert-warning d-flex align-items-center" style="border-radius: 10px;">
                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 18px;"></i>
                        <div>
                            <strong>Perhatikan!</strong>
                            <small class="d-block">Pastikan tidak ada jadwal yang bentrok untuk kelas, guru, dan ruangan yang sama.</small>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-secondary" id="resetBtn">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-1"></i> Update Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // ========== AUTO-FILL RUANGAN DARI KELAS ==========
    $('#kelas_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kodeKelas = selectedOption.data('kode');
        var namaKelas = selectedOption.data('nama');
        var tingkatKelas = selectedOption.data('tingkat');
        var jurusanKelas = selectedOption.data('jurusan');
        
        var ruangan = kodeKelas || (namaKelas ? namaKelas.substring(0, 5).toUpperCase().replace(/\s/g, '') : '');
        
        if ($(this).val() !== '') {
            $('#kelasPreview').fadeIn(300);
            $('#previewKode').text(kodeKelas || '-');
            $('#previewTingkat').text(tingkatKelas || '-');
            $('#previewJurusan').text(jurusanKelas || '-');
            $('#ruangHint').html('<i class="fas fa-check-circle text-success"></i> Ruangan terisi otomatis: <strong>' + ruangan + '</strong>');
            $('#ruang').css('background-color', '#e8f5e9');
            $(this).css('border-color', '#28a745');
        } else {
            $('#kelasPreview').fadeOut(300);
            $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
            $('#ruang').css('background-color', '#f8f9fa');
            $(this).css('border-color', '#ddd');
        }
        $('#ruang').val(ruangan);
        $('#ruang').removeClass('is-invalid');
    });
    
    if ($('#kelas_id').val()) {
        $('#kelas_id').trigger('change');
    }
    
    // ========== VALIDASI JAM ==========
    function validateTime() {
        var jamMulai = $('#jam_mulai').val();
        var jamSelesai = $('#jam_selesai').val();
        if (jamMulai && jamSelesai && jamMulai >= jamSelesai) {
            $('#jam_selesai').addClass('is-invalid');
            if ($('#jam_selesai').next('.invalid-feedback').length === 0) {
                $('#jam_selesai').after('<div class="invalid-feedback">Jam selesai harus lebih besar dari jam mulai</div>');
            }
            return false;
        } else {
            $('#jam_selesai').removeClass('is-invalid');
            $('#jam_selesai').next('.invalid-feedback').remove();
            return true;
        }
        return true;
    }
    
    $('#jam_mulai, #jam_selesai').on('change', validateTime);
    
    // ========== RESET FORM ==========
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        $('#jadwalForm')[0].reset();
        $('#kelasPreview').hide();
        $('#ruang').val('');
        $('#ruang').css('background-color', '#f8f9fa');
        $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
        $('#kelas_id').css('border-color', '#ddd');
        
        @if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
        $('#tahun_ajaran').val('{{ $tahunAjaranAktif->nama_tahun }}');
        @else
        $('#tahun_ajaran').val('');
        @endif
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $('#submitBtn').html('<i class="fas fa-save me-1"></i> Update Jadwal');
        $('#submitBtn').prop('disabled', false);
    });
    
    // ========== VALIDASI FORM ==========
    $('#jadwalForm').on('submit', function(e) {
        var isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
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
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
        $('#submitBtn').prop('disabled', true);
        
        return true;
    });
});
</script>
@endpush
@endsection