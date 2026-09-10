@extends('administrasi.layouts.header')

@section('title', 'Edit Jadwal')

@section('content')
<style>
    .page-header-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-edit::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-edit .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-edit h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-edit p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-card-header i { color: #d97706; font-size: 1.05rem; }
    .form-card-header h5 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .form-card-body { padding: 24px; }

    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #92400e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #fef3c7;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title i { color: #f59e0b; }

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
    .form-label-modern i { color: #f59e0b; }
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
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }

    .info-card {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 12px;
        padding: 15px 18px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        margin-top: 6px;
    }
    .info-card .row > div { padding: 4px 8px; }
    .info-card small { opacity: 0.9; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; }
    .info-card strong { font-size: 0.88rem; display: block; margin-top: 2px; }

    .input-group-text-style {
        background: #fffbeb;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #d97706;
        font-weight: 600;
    }
    .input-with-icon { border-radius: 0 10px 10px 0 !important; }

    .btn-submit-modern {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        color: #fff;
    }
    .btn-submit-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
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
<div class="page-header-edit">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-edit me-2"></i>
                Edit Jadwal Pelajaran
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Perbarui data jadwal pelajaran
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ FORM CARD ============ --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-edit"></i>
        <h5>Form Edit Jadwal Pelajaran</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.jadwal.update', $jadwal->id) }}" method="POST" id="jadwalForm">
            @csrf
            @method('PUT')

            {{-- Bagian 1: Kelas & Pengajar --}}
            <div class="form-section-title">
                <i class="fas fa-school"></i> Informasi Kelas & Pengajar
            </div>

            <div class="row">
                {{-- Pilih Kelas --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-school"></i> Kelas <span class="required">*</span>
                    </label>
                    <select name="kelas_id" id="kelas_id"
                            class="form-select-modern @error('kelas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @forelse($kelas ?? [] as $k)
                            @php
                                $kodeKelas = $k->kode_kelas ?? $k->kode ?? '';
                                $namaKelas = $k->nama ?? $k->nama_kelas ?? $k->kelas ?? '-';
                                $tingkat   = $k->tingkat ?? '-';
                                $jurusan   = $k->jurusan->nama ?? '-';
                            @endphp
                            <option value="{{ $k->id }}"
                                    data-kode="{{ $kodeKelas }}"
                                    data-nama="{{ $namaKelas }}"
                                    data-tingkat="{{ $tingkat }}"
                                    data-jurusan="{{ $jurusan }}"
                                    {{ old('kelas_id', $jadwal->kelas_id) == $k->id ? 'selected' : '' }}>
                                {{ $namaKelas }} ({{ $kodeKelas ?: 'Kode tidak tersedia' }})
                            </option>
                        @empty
                            <option value="" disabled>⚠️ Belum ada data kelas</option>
                        @endforelse
                    </select>
                    @error('kelas_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Preview Kelas --}}
                <div class="col-md-6 mb-3" id="kelasPreview"
                     style="display: {{ old('kelas_id', $jadwal->kelas_id) ? 'block' : 'none' }};">
                    <label class="form-label-modern">
                        <i class="fas fa-info-circle"></i> Informasi Kelas
                    </label>
                    <div class="info-card">
                        <div class="row">
                            <div class="col-4">
                                <small><i class="fas fa-tag"></i> Kode</small>
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

                {{-- Mata Pelajaran --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-book"></i> Mata Pelajaran <span class="required">*</span>
                    </label>
                    <select name="mapel_id" id="mapel_id"
                            class="form-select-modern @error('mapel_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @if(!empty($mapel) && ((is_object($mapel) && $mapel->count() > 0) || (is_array($mapel) && count($mapel) > 0)))
                            @foreach($mapel as $m)
                                @php
                                    $id   = is_object($m) ? $m->id : $m['id'];
                                    $nama = is_object($m) ? ($m->nama ?? '-') : ($m['nama'] ?? '-');
                                @endphp
                                <option value="{{ $id }}" {{ old('mapel_id', $jadwal->mapel_id) == $id ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>⚠️ Belum ada data mata pelajaran</option>
                        @endif
                    </select>
                    @error('mapel_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Guru Pengajar --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-chalkboard-user"></i> Guru Pengajar <span class="required">*</span>
                    </label>
                    <select name="guru_id" id="guru_id"
                            class="form-select-modern @error('guru_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Guru --</option>
                        @if(isset($guru) && ((is_object($guru) && $guru->count() > 0) || (is_array($guru) && count($guru) > 0)))
                            @foreach($guru as $g)
                                @php
                                    $id   = is_object($g) ? $g->id : $g['id'];
                                    $nama = is_object($g) ? ($g->user->name ?? $g->nama_lengkap ?? 'Guru') : ($g['nama'] ?? 'Guru');
                                @endphp
                                <option value="{{ $id }}" {{ old('guru_id', $jadwal->guru_id) == $id ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>⚠️ Belum ada data guru</option>
                        @endif
                    </select>
                    @error('guru_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bagian 2: Waktu & Ruangan --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-clock"></i> Waktu & Ruangan
            </div>

            <div class="row">
                {{-- Hari --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-day"></i> Hari <span class="required">*</span>
                    </label>
                    <select name="hari" id="hari"
                            class="form-select-modern @error('hari') is-invalid @enderror" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>
                                {{ ucfirst($h) }}
                            </option>
                        @endforeach
                    </select>
                    @error('hari')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jam Mulai --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-play-circle"></i> Jam Mulai <span class="required">*</span>
                    </label>
                    <input type="time" name="jam_mulai" id="jam_mulai"
                           class="form-control-modern @error('jam_mulai') is-invalid @enderror"
                           value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                           required>
                    @error('jam_mulai')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jam Selesai --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-stop-circle"></i> Jam Selesai <span class="required">*</span>
                    </label>
                    <input type="time" name="jam_selesai" id="jam_selesai"
                           class="form-control-modern @error('jam_selesai') is-invalid @enderror"
                           value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                           required>
                    @error('jam_selesai')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ruangan --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-door-open"></i> Ruangan <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-style"><i class="fas fa-door-open"></i></span>
                        <input type="text" name="ruangan" id="ruangan"
                               class="form-control form-control-modern input-with-icon @error('ruangan') is-invalid @enderror"
                               value="{{ old('ruangan', $jadwal->ruangan ?? $jadwal->ruang ?? '') }}"
                               placeholder="Akan terisi otomatis" readonly
                               style="background:#fffbeb;font-weight:700;color:#92400e;">
                    </div>
                    <small class="text-muted" style="font-size:0.72rem;">
                        <i class="fas fa-info-circle"></i> Terisi otomatis dari kode kelas
                    </small>
                    @error('ruangan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bagian 3: Tahun Ajaran & Semester --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-calendar-alt"></i> Tahun Ajaran & Semester
            </div>

            <div class="row">
                {{-- Tahun Ajaran --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="tahun_ajaran" id="tahun_ajaran"
                            class="form-select-modern @error('tahun_ajaran') is-invalid @enderror">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @if(isset($tahunAjaranList) && $tahunAjaranList->count() > 0)
                            @foreach($tahunAjaranList as $ta)
                                @php
                                    $namaTa  = is_object($ta) ? $ta->nama_tahun : $ta['nama_tahun'];
                                    $isAktif = is_object($ta) ? $ta->is_aktif : $ta['is_aktif'];
                                @endphp
                                <option value="{{ $namaTa }}"
                                    {{ old('tahun_ajaran', $jadwal->tahun_ajaran) == $namaTa ? 'selected' : '' }}>
                                    {{ $namaTa }} @if($isAktif) (Aktif) @endif
                                </option>
                            @endforeach
                        @else
                            <option value="{{ date('Y') . '/' . (date('Y') + 1) }}">
                                {{ date('Y') . '/' . (date('Y') + 1) }}
                            </option>
                        @endif
                    </select>
                    @error('tahun_ajaran')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Semester --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-week"></i> Semester
                    </label>
                    <select name="semester" id="semester"
                            class="form-select-modern @error('semester') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>
                        <option value="ganjil" {{ old('semester', $jadwal->semester) == 'ganjil' ? 'selected' : '' }}>
                            Ganjil
                        </option>
                        <option value="genap" {{ old('semester', $jadwal->semester) == 'genap' ? 'selected' : '' }}>
                            Genap
                        </option>
                    </select>
                    @error('semester')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
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
                <button type="button" class="btn-reset-modern" id="resetBtn">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit-modern" id="submitBtn">
                    <i class="fas fa-save"></i> Update Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    // ========== AUTO-FILL RUANGAN & PREVIEW KELAS ==========
    function updateKelasInfo() {
        var opt = $('#kelas_id').find('option:selected');
        var kodeKelas = opt.data('kode') || '';
        var namaKelas = opt.data('nama') || '';
        var tingkatKelas = opt.data('tingkat') || '';
        var jurusanKelas = opt.data('jurusan') || '';
        var val = $('#kelas_id').val();

        var ruangan = '';
        if (kodeKelas) {
            ruangan = kodeKelas;
        } else if (namaKelas) {
            ruangan = namaKelas.substring(0, 5).toUpperCase().replace(/\s/g, '');
        } else if (val) {
            ruangan = 'KLS-' + val;
        }

        if (val) {
            $('#kelasPreview').fadeIn(300);
            $('#previewKode').text(kodeKelas || '-');
            $('#previewTingkat').text(tingkatKelas || '-');
            $('#previewJurusan').text(jurusanKelas || '-');
        } else {
            $('#kelasPreview').fadeOut(300);
        }
        $('#ruangan').val(ruangan);
    }

    // Hanya trigger ruangan jika kelas diganti oleh user (bukan saat load)
    $('#kelas_id').on('change', function() {
        // Set flag bahwa user baru ganti kelas
        $(this).data('changed', true);
        updateKelasInfo();
    });

    // Saat halaman load: JANGAN override ruangan yang sudah ada dari database
    // Cukup tampilkan preview kelas saja
    if ($('#kelas_id').val()) {
        var opt = $('#kelas_id').find('option:selected');
        $('#kelasPreview').fadeIn(300);
        $('#previewKode').text(opt.data('kode') || '-');
        $('#previewTingkat').text(opt.data('tingkat') || '-');
        $('#previewJurusan').text(opt.data('jurusan') || '-');
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

    // ========== VALIDASI FORM SEBELUM SUBMIT ==========
    $('#jadwalForm').on('submit', function(e) {
        // Cek jam
        if (!validateTime()) {
            e.preventDefault();
            Swal.fire('Periksa Jam!', 'Jam selesai harus lebih besar dari jam mulai.', 'warning');
            return false;
        }

        // Cek field wajib
        var requiredFields = [
            { id: '#kelas_id', label: 'Kelas' },
            { id: '#mapel_id', label: 'Mata Pelajaran' },
            { id: '#guru_id', label: 'Guru Pengajar' },
            { id: '#hari', label: 'Hari' },
            { id: '#jam_mulai', label: 'Jam Mulai' },
            { id: '#jam_selesai', label: 'Jam Selesai' },
            { id: '#ruangan', label: 'Ruangan' }
        ];

        for (var i = 0; i < requiredFields.length; i++) {
            var field = requiredFields[i];
            if (!$(field.id).val()) {
                e.preventDefault();
                $(field.id).addClass('is-invalid').focus();
                Swal.fire('Lengkapi Form!', 'Field "' + field.label + '" wajib diisi.', 'warning');
                return false;
            }
        }

        // Loading state
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...').prop('disabled', true);
        return true;
    });

    // ========== RESET FORM ==========
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();

        // Reset semua field ke nilai awal dari database
        $('#kelas_id').val('{{ $jadwal->kelas_id }}');
        $('#mapel_id').val('{{ $jadwal->mapel_id }}');
        $('#guru_id').val('{{ $jadwal->guru_id }}');
        $('#hari').val('{{ $jadwal->hari }}');
        $('#jam_mulai').val('{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format("H:i") }}');
        $('#jam_selesai').val('{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format("H:i") }}');
        $('#ruangan').val('{{ $jadwal->ruangan ?? $jadwal->ruang ?? "" }}');
        $('#tahun_ajaran').val('{{ $jadwal->tahun_ajaran }}');
        $('#semester').val('{{ $jadwal->semester }}');

        // Update preview kelas
        updateKelasInfo();

        // Reset validation state
        $('.is-invalid').removeClass('is-invalid');

        // Reset tombol submit
        $('#submitBtn').html('<i class="fas fa-save"></i> Update Jadwal').prop('disabled', false);

        // Notifikasi
        Swal.fire({
            icon: 'info',
            title: 'Form Direset',
            text: 'Form dikembalikan ke data awal.',
            timer: 1200,
            showConfirmButton: false
        });
    });
});
</script>
@endpush
@endsection