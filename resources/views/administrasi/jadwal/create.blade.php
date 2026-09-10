@extends('administrasi.layouts.header')

@section('title', 'Tambah Jadwal')

@section('content')
<style>
    .page-header-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-create::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-create .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-create h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-create p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    /* FORM CARD */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-card-header i { color: #059669; font-size: 1.05rem; }
    .form-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .form-card-body { padding: 24px; }

    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #d1fae5;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title i { color: #10b981; }

    .form-label-modern {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-modern i { color: #10b981; }
    .form-label-modern .required { color: #ef4444; }

    .form-control-modern,
    .form-select-modern {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.88rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    /* INFO CARD */
    .info-card {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 12px;
        padding: 15px 18px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        margin-top: 6px;
    }
    .info-card .row > div {
        padding: 4px 8px;
    }
    .info-card small {
        opacity: 0.9;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .info-card strong {
        font-size: 0.88rem;
        display: block;
        margin-top: 2px;
    }

    .input-group-text-style {
        background: #f0fdf4;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #059669;
        font-weight: 600;
    }
    .input-with-icon {
        border-radius: 0 10px 10px 0 !important;
    }

    .btn-submit-modern {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: #fff;
    }
    .btn-reset-modern {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-reset-modern:hover { background: #e2e8f0; color: #1e293b; }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-create">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-plus-circle me-2"></i>
                Tambah Jadwal Pelajaran
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Buat jadwal pelajaran baru untuk kelas
            </p>
        </div>
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ ALERT ============ --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ FORM CARD ============ --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-calendar-plus"></i>
        <h5>Form Tambah Jadwal Pelajaran</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.jadwal.store') }}" method="POST" id="jadwalForm">
            @csrf

            {{-- Bagian 1: Kelas & Pengajar --}}
            <div class="form-section-title">
                <i class="fas fa-school"></i> Informasi Kelas & Pengajar
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-school"></i> Kelas <span class="required">*</span>
                    </label>
                    <select name="kelas_id" id="kelas_id" class="form-select-modern" required>
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
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3" id="kelasPreview" style="display: {{ old('kelas_id') ? 'block' : 'none' }};">
                    <label class="form-label-modern">
                        <i class="fas fa-info-circle"></i> Informasi Kelas
                    </label>
                    <div class="info-card">
                        <div class="row">
                            <div class="col-4">
                                <small><i class="fas fa-tag"></i> Kode Kelas</small>
                                <strong id="previewKode">-</strong>
                            </div>
                            <div class="col-4">
                                <small><i class="fas fa-layer-group"></i> Tingkat</small>
                                <strong id="previewTingkat">-</strong>
                            </div>
                            <div class="col-4">
                                <small><i class="fas fa-graduation-cap"></i> Jurusan</small>
                                <strong id="previewJurusan">-</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-book"></i> Mata Pelajaran <span class="required">*</span>
                    </label>
                    <select name="mapel_id" id="mapel_id" class="form-select-modern" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @if(!empty($mapel) && ((is_object($mapel) && $mapel->count() > 0) || (is_array($mapel) && count($mapel) > 0)))
                            @foreach($mapel as $m)
                                @php
                                    $id = is_object($m) ? $m->id : $m['id'];
                                    $nama = is_object($m) ? ($m->nama ?? '-') : ($m['nama'] ?? '-');
                                @endphp
                                <option value="{{ $id }}" {{ old('mapel_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>⚠️ Belum ada data mata pelajaran</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-chalkboard-user"></i> Guru Pengajar <span class="required">*</span>
                    </label>
                    <select name="guru_id" id="guru_id" class="form-select-modern" required>
                        <option value="">-- Pilih Guru --</option>
                        @if(isset($guru) && ((is_object($guru) && $guru->count() > 0) || (is_array($guru) && count($guru) > 0)))
                            @foreach($guru as $g)
                                @php
                                    $id = is_object($g) ? $g->id : $g['id'];
                                    $nama = is_object($g) ? ($g->user->name ?? $g->nama_lengkap ?? 'Guru') : ($g['nama'] ?? 'Guru');
                                @endphp
                                <option value="{{ $id }}" {{ old('guru_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>⚠️ Belum ada data guru</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- Bagian 2: Waktu & Ruangan --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-clock"></i> Waktu & Ruangan
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-day"></i> Hari <span class="required">*</span>
                    </label>
                    <select name="hari" id="hari" class="form-select-modern" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-play-circle"></i> Jam Mulai <span class="required">*</span>
                    </label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control-modern"
                           value="{{ old('jam_mulai') }}" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-stop-circle"></i> Jam Selesai <span class="required">*</span>
                    </label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control-modern"
                           value="{{ old('jam_selesai') }}" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-door-open"></i> Ruangan <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-style"><i class="fas fa-door-open"></i></span>
                        <input type="text" name="ruang" id="ruang" class="form-control form-control-modern input-with-icon"
                               value="{{ old('ruang') }}" placeholder="Akan terisi otomatis" readonly
                               style="background:#f0fdf4;font-weight:700;color:#065f46;">
                    </div>
                    <small class="text-muted" id="ruangHint" style="font-size:0.72rem;">
                        <i class="fas fa-info-circle"></i> Terisi otomatis dari kode kelas
                    </small>
                </div>
            </div>

            {{-- Bagian 3: Tahun Ajaran & Semester --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-calendar-alt"></i> Tahun Ajaran & Semester
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-select-modern">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @if(isset($tahunAjaranList) && $tahunAjaranList->count() > 0)
                            @foreach($tahunAjaranList as $ta)
                                @php
                                    $namaTa = is_object($ta) ? $ta->nama_tahun : $ta['nama_tahun'];
                                    $isAktif = is_object($ta) ? $ta->is_aktif : $ta['is_aktif'];
                                @endphp
                                <option value="{{ $namaTa }}"
                                    {{ (old('tahun_ajaran') == $namaTa) || (isset($tahunAjaranAktif) && $tahunAjaranAktif->nama_tahun == $namaTa && !old('tahun_ajaran')) ? 'selected' : '' }}>
                                    {{ $namaTa }} @if($isAktif) (Aktif) @endif
                                </option>
                            @endforeach
                        @else
                            <option value="{{ date('Y') . '/' . (date('Y') + 1) }}" selected>
                                {{ date('Y') . '/' . (date('Y') + 1) }}
                            </option>
                        @endif
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-week"></i> Semester
                    </label>
                    <select name="semester" id="semester" class="form-select-modern">
                        <option value="">-- Pilih Semester --</option>
                        <option value="ganjil" {{ (old('semester') == 'ganjil') || (isset($semesterAktif) && $semesterAktif == 'ganjil' && !old('semester')) ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ (old('semester') == 'genap') || (isset($semesterAktif) && $semesterAktif == 'genap' && !old('semester')) ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>

            {{-- Notifikasi --}}
            <div class="alert alert-warning rounded-3 d-flex align-items-center mt-3">
                <i class="fas fa-exclamation-triangle me-3" style="font-size:18px;"></i>
                <div>
                    <strong>Perhatikan!</strong>
                    <small class="d-block">Pastikan tidak ada jadwal yang bentrok untuk kelas, guru, dan ruangan yang sama.</small>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn-reset-modern" id="resetBtn">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit-modern" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // ========== AUTO-FILL RUANGAN ==========
    $('#kelas_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kodeKelas = selectedOption.data('kode');
        var namaKelas = selectedOption.data('nama');
        var tingkatKelas = selectedOption.data('tingkat');
        var jurusanKelas = selectedOption.data('jurusan');

        var ruangan = '';
        if (kodeKelas && kodeKelas !== '') {
            ruangan = kodeKelas;
        } else if (namaKelas && namaKelas !== '') {
            ruangan = namaKelas.substring(0, 5).toUpperCase().replace(/\s/g, '');
        } else if ($(this).val()) {
            ruangan = 'KLS-' + $(this).val();
        }

        if ($(this).val() !== '') {
            $('#kelasPreview').fadeIn(300);
            $('#previewKode').text(kodeKelas || '-');
            $('#previewTingkat').text(tingkatKelas || '-');
            $('#previewJurusan').text(jurusanKelas || '-');
            $('#ruangHint').html('<i class="fas fa-check-circle text-success"></i> Terisi otomatis: <strong>' + ruangan + '</strong>');
        } else {
            $('#kelasPreview').fadeOut(300);
            $('#ruangHint').html('<i class="fas fa-info-circle"></i> Terisi otomatis dari kode kelas');
        }
        $('#ruang').val(ruangan);
    });

    if ($('#kelas_id').val()) {
        $('#kelas_id').trigger('change');
    }

    // ========== VALIDASI JAM ==========
    function validateTime() {
        var m = $('#jam_mulai').val();
        var s = $('#jam_selesai').val();
        if (m && s && m >= s) {
            $('#jam_selesai').addClass('is-invalid');
            return false;
        }
        $('#jam_selesai').removeClass('is-invalid');
        return true;
    }
    $('#jam_mulai, #jam_selesai').on('change', validateTime);

    // ========== SUBMIT ==========
    $('#jadwalForm').on('submit', function(e) {
        if (!validateTime()) {
            e.preventDefault();
            Swal.fire('Periksa Jam!', 'Jam selesai harus lebih besar dari jam mulai.', 'warning');
            return false;
        }
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...').prop('disabled', true);
        return true;
    });

    // ========== RESET ==========
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();
        $('#jadwalForm')[0].reset();
        $('#kelasPreview').hide();
        $('#ruang').val('');
        $('#ruangHint').html('<i class="fas fa-info-circle"></i> Terisi otomatis dari kode kelas');
        @if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
        $('#tahun_ajaran').val('{{ $tahunAjaranAktif->nama_tahun }}');
        @endif
        @if(isset($semesterAktif) && $semesterAktif)
        $('#semester').val('{{ $semesterAktif }}');
        @endif
    });
});
</script>
@endpush
@endsection