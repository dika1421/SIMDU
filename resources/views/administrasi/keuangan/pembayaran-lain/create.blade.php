@extends('administrasi.layouts.header')

@section('title', 'Tambah Pembayaran Lain')

@section('content')
<style>
    /* ========== PAGE HEADER ========== */
    .page-header-create {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.25);
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
    .page-header-create h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .page-header-create p {
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

    /* ========== SEARCH CARD ========== */
    .search-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .search-card .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .search-card .input-group-text {
        background: #f5f3ff;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #7c3aed;
    }
    .search-card .form-control-lg {
        border: 1.5px solid #e2e8f0;
        border-radius: 0;
        font-size: 1rem;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        letter-spacing: 2px;
    }
    .search-card .form-control-lg:focus {
        border-color: #8b5cf6;
        box-shadow: none;
    }
    .search-card .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border: none;
        border-radius: 0 12px 12px 0;
        font-weight: 600;
        padding: 0 24px;
    }
    .search-card .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
    }

    .info-hint {
        background: #f5f3ff;
        border-left: 4px solid #8b5cf6;
        padding: 12px 16px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        color: #5b21b6;
    }
    .info-hint i {
        color: #8b5cf6;
        font-size: 1.2rem;
    }

    /* ========== SISWA CARD ========== */
    .siswa-card {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 16px;
        padding: 22px;
        color: #fff;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.25);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        display: none;
    }
    .siswa-card.show {
        display: block;
        animation: slideIn 0.4s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-15px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .siswa-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .siswa-avatar {
        width: 64px; height: 64px;
        background: rgba(255,255,255,0.22);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }
    .siswa-name {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 6px;
        position: relative;
        z-index: 2;
    }
    .siswa-info {
        font-size: 0.83rem;
        opacity: 0.95;
        margin-bottom: 3px;
        position: relative;
        z-index: 2;
    }
    .siswa-info i {
        width: 20px;
        margin-right: 6px;
        opacity: 0.85;
    }
    .verified-badge {
        background: rgba(255,255,255,0.25);
        border: 1px solid rgba(255,255,255,0.4);
        color: #fff;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
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
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-card-header i { color: #7c3aed; font-size: 1.05rem; }
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
    .form-label-modern i { color: #8b5cf6; }
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
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        outline: none;
    }
    .input-group-modern .input-group-text {
        background: #f5f3ff;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #7c3aed;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .input-group-modern .form-control {
        border-radius: 0 10px 10px 0;
    }

    .btn-submit-modern {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
        color: #fff;
    }
    .btn-reset-modern {
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
    .btn-reset-modern:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>

{{-- ============ PAGE HEADER ============ --}}
<div class="page-header-create">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-plus-circle me-2"></i>
                Tambah Pembayaran Lain
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Catat pembayaran selain SPP dengan mudah
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
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============ SEARCH CARD ========== --}}
<div class="search-card">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari Siswa berdasarkan NIS
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                <input type="text" name="nis" id="nis" class="form-control form-control-lg"
                       placeholder="Contoh: 2210111" autocomplete="off">
                <button type="button" class="btn btn-primary" id="btnCariNis">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i>
                Masukkan NIS dan klik cari, data siswa akan muncul di bawah
            </small>
        </div>
        <div class="col-md-4">
            <div class="info-hint">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Petunjuk:</strong>
                    <small>Pastikan NIS yang dimasukkan benar</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============ SISWA INFO CARD ========== --}}
<div id="siswaInfoCard" class="siswa-card">
    <div class="row align-items-center">
        <div class="col-md-2 text-center text-md-start">
            <div class="siswa-avatar mx-auto mx-md-0">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="col-md-7">
            <div class="siswa-name" id="displayNama">-</div>
            <div class="siswa-info">
                <i class="fas fa-id-card"></i> NIS: <span id="displayNIS">-</span>
            </div>
            <div class="siswa-info">
                <i class="fas fa-school"></i> Kelas: <span id="displayKelas">-</span>
            </div>
            <div class="siswa-info">
                <i class="fas fa-chalkboard-user"></i> Wali Kelas: <span id="displayWaliKelas">-</span>
            </div>
        </div>
        <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
            <div class="verified-badge">
                <i class="fas fa-check-circle"></i> Siswa Terverifikasi
            </div>
        </div>
    </div>
</div>

{{-- ============ FORM CARD ========== --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-edit"></i>
        <h5>Form Pembayaran Lain</h5>
    </div>
    <div class="form-card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.store') }}" method="POST" id="formPembayaranLain">
            @csrf
            <input type="hidden" name="siswa_id" id="siswa_id" value="{{ old('siswa_id') }}">
            <input type="hidden" name="kelas_id" id="selected_kelas_id" value="">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-school"></i> Pilih Kelas
                    </label>
                    <select id="kelas_dropdown" class="form-select-modern">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList ?? $kelas ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas ?? $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-user-graduate"></i> Siswa <span class="required">*</span>
                    </label>
                    <select id="siswa_dropdown" class="form-select-modern @error('siswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                    <small class="text-muted">Pilih siswa dari daftar atau cari dengan NIS</small>
                    @error('siswa_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-tags"></i> Kategori Pembayaran <span class="required">*</span>
                    </label>
                    <select name="kategori_pembayaran" id="kategori_pembayaran" class="form-select-modern @error('kategori_pembayaran') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Uang Gedung" {{ old('kategori_pembayaran') == 'Uang Gedung' ? 'selected' : '' }}>🏢 Uang Gedung</option>
                        <option value="Uang Seragam" {{ old('kategori_pembayaran') == 'Uang Seragam' ? 'selected' : '' }}>👕 Uang Seragam</option>
                        <option value="Uang Buku" {{ old('kategori_pembayaran') == 'Uang Buku' ? 'selected' : '' }}>📚 Uang Buku</option>
                        <option value="Uang Kegiatan" {{ old('kategori_pembayaran') == 'Uang Kegiatan' ? 'selected' : '' }}>🎯 Uang Kegiatan</option>
                        <option value="Daftar Ulang" {{ old('kategori_pembayaran') == 'Daftar Ulang' ? 'selected' : '' }}>📝 Daftar Ulang</option>
                        <option value="Lainnya" {{ old('kategori_pembayaran') == 'Lainnya' ? 'selected' : '' }}>📌 Lainnya</option>
                    </select>
                    @error('kategori_pembayaran')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-check"></i> Tanggal Bayar <span class="required">*</span>
                    </label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control-modern @error('tanggal_bayar') is-invalid @enderror"
                           value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-money-bill-wave"></i> Jumlah <span class="required">*</span>
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="jumlah" id="jumlah" class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah') }}" placeholder="0" required min="1000">
                    </div>
                    <small class="text-muted">Minimal Rp 1.000</small>
                    @error('jumlah')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran <span class="required">*</span>
                    </label>
                    <select name="metode_bayar" id="metode_bayar" class="form-select-modern @error('metode_bayar') is-invalid @enderror" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Tunai" {{ old('metode_bayar') == 'Tunai' ? 'selected' : '' }}>💵 Tunai</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                        <option value="Virtual Account" {{ old('metode_bayar') == 'Virtual Account' ? 'selected' : '' }}>💳 Virtual Account</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>📱 QRIS</option>
                        <option value="EDC" {{ old('metode_bayar') == 'EDC' ? 'selected' : '' }}>💳 EDC</option>
                    </select>
                    @error('metode_bayar')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-info-circle"></i> Status
                    </label>
                    <select name="status" id="status" class="form-select-modern">
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>✅ Lunas</option>
                        <option value="belum_bayar" {{ old('status') == 'belum_bayar' ? 'selected' : '' }}>⏳ Belum Bayar</option>
                        <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>⚠️ Terlambat</option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label-modern">
                        <i class="fas fa-comment"></i> Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" class="form-control-modern" rows="3"
                              placeholder="Tambahkan keterangan (opsional)...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn-reset-modern" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn-submit-modern" id="btnSubmit">
                    <i class="fas fa-save"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentSiswa = null;

    $(document).ready(function() {
        // LOAD SISWA BY KELAS
        $('#kelas_dropdown').on('change', function() {
            let kelasId = $(this).val();
            $('#selected_kelas_id').val(kelasId);
            $('#siswa_id').val('');
            if (!kelasId) {
                $('#siswa_dropdown').html('<option value="">-- Pilih Siswa --</option>');
                return;
            }
            $.ajax({
                url: '{{ route("administrasi.keuangan.get-siswa-by-kelas") }}',
                data: { kelas_id: kelasId },
                success: function(res) {
                    let opts = '<option value="">-- Pilih Siswa --</option>';
                    $.each(res.data || [], function(i, s) {
                        opts += `<option value="${s.id}" data-nama="${s.nama}" data-nis="${s.nis}" data-kelas="${s.kelas_nama}">${s.nama} - ${s.nis}</option>`;
                    });
                    $('#siswa_dropdown').html(opts);
                }
            });
        });

        // PILIH SISWA DARI DROPDOWN
        $('#siswa_dropdown').on('change', function() {
            let id = $(this).val();
            $('#siswa_id').val(id);
            if (id) {
                let nama = $(this).find(':selected').data('nama');
                let nis = $(this).find(':selected').data('nis');
                let kelas = $(this).find(':selected').data('kelas') || $('#kelas_dropdown option:selected').text();
                currentSiswa = { id: id, nama: nama, nis: nis, kelas_nama: kelas };
                $('#displayNama').text(nama);
                $('#displayNIS').text(nis);
                $('#displayKelas').text(kelas);
                $('#displayWaliKelas').text('-');
                $('#siswaInfoCard').addClass('show');
            } else {
                $('#siswaInfoCard').removeClass('show');
                currentSiswa = null;
            }
        });

        // CARI NIS
        $('#btnCariNis').on('click', function() {
            var nis = $('#nis').val().trim();
            if (!nis) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Masukkan NIS terlebih dahulu!' });
                $('#nis').focus();
                return;
            }

            Swal.fire({ title: 'Mencari Siswa...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            $('#btnCariNis').html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route("administrasi.keuangan.cari-siswa") }}',
                type: 'GET',
                data: { nis: nis },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        currentSiswa = response.data;
                        $('#displayNama').text(currentSiswa.nama);
                        $('#displayNIS').text(currentSiswa.nis);
                        $('#displayKelas').text(currentSiswa.kelas_nama || 'Tidak ada kelas');
                        $('#displayWaliKelas').text('-');
                        $('#siswa_id').val(currentSiswa.id);
                        $('#selected_kelas_id').val(currentSiswa.kelas_id);
                        $('#kelas_dropdown').val(currentSiswa.kelas_id);

                        $.ajax({
                            url: '{{ route("administrasi.keuangan.get-siswa-by-kelas") }}',
                            data: { kelas_id: currentSiswa.kelas_id },
                            success: function(res2) {
                                let opts = '<option value="">-- Pilih Siswa --</option>';
                                $.each(res2.data || [], function(i, s) {
                                    let sel = s.id == currentSiswa.id ? 'selected' : '';
                                    opts += `<option value="${s.id}" ${sel} data-nama="${s.nama}" data-nis="${s.nis}">${s.nama} - ${s.nis}</option>`;
                                });
                                $('#siswa_dropdown').html(opts);
                            }
                        });

                        $('#siswaInfoCard').addClass('show');
                        Swal.fire({
                            icon: 'success',
                            title: '✓ Siswa Ditemukan',
                            text: currentSiswa.nama + ' - ' + currentSiswa.nis,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#nis').val('');
                    } else {
                        $('#siswaInfoCard').removeClass('show');
                        currentSiswa = null;
                        $('#siswa_id').val('');
                        Swal.fire({ icon: 'error', title: 'Siswa Tidak Ditemukan', text: response.message || 'NIS tidak ditemukan' });
                        $('#nis').focus();
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal mencari siswa' });
                },
                complete: function() {
                    $('#btnCariNis').html('<i class="fas fa-search me-1"></i> Cari').prop('disabled', false);
                }
            });
        });

        // Enter key
        $('#nis').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btnCariNis').click();
            }
        });

        // VALIDASI SUBMIT
        $('#formPembayaranLain').on('submit', function(e) {
            var siswaId = $('#siswa_id').val();
            var kategori = $('#kategori_pembayaran').val();
            var tanggal = $('#tanggal_bayar').val();
            var jumlah = $('#jumlah').val();
            var metode = $('#metode_bayar').val();

            if (!siswaId || siswaId === '' || siswaId === '0' || isNaN(siswaId) || parseInt(siswaId) <= 0) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi Gagal', text: 'Silakan cari dan pilih siswa terlebih dahulu!' });
                $('#nis').focus();
                return false;
            }
            if (!kategori) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih kategori pembayaran!' });
                return false;
            }
            if (!tanggal) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih tanggal bayar!' });
                return false;
            }
            if (!jumlah || jumlah < 1000) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Jumlah minimal Rp 1.000!' });
                return false;
            }
            if (!metode) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih metode pembayaran!' });
                return false;
            }
            $('#btnSubmit').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop('disabled', true);
            return true;
        });
    });

    function resetForm() {
        currentSiswa = null;
        $('#siswa_id').val('');
        $('#selected_kelas_id').val('');
        $('#kelas_dropdown').val('');
        $('#siswa_dropdown').html('<option value="">-- Pilih Siswa --</option>');
        $('#kategori_pembayaran').val('');
        $('#tanggal_bayar').val('{{ date('Y-m-d') }}');
        $('#jumlah').val('');
        $('#metode_bayar').val('');
        $('#status').val('lunas');
        $('#keterangan').val('');
        $('#nis').val('');
        $('#siswaInfoCard').removeClass('show');
        $('#nis').focus();
    }
</script>
@endpush
@endsection