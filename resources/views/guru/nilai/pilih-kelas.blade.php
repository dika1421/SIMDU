@extends('guru.layouts.header')

@section('title', 'Pilih Siswa - Cetak Raport')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-print me-2"></i>
            Cetak Raport Siswa
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-user-graduate me-2"></i>
                Pilih Siswa dan Periode
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.nilai.raport') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->nis ?? '' }} - {{ $s->user->name ?? $s->nama_lengkap ?? '-' }} 
                                    ({{ $s->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran" class="form-select" required>
                            @foreach($tahunAjaranList as $ta)
                                <option value="{{ $ta }}" {{ $ta == date('Y') . '/' . (date('Y') + 1) ? 'selected' : '' }}>
                                    {{ $ta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem }}" {{ $sem == 'ganjil' ? 'selected' : '' }}>
                                    {{ ucfirst($sem) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Raport
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const siswaSelect = document.querySelector('select[name="siswa_id"]');
        if (!siswaSelect.value) {
            e.preventDefault();
            alert('Silakan pilih siswa terlebih dahulu!');
            siswaSelect.focus();
        }
    });
</script>
@endsection