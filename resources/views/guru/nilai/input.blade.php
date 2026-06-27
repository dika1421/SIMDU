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
    }
    .form-control-sm-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    .form-control-sm-custom:hover {
        border-color: #667eea;
    }
    .table-input th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding: 10px 8px;
    }
    .table-input td {
        padding: 6px 8px;
        vertical-align: middle;
    }
    .table-input tbody tr:hover {
        background-color: #f8f9fa;
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
        position: sticky;
        top: 0;
        z-index: 10;
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

<!-- Info Header -->
<div class="info-header">
    <div class="row align-items-center">
        <div class="col-md-3">
            <div class="label">Kelas</div>
            <div class="value"><i class="fas fa-users me-2"></i>{{ $kelas->nama ?? $kelas->nama_kelas ?? '-' }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Mata Pelajaran</div>
            <div class="value"><i class="fas fa-book me-2"></i>{{ $mataPelajaran->nama_mapel }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Tahun Ajaran</div>
            <div class="value"><i class="fas fa-calendar me-2"></i>{{ $tahunAjaran }}</div>
        </div>
        <div class="col-md-3">
            <div class="label">Semester</div>
            <div class="value"><i class="fas fa-clock me-2"></i>{{ ucfirst($semester) }}</div>
        </div>
    </div>
</div>

<!-- Form Input Nilai -->
<div class="card card-modern">
    <div class="card-body">
        <form action="{{ route('guru.nilai.save') }}" method="POST">
            @csrf
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
            <input type="hidden" name="semester" value="{{ $semester }}">
            
            <div class="table-wrapper">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover table-input" id="nilaiTable">
                        <thead>
                            <tr class="text-center">
                                <th width="5%" style="min-width: 40px;">No</th>
                                <th width="10%" style="min-width: 80px;">NIS</th>
                                <th width="12%" style="min-width: 120px;">Nama Siswa</th>
                                <th width="9%" style="min-width: 90px;">Harian 1</th>
                                <th width="9%" style="min-width: 90px;">Harian 2</th>
                                <th width="9%" style="min-width: 90px;">Harian 3</th>
                                <th width="9%" style="min-width: 90px;">Tugas 1</th>
                                <th width="9%" style="min-width: 90px;">Tugas 2</th>
                                <th width="9%" style="min-width: 90px;">UTS</th>
                                <th width="9%" style="min-width: 90px;">UAS</th>
                                <th width="9%" style="min-width: 90px;">Praktek</th>
                                <th width="12%" style="min-width: 120px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $s->nis ?? '-' }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $s->nama_lengkap ?? $s->user->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_1]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_harian_1 ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_2]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_harian_2 ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_harian_3]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_harian_3 ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_1]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_tugas_1 ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_2]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_tugas_2 ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_uts]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_uts ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_uas]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_uas ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" name="nilai[{{ $s->id }}][nilai_praktek]" 
                                        class="form-control-sm-custom" step="0.01" min="0" max="100"
                                        value="{{ $s->nilai->nilai_praktek ?? '' }}"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="text" name="nilai[{{ $s->id }}][catatan_guru]" 
                                        class="form-control-sm-custom" 
                                        placeholder="Catatan..."
                                        value="{{ $s->nilai->catatan_guru ?? '' }}">
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
                    <button type="reset" class="btn btn-secondary btn-action-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-action" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto scroll to row when input is focused
    document.querySelectorAll('.form-control-sm-custom').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.closest('tr').scrollIntoView({ block: 'center', behavior: 'smooth' });
        });
    });

    // Loading state on submit
    document.querySelector('form').addEventListener('submit', function() {
        var btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
    });

    // Auto calculate and highlight if value > 100 or < 0
    document.querySelectorAll('.form-control-sm-custom').forEach(function(input) {
        input.addEventListener('change', function() {
            var val = parseFloat(this.value);
            if (val > 100) {
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#fff5f5';
            } else if (val < 0) {
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#fff5f5';
            } else {
                this.style.borderColor = '#dee2e6';
                this.style.backgroundColor = '';
            }
        });
    });
</script>
@endpush
@endsection