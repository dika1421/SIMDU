@extends('administrasi.layouts.header')

@section('title', 'Edit Pembayaran Lain')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
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
    .page-header-edit h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-edit p {
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

    /* ========== INFO BANNER ========== */
    .info-banner {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 14px 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.85rem;
        color: #92400e;
        margin-bottom: 20px;
    }
    .info-banner i { color: #f59e0b; font-size: 1.3rem; }

    /* ========== FORM CARD ========== */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-card-header i { color: #d97706; font-size: 1.05rem; }
    .form-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .form-card-body { padding: 24px; }

    .form-label-modern {
        font-size: 0.75rem;
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
    .input-group-modern .input-group-text {
        background: #fffbeb;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #d97706;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .input-group-modern .form-control {
        border-radius: 0 10px 10px 0;
    }

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
    .btn-cancel-modern {
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
        text-decoration: none;
    }
    .btn-cancel-modern:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-edit">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-edit me-2"></i>
                Edit Pembayaran Lain
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Perbarui data pembayaran non-SPP
            </p>
        </div>
        <a href="{{ route('administrasi.keuangan.pembayaran-lain.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ ALERT ============ --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ INFO BANNER ============ --}}
<div class="info-banner">
    <i class="fas fa-info-circle"></i>
    <div>
        <strong>Perhatian:</strong>
        <span class="small">Pastikan data yang diubah sudah benar sebelum disimpan.</span>
    </div>
</div>

{{-- ============ FORM CARD ============ --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-edit"></i>
        <h5>Form Edit Pembayaran Lain</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.update', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-user-graduate"></i> Siswa <span class="required">*</span>
                    </label>
                    <select name="siswa_id" class="form-select-modern @error('siswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswaByKelas as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id', $pembayaran->siswa_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->nis }} - {{ $s->user->name ?? $s->nama_lengkap }} ({{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-tags"></i> Kategori Pembayaran <span class="required">*</span>
                    </label>
                    <select name="kategori" class="form-select-modern @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $key => $value)
                            <option value="{{ $key }}" {{ old('kategori', $pembayaran->kategori) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-money-bill-wave"></i> Jumlah <span class="required">*</span>
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="jumlah" class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah', $pembayaran->jumlah) }}" required min="1000">
                    </div>
                    @error('jumlah')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran <span class="required">*</span>
                    </label>
                    <select name="metode_bayar" class="form-select-modern @error('metode_bayar') is-invalid @enderror" required>
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodeList as $key => $value)
                            <option value="{{ $key }}" {{ old('metode_bayar', $pembayaran->metode_bayar) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('metode_bayar')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-check"></i> Tanggal Bayar
                    </label>
                    <input type="date" name="tanggal_bayar" class="form-control-modern @error('tanggal_bayar') is-invalid @enderror"
                           value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar ? date('Y-m-d', strtotime($pembayaran->tanggal_bayar)) : date('Y-m-d')) }}">
                    @error('tanggal_bayar')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-comment"></i> Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control-modern" rows="3">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('administrasi.keuangan.pembayaran-lain.index') }}" class="btn-cancel-modern">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-submit-modern">
                    <i class="fas fa-save"></i> Update Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection