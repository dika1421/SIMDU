{{-- resources/views/guru/nilai/input.blade.php --}}
@extends('guru.layouts.header')

@section('title', 'Input Nilai')

@section('content')
<style>
    /* ===== Modern Input ===== */
    .form-control-sm-custom {
        padding: 6px 10px;
        font-size: 0.875rem;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease;
        width: 100%;
        background: #fff;
        font-family: inherit;
        color: #1e293b;
    }
    .form-control-sm-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }
    .form-control-sm-custom:hover {
        border-color: #667eea;
    }
    .form-control-sm-custom.has-value {
        border-color: #10b981;
        background: #f0fdf4;
        font-weight: 600;
    }

    /* ===== Table ===== */
    .table-input {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border: none !important;
    }
    .table-input thead tr:first-child th {
        border-top: none !important;
    }
    .table-input th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        padding: 12px 8px;
        background: #f8fafc !important;
        border-top: none !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-left: none !important;
        border-right: none !important;
        color: #64748b;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }
    .table-input td {
        padding: 8px;
        vertical-align: middle;
        border-top: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-left: none !important;
        border-right: none !important;
        background: #fff;
    }
    .table-input tbody tr:hover td {
        background-color: #f8faff;
    }
    .table-input tbody tr:nth-child(even) td {
        background-color: #fafbfc;
    }
    .table-input tbody tr:nth-child(even):hover td {
        background-color: #f1f5f9;
    }

    /* ===== Info Header ===== */
    .info-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.25);
    }
    .info-header .label {
        font-size: 0.7rem;
        opacity: 0.85;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }
    .info-header .value {
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== Page Header ===== */
    .page-header-input {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        padding: 1rem 0 1.5rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .page-title-input {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 6px 0;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }
    .page-subtitle-input {
        color: #64748b;
        font-size: .85rem;
        margin: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .page-subtitle-input .sep {
        color: #cbd5e1;
    }
    .page-subtitle-input strong {
        color: #4f46e5;
        font-weight: 700;
    }

    /* ===== Buttons ===== */
    .btn-action {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.25s ease;
        border: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .btn-action-secondary {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-action-secondary:hover {
        transform: translateY(-2px);
    }

    /* ===== Table Wrapper ===== */
    .table-wrapper {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        background: #fff;
    }

    /* ===== Empty State ===== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
        display: block;
    }
    .empty-state h4 {
        color: #475569;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #94a3b8;
        margin-bottom: 20px;
    }

    /* ===== Card ===== */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        background: #fff;
    }
    .card-modern .card-body {
        padding: 1.75rem;
    }

    /* ===== Alert Info Custom ===== */
    .alert-info-custom {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.85rem;
        color: #1e40af;
        font-weight: 500;
    }
    .alert-info-custom i {
        font-size: 1rem;
    }
    .alert-info-custom .ms-auto {
        margin-left: auto;
    }

    /* ===== Action Bar ===== */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 16px 16px;
        margin-top: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-title-input { font-size: 1.25rem; }
        .info-header .value { font-size: .85rem; }
        .action-bar { flex-direction: column; align-items: stretch; }
        .action-bar .btn { width: 100%; }
        .alert-info-custom { font-size: .78rem; }
        .alert-info-custom .ms-auto { margin-left: 0; }
    }
</style>

<!-- ============================================================
     PAGE HEADER
     ============================================================ -->
<div class="page-header-input">
    <div>
        <h1 class="page-title-input">Input Nilai</h1>
        <p class="page-subtitle-input">
            <span><i class="fas fa-users me-1"></i> <strong>{{ $kelas->nama_kelas ?? $kelas->nama ?? '-' }}</strong></span>
            <span class="sep">|</span>
            <span><i class="fas fa-book me-1"></i> <strong>{{ $mataPelajaran->nama_mapel ?? '-' }}</strong></span>
        </p>
    </div>
    <div>
        <a href="{{ route('guru.nilai.index') }}" class="btn btn-outline-secondary btn-action-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- ============================================================
     ALERT ERRORS (Validation)
     ============================================================ -->
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert"
         style="border-radius:12px; border:none; border-left:4px solid #ef4444;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Validasi Gagal!</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ============================================================
     INFO HEADER (Gradient)
     ============================================================ -->
<div class="info-header">
    <div class="row align-items-center g-3">
        <div class="col-md-3 col-6">
            <div class="label">Kelas</div>
            <div class="value">
                <i class="fas fa-users"></i>
                {{ $kelas->nama_kelas ?? $kelas->nama ?? '-' }}
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="label">Mata Pelajaran</div>
            <div class="value">
                <i class="fas fa-book"></i>
                {{ $mataPelajaran->nama_mapel ?? '-' }}
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="label">Tahun Ajaran</div>
            <div class="value">
                <i class="fas fa-calendar"></i>
                {{ $tahunAjaran ?? date('Y') }}
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="label">Semester</div>
            <div class="value">
                <i class="fas fa-clock"></i>
                {{ ucfirst($semester ?? 'Ganjil') }}
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     FORM INPUT NILAI
     ============================================================ -->
<div class="card card-modern">
    <div class="card-body">

        @if(isset($siswa) && $siswa->count() > 0)
        <form action="{{ route('guru.nilai.save') }}" method="POST" id="formNilai">
            @csrf

            {{-- Hidden inputs --}}
            <input type="hidden" name="kelas_id" value="{{ $kelasId ?? $kelas->id ?? '' }}">
            <input type="hidden" name="mapel_id" value="{{ $mapelId ?? $mataPelajaran->id ?? '' }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId ?? $mataPelajaran->id ?? '' }}">
            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran ?? date('Y') }}">
            <input type="hidden" name="semester" value="{{ $semester ?? 'Ganjil' }}">

            {{-- Info Alert --}}
            <div class="alert-info-custom mb-3">
                <i class="fas fa-info-circle"></i>
                <span><strong>Total Siswa:</strong> {{ $siswa->count() }} siswa</span>
                <span class="ms-auto">
                    <i class="fas fa-edit me-1"></i>Klik pada kolom nilai untuk mengisi
                    <span class="badge bg-success ms-2">Hijau</span> = sudah diisi
                </span>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-input" id="nilaiTable">
                        <thead>
                            <tr class="text-center">
                                <th style="min-width: 45px;">No</th>
                                <th style="min-width: 80px;">NIS</th>
                                <th style="min-width: 150px; text-align: left;">Nama Siswa</th>
                                <th style="min-width: 80px;">Harian 1</th>
                                <th style="min-width: 80px;">Harian 2</th>
                                <th style="min-width: 80px;">Harian 3</th>
                                <th style="min-width: 80px;">Tugas 1</th>
                                <th style="min-width: 80px;">Tugas 2</th>
                                <th style="min-width: 80px;">UTS</th>
                                <th style="min-width: 80px;">UAS</th>
                                <th style="min-width: 80px;">Praktek</th>
                                <th style="min-width: 120px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                                @php
                                    // ✅ FIX: $s->nilai dari controller sudah berupa object Nilai (single)
                                    $nilaiSiswa = $s->nilai ?? null;
                                    $namaSiswa = $s->nama ?? $s->user->name ?? $s->nama_lengkap ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $s->nis ?? '-' }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $namaSiswa }}</span>
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_harian_1]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_harian_1 ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Harian 1">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_harian_2]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_harian_2 ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Harian 2">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_harian_3]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_harian_3 ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Harian 3">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_1]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_tugas_1 ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Tugas 1">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_2]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_tugas_2 ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Tugas 2">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_uts]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_uts ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="UTS">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_uas]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_uas ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="UAS">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $s->id }}][nilai_praktek]"
                                            class="form-control-sm-custom nilai-input"
                                            step="0.01" min="0" max="100"
                                            value="{{ $nilaiSiswa->nilai_praktek ?? '' }}"
                                            placeholder="0-100"
                                            data-siswa="{{ $namaSiswa }}"
                                            data-field="Praktek">
                                    </td>
                                    <td>
                                        <input type="text" name="nilai[{{ $s->id }}][catatan_guru]"
                                            class="form-control-sm-custom"
                                            placeholder="Catatan..."
                                            value="{{ $nilaiSiswa->catatan_guru ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="action-bar">
                <div>
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Total siswa: <strong>{{ $siswa->count() }}</strong>
                        <span class="mx-2">|</span>
                        Nilai disimpan sebagai
                        <span class="badge bg-warning text-dark">Draft</span>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <button type="reset" class="btn btn-outline-secondary btn-action-secondary"
                            onclick="return confirmReset()">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-action" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
        @else
        {{-- Empty State --}}
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h4>Tidak Ada Siswa</h4>
            <p>Belum ada siswa di kelas ini atau kelas belum dipilih.</p>
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-primary btn-action">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.nilai-input');

        inputs.forEach(function(input) {
            if (input.value && input.value !== '') {
                input.classList.add('has-value');
            }

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const allInputs = Array.from(document.querySelectorAll('.nilai-input'));
                    const currentIndex = allInputs.indexOf(this);
                    if (currentIndex < allInputs.length - 1) {
                        allInputs[currentIndex + 1].focus();
                    }
                }
            });

            input.addEventListener('change', function() {
                if (this.value && this.value !== '') {
                    this.classList.add('has-value');
                    const val = parseFloat(this.value);
                    if (val > 100 || val < 0) {
                        this.style.borderColor = '#dc3545';
                        this.style.backgroundColor = '#fff5f5';
                    } else {
                        this.style.borderColor = '#10b981';
                        this.style.backgroundColor = '#f0fdf4';
                    }
                } else {
                    this.classList.remove('has-value');
                    this.style.borderColor = '';
                    this.style.backgroundColor = '';
                }
            });
        });
    });

    function confirmReset() {
        return confirm('Apakah Anda yakin ingin mereset semua nilai yang sudah diisi?');
    }

    document.querySelector('#formNilai')?.addEventListener('submit', function(e) {
        var hasError = false;
        var errorMessage = '';

        document.querySelectorAll('.nilai-input').forEach(function(input) {
            var val = parseFloat(input.value);
            if (input.value && input.value !== '' && (val > 100 || val < 0)) {
                hasError = true;
                errorMessage += '• ' + input.getAttribute('data-siswa') +
                    ' (' + input.getAttribute('data-field') + ') harus antara 0-100.\n';
                input.focus();
                input.select();
            }
        });

        if (hasError) {
            e.preventDefault();
            alert('Validasi gagal:\n\n' + errorMessage);
            return false;
        }

        var btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        return true;
    });

    document.querySelectorAll('.nilai-input').forEach(function(input) {
        input.addEventListener('focus', function() {
            setTimeout(function() {
                this.closest('tr').scrollIntoView({ block: 'center', behavior: 'smooth' });
            }.bind(this), 100);
        });
    });
</script>
@endpush
@endsection