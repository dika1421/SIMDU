{{-- resources/views/guru/nilai/input.blade.php --}}
@extends('guru.layouts.header')

@section('title', 'Input Nilai')

@section('content')
<style>
    .form-control-sm-custom {
        padding: 4px 8px;
        font-size: 0.875rem;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
        width: 100%;
        background: #fff;
    }
    .form-control-sm-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    .form-control-sm-custom:hover {
        border-color: #667eea;
    }
    .form-control-sm-custom.has-value {
        border-color: #28a745;
        background: #f0fff4;
    }
    .table-input th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding: 10px 8px;
        background: #f8f9fa !important;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .table-input td {
        padding: 6px 8px;
        vertical-align: middle;
    }
    .table-input tbody tr:hover {
        background-color: #f8f9fa;
    }
    .table-input tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    .table-input tbody tr:nth-child(even):hover {
        background-color: #f1f3f5;
    }
    .info-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .info-header .label {
        font-size: 0.8rem;
        opacity: 0.85;
        font-weight: 300;
    }
    .info-header .value {
        font-size: 1rem;
        font-weight: 600;
    }
    .btn-action {
        border-radius: 10px;
        padding: 8px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-action-secondary {
        border-radius: 10px;
        padding: 8px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action-secondary:hover {
        transform: translateY(-2px);
    }
    .badge-required {
        font-size: 0.6rem;
        background: #dc3545;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 4px;
    }
    .table-wrapper {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    .table-wrapper table {
        margin-bottom: 0;
    }
    .table-wrapper thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    .card-modern {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.06);
    }
    .card-modern .card-body {
        padding: 25px;
    }
    .alert-info-custom {
        background: #e8f4fd;
        border: 1px solid #b8d8f0;
        border-radius: 10px;
        padding: 12px 16px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <div>
        <h1 class="h2 fw-bold mb-0">
            <i class="fas fa-edit me-2 text-primary"></i>
            Input Nilai
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('guru.nilai.index') }}">Nilai</a></li>
                <li class="breadcrumb-item active">Input Nilai</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('guru.nilai.index') }}" class="btn btn-secondary btn-action-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- 🔥 DEBUG: Cek data yang dikirim -->
@php
    $debugKelasId = $kelasId ?? $kelas->id ?? 'TIDAK ADA';
    $debugMapelId = $mapelId ?? $mataPelajaran->id ?? 'TIDAK ADA';
    $debugSiswaCount = isset($siswa) ? $siswa->count() : 0;
    \Log::info('View Input Nilai - Data:', [
        'kelas_id' => $debugKelasId,
        'mapel_id' => $debugMapelId,
        'siswa_count' => $debugSiswaCount
    ]);
@endphp

<!-- 🔥 ALERT ERROR -->
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

<!-- Info Header -->
<div class="info-header">
    <div class="row align-items-center">
        <div class="col-md-3">
            <div class="label">Kelas</div>
            <div class="value"><i class="fas fa-users me-2"></i>{{ $kelas->nama_kelas ?? $kelas->nama ?? '-' }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Mata Pelajaran</div>
            <div class="value"><i class="fas fa-book me-2"></i>{{ $mataPelajaran->nama_mapel ?? '-' }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Tahun Ajaran</div>
            <div class="value"><i class="fas fa-calendar me-2"></i>{{ $tahunAjaran ?? date('Y') }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Semester</div>
            <div class="value"><i class="fas fa-clock me-2"></i>{{ ucfirst($semester ?? 'Ganjil') }}</div>
        </div>
    </div>
</div>

<!-- Form Input Nilai -->
<div class="card card-modern">
    <div class="card-body">

        @if(isset($siswa) && $siswa->count() > 0)
        <form action="{{ route('guru.nilai.save') }}" method="POST" id="formNilai">
            @csrf
            
            <!-- 🔥 HIDDEN INPUTS - WAJIB ADA -->
            <input type="hidden" name="kelas_id" value="{{ $kelasId ?? $kelas->id ?? '' }}">
            <input type="hidden" name="mapel_id" value="{{ $mapelId ?? $mataPelajaran->id ?? '' }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId ?? $mataPelajaran->id ?? '' }}">
            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran ?? date('Y') }}">
            <input type="hidden" name="semester" value="{{ $semester ?? 'Ganjil' }}">
            
            <div class="alert alert-info-custom mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <span class="fw-semibold">Total Siswa:</span> {{ $siswa->count() }} siswa
                <span class="ms-3"><i class="fas fa-edit me-1"></i>Klik pada kolom nilai untuk mengisi</span>
                <span class="ms-3"><span class="badge bg-success">Hijau</span> = sudah diisi</span>
            </div>

            <div class="table-wrapper">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover table-input" id="nilaiTable">
                        <thead>
                            <tr class="text-center">
                                <th width="5%" style="min-width: 40px;">No</th>
                                <th width="10%" style="min-width: 80px;">NIS</th>
                                <th width="15%" style="min-width: 120px;">Nama Siswa</th>
                                <th width="9%" style="min-width: 80px;">Harian 1</th>
                                <th width="9%" style="min-width: 80px;">Harian 2</th>
                                <th width="9%" style="min-width: 80px;">Harian 3</th>
                                <th width="9%" style="min-width: 80px;">Tugas 1</th>
                                <th width="9%" style="min-width: 80px;">Tugas 2</th>
                                <th width="9%" style="min-width: 80px;">UTS</th>
                                <th width="9%" style="min-width: 80px;">UAS</th>
                                <th width="9%" style="min-width: 80px;">Praktek</th>
                                <th width="12%" style="min-width: 100px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                            @php
                                $nilaiSiswa = $s->nilai->first() ?? null;
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $s->nis ?? '-' }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $s->nama ?? $s->user->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_1]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_harian_1 ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_harian_1">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_2]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_harian_2 ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_harian_2">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_3]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_harian_3 ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_harian_3">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_1]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_tugas_1 ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_tugas_1">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_2]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_tugas_2 ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_tugas_2">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_uts]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_uts ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_uts">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_uas]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_uas ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_uas">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_praktek]" 
                                        class="form-control-sm-custom nilai-input" 
                                        step="0.01" min="0" max="100"
                                        value="{{ $nilaiSiswa->nilai_praktek ?? '' }}"
                                        placeholder="0-100"
                                        data-siswa="{{ $s->nama }}"
                                        data-field="nilai_praktek">
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
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Total siswa: <strong>{{ $siswa->count() }}</strong> | 
                        Nilai akan disimpan sebagai <span class="badge bg-warning text-dark">Draft</span>
                    </span>
                </div>
                <div>
                    <button type="reset" class="btn btn-secondary btn-action-secondary me-2" onclick="return confirmReset()">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-action" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h4 class="text-muted">Tidak Ada Siswa</h4>
            <p class="text-muted">Belum ada siswa di kelas ini atau kelas belum dipilih.</p>
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // ===== AUTO FOCUS NEXT =====
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.nilai-input');
        
        // Highlight jika ada nilai
        inputs.forEach(function(input) {
            if (input.value && input.value !== '') {
                input.classList.add('has-value');
            }
            
            // Auto focus next on Enter
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const inputs = Array.from(document.querySelectorAll('.nilai-input'));
                    const currentIndex = inputs.indexOf(this);
                    if (currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    }
                }
            });
            
            // Highlight on change
            input.addEventListener('change', function() {
                if (this.value && this.value !== '') {
                    this.classList.add('has-value');
                    // Validasi range
                    const val = parseFloat(this.value);
                    if (val > 100 || val < 0) {
                        this.style.borderColor = '#dc3545';
                        this.style.backgroundColor = '#fff5f5';
                    } else {
                        this.style.borderColor = '#28a745';
                        this.style.backgroundColor = '#f0fff4';
                    }
                } else {
                    this.classList.remove('has-value');
                    this.style.borderColor = '#dee2e6';
                    this.style.backgroundColor = '';
                }
            });
        });
    });

    // ===== CONFIRM RESET =====
    function confirmReset() {
        return confirm('Apakah Anda yakin ingin mereset semua nilai yang sudah diisi?');
    }

    // ===== LOADING STATE ON SUBMIT =====
    document.querySelector('#formNilai')?.addEventListener('submit', function(e) {
        // 🔥 Validasi sebelum submit
        var hasError = false;
        var errorMessage = '';
        
        document.querySelectorAll('.nilai-input').forEach(function(input) {
            var val = parseFloat(input.value);
            if (input.value && input.value !== '' && (val > 100 || val < 0)) {
                hasError = true;
                errorMessage += 'Nilai ' + input.getAttribute('data-siswa') + ' (' + 
                    input.getAttribute('data-field') + ') harus antara 0-100.\n';
                input.focus();
                input.select();
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
        
        var btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        return true;
    });

    // ===== SCROLL TO INPUT =====
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