@extends('administrasi.layouts.header')

@section('title', 'Tambah Pembayaran Lain')

@section('content')
<style>
    /* ================= ORIGINAL CSS KAMU - TETAP ADA ================= */
    .siswa-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .siswa-card:hover {
        transform: translateY(-5px);
    }
    
    .siswa-card .siswa-avatar {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .siswa-card .siswa-name {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .siswa-card .siswa-info {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 3px;
    }
    
    .siswa-card .siswa-info i {
        width: 20px;
        margin-right: 5px;
    }
    
    .search-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .info-badge {
        background: #e8f5e9;
        border-left: 4px solid #4caf50;
        padding: 12px 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
    
    .info-badge i {
        color: #4caf50;
        font-size: 1.2rem;
        margin-right: 10px;
    }

    /* TAMBAHAN CSS UNTUK FORM BARU */
    .form-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Tambah Pembayaran Lain
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.keuangan.pembayaran-lain.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <!-- Card Pencarian NIS - ORIGINAL KAMU -->
        <div class="search-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <label class="form-label fw-bold">
                        <i class="fas fa-search text-primary"></i> Cari berdasarkan NIS
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-id-card text-primary"></i>
                        </span>
                        <input type="text" name="nis" id="nis" class="form-control form-control-lg" 
                               placeholder="Contoh: 2210111" autocomplete="off" value="{{ old('nis') }}">
                        <button type="button" class="btn btn-primary" id="btnCariNis">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle"></i> Masukkan NIS dan klik cari, maka data siswa akan muncul di bawah
                    </small>
                </div>
                <div class="col-md-4">
                    <div class="info-badge">
                        <i class="fas fa-lightbulb"></i>
                        <small>Pastikan NIS yang dimasukkan benar</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Informasi Siswa - ORIGINAL KAMU -->
        <div id="siswaInfoCard" style="display: none;">
            <div class="siswa-card">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="siswa-avatar mx-auto">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-4 text-end">
                        <div class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-check-circle"></i> Siswa Terverifikasi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3 form-card">
    <div class="card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.store') }}" method="POST" id="formSPP">
            @csrf
            
            <!-- Hidden fields untuk menyimpan data siswa -->
            <input type="hidden" name="siswa_id" id="siswa_id" value="">
            <input type="hidden" name="kelas_id" id="selected_kelas_id" value="">
            
            <div class="row">
                {{-- ==================== TAMBAHAN BARU SESUAI SCREENSHOT KAMU ==================== --}}

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school text-primary"></i> Pilih Kelas <span class="text-danger">*</span>
                    </label>
                    <select id="kelas_dropdown" class="form-select @error('kelas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList ?? $kelas ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas ?? $k->nama }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Kelas akan otomatis terisi setelah NIS ditemukan</small>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-graduate text-primary"></i> Siswa <span class="text-danger">*</span>
                    </label>
                    <select id="siswa_dropdown" class="form-select @error('siswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                    <small class="text-muted">Siswa akan otomatis terisi setelah NIS ditemukan</small>
                    @error('siswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags text-primary"></i> Kategori Pembayaran <span class="text-danger">*</span>
                    </label>
                    <select name="kategori_pembayaran" id="kategori" class="form-select @error('kategori_pembayaran') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Daftar Ulang" {{ old('kategori_pembayaran')=='Daftar Ulang'?'selected':'' }}>Daftar Ulang</option>
                        <option value="Uang Gedung" {{ old('kategori_pembayaran')=='Uang Gedung'?'selected':'' }}>Uang Gedung</option>
                        <option value="Uang Seragam" {{ old('kategori_pembayaran')=='Uang Seragam'?'selected':'' }}>Uang Seragam</option>
                        <option value="Uang Buku" {{ old('kategori_pembayaran')=='Uang Buku'?'selected':'' }}>Uang Buku</option>
                        <option value="Uang Kegiatan" {{ old('kategori_pembayaran')=='Uang Kegiatan'?'selected':'' }}>Uang Kegiatan</option>
                        <option value="Lainnya" {{ old('kategori_pembayaran')=='Lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                    @error('kategori_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-money-bill-wave text-primary"></i> Jumlah <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah') }}" placeholder="Masukkan jumlah" required min="1000">
                    </div>
                    <small class="text-muted">Minimal pembayaran Rp 1.000</small>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-credit-card text-primary"></i> Metode Pembayaran <span class="text-danger">*</span>
                    </label>
                    <select name="metode_bayar" id="metode_bayar" class="form-select @error('metode_bayar') is-invalid @enderror" required>
                        <option value="">Pilih Metode</option>
                        <option value="Tunai" {{ old('metode_bayar') == 'Tunai' ? 'selected' : '' }}>💵 Tunai</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                        <option value="Virtual Account" {{ old('metode_bayar') == 'Virtual Account' ? 'selected' : '' }}>📱 Virtual Account</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>📱 QRIS</option>
                        <option value="EDC" {{ old('metode_bayar') == 'EDC' ? 'selected' : '' }}>💳 EDC</option>
                    </select>
                    @error('metode_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar text-primary"></i> Tanggal Bayar
                    </label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle text-primary"></i> Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    <i class="fas fa-save"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Variabel untuk menyimpan data siswa yang ditemukan
let currentSiswa = null;
let isSubmitting = false;

$(document).ready(function() {
    
    // ================== FUNGSI BARU: LOAD SISWA BY KELAS ==================
        $('#kelas_dropdown').on('change', function(){
            let kelasId = $(this).val();
            $('#selected_kelas_id').val(kelasId);
            $('#siswa_id').val('');
            if(!kelasId){
                $('#siswa_dropdown').html('<option value="">-- Pilih Siswa --</option>');
                return;
            }
            $.ajax({
                url: '{{ route("administrasi.keuangan.get-siswa-by-kelas") }}',
                data: {kelas_id: kelasId},
                success: function(res){
                    let opts = '<option value="">-- Pilih Siswa --</option>';
                    $.each(res.data||[], function(i,s){
                        opts += `<option value="${s.id}" data-nama="${s.nama}" data-nis="${s.nis}" data-kelas="${s.kelas_nama}">${s.nama} - ${s.nis}</option>`;
                    });
                    $('#siswa_dropdown').html(opts);
                }
            });
        });
        
    $('#siswa_dropdown').on('change', function(){
        let id = $(this).val();
        $('#siswa_id').val(id);
        if(id){
            let nama = $(this).find(':selected').data('nama');
            let nis = $(this).find(':selected').data('nis');
            let kelas = $(this).find(':selected').data('kelas') || $('#kelas_dropdown option:selected').text();
            let wali = $(this).find(':selected').data('wali') || '-';
            currentSiswa = {id:id, nama:nama, nis:nis, kelas_nama:kelas, wali_kelas:wali};
            $('#displayNama').text(nama);
            $('#displayNIS').text(nis);
            $('#displayKelas').text(kelas);
            $('#displayWaliKelas').text(wali);
            $('#siswaInfoCard').fadeIn();
        } else {
            $('#siswaInfoCard').fadeOut();
            currentSiswa = null;
        }
    });
    
    // ================== ORIGINAL CODE KAMU: CARI NIS ==================
    $('#btnCariNis').on('click', function() {
        var nis = $('#nis').val().trim();
        
        if (!nis) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Masukkan NIS terlebih dahulu!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            $('#nis').focus();
            return;
        }
        
        // Tampilkan loading
        Swal.fire({
            title: 'Mencari Siswa...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $('#btnCariNis').html('<i class="fas fa-spinner fa-spin"></i> Mencari...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("administrasi.keuangan.cari-siswa") }}',
            type: 'GET',
            data: {nis: nis},
            dataType: 'json',
            success: function(response) {
                Swal.close();
                
                if (response.success) {
                    currentSiswa = response.data;
                    
                    // Tampilkan card informasi siswa
                    $('#displayNama').text(currentSiswa.nama);
                    $('#displayNIS').text(currentSiswa.nis);
                    $('#displayKelas').text(currentSiswa.kelas_nama || 'Tidak ada kelas');
                    $('#displayWaliKelas').text(currentSiswa.wali_kelas || '-');
                    
                    // Set hidden fields
                    $('#siswa_id').val(currentSiswa.id);
                    $('#selected_kelas_id').val(currentSiswa.kelas_id);
                    $('#kelas_dropdown').val(currentSiswa.kelas_id);

                    // Trigger load siswa di dropdown
                    $.ajax({
                        url: '{{ route("administrasi.keuangan.get-siswa-by-kelas") }}',
                        data: {kelas_id: currentSiswa.kelas_id},
                        success: function(res2){
                            let opts = '<option value="">-- Pilih Siswa --</option>';
                            $.each(res2.data||[], function(i,s){
                                let sel = s.id==currentSiswa.id?'selected':'';
                                let nama = s.nama || s.user?.name || s.nama_lengkap;
                                opts += `<option value="${s.id}" ${sel} data-nama="${nama}" data-nis="${s.nis}">${nama} - ${s.nis}</option>`;
                            });
                            $('#siswa_dropdown').html(opts);
                        }
                    });
                    
                    // Tampilkan card
                    $('#siswaInfoCard').fadeIn();
                    
                    // Notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: '✓ Siswa Ditemukan',
                        html: `
                            <div style="text-align: left; margin-top: 15px;">
                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <i class="fas fa-user-graduate" style="font-size: 24px; color: #4caf50; width: 40px;"></i>
                                    <div>
                                        <strong style="font-size: 16px;">${currentSiswa.nama}</strong>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <i class="fas fa-school" style="width: 40px; color: #2196f3;"></i>
                                    <span>Kelas: ${currentSiswa.kelas_nama || 'Tidak ada kelas'}</span>
                                </div>
                                <div style="display: flex; align-items: center;">
                                    <i class="fas fa-id-card" style="width: 40px; color: #ff9800;"></i>
                                    <span>NIS: ${currentSiswa.nis}</span>
                                </div>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Lanjutkan',
                        confirmButtonColor: '#4caf50',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    // Bersihkan input NIS
                    $('#nis').val('');
                    
                } else {
                    // Sembunyikan card jika ada
                    $('#siswaInfoCard').fadeOut();
                    currentSiswa = null;
                    $('#siswa_id').val('');
                    $('#selected_kelas_id').val('');
                    
                    // Notifikasi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Siswa Tidak Ditemukan',
                        html: `
                            <div style="text-align: center;">
                                <i class="fas fa-user-slash" style="font-size: 48px; color: #f44336; margin-bottom: 15px; display: block;"></i>
                                <p><strong>NIS: ${nis}</strong></p>
                                <p>${response.message || 'Siswa dengan NIS tersebut tidak ditemukan di database'}</p>
                                <p class="text-muted mt-2">Pastikan NIS yang dimasukkan benar</p>
                            </div>
                        `,
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#nis').focus();
                    $('#nis').select();
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Ajax error:', error, xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal mencari siswa. Silakan coba lagi.',
                    confirmButtonColor: '#3085d6'
                });
            },
            complete: function() {
                $('#btnCariNis').html('<i class="fas fa-search"></i> Cari').prop('disabled', false);
            }
        });
    });
    
    // Enter key untuk mencari NIS
    $('#nis').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btnCariNis').click();
        }
    });
    
    // VALIDASI FORM SEBELUM SUBMIT - PERBAIKAN UTAMA + TAMBAHAN KATEGORI
    $('#formSPP').on('submit', function(e) {
        e.preventDefault();
        
        if (!currentSiswa && !$('#siswa_id').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Validasi',
                text: 'Silakan cari dan pilih siswa terlebih dahulu!',
                confirmButtonColor: '#3085d6'
            });
            $('#nis').focus();
            return false;
        }
        
        var kategori = $('#kategori').val();
        var jumlah = $('#jumlah').val();
        var metode = $('#metode_bayar').val();
        var tanggal = $('#tanggal_bayar').val();
        
        if (!kategori || kategori === '') {
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih kategori pembayaran!'});
            $('#kategori').focus();
            return false;
        }
        if (!jumlah || jumlah < 1000) {
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Jumlah pembayaran minimal Rp 1.000!'});
            $('#jumlah').focus();
            return false;
        }
        if (!metode || metode === '') {
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih metode pembayaran!'});
            $('#metode_bayar').focus();
            return false;
        }
        if (!tanggal) {
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih tanggal bayar!'});
            $('#tanggal_bayar').focus();
            return false;
        }
        
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `
                <div style="text-align: left;">
                    <p>Apakah Anda yakin ingin menyimpan pembayaran untuk:</p>
                    <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <strong style="font-size: 16px;">${currentSiswa ? currentSiswa.nama : $('#siswa_dropdown option:selected').text()}</strong><br>
                        <span class="text-muted">Kelas: ${currentSiswa ? currentSiswa.kelas_nama : '-'}</span><br>
                        <span class="text-muted">NIS: ${currentSiswa ? currentSiswa.nis : '-'}</span>
                        <hr class="my-2">
                        <table style="width: 100%;">
                            <tr><td>Kategori</td><td>: ${$('#kategori option:selected').text()}</td></tr>
                            <tr><td>Jumlah</td><td>: Rp ${parseInt(jumlah).toLocaleString('id-ID')}</td></tr>
                            <tr><td>Metode</td><td>: ${metode}</td></tr>
                            <tr><td>Tanggal</td><td>: ${tanggal}</td></tr>
                        </table>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4caf50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
                $('#formSPP')[0].submit();
            }
        });
        
        return false;
    });
});

// Fungsi reset form - ORIGINAL + UPDATE
function resetForm() {
    if (isSubmitting) return;
    
    currentSiswa = null;
    $('#siswa_id').val('');
    $('#selected_kelas_id').val('');
    $('#kelas_dropdown').val('');
    $('#siswa_dropdown').html('<option value="">-- Pilih Siswa --</option>');
    $('#kategori').val('');
    $('#jumlah').val('');
    $('#metode_bayar').val('');
    $('#tanggal_bayar').val('{{ date('Y-m-d') }}');
    $('#keterangan').val('');
    $('#nis').val('');
    $('#siswaInfoCard').fadeOut();
    $('#btnSubmit').prop('disabled', false);
    $('#btnSubmit').html('<i class="fas fa-save"></i> Simpan Pembayaran');
    
    Swal.fire({
        icon: 'info',
        title: 'Form Direset',
        text: 'Semua field telah dikosongkan',
        timer: 1500,
        showConfirmButton: false,
        background: '#fff'
    });
    
    $('#nis').focus();
}
</script>
@endpush
@endsection