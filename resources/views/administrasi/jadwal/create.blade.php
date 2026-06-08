@extends('administrasi.layouts.header')

@section('title', 'Tambah Jadwal')

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
        <i class="fas fa-plus-circle me-2"></i>
        Tambah Jadwal Pelajaran
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
        <i class="fas fa-calendar-plus me-2 text-primary"></i>
        <strong>Form Tambah Jadwal Pelajaran</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('administrasi.jadwal.store') }}" method="POST" id="jadwalForm">
            @csrf
            
            <div class="row">
                <!-- Pilih Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school"></i> Kelas 
                        <span class="text-danger">*</span>
                        <span class="auto-fill-badge">
                            <i class="fas fa-magic"></i> Pilih dulu untuk isi ruangan
                        </span>
                    </label>
                    <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" 
                                data-kode="{{ $k->kode_kelas ?? $k->kode ?? '' }}"
                                data-nama="{{ $k->nama ?? $k->nama_kelas ?? '' }}"
                                data-tingkat="{{ $k->tingkat ?? '' }}"
                                data-jurusan="{{ $k->jurusan->nama ?? '' }}"
                                {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama ?? $k->nama_kelas ?? $k->kelas }} 
                            ({{ $k->kode_kelas ?? $k->kode ?? 'Kode tidak tersedia' }})
                            @if($k->jurusan)
                                - {{ $k->jurusan->nama ?? '' }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Preview Informasi Kelas -->
                <div class="col-md-6 mb-3" id="kelasPreview" style="display: {{ old('kelas_id') ? 'block' : 'none' }};">
                    <label class="form-label">
                        <i class="fas fa-info-circle"></i> Informasi Kelas
                    </label>
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <small><i class="fas fa-tag"></i> Kode Kelas:</small><br>
                                <strong id="previewKode">-</strong>
                            </div>
                            <div class="col-6">
                                <small><i class="fas fa-layer-group"></i> Tingkat:</small><br>
                                <strong id="previewTingkat">-</strong>
                            </div>
                            <div class="col-12 mt-2">
                                <small><i class="fas fa-graduation-cap"></i> Jurusan:</small><br>
                                <strong id="previewJurusan">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mata Pelajaran -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-book"></i> Mata Pelajaran 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="mapel_id" id="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @if(!empty($mapel) && ((is_object($mapel) && $mapel->count() > 0) || (is_array($mapel) && count($mapel) > 0)))
                            @foreach($mapel as $m)
                                @php
                                    // Handle jika object atau array
                                    $id = is_object($m) ? $m->id : $m['id'];
                                    $nama = is_object($m) ? ($m->nama ?? '-') : ($m['nama'] ?? '-');
                                    $kode = is_object($m) ? ($m->kode ?? $m->kk ?? '') : ($m['kode'] ?? $m['kk'] ?? '');
                                @endphp
                            <option value="{{ $id }}" {{ old('mapel_id') == $id ? 'selected' : '' }}>
                                {{ $nama }} 
                                @if($kode)
                                    ({{ $kode }})
                                @endif
                            </option>
                            @endforeach
                        @else
                            <option value="" disabled class="text-danger">⚠️ Belum ada data mata pelajaran</option>
                        @endif
                    </select>
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
                        <i class="fas fa-chalkboard-user"></i> Guru Pengajar 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Guru --</option>
                        @if(isset($guru) && ((is_object($guru) && $guru->count() > 0) || (is_array($guru) && count($guru) > 0)))
                            @foreach($guru as $g)
                                @php
                                    $id = is_object($g) ? $g->id : $g['id'];
                                    $nama = is_object($g) ? ($g->user->name ?? $g->nama_lengkap ?? $g->nama ?? 'Guru') : ($g['nama'] ?? 'Guru');
                                    $nip = is_object($g) ? ($g->nip ?? '') : ($g['nip'] ?? '');
                                @endphp
                            <option value="{{ $id }}" {{ old('guru_id') == $id ? 'selected' : '' }}>
                                {{ $nama }}
                                @if($nip)
                                    ({{ $nip }})
                                @endif
                            </option>
                            @endforeach
                        @else
                            <option value="" disabled class="text-danger">⚠️ Belum ada data guru</option>
                        @endif
                    </select>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Pilih guru yang mengajar mata pelajaran ini
                    </small>
                    @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Hari -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-day"></i> Hari 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
                        <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Jam Mulai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Jam Mulai 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="time" name="jam_mulai" id="jam_mulai" 
                           class="form-control @error('jam_mulai') is-invalid @enderror" 
                           value="{{ old('jam_mulai') }}"
                           required>
                    @error('jam_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Jam Selesai -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Jam Selesai 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="time" name="jam_selesai" id="jam_selesai" 
                           class="form-control @error('jam_selesai') is-invalid @enderror" 
                           value="{{ old('jam_selesai') }}"
                           required>
                    @error('jam_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Ruangan (Otomatis dari kelas yang dipilih) -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Ruangan 
                        <span class="text-danger">*</span>
                        <span class="auto-fill-badge" id="autoFillBadge" style="display: {{ old('kelas_id') ? 'inline-block' : 'none' }};">
                            <i class="fas fa-sync-alt"></i> Auto-fill
                        </span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-door-open"></i>
                        </span>
                        <input type="text" name="ruang" id="ruang" 
                               class="form-control @error('ruang') is-invalid @enderror" 
                               value="{{ old('ruang') }}"
                               placeholder="Akan terisi otomatis setelah pilih kelas" 
                               readonly style="background-color: {{ old('ruang') ? '#e8f5e9' : '#f5f5f5' }};">
                    </div>
                    <small class="text-muted" id="ruangHint">
                        <i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis dari kode kelas yang dipilih
                    </small>
                    @error('ruang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Tahun Ajaran -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror">
                        <option value="">-- Pilih Tahun Ajaran (Opsional) --</option>
                        @if(isset($tahunAjaranList) && ((is_object($tahunAjaranList) && $tahunAjaranList->count() > 0) || (is_array($tahunAjaranList) && count($tahunAjaranList) > 0)))
                            @foreach($tahunAjaranList as $ta)
                                @php
                                    $namaTa = is_object($ta) ? $ta->nama : $ta['nama'];
                                @endphp
                            <option value="{{ $namaTa }}" {{ (old('tahun_ajaran') == $namaTa) || (isset($tahunAjaranAktif) && $tahunAjaranAktif->nama == $namaTa && !old('tahun_ajaran')) ? 'selected' : '' }}>
                                {{ $namaTa }}
                            </option>
                            @endforeach
                        @else
                            <option value="{{ date('Y') . '/' . (date('Y') + 1) }}" selected>
                                {{ date('Y') . '/' . (date('Y') + 1) }}
                            </option>
                        @endif
                    </select>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Kosongkan akan menggunakan tahun ajaran aktif
                    </small>
                    @error('tahun_ajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Semester -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-week"></i> Semester
                    </label>
                    <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror">
                        <option value="">-- Pilih Semester (Opsional) --</option>
                        <option value="ganjil" {{ (old('semester') == 'ganjil') || (isset($semesterAktif) && $semesterAktif == 'ganjil' && !old('semester')) ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ (old('semester') == 'genap') || (isset($semesterAktif) && $semesterAktif == 'genap' && !old('semester')) ? 'selected' : '' }}>Genap</option>
                    </select>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Kosongkan akan otomatis berdasarkan bulan saat ini
                    </small>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                <button type="reset" class="btn btn-secondary" id="resetBtn">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Jadwal
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
        
        // Tentukan ruangan berdasarkan kode kelas
        var ruangan = '';
        
        if (kodeKelas && kodeKelas !== '') {
            ruangan = kodeKelas;
        } else if (namaKelas && namaKelas !== '') {
            ruangan = namaKelas.substring(0, 5).toUpperCase().replace(/\s/g, '');
        } else if ($(this).val()) {
            ruangan = 'KLS-' + $(this).val();
        }
        
        // Tampilkan preview dan set ruangan
        if ($(this).val() !== '') {
            $('#kelasPreview').fadeIn();
            $('#previewKode').text(kodeKelas || '-');
            $('#previewTingkat').text(tingkatKelas || '-');
            $('#previewJurusan').text(jurusanKelas || '-');
            $('#autoFillBadge').fadeIn();
            $('#ruangHint').html('<i class="fas fa-check-circle text-success"></i> Ruangan terisi otomatis: <strong>' + ruangan + '</strong>');
            $('#ruang').css('background-color', '#e8f5e9');
            $(this).css('border-color', '#28a745');
        } else {
            $('#kelasPreview').fadeOut();
            $('#autoFillBadge').fadeOut();
            $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
            $('#ruang').css('background-color', '#f5f5f5');
            $(this).css('border-color', '#ddd');
        }
        
        // Set value ruangan
        $('#ruang').val(ruangan);
        
        // Hapus error jika ada
        $('#ruang').removeClass('is-invalid');
    });
    
    // Trigger change jika sudah ada old value
    if ($('#kelas_id').val()) {
        $('#kelas_id').trigger('change');
    }
    
    // ========== VALIDASI JAM ==========
    function validateTime() {
        var jamMulai = $('#jam_mulai').val();
        var jamSelesai = $('#jam_selesai').val();
        
        if (jamMulai && jamSelesai) {
            if (jamMulai >= jamSelesai) {
                $('#jam_selesai').addClass('is-invalid');
                $('#jam_selesai').next('.invalid-feedback').remove();
                $('#jam_selesai').after('<div class="invalid-feedback">Jam selesai harus lebih besar dari jam mulai</div>');
                return false;
            } else {
                $('#jam_selesai').removeClass('is-invalid');
                $('#jam_selesai').next('.invalid-feedback').remove();
                return true;
            }
        }
        return true;
    }
    
    $('#jam_mulai, #jam_selesai').on('change', function() {
        validateTime();
    });
    
    // ========== VALIDASI FORM SEBELUM SUBMIT ==========
    $('#jadwalForm').on('submit', function(e) {
        var isValid = true;
        
        // Reset semua error
        $('.is-invalid').removeClass('is-invalid');
        
        // Validasi kelas
        if (!$('#kelas_id').val()) {
            $('#kelas_id').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi mapel
        if (!$('#mapel_id').val()) {
            $('#mapel_id').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi guru
        if (!$('#guru_id').val()) {
            $('#guru_id').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi hari
        if (!$('#hari').val()) {
            $('#hari').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi jam mulai
        if (!$('#jam_mulai').val()) {
            $('#jam_mulai').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi jam selesai
        if (!$('#jam_selesai').val()) {
            $('#jam_selesai').addClass('is-invalid');
            isValid = false;
        }
        
        // Validasi ruangan
        if (!$('#ruang').val()) {
            $('#ruang').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        if (!validateTime()) {
            e.preventDefault();
            alert('Periksa kembali jam mulai dan jam selesai');
            return false;
        }
        
        // Tampilkan loading state
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $('#submitBtn').prop('disabled', true);
        
        return true;
    });
    
    // ========== RESET FORM ==========
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        
        // Reset form
        $('#jadwalForm')[0].reset();
        
        // Reset tampilan
        $('#kelasPreview').hide();
        $('#ruang').val('');
        $('#ruang').css('background-color', '#f5f5f5');
        $('#autoFillBadge').hide();
        $('#ruangHint').html('<i class="fas fa-info-circle"></i> Ruangan akan terisi otomatis setelah memilih kelas');
        $('#kelas_id').css('border-color', '#ddd');
        
        // Reset tahun ajaran ke default (tahun ajaran aktif)
        @if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
        $('#tahun_ajaran').val('{{ $tahunAjaranAktif->nama }}');
        @else
        $('#tahun_ajaran').val('');
        @endif
        
        // Reset semester ke default (semester aktif berdasarkan bulan)
        @if(isset($semesterAktif) && $semesterAktif)
        $('#semester').val('{{ $semesterAktif }}');
        @else
        $('#semester').val('');
        @endif
        
        // Hapus semua error
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Reset tombol submit
        $('#submitBtn').html('<i class="fas fa-save"></i> Simpan Jadwal');
        $('#submitBtn').prop('disabled', false);
    });
});
</script>
@endpush
@endsection