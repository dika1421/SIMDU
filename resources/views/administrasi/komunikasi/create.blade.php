@extends('administrasi.layouts.header')

@section('title', 'Tulis Pesan')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-create::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-create .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-create h1 { font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-create p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
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

    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header i { color: #059669; font-size: 1.05rem; }
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
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #d1fae5;
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
    .form-label-modern i { color: #10b981; }
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
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .form-control-modern.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }

    /* ========== TIPE PESAN CARDS ========== */
    .tipe-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .tipe-card {
        position: relative;
        cursor: pointer;
    }
    .tipe-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .tipe-card .card-inner {
        padding: 16px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #fff;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .tipe-card .card-inner:hover {
        border-color: #6ee7b7;
        background: #f0fdf4;
    }
    .tipe-card input:checked + .card-inner {
        border-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4, #d1fae5);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    .tipe-card .tipe-icon {
        width: 42px; height: 42px;
        border-radius: 11px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .tipe-card input:checked + .card-inner .tipe-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    .tipe-card .tipe-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
        margin-bottom: 2px;
    }
    .tipe-card .tipe-desc {
        font-size: 0.72rem;
        color: #94a3b8;
        line-height: 1.3;
    }

    /* ========== PENERIMA TABS ========== */
    .nav-tabs-penerima {
        border: none;
        background: #f1f5f9;
        border-radius: 12px;
        padding: 5px;
        display: inline-flex;
        gap: 4px;
        margin-bottom: 14px;
    }
    .nav-tabs-penerima .nav-link {
        border: none;
        border-radius: 9px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 8px 16px;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .nav-tabs-penerima .nav-link:hover {
        color: #334155;
    }
    .nav-tabs-penerima .nav-link.active {
        background: #fff;
        color: #059669;
        box-shadow: 0 3px 8px rgba(16, 185, 129, 0.15);
    }
    .nav-tabs-penerima .nav-link .badge {
        font-size: 0.65rem;
        padding: 3px 7px;
    }

    .penerima-box {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        max-height: 380px;
        overflow-y: auto;
        padding: 12px;
        background: #fafbfc;
    }
    .penerima-box::-webkit-scrollbar { width: 6px; }
    .penerima-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

    .penerima-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        margin-bottom: 8px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .penerima-header label {
        font-weight: 700;
        color: #334155;
        font-size: 0.82rem;
        margin: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .penerima-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.15s;
        cursor: pointer;
        margin-bottom: 2px;
    }
    .penerima-item:hover {
        background: #f0fdf4;
    }
    .penerima-item input[type="checkbox"] {
        margin-right: 10px;
        cursor: pointer;
    }
    .penerima-item input[type="checkbox"]:checked ~ label {
        color: #059669;
        font-weight: 600;
    }
    .penerima-item label {
        margin: 0;
        cursor: pointer;
        font-size: 0.82rem;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        width: 100%;
    }
    .penerima-item .penerima-meta {
        font-size: 0.7rem;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .info-selected {
        margin-top: 12px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #f0fdf4, #d1fae5);
        border-radius: 12px;
        border: 1px solid #a7f3d0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #065f46;
        font-weight: 600;
    }
    .info-selected i {
        font-size: 1.1rem;
        color: #10b981;
    }

    /* ========== BUTTONS ========== */
    .btn-submit-modern {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: #fff;
    }
    .btn-submit-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
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
    }
    .btn-cancel-modern:hover { background: #e2e8f0; color: #1e293b; }

    .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    @media (max-width: 768px) {
        .page-header-create { padding: 18px; }
        .page-header-create h1 { font-size: 1.15rem; }
        .form-card-body { padding: 18px; }
        .tipe-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ============ PAGE HEADER ========== --}}
<div class="page-header-create">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-pen me-2"></i>
                Tulis Pesan Baru
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Kirim pesan ke siswa, guru, atau administrasi
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

{{-- ============ FORM ========== --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-envelope"></i>
        <h5>Form Pesan Baru</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.komunikasi.store') }}" method="POST" id="formKirimPesan">
            @csrf

            {{-- Judul --}}
            <div class="mb-4">
                <label class="form-label-modern">
                    <i class="fas fa-heading"></i> Judul Pesan <span class="required">*</span>
                </label>
                <input type="text" name="judul"
                       class="form-control-modern @error('judul') is-invalid @enderror"
                       placeholder="Masukkan judul pesan..."
                       value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipe Pesan --}}
            <div class="form-section-title">
                <i class="fas fa-list"></i> Tipe Pesan
            </div>

            <div class="tipe-grid mb-4">
                <label class="tipe-card">
                    <input type="radio" name="jenis" value="personal"
                           id="tipeIndividual"
                           {{ old('jenis', 'personal') == 'personal' ? 'checked' : '' }}>
                    <div class="card-inner">
                        <div class="tipe-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="tipe-title">Pesan Individual</div>
                            <div class="tipe-desc">Kirim ke penerima tertentu</div>
                        </div>
                    </div>
                </label>
                <label class="tipe-card">
                    <input type="radio" name="jenis" value="broadcast"
                           id="tipeBroadcast"
                           {{ old('jenis') == 'broadcast' ? 'checked' : '' }}>
                    <div class="card-inner">
                        <div class="tipe-icon"><i class="fas fa-bullhorn"></i></div>
                        <div>
                            <div class="tipe-title">Broadcast</div>
                            <div class="tipe-desc">Kirim ke semua pengguna</div>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Penerima Section --}}
            <div id="penerimaSection">
                <div class="form-section-title">
                    <i class="fas fa-users"></i> Pilih Penerima <span style="color:#ef4444;margin-left:4px;">*</span>
                </div>

                <div id="penerimaError" class="alert alert-danger rounded-3" style="display:none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Silakan pilih minimal satu penerima!
                </div>

                <ul class="nav nav-tabs-penerima">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#siswaTab">
                            <i class="fas fa-user-graduate"></i> Siswa
                            <span class="badge bg-secondary" id="siswaCount">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#guruTab">
                            <i class="fas fa-chalkboard-teacher"></i> Guru
                            <span class="badge bg-secondary" id="guruCount">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#adminTab">
                            <i class="fas fa-user-tie"></i> Administrasi
                            <span class="badge bg-secondary" id="adminCount">0</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab Siswa --}}
                    <div class="tab-pane fade show active" id="siswaTab">
                        <div class="penerima-box">
                            <div class="penerima-header">
                                <label>
                                    <input type="checkbox" id="selectAllSiswa"
                                           onchange="selectAll('siswa-checkbox', this.checked)">
                                    Pilih Semua Siswa
                                </label>
                            </div>
                            @forelse($siswa ?? [] as $s)
                                @if($s && $s->user)
                                <div class="penerima-item">
                                    <input class="form-check-input penerima-checkbox siswa-checkbox"
                                           type="checkbox"
                                           name="penerima_id[]"
                                           value="{{ $s->user->id }}"
                                           id="siswa_{{ $s->user->id }}"
                                           onchange="updateCount()">
                                    <label for="siswa_{{ $s->user->id }}">
                                        {{ $s->user->name ?? '-' }}
                                        <span class="penerima-meta">
                                            {{ $s->nis ?? '-' }}
                                            @if(isset($s->kelas))
                                                · {{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? '-' }}
                                            @endif
                                        </span>
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    Tidak ada data siswa
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab Guru --}}
                    <div class="tab-pane fade" id="guruTab">
                        <div class="penerima-box">
                            <div class="penerima-header">
                                <label>
                                    <input type="checkbox" id="selectAllGuru"
                                           onchange="selectAll('guru-checkbox', this.checked)">
                                    Pilih Semua Guru
                                </label>
                            </div>
                            @forelse($guru ?? [] as $g)
                                @if($g && $g->user)
                                <div class="penerima-item">
                                    <input class="form-check-input penerima-checkbox guru-checkbox"
                                           type="checkbox"
                                           name="penerima_id[]"
                                           value="{{ $g->user->id }}"
                                           id="guru_{{ $g->user->id }}"
                                           onchange="updateCount()">
                                    <label for="guru_{{ $g->user->id }}">
                                        {{ $g->user->name ?? '-' }}
                                        <span class="penerima-meta">
                                            {{ $g->nip ?? '-' }}
                                        </span>
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    Tidak ada data guru
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab Administrasi --}}
                    <div class="tab-pane fade" id="adminTab">
                        <div class="penerima-box">
                            <div class="penerima-header">
                                <label>
                                    <input type="checkbox" id="selectAllAdmin"
                                           onchange="selectAll('admin-checkbox', this.checked)">
                                    Pilih Semua Administrasi
                                </label>
                            </div>
                            @forelse($administrasi ?? [] as $a)
                                @if($a && $a->user)
                                <div class="penerima-item">
                                    <input class="form-check-input penerima-checkbox admin-checkbox"
                                           type="checkbox"
                                           name="penerima_id[]"
                                           value="{{ $a->user->id }}"
                                           id="admin_{{ $a->user->id }}"
                                           onchange="updateCount()">
                                    <label for="admin_{{ $a->user->id }}">
                                        {{ $a->user->name ?? '-' }}
                                        <span class="penerima-meta">Administrasi</span>
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    Tidak ada data administrasi
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="info-selected" id="selectedInfo" style="display:none;">
                    <i class="fas fa-check-circle"></i>
                    <span><strong id="totalCount">0</strong> penerima telah dipilih</span>
                </div>
            </div>

            {{-- Isi Pesan --}}
            <div class="form-section-title mt-4">
                <i class="fas fa-edit"></i> Isi Pesan
            </div>

            <div class="mb-4">
                <label class="form-label-modern">
                    <i class="fas fa-comment"></i> Pesan <span class="required">*</span>
                </label>
                <textarea name="isi" rows="5"
                          class="form-control-modern @error('isi') is-invalid @enderror"
                          placeholder="Tulis isi pesan di sini..."
                          required>{{ old('isi') }}</textarea>
                @error('isi')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Opsi Tambahan --}}
            <div class="mb-4">
                <label class="penerima-item" style="padding:12px 16px;background:#fffbeb;border-radius:10px;border:1px solid #fde68a;cursor:pointer;">
                    <input class="form-check-input" type="checkbox" name="is_urgent" value="1">
                    <label style="margin:0;cursor:pointer;font-weight:600;color:#92400e;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Tandai sebagai pesan penting (urgent)
                    </label>
                </label>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2 flex-wrap">
                <button type="button" class="btn-cancel-modern" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit-modern" id="btnKirim">
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Pesan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi update jumlah penerima
function updateCount() {
    var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
    var siswaChecked = document.querySelectorAll('.siswa-checkbox:checked').length;
    var guruChecked  = document.querySelectorAll('.guru-checkbox:checked').length;
    var adminChecked = document.querySelectorAll('.admin-checkbox:checked').length;

    document.getElementById('siswaCount').innerText = siswaChecked;
    document.getElementById('guruCount').innerText  = guruChecked;
    document.getElementById('adminCount').innerText = adminChecked;
    document.getElementById('totalCount').innerText = totalChecked;

    // Update warna badge
    ['siswa', 'guru', 'admin'].forEach(function(type) {
        var count = type === 'siswa' ? siswaChecked : (type === 'guru' ? guruChecked : adminChecked);
        var badge = document.getElementById(type + 'Count');
        if (badge) {
            badge.classList.remove('bg-secondary', 'bg-success');
            badge.classList.add(count > 0 ? 'bg-success' : 'bg-secondary');
        }
    });

    var selectedInfo = document.getElementById('selectedInfo');
    if (totalChecked > 0) {
        selectedInfo.style.display = 'flex';
        document.getElementById('penerimaError').style.display = 'none';
    } else {
        selectedInfo.style.display = 'none';
    }

    updateSelectAllStatus();
}

// Select all per kategori
function selectAll(className, isChecked) {
    document.querySelectorAll('.' + className).forEach(function(cb) {
        cb.checked = isChecked;
    });
    updateCount();
}

// Update status "Pilih Semua"
function updateSelectAllStatus() {
    ['siswa', 'guru', 'admin'].forEach(function(type) {
        var total   = document.querySelectorAll('.' + type + '-checkbox').length;
        var checked = document.querySelectorAll('.' + type + '-checkbox:checked').length;
        var btn = document.getElementById('selectAll' + type.charAt(0).toUpperCase() + type.slice(1));
        if (btn && total > 0) {
            btn.checked = (total === checked);
        }
    });
}

// Toggle penerima section
function togglePenerimaSection() {
    var broadcast = document.getElementById('tipeBroadcast');
    var section = document.getElementById('penerimaSection');
    if (broadcast && broadcast.checked) {
        section.style.display = 'none';
    } else {
        section.style.display = 'block';
    }
}

// Reset form
function resetForm() {
    Swal.fire({
        icon: 'question',
        title: 'Reset Form?',
        text: 'Semua data akan dihapus.',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formKirimPesan').reset();
            document.querySelectorAll('input[name="penerima_id[]"]').forEach(function(cb) {
                cb.checked = false;
            });
            updateCount();
            togglePenerimaSection();
            document.getElementById('penerimaError').style.display = 'none';
            Swal.fire({ icon: 'success', title: 'Form Direset', timer: 1000, showConfirmButton: false });
        }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    var individual = document.getElementById('tipeIndividual');
    var broadcast = document.getElementById('tipeBroadcast');

    if (individual) individual.addEventListener('change', togglePenerimaSection);
    if (broadcast) broadcast.addEventListener('change', togglePenerimaSection);

    var form = document.getElementById('formKirimPesan');
    if (form) {
        form.addEventListener('submit', function(e) {
            var broadcastChecked = document.getElementById('tipeBroadcast').checked;

            if (!broadcastChecked) {
                var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
                if (totalChecked === 0) {
                    e.preventDefault();
                    document.getElementById('penerimaError').style.display = 'block';
                    document.getElementById('penerimaSection').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    Swal.fire({
                        icon: 'warning',
                        title: 'Penerima Belum Dipilih',
                        text: 'Silakan pilih minimal satu penerima pesan.',
                        confirmButtonColor: '#10b981'
                    });
                    return false;
                }
            }

            var btn = document.getElementById('btnKirim');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Mengirim...</span>';
            return true;
        });
    }

    togglePenerimaSection();
    updateCount();
});
</script>
@endpush
@endsection