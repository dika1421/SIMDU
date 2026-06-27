@extends('guru.layouts.header')

@section('title', 'Pilih Siswa - Cetak Raport')

@section('content')
<style>
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .card-modern:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .form-select-modern {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 16px;
        transition: all 0.3s ease;
        background-color: white;
    }
    .form-select-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .form-label-modern {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 0.9rem;
    }
    .btn-modern {
        border-radius: 10px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .info-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }
    .info-box .icon {
        font-size: 1.5rem;
        color: #667eea;
        margin-right: 10px;
    }
    .stat-badge {
        padding: 8px 16px;
        border-radius: 20px;
        background: white;
        border: 1px solid #e9ecef;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .stat-badge i {
        margin-right: 6px;
        color: #667eea;
    }
    .siswa-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        margin-top: 5px;
        font-size: 0.9rem;
    }
    .siswa-info .label {
        color: #6c757d;
        font-weight: 500;
    }
    .alert-info-custom {
        background: #e8f4fd;
        border: 1px solid #b8d4e3;
        border-radius: 12px;
        padding: 15px 20px;
        color: #0c5460;
    }
    .alert-info-custom i {
        font-size: 1.2rem;
        margin-right: 10px;
    }
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-print me-2 text-primary"></i>
                Cetak Raport Siswa
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.nilai.index') }}">Nilai</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.nilai.raport') }}">Raport</a></li>
                    <li class="breadcrumb-item active">Pilih Siswa</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{ route('guru.nilai.raport') }}" class="btn btn-outline-secondary btn-modern me-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Raport
            </a>
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-secondary btn-modern">
                <i class="fas fa-th-list me-1"></i> Dashboard Nilai
            </a>
        </div>
    </div>

    <!-- Alert Error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Info Box -->
    <div class="info-box no-print">
        <div class="d-flex align-items-center">
            <div class="icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <strong>Petunjuk:</strong> 
                Pilih siswa yang ingin dicetak raportnya, kemudian pilih tahun ajaran dan semester.
                Raport akan ditampilkan dalam format yang siap dicetak.
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4 no-print">
        <div class="col-md-3">
            <div class="stat-badge">
                <i class="fas fa-user-graduate"></i>
                Total Siswa: <strong>{{ $siswaList->count() ?? 0 }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-badge">
                <i class="fas fa-calendar-alt"></i>
                Tahun Ajaran: <strong>{{ count($tahunAjaranList) }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-badge">
                <i class="fas fa-book"></i>
                Semester: <strong>{{ count($semesterList) }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-badge">
                <i class="fas fa-school"></i>
                Kelas: <strong>{{ $kelasDiAjar->count() ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <!-- Form Pilihan -->
    <div class="card card-modern no-print">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-filter me-2 text-primary"></i>
                Pilih Siswa dan Periode
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.nilai.raport.cetak', ['siswaId' => '__siswa_id__']) }}" id="formCetakRaport">
                @csrf
                <div class="row g-4">
                    <!-- Pilih Kelas -->
                    <div class="col-md-4">
                        <label class="form-label-modern">
                            <i class="fas fa-users me-1 text-primary"></i>
                            Kelas <span class="text-danger">*</span>
                        </label>
                        <select name="kelas_id" id="kelasSelect" class="form-select form-select-modern" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasDiAjar as $k)
                                <option value="{{ $k->id }}" 
                                    {{ old('kelas_id', request('kelas_id')) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? $k->nama }} 
                                    ({{ $k->jurusan->nama ?? 'Tanpa Jurusan' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Kelas yang Anda ajar</small>
                    </div>

                    <!-- Pilih Siswa -->
                    <div class="col-md-4">
                        <label class="form-label-modern">
                            <i class="fas fa-user-graduate me-1 text-primary"></i>
                            Siswa <span class="text-danger">*</span>
                        </label>
                        <select name="siswa_id" id="siswaSelect" class="form-select form-select-modern" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}" 
                                    data-kelas="{{ $s->kelas_id }}"
                                    {{ old('siswa_id', request('siswa_id')) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih siswa yang akan dicetak raportnya</small>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="col-md-2">
                        <label class="form-label-modern">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
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
                            <i class="fas fa-clock me-1 text-primary"></i>
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

                    <!-- Tombol Aksi -->
                    <div class="col-12">
                        <hr>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-modern" id="btnCetak">
                                <i class="fas fa-print me-2"></i> Cetak Raport
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-modern">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <a href="{{ route('guru.nilai.raport') }}" class="btn btn-outline-info btn-modern">
                                <i class="fas fa-table me-1"></i> Lihat Semua Raport
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Informasi Siswa yang Dipilih -->
            <div class="row mt-4" id="siswaInfoContainer" style="display: none;">
                <div class="col-12">
                    <div class="siswa-info">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <span class="label">Siswa Terpilih:</span>
                                <strong id="siswaNama">-</strong>
                            </div>
                            <div class="col-md-3">
                                <span class="label">NIS:</span>
                                <span id="siswaNis">-</span>
                            </div>
                            <div class="col-md-3">
                                <span class="label">Kelas:</span>
                                <span id="siswaKelas">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Raport (Jika ada data) -->
    @if(isset($siswa) && isset($nilaiSiswa))
    <div class="card card-modern mt-4" id="previewRaport">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-4">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-file-alt me-2 text-primary"></i>
                Preview Raport
            </h5>
            <div>
                <span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="fas fa-check me-1"></i> Siap Cetak
                </span>
            </div>
        </div>
        <div class="card-body">
            <!-- Informasi Siswa -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="120"><strong>Nama Siswa</strong></td>
                            <td>: {{ $siswa->user->name ?? $siswa->nama_lengkap ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>: {{ $siswa->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: {{ $siswa->kelas->nama_kelas ?? $siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="120"><strong>Tahun Ajaran</strong></td>
                            <td>: {{ $tahunAjaran ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Semester</strong></td>
                            <td>: {{ ucfirst($semester ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Rata-rata</strong></td>
                            <td>: 
                                <span class="badge bg-{{ ($rataRata ?? 0) >= 75 ? 'success' : 'danger' }} rounded-pill px-3">
                                    {{ number_format($rataRata ?? 0, 2) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tabel Nilai -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 35%;">Mata Pelajaran</th>
                            <th style="width: 15%;">Tugas</th>
                            <th style="width: 15%;">UTS</th>
                            <th style="width: 15%;">UAS</th>
                            <th style="width: 15%;">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilaiSiswa as $index => $n)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $n->mapel->nama_mapel ?? $n->mapel->nama ?? '-' }}</td>
                            <td class="text-center">{{ number_format($n->nilai_tugas_1 ?? 0, 1) }}</td>
                            <td class="text-center">{{ number_format($n->nilai_uts ?? 0, 1) }}</td>
                            <td class="text-center">{{ number_format($n->nilai_uas ?? 0, 1) }}</td>
                            <td class="text-center fw-bold 
                                {{ ($n->nilai_akhir ?? 0) >= 85 ? 'text-success' : 
                                   (($n->nilai_akhir ?? 0) >= 70 ? 'text-warning' : 'text-danger') }}">
                                {{ number_format($n->nilai_akhir ?? 0, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Belum ada data nilai untuk siswa ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-info fw-bold">
                            <td colspan="5" class="text-end">Rata-rata</td>
                            <td class="text-center">
                                {{ number_format($rataRata ?? 0, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Cetak -->
            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-success btn-lg btn-modern">
                    <i class="fas fa-print me-2"></i> Cetak / Download PDF
                </button>
                <a href="{{ route('guru.nilai.raport') }}" class="btn btn-outline-secondary btn-lg btn-modern">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter siswa berdasarkan kelas
        $('#kelasSelect').on('change', function() {
            var kelasId = $(this).val();
            var siswaSelect = $('#siswaSelect');
            
            // Reset siswa select
            siswaSelect.html('<option value="">-- Pilih Siswa --</option>');
            
            if (kelasId) {
                // Tampilkan siswa berdasarkan kelas
                @foreach($siswaList as $s)
                    if ({{ $s->kelas_id }} == kelasId) {
                        siswaSelect.append(
                            '<option value="{{ $s->id }}" data-kelas="{{ $s->kelas_id }}">' +
                            '{{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }}' +
                            '</option>'
                        );
                    }
                @endforeach
                
                // Jika hanya ada 1 siswa, auto select
                if (siswaSelect.find('option').length === 2) {
                    siswaSelect.val(siswaSelect.find('option:last').val());
                    siswaSelect.trigger('change');
                }
            }
        });

        // Tampilkan info siswa yang dipilih
        $('#siswaSelect').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var siswaId = $(this).val();
            
            if (siswaId) {
                // Ambil data siswa dari option
                var nama = selectedOption.text().split(' - ')[1] || selectedOption.text();
                var nis = selectedOption.text().split(' - ')[0] || '-';
                var kelas = '';
                
                // Cari nama kelas dari data
                @foreach($siswaList as $s)
                    if ({{ $s->id }} == siswaId) {
                        kelas = '{{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? '-' }}';
                    }
                @endforeach
                
                $('#siswaNama').text(nama);
                $('#siswaNis').text(nis);
                $('#siswaKelas').text(kelas);
                $('#siswaInfoContainer').show();
            } else {
                $('#siswaInfoContainer').hide();
            }
        });

        // Trigger change untuk menampilkan info siswa jika sudah ada pilihan
        if ($('#siswaSelect').val()) {
            $('#siswaSelect').trigger('change');
        }

        // Trigger change untuk filter kelas jika sudah ada pilihan
        if ($('#kelasSelect').val()) {
            $('#kelasSelect').trigger('change');
        }

        // Form submit - update action URL dengan siswa_id
        $('#formCetakRaport').on('submit', function(e) {
            e.preventDefault();
            
            var siswaId = $('#siswaSelect').val();
            var kelasId = $('#kelasSelect').val();
            var tahunAjaran = $('#tahunAjaran').val();
            var semester = $('#semesterSelect').val();
            
            if (!siswaId) {
                alert('Silakan pilih siswa terlebih dahulu!');
                $('#siswaSelect').focus();
                return false;
            }
            
            if (!kelasId) {
                alert('Silakan pilih kelas terlebih dahulu!');
                $('#kelasSelect').focus();
                return false;
            }
            
            // Build URL
            var url = '{{ route("guru.nilai.raport.cetak", ["siswaId" => "__siswa_id__"]) }}';
            url = url.replace('__siswa_id__', siswaId);
            url += '?tahun_ajaran=' + encodeURIComponent(tahunAjaran);
            url += '&semester=' + encodeURIComponent(semester);
            
            // Redirect ke URL cetak
            window.open(url, '_blank');
        });

        // Tombol reset
        $('button[type="reset"]').on('click', function(e) {
            e.preventDefault();
            $('#kelasSelect').val('');
            $('#siswaSelect').html('<option value="">-- Pilih Siswa --</option>');
            $('#tahunAjaran').val('{{ date("Y") . "/" . (date("Y") + 1) }}');
            $('#semesterSelect').val('ganjil');
            $('#siswaInfoContainer').hide();
            
            // Reset filter kelas
            @foreach($siswaList as $s)
                $('#siswaSelect').append(
                    '<option value="{{ $s->id }}" data-kelas="{{ $s->kelas_id }}">' +
                    '{{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }}' +
                    '</option>'
                );
            @endforeach
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Style untuk print */
    @media print {
        .no-print {
            display: none !important;
        }
        #previewRaport {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        .card-modern {
            box-shadow: none !important;
        }
        body {
            background: white !important;
        }
        .container-fluid {
            padding: 0 !important;
        }
        .table-hover tbody tr:hover {
            background-color: transparent !important;
        }
    }
</style>
@endpush
@endsection