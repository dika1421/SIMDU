@extends('administrasi.layouts.header')

@section('title', 'Scan RFID Absensi Sholat')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-sholat {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-sholat::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-sholat::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .page-header-sholat .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-sholat h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-sholat p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.22);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ========== LAYOUT ========== */
    .rfid-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        max-width: 1080px;
        margin: 0 auto;
    }

    /* ========== SCAN PANEL ========== */
    .scan-panel {
        background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
        border-radius: 20px;
        padding: 32px 26px;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(2, 132, 199, 0.3);
        min-height: 460px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .scan-panel::before {
        content: '';
        position: absolute;
        top: -50%; left: -20%;
        width: 400px; height: 400px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .scan-panel::after {
        content: '';
        position: absolute;
        bottom: -50%; right: -20%;
        width: 350px; height: 350px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .scan-panel-content {
        position: relative;
        z-index: 2;
    }

    .scan-rings {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .scan-rings::before,
    .scan-rings::after,
    .scan-rings .ring-3 {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.6);
        animation: ring-pulse 2.4s infinite ease-out;
    }
    .scan-rings::after { animation-delay: 0.8s; }
    .scan-rings .ring-3 { animation-delay: 1.6s; }
    @keyframes ring-pulse {
        0%   { transform: scale(0.5); opacity: 0.8; }
        100% { transform: scale(1.4); opacity: 0; }
    }
    .scan-rings .rfid-icon-inner {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(255,255,255,0.22);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.4rem;
        position: relative;
        z-index: 3;
        animation: icon-breathe 2s infinite ease-in-out;
    }
    @keyframes icon-breathe {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.06); }
    }

    .scan-panel h3 {
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .scan-panel .scan-sub {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 18px;
    }
    .rfid-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.35);
        border-radius: 20px;
        padding: 7px 16px;
        font-size: 0.78rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    .rfid-status-badge.waiting { color: #fef3c7; }
    .rfid-status-badge.ready   { color: #d1fae5; }
    .rfid-status-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: dot-pulse 1.4s infinite;
    }
    @keyframes dot-pulse {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.3; }
    }

    /* ========== FORM CARD ========== */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i {
        color: #0284c7;
        font-size: 1.1rem;
    }
    .form-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .form-card-body {
        padding: 22px;
    }

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
    .form-label-modern i { color: #0284c7; }

    .form-control-modern,
    .form-select-modern {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 14px;
        font-size: 0.88rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        outline: none;
    }
    .rfid-input-big {
        font-family: 'Courier New', monospace;
        font-size: 1.3rem !important;
        font-weight: 700;
        letter-spacing: 5px;
        text-align: center;
        background: #f8fafc;
        padding: 16px !important;
    }
    .rfid-input-big:focus {
        background: #fff;
    }

    .btn-submit-modern {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 13px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        color: #fff;
    }

    /* ========== INFO CARD ========== */
    .info-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-top: 20px;
    }
    .info-card h6 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-card h6 i { color: #f59e0b; }

    .step-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .step-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 8px 0;
        font-size: 0.83rem;
        color: #475569;
    }
    .step-list li .step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 900px) {
        .rfid-layout { grid-template-columns: 1fr; }
        .scan-panel { min-height: 380px; }
    }
    @media (max-width: 576px) {
        .page-header-sholat { padding: 18px; }
        .page-header-sholat h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-sholat">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-mosque me-2"></i>
                Scan RFID Absensi Sholat
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Catat kehadiran sholat siswa & guru secara otomatis
            </p>
        </div>
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn-glass">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ LAYOUT ============ --}}
<div class="rfid-layout">
    {{-- SCAN PANEL --}}
    <div class="scan-panel">
        <div class="scan-panel-content">
            <div class="scan-rings">
                <div class="ring-3"></div>
                <div class="rfid-icon-inner">
                    <i class="fas fa-id-card"></i>
                </div>
            </div>
            <h3>TEMPELKAN KARTU RFID</h3>
            <p class="scan-sub">Dekatkan kartu ke reader untuk absensi sholat</p>
            <div class="rfid-status-badge waiting" id="rfidStatus">
                <span class="dot"></span>
                <span>Menunggu Kartu...</span>
            </div>
        </div>
    </div>

    {{-- FORM PANEL --}}
    <div>
        <form action="{{ route('administrasi.absensi-sholat.scan-store') }}" method="POST" id="scanForm">
            @csrf

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-edit"></i>
                    <h5>Data Absensi Sholat</h5>
                </div>
                <div class="form-card-body">

                    {{-- Nomor Kartu RFID --}}
                    <div class="mb-3">
                        <label class="form-label-modern">
                            <i class="fas fa-id-card"></i> Nomor Kartu RFID
                        </label>
                        <input type="text" name="card_number" id="cardNumber"
                               class="form-control-modern rfid-input-big"
                               placeholder="Tempelkan kartu ke reader..."
                               autocomplete="off" required>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Nomor akan otomatis terisi saat kartu didekatkan
                        </small>
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label-modern">
                            <i class="fas fa-user-tag"></i> Role
                        </label>
                        <select name="role" id="roleSelect" class="form-select-modern" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>

                    <input type="hidden" name="user_id" id="userId" value="">

                    {{-- Pilihan Sholat --}}
                    <div class="mb-3">
                        <label class="form-label-modern">
                            <i class="fas fa-mosque"></i> Sholat
                        </label>
                        <select name="sholat" class="form-select-modern" required>
                            <option value="">-- Pilih Sholat --</option>
                            <option value="subuh">🌙 Subuh</option>
                            <option value="dzuhur">☀️ Dzuhur</option>
                            <option value="ashar">🌤️ Ashar</option>
                            <option value="maghrib">🌆 Maghrib</option>
                            <option value="isya">🌃 Isya</option>
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-4">
                        <label class="form-label-modern">
                            <i class="fas fa-info-circle"></i> Keterangan
                        </label>
                        <textarea name="keterangan" class="form-control-modern" rows="2"
                                  placeholder="Opsional..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit-modern" id="btnSubmit">
                        <i class="fas fa-save"></i>
                        <span>Simpan Absensi</span>
                    </button>
                </div>
            </div>
        </form>

        {{-- INFO CARD --}}
        <div class="info-card">
            <h6>
                <i class="fas fa-lightbulb"></i>
                Cara Penggunaan
            </h6>
            <ol class="step-list">
                <li>
                    <span class="step-num">1</span>
                    <span>Tempelkan kartu RFID ke reader</span>
                </li>
                <li>
                    <span class="step-num">2</span>
                    <span>Pilih Role (Siswa / Guru)</span>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <span>Pilih waktu sholat yang akan diabsensi</span>
                </li>
                <li>
                    <span class="step-num">4</span>
                    <span>Klik <strong>Simpan Absensi</strong> untuk menyimpan</span>
                </li>
            </ol>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#cardNumber').focus();

        $('#cardNumber').on('input', function() {
            const val = $(this).val();
            const $badge = $('#rfidStatus');
            if (val.length >= 4) {
                $badge.removeClass('waiting').addClass('ready')
                      .find('span:last').text('Kartu Terbaca ✓');
            } else {
                $badge.removeClass('ready').addClass('waiting')
                      .find('span:last').text('Menunggu Kartu...');
            }
        });
    });

    $('#scanForm').on('submit', function(e) {
        e.preventDefault();

        let cardNumber = $('#cardNumber').val();
        let role       = $('#roleSelect').val();
        let sholat     = $('select[name="sholat"]').val();

        if (!cardNumber) {
            Swal.fire('Error!', 'Silakan tempelkan kartu RFID terlebih dahulu', 'error');
            return;
        }
        if (!role) {
            Swal.fire('Error!', 'Silakan pilih role (Siswa/Guru)', 'error');
            return;
        }
        if (!sholat) {
            Swal.fire('Error!', 'Silakan pilih sholat', 'error');
            return;
        }

        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#scanForm')[0].reset();
                        $('#cardNumber').focus();
                        $('#rfidStatus').removeClass('ready').addClass('waiting')
                                      .find('span:last').text('Menunggu Kartu...');
                        $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                    $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire('Error!', msg, 'error');
                $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
            }
        });
    });
</script>
@endpush
@endsection