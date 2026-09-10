@extends('administrasi.layouts.header')

@section('title', 'Edit Pembayaran SPP')

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
    .form-control-modern[readonly] {
        background: #f8fafc;
        cursor: not-allowed;
        color: #64748b;
    }

    .info-box {
        background: #f0fdf4;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #065f46;
        margin-bottom: 20px;
    }
    .info-box i {
        color: #10b981;
        font-size: 1.2rem;
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
                Edit Pembayaran SPP
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Perbarui data pembayaran SPP siswa
            </p>
        </div>
        <a href="{{ route('administrasi.keuangan.spp') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ ALERT ============ --}}
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

{{-- ============ INFO BOX ============ --}}
<div class="info-box">
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
        <h5>Form Edit Pembayaran SPP</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.keuangan.spp.update', $pembayaran->id ?? $spp->id ?? '') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-user-graduate"></i> Siswa
                    </label>
                    <input type="text" class="form-control-modern"
                           value="{{ $pembayaran->siswa->user->name ?? $pembayaran->siswa->nama_lengkap ?? '-' }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Bulan
                    </label>
                    <input type="text" name="bulan" class="form-control-modern"
                           value="{{ old('bulan', $pembayaran->bulan ?? '') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-money-bill-wave"></i> Jumlah
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#fffbeb;color:#d97706;border:1.5px solid #e2e8f0;border-right:none;font-weight:600;">Rp</span>
                        <input type="number" name="jumlah" class="form-control-modern" style="border-radius:0 10px 10px 0;"
                               value="{{ old('jumlah', $pembayaran->jumlah ?? 0) }}" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-info-circle"></i> Status
                    </label>
                    <select name="status" class="form-select-modern" required>
                        <option value="lunas" {{ old('status', $pembayaran->status ?? '') == 'lunas' ? 'selected' : '' }}>✅ Lunas</option>
                        <option value="belum_lunas" {{ old('status', $pembayaran->status ?? '') == 'belum_lunas' ? 'selected' : '' }}>⏳ Belum Lunas</option>
                        <option value="terlambat" {{ old('status', $pembayaran->status ?? '') == 'terlambat' ? 'selected' : '' }}>⚠️ Terlambat</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-check"></i> Tanggal Bayar
                    </label>
                    <input type="date" name="tanggal_bayar" class="form-control-modern"
                           value="{{ old('tanggal_bayar', isset($pembayaran->tanggal_bayar) ? date('Y-m-d', strtotime($pembayaran->tanggal_bayar)) : '') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran
                    </label>
                    <select name="metode_pembayaran" class="form-select-modern">
                        <option value="">-- Pilih Metode --</option>
                        <option value="tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
                        <option value="transfer" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                        <option value="virtual_account" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'virtual_account' ? 'selected' : '' }}>💳 Virtual Account</option>
                        <option value="qris" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                        <option value="edc" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '') == 'edc' ? 'selected' : '' }}>💳 EDC</option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-comment"></i> Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control-modern" rows="3">{{ old('keterangan', $pembayaran->keterangan ?? '') }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('administrasi.keuangan.spp') }}" class="btn-cancel-modern">
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