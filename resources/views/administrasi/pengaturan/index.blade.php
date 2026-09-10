@extends('administrasi.layouts.header')

@section('title', 'Pengaturan')

@section('content')
<style>
.up-set-wrapper {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-success: #10b981;
    --up-warning: #f59e0b;
    --up-danger: #dc2626;
    --up-info: #0ea5e9;
    --up-text: #1e293b;
    --up-text-muted: #64748b;
    --up-text-light: #94a3b8;
    --up-bg-soft: #f8fafc;
    --up-border: #e2e8f0;
    --up-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
    color: var(--up-text);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-bottom: 2rem;
}
.up-set-wrapper * { box-sizing: border-box; }

.up-set-wrapper .up-page-head {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-set-wrapper .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-set-wrapper .up-page-title i { color: var(--up-primary); }
.up-set-wrapper .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}

/* Card */
.up-set-wrapper .up-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: var(--up-shadow);
    overflow: hidden;
}

/* Tabs */
.up-set-wrapper .up-tabs {
    display: flex;
    border-bottom: 1px solid var(--up-border);
    background: var(--up-bg-soft);
    overflow-x: auto;
    scrollbar-width: none;
}
.up-set-wrapper .up-tabs::-webkit-scrollbar { display: none; }
.up-set-wrapper .up-tab {
    padding: 16px 22px;
    font-size: .875rem;
    font-weight: 600;
    color: var(--up-text-muted);
    cursor: pointer;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all .2s;
    border-bottom: 3px solid transparent;
    margin-bottom: -1px;
}
.up-set-wrapper .up-tab:hover {
    color: var(--up-primary);
    background: #eef2ff;
}
.up-set-wrapper .up-tab.active {
    color: var(--up-primary);
    border-bottom-color: var(--up-primary);
    background: #fff;
}
.up-set-wrapper .up-tab i { font-size: 1rem; }

/* Tab content */
.up-set-wrapper .up-tab-pane { display: none; padding: 2rem 1.75rem; }
.up-set-wrapper .up-tab-pane.active { display: block; animation: upFade .3s ease; }
@keyframes upFade {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Section */
.up-set-wrapper .up-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px dashed var(--up-border);
}
.up-set-wrapper .up-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.up-set-wrapper .up-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0 0 .35rem 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-set-wrapper .up-section-title i { color: var(--up-primary); }
.up-set-wrapper .up-section-desc {
    color: var(--up-text-light);
    font-size: .82rem;
    margin: 0 0 1.25rem 0;
}

/* Form */
.up-set-wrapper .up-form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
}
.up-set-wrapper .up-form-group {
    margin-bottom: 1rem;
}
.up-set-wrapper .up-form-label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    color: var(--up-text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: .5rem;
}
.up-set-wrapper .up-form-control {
    width: 100%;
    padding: 10px 14px;
    font-size: .875rem;
    color: var(--up-text);
    background: #fff;
    border: 1.5px solid var(--up-border);
    border-radius: 10px;
    outline: none;
    transition: all .2s;
    font-family: inherit;
}
.up-set-wrapper .up-form-control:focus {
    border-color: var(--up-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.up-set-wrapper textarea.up-form-control {
    min-height: 90px;
    resize: vertical;
}
.up-set-wrapper select.up-form-control {
    cursor: pointer;
}

/* Switch */
.up-set-wrapper .up-switch-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
}
.up-set-wrapper .up-switch-row:last-child { border-bottom: none; }
.up-set-wrapper .up-switch-info {
    flex-grow: 1;
    min-width: 0;
}
.up-set-wrapper .up-switch-label {
    font-size: .9rem;
    font-weight: 600;
    color: var(--up-text);
    margin: 0 0 2px 0;
}
.up-set-wrapper .up-switch-desc {
    font-size: .78rem;
    color: var(--up-text-light);
    margin: 0;
}
.up-set-wrapper .up-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}
.up-set-wrapper .up-switch input { opacity: 0; width: 0; height: 0; }
.up-set-wrapper .up-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #cbd5e1;
    border-radius: 999px;
    transition: .3s;
}
.up-set-wrapper .up-slider:before {
    content: "";
    position: absolute;
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .3s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.up-set-wrapper .up-switch input:checked + .up-slider {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
}
.up-set-wrapper .up-switch input:checked + .up-slider:before {
    transform: translateX(22px);
}

/* Buttons */
.up-set-wrapper .up-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 22px;
    font-size: .85rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    white-space: nowrap;
    font-family: inherit;
}
.up-set-wrapper .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25);
}
.up-set-wrapper .up-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, .4);
    color: #fff;
}
.up-set-wrapper .up-btn-outline {
    background: #fff;
    color: var(--up-text-muted);
    border: 1.5px solid var(--up-border);
}
.up-set-wrapper .up-btn-outline:hover {
    background: var(--up-bg-soft);
    color: var(--up-text);
}
.up-set-wrapper .up-btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(220, 38, 38, .25);
}
.up-set-wrapper .up-btn-danger:hover {
    transform: translateY(-2px);
    color: #fff;
}
.up-set-wrapper .up-btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(245, 158, 11, .25);
}
.up-set-wrapper .up-btn-warning:hover {
    transform: translateY(-2px);
    color: #fff;
}

/* Info box */
.up-set-wrapper .up-info-box {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px 18px;
    border-radius: 12px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: .85rem;
    margin-bottom: 1.5rem;
}
.up-set-wrapper .up-info-box.warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}

/* Logo preview */
.up-set-wrapper .up-logo-preview {
    width: 90px;
    height: 90px;
    border-radius: 16px;
    background: var(--up-bg-soft);
    border: 2px dashed var(--up-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--up-text-light);
    font-size: 2rem;
    flex-shrink: 0;
    overflow: hidden;
}
.up-set-wrapper .up-logo-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* Footer */
.up-set-wrapper .up-tab-foot {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 1.25rem 1.75rem;
    border-top: 1px solid var(--up-border);
    background: var(--up-bg-soft);
}

/* Alert */
.up-set-wrapper .up-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: .9rem;
}
.up-set-wrapper .up-alert-success {
    background: #ecfdf5;
    color: #065f46;
    border-left: 4px solid var(--up-success);
}
.up-set-wrapper .up-alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}
.up-set-wrapper .up-alert ul {
    margin: 4px 0 0 0;
    padding-left: 18px;
    font-weight: 500;
}

/* Backup item */
.up-set-wrapper .up-backup-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 16px 18px;
    background: #fff;
    border: 1px solid var(--up-border);
    border-radius: 12px;
    margin-bottom: .75rem;
}
.up-set-wrapper .up-backup-info { flex-grow: 1; min-width: 0; }
.up-set-wrapper .up-backup-title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--up-text);
    margin: 0 0 2px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-set-wrapper .up-backup-meta {
    font-size: .75rem;
    color: var(--up-text-light);
    margin: 0;
}

@media (max-width: 768px) {
    .up-set-wrapper .up-tab { padding: 12px 16px; font-size: .8rem; }
    .up-set-wrapper .up-tab-pane { padding: 1.25rem 1rem; }
    .up-set-wrapper .up-tab-foot { padding: 1rem; flex-direction: column; }
    .up-set-wrapper .up-tab-foot .up-btn { width: 100%; }
}
</style>

<div class="up-set-wrapper">

    {{-- Page Header --}}
    <div class="up-page-head">
        <h1 class="up-page-title">
            <i class="fas fa-cog"></i>
            Pengaturan
        </h1>
        <p class="up-page-sub">Kelola pengaturan umum, notifikasi, keamanan, dan data sistem</p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="up-alert up-alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="up-alert up-alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="up-card">
        {{-- Tabs --}}
        <div class="up-tabs" role="tablist">
            <button type="button" class="up-tab active" data-tab="umum">
                <i class="fas fa-globe"></i> Umum
            </button>
            <button type="button" class="up-tab" data-tab="notifikasi">
                <i class="fas fa-bell"></i> Notifikasi
            </button>
            <button type="button" class="up-tab" data-tab="keamanan">
                <i class="fas fa-shield-alt"></i> Keamanan
            </button>
            <button type="button" class="up-tab" data-tab="backup">
                <i class="fas fa-database"></i> Backup & Restore
            </button>
        </div>

        {{-- ============================================================
             TAB 1: UMUM
             ============================================================ --}}
        <div class="up-tab-pane active" id="tab-umum">
            <form action="{{ route('administrasi.pengaturan.update-umum') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-school"></i> Identitas Sekolah</h6>
                    <p class="up-section-desc">Informasi ini akan ditampilkan di seluruh sistem.</p>

                    <div class="up-form-row">
                        <div class="up-form-group">
                            <label class="up-form-label">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" class="up-form-control"
                                   value="{{ old('nama_sekolah', $settings['nama_sekolah'] ?? 'SIM Sekolah') }}"
                                   placeholder="Contoh: SMK Negeri 1 Jakarta" required>
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">NPSN</label>
                            <input type="text" name="npsn" class="up-form-control"
                                   value="{{ old('npsn', $settings['npsn'] ?? '') }}"
                                   placeholder="Nomor Pokok Sekolah Nasional">
                        </div>
                    </div>

                    <div class="up-form-row">
                        <div class="up-form-group">
                            <label class="up-form-label">Telepon</label>
                            <input type="text" name="telepon" class="up-form-control"
                                   value="{{ old('telepon', $settings['telepon'] ?? '') }}"
                                   placeholder="021-xxxxxxx">
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">Email</label>
                            <input type="email" name="email" class="up-form-control"
                                   value="{{ old('email', $settings['email'] ?? '') }}"
                                   placeholder="info@sekolah.sch.id">
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">Website</label>
                            <input type="url" name="website" class="up-form-control"
                                   value="{{ old('website', $settings['website'] ?? '') }}"
                                   placeholder="https://sekolah.sch.id">
                        </div>
                    </div>

                    <div class="up-form-group">
                        <label class="up-form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="up-form-control"
                                  placeholder="Jl. Pendidikan No. 1, Kota, Provinsi">{{ old('alamat', $settings['alamat'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-image"></i> Logo Sekolah</h6>
                    <p class="up-section-desc">Upload logo dalam format PNG/JPG, maksimal 2MB.</p>

                    <div style="display:flex; gap:1.5rem; align-items:center; flex-wrap:wrap;">
                        <div class="up-logo-preview">
                            @if(!empty($settings['logo']))
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo">
                            @else
                                <i class="fas fa-image"></i>
                            @endif
                        </div>
                        <div style="flex:1; min-width:200px;">
                            <input type="file" name="logo" class="up-form-control" accept="image/*">
                            <small style="color:var(--up-text-light); font-size:.75rem; display:block; margin-top:6px;">
                                Kosongkan jika tidak ingin mengubah logo.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-clock"></i> Lokal & Zona Waktu</h6>
                    <p class="up-section-desc">Pengaturan zona waktu dan format tanggal.</p>

                    <div class="up-form-row">
                        <div class="up-form-group">
                            <label class="up-form-label">Zona Waktu</label>
                            <select name="timezone" class="up-form-control">
                                @php $tz = old('timezone', $settings['timezone'] ?? 'Asia/Jakarta'); @endphp
                                <option value="Asia/Jakarta" {{ $tz === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Asia/Jakarta)</option>
                                <option value="Asia/Makassar" {{ $tz === 'Asia/Makassar' ? 'selected' : '' }}>WITA (Asia/Makassar)</option>
                                <option value="Asia/Jayapura" {{ $tz === 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Asia/Jayapura)</option>
                            </select>
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">Format Tanggal</label>
                            <select name="format_tanggal" class="up-form-control">
                                @php $ft = old('format_tanggal', $settings['format_tanggal'] ?? 'd/m/Y'); @endphp
                                <option value="d/m/Y" {{ $ft === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="Y-m-d" {{ $ft === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                <option value="d F Y" {{ $ft === 'd F Y' ? 'selected' : '' }}>DD Bulan YYYY</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="up-tab-foot">
                    <button type="reset" class="up-btn up-btn-outline">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="up-btn up-btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengaturan Umum
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================================
             TAB 2: NOTIFIKASI
             ============================================================ --}}
        <div class="up-tab-pane" id="tab-notifikasi">
            <form action="{{ route('administrasi.pengaturan.update-notifikasi') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="up-info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>Atur bagaimana dan kapan sistem mengirim notifikasi kepada Anda.</div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-envelope"></i> Notifikasi Email</h6>
                    <p class="up-section-desc">Aktifkan jika ingin menerima pemberitahuan via email.</p>

                    @php $notif = $settings['notifikasi'] ?? []; @endphp

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Notifikasi Absensi Siswa</p>
                            <p class="up-switch-desc">Kirim email ketika ada siswa tidak hadir.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_absensi_siswa" value="0">
                            <input type="checkbox" name="notif_absensi_siswa" value="1"
                                   {{ !empty($notif['absensi_siswa']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Notifikasi Absensi Guru</p>
                            <p class="up-switch-desc">Kirim email ketika guru tidak hadir.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_absensi_guru" value="0">
                            <input type="checkbox" name="notif_absensi_guru" value="1"
                                   {{ !empty($notif['absensi_guru']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Notifikasi Pembayaran</p>
                            <p class="up-switch-desc">Kirim email ketika ada pembayaran SPP masuk.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_pembayaran" value="0">
                            <input type="checkbox" name="notif_pembayaran" value="1"
                                   {{ !empty($notif['pembayaran']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Notifikasi Pengumuman</p>
                            <p class="up-switch-desc">Kirim email ketika ada pengumuman baru.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_pengumuman" value="0">
                            <input type="checkbox" name="notif_pengumuman" value="1"
                                   {{ !empty($notif['pengumuman']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fab fa-whatsapp"></i> Notifikasi WhatsApp</h6>
                    <p class="up-section-desc">Kirim notifikasi via WhatsApp (butuh konfigurasi gateway).</p>

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Aktifkan WhatsApp Gateway</p>
                            <p class="up-switch-desc">Kirim notifikasi via WhatsApp ke orang tua siswa.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_whatsapp" value="0">
                            <input type="checkbox" name="notif_whatsapp" value="1"
                                   {{ !empty($notif['whatsapp']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-bell-slash"></i> Quiet Hours</h6>
                    <p class="up-section-desc">Jangan kirim notifikasi pada jam tertentu (misal: malam hari).</p>

                    <div class="up-form-row">
                        <div class="up-form-group">
                            <label class="up-form-label">Mulai</label>
                            <input type="time" name="quiet_start" class="up-form-control"
                                   value="{{ old('quiet_start', $settings['quiet_start'] ?? '22:00') }}">
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">Selesai</label>
                            <input type="time" name="quiet_end" class="up-form-control"
                                   value="{{ old('quiet_end', $settings['quiet_end'] ?? '06:00') }}">
                        </div>
                    </div>
                </div>

                <div class="up-tab-foot">
                    <button type="reset" class="up-btn up-btn-outline">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="up-btn up-btn-primary">
                        <i class="fas fa-save"></i> Simpan Notifikasi
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================================
             TAB 3: KEAMANAN
             ============================================================ --}}
        <div class="up-tab-pane" id="tab-keamanan">
            <form action="{{ route('administrasi.pengaturan.update-keamanan') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-key"></i> Ganti Password</h6>
                    <p class="up-section-desc">Kosongkan jika tidak ingin mengubah password.</p>

                    <div class="up-form-group">
                        <label class="up-form-label">Password Lama</label>
                        <input type="password" name="current_password" class="up-form-control"
                               placeholder="Masukkan password lama Anda" autocomplete="current-password">
                    </div>
                    <div class="up-form-row">
                        <div class="up-form-group">
                            <label class="up-form-label">Password Baru</label>
                            <input type="password" name="new_password" class="up-form-control"
                                   placeholder="Minimal 8 karakter" autocomplete="new-password">
                        </div>
                        <div class="up-form-group">
                            <label class="up-form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="up-form-control"
                                   placeholder="Ulangi password baru" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-user-shield"></i> Keamanan Akun</h6>
                    <p class="up-section-desc">Pengaturan tambahan untuk keamanan akun Anda.</p>

                    @php $sec = $settings['keamanan'] ?? []; @endphp

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Two-Factor Authentication (2FA)</p>
                            <p class="up-switch-desc">Wajibkan verifikasi tambahan saat login.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="two_factor" value="0">
                            <input type="checkbox" name="two_factor" value="1"
                                   {{ !empty($sec['two_factor']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>

                    <div class="up-switch-row">
                        <div class="up-switch-info">
                            <p class="up-switch-label">Notifikasi Login Baru</p>
                            <p class="up-switch-desc">Kirim email jika ada login dari perangkat baru.</p>
                        </div>
                        <label class="up-switch">
                            <input type="hidden" name="notif_login" value="0">
                            <input type="checkbox" name="notif_login" value="1"
                                   {{ !empty($sec['notif_login']) ? 'checked' : '' }}>
                            <span class="up-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-hourglass-half"></i> Session Timeout</h6>
                    <p class="up-section-desc">Otomatis logout jika tidak ada aktivitas.</p>

                    <div class="up-form-group">
                        <label class="up-form-label">Timeout (menit)</label>
                        <select name="session_timeout" class="up-form-control">
                            @php $st = old('session_timeout', $settings['session_timeout'] ?? '30'); @endphp
                            <option value="15" {{ $st == 15 ? 'selected' : '' }}>15 menit</option>
                            <option value="30" {{ $st == 30 ? 'selected' : '' }}>30 menit (default)</option>
                            <option value="60" {{ $st == 60 ? 'selected' : '' }}>1 jam</option>
                            <option value="120" {{ $st == 120 ? 'selected' : '' }}>2 jam</option>
                        </select>
                    </div>
                </div>

                <div class="up-section">
                    <h6 class="up-section-title"><i class="fas fa-history"></i> Riwayat Login Terakhir</h6>
                    <p class="up-section-desc">Informasi login terakhir Anda.</p>

                    <div class="up-backup-item">
                        <div class="up-backup-info">
                            <p class="up-backup-title">
                                <i class="fas fa-clock" style="color:var(--up-primary);"></i>
                                Login Terakhir
                            </p>
                            <p class="up-backup-meta">
                                {{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d M Y, H:i') : 'Belum pernah login' }}
                                @if(Auth::user()->last_login_ip)
                                    • IP: {{ Auth::user()->last_login_ip }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="up-tab-foot">
                    <button type="reset" class="up-btn up-btn-outline">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="up-btn up-btn-primary">
                        <i class="fas fa-save"></i> Simpan Keamanan
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================================
             TAB 4: BACKUP & RESTORE
             ============================================================ --}}
        <div class="up-tab-pane" id="tab-backup">
            <div class="up-info-box warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    Backup database secara berkala untuk mencegah kehilangan data.
                    Simpan file backup di tempat yang aman.
                </div>
            </div>

            <div class="up-section">
                <h6 class="up-section-title"><i class="fas fa-database"></i> Informasi Database</h6>
                <p class="up-section-desc">Detail koneksi database yang sedang digunakan.</p>

                @php
                    $dbInfo = [
                        'Driver'   => config('database.default'),
                        'Database' => config('database.connections.' . config('database.default') . '.database'),
                        'Host'     => config('database.connections.' . config('database.default') . '.host'),
                        'Port'     => config('database.connections.' . config('database.default') . '.port'),
                    ];
                @endphp

                <div class="up-backup-item">
                    <div class="up-backup-info">
                        <p class="up-backup-title">
                            <i class="fas fa-server" style="color:var(--up-primary);"></i>
                            {{ ucfirst($dbInfo['Driver']) }} Database
                        </p>
                        <p class="up-backup-meta">
                            {{ $dbInfo['Database'] }} @ {{ $dbInfo['Host'] }}:{{ $dbInfo['Port'] }}
                        </p>
                    </div>
                    <span class="badge" style="background:#ecfdf5; color:#065f46; padding:6px 12px; border-radius:999px; font-weight:700; font-size:.7rem;">
                        <i class="fas fa-check-circle"></i> Terhubung
                    </span>
                </div>
            </div>

            <div class="up-section">
                <h6 class="up-section-title"><i class="fas fa-download"></i> Backup Manual</h6>
                <p class="up-section-desc">Buat file backup database sekarang.</p>

                <form action="{{ route('administrasi.pengaturan.backup') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="up-btn up-btn-primary">
                        <i class="fas fa-download"></i> Buat Backup Sekarang
                    </button>
                </form>

                <button type="button" class="up-btn up-btn-outline" onclick="alert('Fitur restore akan tersedia setelah backup pertama dibuat.');">
                    <i class="fas fa-upload"></i> Restore dari File
                </button>
            </div>

            <div class="up-section">
                <h6 class="up-section-title"><i class="fas fa-history"></i> Riwayat Backup</h6>
                <p class="up-section-desc">Daftar file backup yang pernah dibuat.</p>

                @if(!empty($backups) && count($backups) > 0)
                    @foreach($backups as $b)
                        <div class="up-backup-item">
                            <div class="up-backup-info">
                                <p class="up-backup-title">
                                    <i class="fas fa-file-archive" style="color:var(--up-primary);"></i>
                                    {{ $b['name'] ?? 'backup.sql' }}
                                </p>
                                <p class="up-backup-meta">
                                    {{ $b['date'] ?? '-' }}
                                    @if(!empty($b['size']))
                                        • {{ $b['size'] }}
                                    @endif
                                </p>
                            </div>
                            <a href="#" class="up-btn up-btn-outline" style="padding:6px 14px; font-size:.75rem;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endforeach
                @else
                    <div style="text-align:center; padding:2rem; color:var(--up-text-light);">
                        <i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:8px; opacity:.5;"></i>
                        Belum ada file backup.
                    </div>
                @endif
            </div>

            <div class="up-tab-foot">
                <button type="button" class="up-btn up-btn-danger"
                        onclick="if(confirm('Yakin ingin menghapus semua file backup lama? Tindakan ini tidak bisa dibatalkan.')) { alert('Fitur pembersihan backup akan segera hadir.'); }">
                    <i class="fas fa-trash"></i> Bersihkan Backup Lama
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching
    document.querySelectorAll('.up-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            const target = this.dataset.tab;

            // Update active tab
            document.querySelectorAll('.up-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Update active pane
            document.querySelectorAll('.up-tab-pane').forEach(p => p.classList.remove('active'));
            const pane = document.getElementById('tab-' + target);
            if (pane) pane.classList.add('active');
        });
    });
</script>
@endsection