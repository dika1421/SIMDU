@extends('administrasi.layouts.header')

@section('title', 'Scan RFID - Absensi')

@section('content')
<style>
    /* ============ GLOBAL ============ */
    .scan-wrapper {
        max-width: 780px;
        margin: 0 auto;
        padding: 10px;
    }

    /* ============ HEADER ============ */
    .scan-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    .scan-header::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .scan-header::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .scan-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .scan-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .scan-header p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.9;
    }
    .scan-header-clock {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 12px;
        padding: 10px 16px;
        text-align: center;
    }
    .scan-header-clock .clock-time {
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1;
    }
    .scan-header-clock .clock-date {
        font-size: 0.7rem;
        opacity: 0.85;
        margin-top: 4px;
    }

    /* ============ SEGMENTED CONTROL ============ */
    .segmented-control {
        background: #f1f5f9;
        border-radius: 14px;
        padding: 5px;
        display: inline-flex;
        gap: 4px;
        margin-bottom: 18px;
        width: 100%;
        max-width: 100%;
    }
    .segmented-control .seg-btn {
        flex: 1;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 11px 18px;
        border-radius: 10px;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .segmented-control .seg-btn:hover {
        color: #334155;
    }
    .segmented-control .seg-btn.active {
        background: #fff;
        color: #667eea;
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.15);
    }
    .segmented-control .seg-btn i {
        font-size: 0.95rem;
    }

    /* ============ SCAN CARD ============ */
    .scan-card {
        background: #fff;
        border-radius: 18px;
        padding: 32px 26px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .scan-icon-wrap {
        width: 110px;
        height: 110px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: #667eea;
        font-size: 2.6rem;
    }
    .scan-icon-wrap::before,
    .scan-icon-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid #667eea;
        opacity: 0;
        animation: pulse-ring 2.4s infinite;
    }
    .scan-icon-wrap::after {
        animation-delay: 1.2s;
    }
    @keyframes pulse-ring {
        0%   { transform: scale(1);   opacity: 0.5; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    .scan-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .scan-subtitle {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-bottom: 22px;
    }

    /* ============ RFID INPUT ============ */
    .rfid-input-wrap {
        position: relative;
        max-width: 420px;
        margin: 0 auto 14px;
    }
    .rfid-input {
        font-size: 1.3rem !important;
        text-align: center;
        letter-spacing: 4px;
        font-weight: 700;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        transition: all 0.25s;
        font-family: 'Courier New', monospace;
        background: #fafbfc;
        width: 100%;
    }
    .rfid-input:focus {
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.12);
        outline: none;
    }
    .rfid-input::placeholder {
        font-size: 0.9rem;
        letter-spacing: 1px;
        color: #cbd5e1;
        font-family: 'Segoe UI', sans-serif;
        font-weight: 400;
    }
    .rfid-input-wrap i.scan-ico {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #cbd5e1;
        font-size: 1.1rem;
        pointer-events: none;
    }

    .scan-hint {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: #94a3b8;
        background: #f8fafc;
        padding: 8px 14px;
        border-radius: 20px;
        border: 1px dashed #e2e8f0;
    }

    /* ============ RESULT CARD ============ */
    .result-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 1080;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .result-overlay.show {
        display: flex;
        animation: overlay-fade 0.25s ease;
    }
    @keyframes overlay-fade {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .result-card {
        background: #fff;
        border-radius: 22px;
        max-width: 460px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        animation: result-pop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes result-pop {
        from { transform: scale(0.85) translateY(20px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    .result-card .result-banner {
        padding: 26px 20px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .result-card .result-banner::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .result-card .result-banner.banner-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .result-card .result-banner.banner-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .result-banner .banner-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(255,255,255,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 2.2rem;
        border: 2px solid rgba(255,255,255,0.4);
        position: relative;
        z-index: 2;
    }
    .result-banner .banner-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .result-body {
        padding: 22px 26px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 0.88rem;
    }
    .info-row:last-of-type {
        border-bottom: none;
    }
    .info-row .label {
        color: #64748b;
        font-weight: 500;
    }
    .info-row .value {
        color: #1e293b;
        font-weight: 700;
        text-align: right;
    }

    .result-timer {
        height: 4px;
        background: #f1f5f9;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 16px;
    }
    .result-timer .timer-bar {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        width: 100%;
        transform-origin: left;
        animation: timer-shrink 3s linear forwards;
    }
    @keyframes timer-shrink {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
    }

    /* ============ BEEP EFFECT ============ */
    .beep-flash {
        position: fixed;
        inset: 0;
        z-index: 1070;
        pointer-events: none;
        animation: beep-bg 0.5s ease;
    }
    .beep-flash.success {
        background: radial-gradient(circle, rgba(16,185,129,0.25), transparent 70%);
    }
    .beep-flash.error {
        background: radial-gradient(circle, rgba(239,68,68,0.25), transparent 70%);
    }
    @keyframes beep-bg {
        from { opacity: 1; }
        to   { opacity: 0; }
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 576px) {
        .scan-header { padding: 18px; }
        .scan-header h1 { font-size: 1.15rem; }
        .scan-header-clock .clock-time { font-size: 1.05rem; }
        .scan-card { padding: 24px 18px; }
        .scan-icon-wrap { width: 90px; height: 90px; font-size: 2.1rem; }
        .rfid-input { font-size: 1.1rem !important; padding: 14px; }
        .result-body { padding: 18px 20px; }
    }
</style>

<div class="scan-wrapper">
    {{-- ============ HEADER ============ --}}
    <div class="scan-header">
        <div class="scan-header-content">
            <div>
                <h1>
                    <i class="fas fa-wifi me-2"></i>
                    Scan RFID - Absensi
                </h1>
                <p>
                    <i class="fas fa-info-circle me-1"></i>
                    Tempelkan kartu RFID ke pembaca untuk mencatat kehadiran
                </p>
            </div>
            <div class="scan-header-clock">
                <div class="clock-time" id="clockTime">--:--:--</div>
                <div class="clock-date" id="clockDate">-- --- ----</div>
            </div>
        </div>
    </div>

    {{-- ============ SEGMENTED CONTROL ============ --}}
    <div class="segmented-control">
        <button type="button" class="seg-btn active" id="btnSiswa">
            <i class="fas fa-user-graduate"></i>
            Absensi Siswa
        </button>
        <button type="button" class="seg-btn" id="btnGuru">
            <i class="fas fa-chalkboard-teacher"></i>
            Absensi Guru
        </button>
    </div>

    {{-- ============ SCAN CARD ============ --}}
    <div class="scan-card" id="scanArea">
        <div class="scan-icon-wrap">
            <i class="fas fa-id-card-alt"></i>
        </div>

        <div class="scan-title">Scan Kartu RFID</div>
        <div class="scan-subtitle">Dekatkan kartu ke pembaca, atau ketik nomor RFID secara manual</div>

        <div class="rfid-input-wrap">
            <input type="text"
                   id="rfidInput"
                   class="rfid-input"
                   placeholder="Ketik nomor RFID di sini..."
                   autocomplete="off"
                   autofocus>
            <i class="fas fa-qrcode scan-ico"></i>
        </div>

        <div class="scan-hint">
            <i class="fas fa-lightbulb"></i>
            <span>Kartu akan otomatis terdeteksi saat didekatkan ke reader</span>
        </div>
    </div>
</div>

{{-- ============ RESULT OVERLAY ============ --}}
<div class="result-overlay" id="resultOverlay">
    <div class="result-card">
        <div class="result-banner" id="resultBanner">
            <div class="banner-icon" id="resultIcon">
                <i class="fas fa-check"></i>
            </div>
            <h5 class="banner-title" id="resultStatus">Absensi Berhasil</h5>
        </div>
        <div class="result-body">
            <div id="resultContent"></div>
            <p class="text-muted small text-center mt-3 mb-0" id="resultMessage"></p>
            <div class="result-timer">
                <div class="timer-bar" id="timerBar"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentType = 'siswa';
    let timeoutId  = null;
    let buffer     = '';
    let clockTimer = null;
    let autoCloseTimer = null;

    // ============ CLOCK ============
    function updateClock() {
        const now  = new Date();
        const hh   = String(now.getHours()).padStart(2, '0');
        const mm   = String(now.getMinutes()).padStart(2, '0');
        const ss   = String(now.getSeconds()).padStart(2, '0');
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const mons = ['Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];

        $('#clockTime').text(`${hh}:${mm}:${ss}`);
        $('#clockDate').text(`${days[now.getDay()]}, ${now.getDate()} ${mons[now.getMonth()]} ${now.getFullYear()}`);
    }

    $(document).ready(function() {
        updateClock();
        clockTimer = setInterval(updateClock, 1000);

        $('#rfidInput').focus();

        // ============ TAB SWITCH ============
        $('#btnSiswa').click(function() {
            currentType = 'siswa';
            $(this).addClass('active');
            $('#btnGuru').removeClass('active');
            resetInput();
        });

        $('#btnGuru').click(function() {
            currentType = 'guru';
            $(this).addClass('active');
            $('#btnSiswa').removeClass('active');
            resetInput();
        });

        // ============ ENTER KEY ============
        $('#rfidInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const rfid = $(this).val().trim();
                if (rfid) processScan(rfid);
            }
        });

        // ============ AUTO DETECT (BUFFER) ============
        $('#rfidInput').on('keyup', function(e) {
            if (e.key === 'Enter') return;

            clearTimeout(timeoutId);
            if (e.key && e.key.length === 1) buffer += e.key;

            timeoutId = setTimeout(function() {
                if (buffer.length > 5) {
                    processScan($('#rfidInput').val().trim());
                }
                buffer = '';
            }, 120);
        });

        // ============ CLICK AREA FOCUS ============
        $('#scanArea').click(function() {
            $('#rfidInput').focus();
        });
    });

    // ============ PROCESS SCAN ============
    function processScan(rfid) {
        const tanggal = new Date().toISOString().split('T')[0];

        $.ajax({
            url: '{{ url("administrasi/rfid/scan") }}/' + currentType,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rfid: rfid,
                tanggal: tanggal
            },
            success: function(response) {
                showResult(response.success, response);
                playBeep(response.success ? 'success' : 'error');
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {
                    success: false,
                    message: 'Terjadi kesalahan saat memproses data'
                };
                showResult(false, response);
                playBeep('error');
            }
        });
    }

    // ============ SHOW RESULT ============
    function showResult(success, data) {
        clearTimeout(autoCloseTimer);

        const $banner = $('#resultBanner');
        const $icon   = $('#resultIcon');
        const $status = $('#resultStatus');
        const $cont   = $('#resultContent');
        const $msg    = $('#resultMessage');
        const $timer  = $('#timerBar');

        if (success && data.data) {
            $banner.removeClass('banner-error').addClass('banner-success');
            $icon.html('<i class="fas fa-check"></i>');
            $status.text('Absensi Berhasil');

            let rows = '';
            if (currentType === 'siswa') {
                rows = `
                    <div class="info-row"><span class="label">NIS</span><span class="value">${data.data.nis || '-'}</span></div>
                    <div class="info-row"><span class="label">Nama</span><span class="value">${data.data.nama || '-'}</span></div>
                    <div class="info-row"><span class="label">Kelas</span><span class="value">${data.data.kelas || '-'}</span></div>
                    <div class="info-row"><span class="label">Status</span><span class="value text-success">${data.data.status || 'Hadir'}</span></div>
                    <div class="info-row"><span class="label">Jam Masuk</span><span class="value">${data.data.waktu_masuk || '-'}</span></div>
                `;
            } else {
                rows = `
                    <div class="info-row"><span class="label">NIP</span><span class="value">${data.data.nip || '-'}</span></div>
                    <div class="info-row"><span class="label">Nama</span><span class="value">${data.data.nama || '-'}</span></div>
                    <div class="info-row"><span class="label">Status</span><span class="value text-success">${data.data.status || 'Hadir'}</span></div>
                    <div class="info-row"><span class="label">Jam Masuk</span><span class="value">${data.data.waktu_masuk || '-'}</span></div>
                `;
            }
            $cont.html(rows);
            $msg.text(data.message || '');
        } else {
            $banner.removeClass('banner-success').addClass('banner-error');
            $icon.html('<i class="fas fa-times"></i>');
            $status.text('Absensi Gagal');
            $cont.html(`
                <div class="alert alert-danger mb-0 rounded-3 text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${data.message || 'Kartu RFID tidak terdaftar!'}
                </div>
            `);
            $msg.text('Silakan coba lagi atau hubungi administrator');
        }

        // Reset animasi timer
        $timer.css('animation', 'none').height(0).height('4px');
        void $timer[0].offsetWidth;
        $timer.css('animation', 'timer-shrink 3s linear forwards');

        // Show overlay
        $('#resultOverlay').addClass('show');

        // Flash beep
        const $flash = $('<div class="beep-flash ' + (success ? 'success' : 'error') + '"></div>');
        $('body').append($flash);
        setTimeout(() => $flash.remove(), 550);

        // Auto close 3 detik
        autoCloseTimer = setTimeout(function() {
            closeResult();
        }, 3000);
    }

    // ============ CLOSE RESULT ============
    function closeResult() {
        clearTimeout(autoCloseTimer);
        $('#resultOverlay').removeClass('show');
        resetInput();
    }

    function resetInput() {
        $('#rfidInput').val('').focus();
        buffer = '';
    }

    // ============ BEEP SOUND ============
    function playBeep(type) {
        try {
            if (navigator.vibrate) {
                navigator.vibrate(type === 'success' ? 100 : [80, 50, 80]);
            }
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc      = audioCtx.createOscillator();
            const gain     = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.value = type === 'success' ? 880 : 220;
            gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.25);
        } catch (e) {
            console.log('Beep not supported');
        }
    }

    // ============ CLICK OVERLAY ============
    $('#resultOverlay').on('click', function(e) {
        if (e.target === this) closeResult();
    });

    // ============ ESC KEY ============
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#resultOverlay').hasClass('show')) {
            closeResult();
        }
    });
</script>
@endpush
@endsection