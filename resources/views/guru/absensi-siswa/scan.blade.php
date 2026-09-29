@extends('guru.layouts.header')

@section('title', 'Scan RFID Absensi Siswa')

@section('content')
<style>
    /* ============================================
       SCAN RFID - MODERN UI
       ============================================ */
    .scan-wrapper {
        --scan-primary: #667eea;
        --scan-purple: #764ba2;
        --scan-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --scan-gradient-hover: linear-gradient(135deg, #5a6fd6 0%, #6a4195 100%);
        --scan-success: #10b981;
        --scan-warning: #f59e0b;
        --scan-danger: #ef4444;
        --scan-text: #1e293b;
        --scan-muted: #64748b;
        --scan-border: #e2e8f0;
        max-width: 620px;
        margin: 0 auto;
        padding: 1rem 0 3rem;
    }

    /* ===== Page Header ===== */
    .scan-page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 1.75rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--scan-border);
    }
    .scan-page-head .title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }
    .scan-page-head .title-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--scan-gradient);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    .scan-page-head h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--scan-text);
        margin: 0;
        letter-spacing: -0.5px;
    }
    .scan-page-head p {
        font-size: .82rem;
        color: var(--scan-muted);
        margin: 2px 0 0 0;
    }
    .scan-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        font-size: .82rem;
        font-weight: 700;
        background: #fff;
        color: var(--scan-text);
        border: 1.5px solid var(--scan-border);
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .scan-btn-back:hover {
        border-color: var(--scan-primary);
        color: var(--scan-primary);
        transform: translateX(-3px);
        text-decoration: none;
    }

    /* ===== Scan Card ===== */
    .scan-card-main {
        position: relative;
        background: var(--scan-gradient);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.35);
        margin-bottom: 1.5rem;
    }
    /* Decorative circles */
    .scan-card-main::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }
    .scan-card-main::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    .scan-card-main > * { position: relative; z-index: 1; }

    /* Icon Animation */
    .scan-icon-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 130px;
        height: 130px;
        margin: 0 auto 1.5rem;
    }
    .scan-icon-wrap .ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.25);
        animation: ring-pulse 2s ease-out infinite;
    }
    .scan-icon-wrap .ring:nth-child(2) { animation-delay: 0.6s; }
    .scan-icon-wrap .ring:nth-child(3) { animation-delay: 1.2s; }

    @keyframes ring-pulse {
        0% { transform: scale(0.6); opacity: 0.8; }
        100% { transform: scale(1.4); opacity: 0; }
    }
    .scan-icon-inner {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        animation: icon-breathe 2.5s ease-in-out infinite;
    }
    @keyframes icon-breathe {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .scan-card-main h3 {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 0 0 6px;
        color: #fff;
    }
    .scan-card-main .sub-text {
        font-size: .88rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 1.5rem;
    }

    /* RFID Input */
    .rfid-input-wrap {
        position: relative;
        max-width: 420px;
        margin: 0 auto;
    }
    .rfid-input {
        width: 100%;
        text-align: center;
        font-size: 1.25rem;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 4px;
        padding: 16px 20px;
        border-radius: 14px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        outline: none;
        transition: all 0.3s;
    }
    .rfid-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
        letter-spacing: 1px;
        font-size: 1rem;
        font-weight: 500;
    }
    .rfid-input:focus {
        border-color: #fff;
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 0 0 6px rgba(255, 255, 255, 0.15);
    }
    .rfid-input.has-value {
        animation: rfid-success 0.6s ease;
        border-color: #4ade80;
        background: rgba(74, 222, 128, 0.2);
    }
    @keyframes rfid-success {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.03); }
    }

    /* ===== Student Info Card ===== */
    .student-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
        animation: slide-up 0.4s ease;
    }
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .student-card-header {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--scan-border);
    }
    .student-card-header .icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--scan-gradient);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .student-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--scan-text);
    }
    .student-card-body {
        padding: 1.5rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-item .label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--scan-muted);
    }
    .info-item .value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--scan-text);
    }
    .info-item .value.mono {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 10px;
        border-radius: 6px;
        display: inline-block;
        font-size: .85rem;
        width: fit-content;
    }

    /* ===== Result Alert ===== */
    .scan-result {
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: .9rem;
        font-weight: 600;
        animation: slide-up 0.4s ease;
    }
    .scan-result .result-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .scan-result.success {
        background: #ecfdf5;
        color: #065f46;
        border: 2px solid #a7f3d0;
    }
    .scan-result.success .result-icon {
        background: #10b981;
        color: #fff;
    }
    .scan-result.warning {
        background: #fffbeb;
        color: #78350f;
        border: 2px solid #fde68a;
    }
    .scan-result.warning .result-icon {
        background: #f59e0b;
        color: #fff;
    }
    .scan-result.danger {
        background: #fef2f2;
        color: #7f1d1d;
        border: 2px solid #fecaca;
    }
    .scan-result.danger .result-icon {
        background: #ef4444;
        color: #fff;
    }
    .scan-result .result-body { flex: 1; }
    .scan-result .result-title {
        font-weight: 800;
        margin-bottom: 4px;
        font-size: .95rem;
    }
    .scan-result .result-sub {
        font-weight: 500;
        opacity: 0.85;
        font-size: .82rem;
    }

    /* ===== Responsive ===== */
    @media (max-width: 576px) {
        .scan-card-main { padding: 2rem 1.25rem; border-radius: 20px; }
        .scan-card-main h3 { font-size: 1.15rem; }
        .scan-icon-wrap { width: 110px; height: 110px; }
        .scan-icon-inner { width: 75px; height: 75px; font-size: 2rem; }
        .rfid-input { font-size: 1rem; padding: 14px 16px; }
        .scan-page-head h1 { font-size: 1.2rem; }
        .scan-page-head .title-icon { width: 40px; height: 40px; font-size: 1.1rem; }
    }
</style>

<div class="scan-wrapper">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="scan-page-head">
        <div class="title">
            <div class="title-icon">
                <i class="fas fa-rss"></i>
            </div>
            <div>
                <h1>Scan RFID Absensi</h1>
                <p>Dekatkan kartu siswa ke reader untuk absensi</p>
            </div>
        </div>
        <a href="{{ route('guru.absensi.index') }}" class="scan-btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ================= SCAN CARD ================= --}}
    <div class="scan-card-main">
        <div class="scan-icon-wrap">
            <span class="ring"></span>
            <span class="ring"></span>
            <span class="ring"></span>
            <div class="scan-icon-inner">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
        <h3>TEMPELKAN KARTU RFID</h3>
        <p class="sub-text">Dekatkan kartu siswa ke reader</p>
        <div class="rfid-input-wrap">
            <input type="text" id="rfidCardNumber" class="rfid-input"
                   placeholder="Menunggu kartu..." readonly>
        </div>
    </div>

    {{-- ================= STUDENT INFO ================= --}}
    <div id="siswaInfo" class="student-card" style="display: none;">
        <div class="student-card-header">
            <div class="icon-box">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h5>Informasi Siswa</h5>
        </div>
        <div class="student-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Nama Lengkap</span>
                    <span class="value" id="siswaNama">-</span>
                </div>
                <div class="info-item">
                    <span class="label">NIS</span>
                    <span class="value mono" id="siswaNis">-</span>
                </div>
                <div class="info-item">
                    <span class="label">Kelas</span>
                    <span class="value" id="siswaKelas">-</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= RESULT ================= --}}
    <div id="absenResult" class="scan-result" style="display: none;"></div>

</div>

@push('scripts')
<script>
    let rfidBuffer = '';
    let rfidTimer = null;
    let currentSiswaId = null;

    // ===== LISTENER RFID =====
    $(document).on('keypress', function(e) {
        clearTimeout(rfidTimer);
        rfidBuffer += String.fromCharCode(e.which);

        rfidTimer = setTimeout(function() {
            if (rfidBuffer.length >= 8) {
                processCard(rfidBuffer.trim());
            }
            rfidBuffer = '';
        }, 100);
    });

    // ===== PROCESS CARD =====
    function processCard(cardNumber) {
        var $input = $('#rfidCardNumber');
        $input.val(cardNumber).addClass('has-value');

        // Reset tampilan sebelumnya
        $('#absenResult').hide();

        $.ajax({
            url: '{{ route("guru.absensi.get-siswa-by-card") }}',
            method: 'GET',
            data: { card_number: cardNumber },
            success: function(response) {
                if (response.success && response.data) {
                    currentSiswaId = response.data.id;
                    $('#siswaNama').text(response.data.nama);
                    $('#siswaNis').text(response.data.nis);
                    $('#siswaKelas').text(response.data.kelas);
                    $('#siswaInfo').fadeIn();

                    if (response.data.sudah_absen) {
                        showResult('warning', 'Sudah Absen', 'Siswa sudah melakukan absensi hari ini.');
                    } else {
                        autoAbsen(response.data.id);
                    }
                } else {
                    $('#siswaInfo').hide();
                    showResult('danger', 'Kartu Tidak Terdaftar', response.message);
                }
            },
            error: function() {
                showResult('danger', 'Terjadi Kesalahan', 'Silakan coba lagi.');
            }
        });
    }

    // ===== AUTO ABSEN =====
    function autoAbsen(siswaId) {
        $.ajax({
            url: '{{ route("guru.absensi.scan-store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                siswa_id: siswaId,
                status: 'hadir'
            },
            success: function(response) {
                if (response.success) {
                    showResult('success', 'Absensi Berhasil', 'Waktu: ' + response.data.waktu);
                    playBeep();
                    setTimeout(function() {
                        resetForm();
                    }, 2500);
                } else {
                    showResult('danger', 'Gagal', response.message);
                }
            },
            error: function() {
                showResult('danger', 'Terjadi Kesalahan', 'Silakan coba lagi.');
            }
        });
    }

    // ===== SHOW RESULT =====
    function showResult(type, title, sub) {
        var icons = {
            'success': 'fa-check-circle',
            'warning': 'fa-info-circle',
            'danger': 'fa-exclamation-circle'
        };
        var icon = icons[type] || 'fa-info-circle';

        var html = '<div class="result-icon"><i class="fas ' + icon + '"></i></div>' +
                   '<div class="result-body">' +
                   '<div class="result-title">' + title + '</div>' +
                   '<div class="result-sub">' + sub + '</div>' +
                   '</div>';

        $('#absenResult')
            .removeClass('success warning danger')
            .addClass(type)
            .html(html)
            .fadeIn();
    }

    // ===== BEEP SOUND =====
    function playBeep() {
        try {
            // Generate beep sederhana menggunakan AudioContext
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 1000;
            gain.gain.value = 0.15;
            osc.start();
            osc.stop(ctx.currentTime + 0.12);
        } catch(e) {}
    }

    // ===== RESET FORM =====
    function resetForm() {
        $('#rfidCardNumber').val('').removeClass('has-value');
        $('#siswaInfo').hide();
        $('#absenResult').hide().empty();
        rfidBuffer = '';
        currentSiswaId = null;
    }
</script>
@endpush
@endsection