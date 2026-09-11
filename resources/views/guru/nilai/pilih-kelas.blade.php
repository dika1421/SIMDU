@extends('guru.layouts.header')

@section('title', 'Pilih Siswa - Cetak Raport')

@section('content')
<style>
    /* ===== Card Modern ===== */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        transition: all 0.3s ease;
    }
    .card-modern:hover {
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.08);
    }
    .card-modern .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .card-modern .card-body {
        padding: 1.75rem;
    }

    /* ===== Form Select Modern ===== */
    .form-select-modern {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 16px;
        transition: all 0.2s ease;
        background-color: white;
        font-size: .9rem;
        color: #1e293b;
    }
    .form-select-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }
    .form-label-modern {
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-modern i {
        color: #667eea;
    }

    /* ===== Buttons ===== */
    .btn-modern {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.25s ease;
        border: none;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* ===== Info Box ===== */
    .info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e40af;
        font-size: .875rem;
    }
    .info-box .icon {
        font-size: 1.25rem;
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* ===== Stat Badge ===== */
    .stat-badge {
        padding: 12px 18px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.03);
        transition: all .25s;
    }
    .stat-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, .1);
        border-color: #c7d2fe;
    }
    .stat-badge i {
        color: #667eea;
        font-size: 1.1rem;
    }
    .stat-badge strong {
        color: #1e293b;
        margin-left: auto;
    }

    /* ===== Siswa Info (Live Preview) ===== */
    .siswa-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 1rem;
        font-size: 0.9rem;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .siswa-info .label {
        color: #065f46;
        font-weight: 600;
        margin-right: 6px;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .siswa-info strong {
        color: #064e3b;
    }

    /* ===== Page Header ===== */
    .page-header-cetak {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 1rem 0 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    .page-title-cetak {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
        letter-spacing: -0.5px;
    }
    .page-subtitle-cetak {
        color: #94a3b8;
        font-size: .85rem;
        margin: 4px 0 0;
    }

    /* ===== Section Title ===== */
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 1.25rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i {
        color: #667eea;
    }

    @media (max-width: 768px) {
        .page-title-cetak { font-size: 1.25rem; }
        .stat-badge { padding: 10px 14px; font-size: .8rem; }
    }
</style>

<div class="container-fluid">

    <!-- ============================================================
         PAGE HEADER
         ============================================================ -->
    <div class="page-header-cetak">
        <div>
            <h1 class="page-title-cetak">
                <i class="fas fa-print me-2 text-primary"></i>
                Cetak Raport Siswa
            </h1>
            <p class="page-subtitle-cetak">
                Pilih siswa dan periode untuk mencetak raport
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('guru.nilai.raport') }}" class="btn btn-outline-secondary btn-modern">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Raport
            </a>
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-secondary btn-modern">
                <i class="fas fa-th-list me-1"></i> Dashboard Nilai
            </a>
        </div>
    </div>

    <!-- ============================================================
         ALERTS
         ============================================================ -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px; border:none; border-left:4px solid #ef4444;">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
             style="border-radius:12px; border:none; border-left:4px solid #10b981;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ============================================================
         INFO BOX
         ============================================================ -->
    <div class="info-box">
        <div class="icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <strong>Petunjuk:</strong>
            Pilih <strong>kelas</strong>, <strong>siswa</strong>, <strong>tahun ajaran</strong>, dan <strong>semester</strong>.
            Raport akan otomatis terbuka di tab baru dalam format siap cetak.
        </div>
    </div>

    <!-- ============================================================
         STATISTIK
         ============================================================ -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-badge">
                <i class="fas fa-user-graduate"></i>
                <span>Total Siswa</span>
                <strong>{{ $siswaList->count() ?? 0 }}</strong>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-badge">
                <i class="fas fa-calendar-alt"></i>
                <span>Tahun Ajaran</span>
                <strong>{{ count($tahunAjaranList ?? []) }}</strong>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-badge">
                <i class="fas fa-clock"></i>
                <span>Semester</span>
                <strong>{{ count($semesterList ?? []) }}</strong>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-badge">
                <i class="fas fa-school"></i>
                <span>Kelas</span>
                <strong>{{ $kelasDiAjar->count() ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <!-- ============================================================
         FORM PILIH SISWA
         ============================================================ -->
    <div class="card card-modern">
        <div class="card-header">
            <h5 class="section-title mb-0">
                <i class="fas fa-filter"></i>
                Pilih Siswa & Periode
            </h5>
        </div>
        <div class="card-body">
            <form method="GET"
                  action="{{ route('guru.nilai.raport.cetak', ['siswaId' => '__siswa_id__']) }}"
                  id="formCetakRaport"
                  target="_blank">

                <div class="row g-4">
                    <!-- Pilih Kelas -->
                    <div class="col-md-4">
                        <label class="form-label-modern">
                            <i class="fas fa-users"></i>
                            Kelas <span class="text-danger">*</span>
                        </label>
                        <select name="kelas_id" id="kelasSelect" class="form-select form-select-modern" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasDiAjar as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kelas_id', request('kelas_id')) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? $k->nama }}
                                    @if($k->jurusan)
                                        — {{ $k->jurusan->nama }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Kelas yang Anda ajar</small>
                    </div>

                    <!-- Pilih Siswa -->
                    <div class="col-md-4">
                        <label class="form-label-modern">
                            <i class="fas fa-user-graduate"></i>
                            Siswa <span class="text-danger">*</span>
                        </label>
                        <select name="siswa_id" id="siswaSelect" class="form-select form-select-modern" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}"
                                    data-kelas="{{ $s->kelas_id }}"
                                    data-nama="{{ $s->user->name ?? $s->nama_lengkap ?? '-' }}"
                                    data-nis="{{ $s->nis ?? '-' }}"
                                    {{ old('siswa_id', request('siswa_id')) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Pilih siswa untuk dicetak raportnya</small>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="col-md-2">
                        <label class="form-label-modern">
                            <i class="fas fa-calendar-alt"></i>
                            Tahun Ajaran <span class="text-danger">*</span>
                        </label>
                        <select name="tahun_ajaran" id="tahunAjaran" class="form-select form-select-modern" required>
                            @foreach($tahunAjaranList as $ta)
                                <option value="{{ $ta }}"
                                    {{ old('tahun_ajaran', request('tahun_ajaran', date('Y') . '/' . (date('Y') + 1))) == $ta ? 'selected' : '' }}>
                                    {{ $ta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="col-md-2">
                        <label class="form-label-modern">
                            <i class="fas fa-clock"></i>
                            Semester <span class="text-danger">*</span>
                        </label>
                        <select name="semester" id="semesterSelect" class="form-select form-select-modern" required>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem }}"
                                    {{ old('semester', request('semester', 'ganjil')) == $sem ? 'selected' : '' }}>
                                    {{ ucfirst($sem) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol -->
                    <div class="col-12">
                        <hr>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary btn-modern" id="btnCetak">
                                <i class="fas fa-print me-2"></i> Cetak Raport
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-modern" id="btnReset">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <a href="{{ route('guru.nilai.raport') }}" class="btn btn-outline-info btn-modern">
                                <i class="fas fa-table me-1"></i> Lihat Semua Raport
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Live Info Siswa Terpilih -->
            <div id="siswaInfoContainer" style="display: none;">
                <div class="siswa-info">
                    <div class="row align-items-center g-2">
                        <div class="col-md-6">
                            <span class="label">Siswa Terpilih:</span>
                            <strong id="siswaNama">-</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="label">NIS:</span>
                            <strong id="siswaNis">-</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="label">Kelas:</span>
                            <strong id="siswaKelas">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Data siswa dalam JSON untuk filter
        var siswaData = @json($siswaList);

        // === Filter siswa berdasarkan kelas ===
        $('#kelasSelect').on('change', function() {
            var kelasId = $(this).val();
            var siswaSelect = $('#siswaSelect');

            siswaSelect.html('<option value="">-- Pilih Siswa --</option>');

            if (kelasId) {
                siswaData.forEach(function(s) {
                    if (s.kelas_id == kelasId) {
                        var nama = s.user ? s.user.name : (s.nama_lengkap || '-');
                        var nis = s.nis || '-';
                        siswaSelect.append(
                            $('<option>', {
                                value: s.id,
                                'data-kelas': s.kelas_id,
                                'data-nama': nama,
                                'data-nis': nis,
                                text: nis + ' - ' + nama
                            })
                        );
                    }
                });
            }

            // Reset info siswa
            $('#siswaInfoContainer').hide();
        });

        // === Tampilkan info siswa terpilih ===
        $('#siswaSelect').on('change', function() {
            var opt = $(this).find('option:selected');
            var id = $(this).val();

            if (id) {
                var nama = opt.data('nama') || opt.text().split(' - ')[1] || '-';
                var nis = opt.data('nis') || '-';
                var kelasNama = '-';

                // Cari nama kelas dari data siswa
                siswaData.forEach(function(s) {
                    if (s.id == id && s.kelas) {
                        kelasNama = s.kelas.nama_kelas || s.kelas.nama || '-';
                    }
                });

                $('#siswaNama').text(nama);
                $('#siswaNis').text(nis);
                $('#siswaKelas').text(kelasNama);
                $('#siswaInfoContainer').show();
            } else {
                $('#siswaInfoContainer').hide();
            }
        });

        // Trigger perubahan kalau ada nilai awal
        if ($('#kelasSelect').val()) $('#kelasSelect').trigger('change');
        if ($('#siswaSelect').val()) $('#siswaSelect').trigger('change');

        // === Submit form: build URL dengan siswa_id ===
        $('#formCetakRaport').on('submit', function(e) {
            e.preventDefault();

            var siswaId = $('#siswaSelect').val();
            var kelasId = $('#kelasSelect').val();
            var tahunAjaran = $('#tahunAjaran').val();
            var semester = $('#semesterSelect').val();

            if (!kelasId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih kelas terlebih dahulu!',
                    confirmButtonColor: '#667eea'
                });
                $('#kelasSelect').focus();
                return false;
            }

            if (!siswaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih siswa terlebih dahulu!',
                    confirmButtonColor: '#667eea'
                });
                $('#siswaSelect').focus();
                return false;
            }

            // Build URL — replace placeholder dengan siswa_id
            var url = '{{ route("guru.nilai.raport.cetak", ["siswaId" => "__siswa_id__"]) }}';
            url = url.replace('__siswa_id__', siswaId);
            url += '?tahun_ajaran=' + encodeURIComponent(tahunAjaran);
            url += '&semester=' + encodeURIComponent(semester);

            // Buka di tab baru
            window.open(url, '_blank');
        });

        // === Tombol Reset ===
        $('#btnReset').on('click', function(e) {
            e.preventDefault();
            $('#kelasSelect').val('');
            $('#siswaSelect').html('<option value="">-- Pilih Siswa --</option>');
            $('#tahunAjaran').val('{{ date("Y") . "/" . (date("Y") + 1) }}');
            $('#semesterSelect').val('ganjil');
            $('#siswaInfoContainer').hide();

            // Kembalikan semua siswa ke dropdown
            siswaData.forEach(function(s) {
                var nama = s.user ? s.user.name : (s.nama_lengkap || '-');
                var nis = s.nis || '-';
                $('#siswaSelect').append(
                    $('<option>', {
                        value: s.id,
                        'data-kelas': s.kelas_id,
                        'data-nama': nama,
                        'data-nis': nis,
                        text: nis + ' - ' + nama
                    })
                );
            });
        });
    });
</script>
@endpush
@endsection