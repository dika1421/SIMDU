@extends('administrasi.layouts.header')

@section('title', 'Broadcast Pesan')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-broadcast {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-broadcast::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .page-header-broadcast::after {
        content: '';
        position: absolute;
        bottom: -60%; right: 25%;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header-broadcast .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-broadcast h1 {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-broadcast p {
        margin: 0;
        font-size: 0.82rem;
        opacity: 0.95;
    }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 9px 18px;
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
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i { color: #6d28d9; font-size: 1.05rem; }
    .form-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .form-card-body { padding: 24px; }

    .form-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #5b21b6;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #ede9fe;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label-modern {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-modern i { color: #8b5cf6; }
    .form-label-modern .required { color: #ef4444; }

    .form-control-modern {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 14px;
        font-size: 0.88rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-control-modern:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        outline: none;
    }
    .form-control-modern.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }

    /* ========== TARGET CARDS ========== */
    .target-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }
    .target-card {
        position: relative;
        cursor: pointer;
    }
    .target-card input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .target-card .card-inner {
        padding: 18px;
        border-radius: 14px;
        border: 2px solid #e2e8f0;
        background: #fff;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 10px;
        min-height: 130px;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .target-card .card-inner::before {
        content: '';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        background: #fff;
        transition: all 0.2s;
    }
    .target-card .card-inner:hover {
        border-color: #c4b5fd;
        background: #f5f3ff;
        transform: translateY(-2px);
    }
    .target-card input:checked + .card-inner {
        border-color: #8b5cf6;
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.2);
    }
    .target-card input:checked + .card-inner::before {
        border-color: #8b5cf6;
        background: #8b5cf6;
        box-shadow: inset 0 0 0 3px #fff;
    }
    .target-card .target-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        transition: all 0.2s;
    }
    .target-card input:checked + .card-inner .target-icon {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        transform: scale(1.05);
    }
    .target-card .target-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.88rem;
    }
    .target-card .target-desc {
        font-size: 0.7rem;
        color: #94a3b8;
        line-height: 1.3;
    }

    /* ========== URGENT TOGGLE ========== */
    .urgent-toggle {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border-radius: 12px;
        border: 1.5px solid #fde68a;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .urgent-toggle:hover {
        border-color: #fcd34d;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
    }
    .urgent-toggle input[type="checkbox"] {
        width: 20px; height: 20px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .urgent-toggle .urgent-info {
        flex: 1;
        min-width: 0;
    }
    .urgent-toggle .urgent-title {
        font-weight: 700;
        color: #92400e;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .urgent-toggle .urgent-desc {
        font-size: 0.72rem;
        color: #a16207;
        margin-top: 2px;
    }

    /* ========== INFO BOX ========== */
    .info-broadcast-box {
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        border: 1px solid #c4b5fd;
        border-radius: 14px;
        padding: 18px;
        display: flex;
        gap: 14px;
        margin-bottom: 18px;
    }
    .info-broadcast-box .ico-wrap {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .info-broadcast-box .info-title {
        font-weight: 700;
        color: #5b21b6;
        font-size: 0.88rem;
        margin-bottom: 4px;
    }
    .info-broadcast-box .info-desc {
        font-size: 0.78rem;
        color: #6d28d9;
        line-height: 1.5;
    }

    /* ========== CHARACTER COUNTER ========== */
    .char-counter {
        font-size: 0.72rem;
        color: #94a3b8;
        text-align: right;
        margin-top: 4px;
    }

    /* ========== BUTTONS ========== */
    .btn-submit-broadcast {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 13px 32px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.35);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
    }
    .btn-submit-broadcast::before {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        width: 0; height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .btn-submit-broadcast:hover::before {
        width: 300px;
        height: 300px;
    }
    .btn-submit-broadcast:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.45);
        color: #fff;
    }
    .btn-submit-broadcast:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    .btn-cancel-modern {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        border-radius: 12px;
        padding: 13px 26px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-cancel-modern:hover { background: #e2e8f0; color: #1e293b; }

    @media (max-width: 768px) {
        .page-header-broadcast { padding: 18px; }
        .page-header-broadcast h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
        .target-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-broadcast">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-bullhorn me-2"></i>
                Broadcast Pesan
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kirim pesan ke banyak penerima sekaligus
            </p>
        </div>
        <a href="{{ route('administrasi.komunikasi.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ============ ALERT ========== --}}
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

{{-- ============ FORM ========== --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-bullhorn"></i>
        <h5>Form Broadcast Pesan</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.komunikasi.send-broadcast') }}" method="POST" id="broadcastForm">
            @csrf

            {{-- Info Box --}}
            <div class="info-broadcast-box">
                <div class="ico-wrap">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="info-title">Tentang Broadcast</div>
                    <div class="info-desc">
                        Pesan broadcast akan dikirim ke <strong>semua penerima</strong> dalam target yang Anda pilih.
                        Pastikan informasi sudah benar sebelum mengirim.
                    </div>
                </div>
            </div>

            {{-- Judul --}}
            <div class="form-section-title">
                <i class="fas fa-heading"></i> Informasi Pesan
            </div>

            <div class="mb-4">
                <label class="form-label-modern">
                    <i class="fas fa-heading"></i> Judul Pesan <span class="required">*</span>
                </label>
                <input type="text" name="judul"
                       class="form-control-modern @error('judul') is-invalid @enderror"
                       placeholder="Masukkan judul broadcast..."
                       value="{{ old('judul') }}" required maxlength="200">
                @error('judul')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Target Penerima --}}
            <div class="form-section-title">
                <i class="fas fa-users"></i> Target Penerima <span style="color:#ef4444;margin-left:4px;">*</span>
            </div>

            <div class="target-grid mb-4">
                <label class="target-card">
                    <input type="checkbox" name="target[]" value="siswa" {{ is_array(old('target')) && in_array('siswa', old('target')) ? 'checked' : '' }}>
                    <div class="card-inner">
                        <div class="target-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="target-title">Siswa</div>
                        <div class="target-desc">Kirim ke semua siswa</div>
                    </div>
                </label>
                <label class="target-card">
                    <input type="checkbox" name="target[]" value="guru" {{ is_array(old('target')) && in_array('guru', old('target')) ? 'checked' : '' }}>
                    <div class="card-inner">
                        <div class="target-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="target-title">Guru</div>
                        <div class="target-desc">Kirim ke semua guru</div>
                    </div>
                </label>
                <label class="target-card">
                    <input type="checkbox" name="target[]" value="orang_tua" {{ is_array(old('target')) && in_array('orang_tua', old('target')) ? 'checked' : '' }}>
                    <div class="card-inner">
                        <div class="target-icon"><i class="fas fa-user-friends"></i></div>
                        <div class="target-title">Orang Tua</div>
                        <div class="target-desc">Kirim ke orang tua siswa</div>
                    </div>
                </label>
            </div>

            {{-- Isi Pesan --}}
            <div class="form-section-title">
                <i class="fas fa-comment"></i> Isi Pesan <span style="color:#ef4444;margin-left:4px;">*</span>
            </div>

            <div class="mb-4">
                <textarea name="isi" id="isiPesan" rows="6"
                          class="form-control-modern @error('isi') is-invalid @enderror"
                          placeholder="Tulis isi pesan broadcast di sini..."
                          required oninput="updateCharCount(this)">{{ old('isi') }}</textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> / 5000 karakter
                </div>
                @error('isi')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Opsi Urgent --}}
            <div class="form-section-title">
                <i class="fas fa-exclamation-triangle"></i> Opsi Tambahan
            </div>

            <label class="urgent-toggle mb-4">
                <input type="checkbox" name="is_penting" value="1" {{ old('is_penting') ? 'checked' : '' }}>
                <div class="urgent-info">
                    <div class="urgent-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tandai sebagai pesan penting
                    </div>
                    <div class="urgent-desc">
                        Pesan akan ditandai dengan badge <strong>Penting</strong> agar lebih menonjol
                    </div>
                </div>
            </label>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2 flex-wrap">
                <a href="{{ route('administrasi.komunikasi.index') }}" class="btn-cancel-modern">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-submit-broadcast" id="btnBroadcast">
                    <i class="fas fa-bullhorn"></i>
                    <span>Kirim Broadcast</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateCharCount(textarea) {
        var max = 5000;
        var current = textarea.value.length;
        var counter = document.getElementById('charCount');
        counter.textContent = current;

        if (current > max * 0.9) {
            counter.style.color = '#f59e0b';
        } else if (current >= max) {
            counter.style.color = '#ef4444';
        } else {
            counter.style.color = '#94a3b8';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('broadcastForm');
        if (form) {
            // Init char count
            var ta = document.getElementById('isiPesan');
            if (ta) updateCharCount(ta);

            // Submit validation
            form.addEventListener('submit', function(e) {
                var targets = document.querySelectorAll('input[name="target[]"]:checked');
                if (targets.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Target Belum Dipilih',
                        text: 'Silakan pilih minimal satu target penerima.',
                        confirmButtonColor: '#8b5cf6'
                    });
                    return false;
                }

                var targetNames = [];
                targets.forEach(function(t) {
                    var label = t.nextElementSibling.querySelector('.target-title').textContent.trim();
                    targetNames.push(label);
                });

                e.preventDefault();
                Swal.fire({
                    icon: 'question',
                    title: 'Kirim Broadcast?',
                    html: 'Pesan akan dikirim ke:<br><strong>' + targetNames.join(', ') + '</strong>',
                    showCancelButton: true,
                    confirmButtonColor: '#8b5cf6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-bullhorn me-1"></i> Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var btn = document.getElementById('btnBroadcast');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Mengirim...</span>';
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection