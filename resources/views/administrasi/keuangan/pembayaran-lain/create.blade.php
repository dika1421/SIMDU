@extends('administrasi.layouts.header')

@section('title', 'Tambah Pembayaran Lain')

@section('content')
<style>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> 
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
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
                               placeholder="Contoh: 2210111" autocomplete="off">
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
        
        <!-- DEBUG VISUAL -->
        <div class="alert alert-info mt-2" id="debugInfo" style="display: none;">
            <strong>🔍 Debug:</strong> ID Siswa yang akan dikirim: <span id="debugSiswaId" class="fw-bold text-success">(belum dipilih)</span>
        </div>
    </div>
</div>

<div class="card mt-3 form-card">
    <div class="card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.store') }}" method="POST" id="formPembayaranLain">
            @csrf
            
            <!-- 🔥 HIDDEN INPUT UNTUK SISWA_ID - WAJIB ADA 🔥 -->
            <input type="hidden" name="siswa_id" id="siswa_id" value="{{ old('siswa_id') }}">
            <input type="hidden" name="kelas_id" id="selected_kelas_id" value="">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school text-primary"></i> Pilih Kelas
                    </label>
                    <select id="kelas_dropdown" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList ?? $kelas ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas ?? $k->nama }}</option>
                        @endforeach
                    </select>
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

                <!-- 🔥 KATEGORI PEMBAYARAN - PASTIKAN NAMA FIELD BENAR 🔥 -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags text-primary"></i> Kategori Pembayaran <span class="text-danger">*</span>
                    </label>
                    <select name="kategori_pembayaran" id="kategori_pembayaran" class="form-select @error('kategori_pembayaran') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Uang Gedung" {{ old('kategori_pembayaran') == 'Uang Gedung' ? 'selected' : '' }}>🏢 Uang Gedung</option>
                        <option value="Uang Seragam" {{ old('kategori_pembayaran') == 'Uang Seragam' ? 'selected' : '' }}>👕 Uang Seragam</option>
                        <option value="Uang Buku" {{ old('kategori_pembayaran') == 'Uang Buku' ? 'selected' : '' }}>📚 Uang Buku</option>
                        <option value="Uang Kegiatan" {{ old('kategori_pembayaran') == 'Uang Kegiatan' ? 'selected' : '' }}>🎯 Uang Kegiatan</option>
                        <option value="Daftar Ulang" {{ old('kategori_pembayaran') == 'Daftar Ulang' ? 'selected' : '' }}>📝 Daftar Ulang</option>
                        <option value="Lainnya" {{ old('kategori_pembayaran') == 'Lainnya' ? 'selected' : '' }}>📌 Lainnya</option>
                    </select>
                    @error('kategori_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-check text-primary"></i> Tanggal Bayar <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                           value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')
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
                        <i class="fas fa-info-circle text-primary"></i> Status
                    </label>
                    <select name="status" id="status" class="form-select">
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>✅ Lunas</option>
                        <option value="belum_bayar" {{ old('status') == 'belum_bayar' ? 'selected' : '' }}>⏳ Belum Bayar</option>
                        <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>⚠️ Terlambat</option>
                    </select>
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
let currentSiswa = null;

$(document).ready(function() {
    
    // LOAD SISWA BY KELAS
    $('#kelas_dropdown').on('change', function(){
        let kelasId = $(this).val();
        $('#selected_kelas_id').val(kelasId);
        $('#siswa_id').val('');
        updateDebugInfo();
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
    
    // PILIH SISWA DARI DROPDOWN
    $('#siswa_dropdown').on('change', function(){
        let id = $(this).val();
        $('#siswa_id').val(id);
        updateDebugInfo();
        if(id){
            let nama = $(this).find(':selected').data('nama');
            let nis = $(this).find(':selected').data('nis');
            let kelas = $(this).find(':selected').data('kelas') || $('#kelas_dropdown option:selected').text();
            currentSiswa = {id:id, nama:nama, nis:nis, kelas_nama:kelas};
            $('#displayNama').text(nama);
            $('#displayNIS').text(nis);
            $('#displayKelas').text(kelas);
            $('#displayWaliKelas').text('-');
            $('#siswaInfoCard').fadeIn();
        } else {
            $('#siswaInfoCard').fadeOut();
            currentSiswa = null;
        }
    });
    
    // CARI NIS
    $('#btnCariNis').on('click', function() {
        var nis = $('#nis').val().trim();
        
        if (!nis) {
            Swal.fire({icon: 'warning', title: 'Peringatan', text: 'Masukkan NIS terlebih dahulu!'});
            $('#nis').focus();
            return;
        }
        
        Swal.fire({title: 'Mencari Siswa...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
        
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
                    
                    $('#displayNama').text(currentSiswa.nama);
                    $('#displayNIS').text(currentSiswa.nis);
                    $('#displayKelas').text(currentSiswa.kelas_nama || 'Tidak ada kelas');
                    $('#displayWaliKelas').text('-');
                    
                    $('#siswa_id').val(currentSiswa.id);
                    $('#selected_kelas_id').val(currentSiswa.kelas_id);
                    $('#kelas_dropdown').val(currentSiswa.kelas_id);
                    updateDebugInfo();

                    $.ajax({
                        url: '{{ route("administrasi.keuangan.get-siswa-by-kelas") }}',
                        data: {kelas_id: currentSiswa.kelas_id},
                        success: function(res2){
                            let opts = '<option value="">-- Pilih Siswa --</option>';
                            $.each(res2.data||[], function(i,s){
                                let sel = s.id==currentSiswa.id?'selected':'';
                                opts += `<option value="${s.id}" ${sel} data-nama="${s.nama}" data-nis="${s.nis}">${s.nama} - ${s.nis}</option>`;
                            });
                            $('#siswa_dropdown').html(opts);
                        }
                    });
                    
                    $('#siswaInfoCard').fadeIn();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '✓ Siswa Ditemukan',
                        text: currentSiswa.nama + ' - ' + currentSiswa.nis,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#nis').val('');
                    
                } else {
                    $('#siswaInfoCard').fadeOut();
                    currentSiswa = null;
                    $('#siswa_id').val('');
                    updateDebugInfo();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Siswa Tidak Ditemukan',
                        text: response.message || 'Siswa dengan NIS tersebut tidak ditemukan',
                    });
                    $('#nis').focus();
                }
            },
            error: function() {
                Swal.close();
                Swal.fire({icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal mencari siswa'});
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
    
    // VALIDASI SEBELUM SUBMIT
    $('#formPembayaranLain').on('submit', function(e) {
        var siswaId = $('#siswa_id').val();
        var kategori = $('#kategori_pembayaran').val();
        var tanggal = $('#tanggal_bayar').val();
        var jumlah = $('#jumlah').val();
        var metode = $('#metode_bayar').val();
        
        // CEK SISWA_ID
        if (!siswaId || siswaId === '' || siswaId === '0') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                text: 'Silakan cari dan pilih siswa terlebih dahulu!'
            });
            $('#nis').focus();
            return false;
        }
        
        // CEK KATEGORI
        if (!kategori || kategori === '') {
            e.preventDefault();
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih kategori pembayaran!'});
            $('#kategori_pembayaran').focus();
            return false;
        }
        
        // CEK TANGGAL
        if (!tanggal) {
            e.preventDefault();
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih tanggal bayar!'});
            $('#tanggal_bayar').focus();
            return false;
        }
        
        // CEK JUMLAH
        if (!jumlah || jumlah < 1000) {
            e.preventDefault();
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Jumlah minimal Rp 1.000!'});
            $('#jumlah').focus();
            return false;
        }
        
        // CEK METODE
        if (!metode || metode === '') {
            e.preventDefault();
            Swal.fire({icon: 'warning', title: 'Validasi', text: 'Silakan pilih metode pembayaran!'});
            $('#metode_bayar').focus();
            return false;
        }
        
        // Jika semua valid, submit
        return true;
    });
});

// FUNGSI UPDATE DEBUG INFO
function updateDebugInfo() {
    var id = $('#siswa_id').val();
    if (id && id !== '') {
        $('#debugSiswaId').text(id).css('color', '#198754');
        $('#debugInfo').fadeIn();
    } else {
        $('#debugSiswaId').text('(belum dipilih)').css('color', '#dc3545');
        $('#debugInfo').fadeIn();
    }
}

// FUNGSI RESET
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
    $('#siswaInfoCard').fadeOut();
    updateDebugInfo();
    $('#nis').focus();
}
</script>
@endpush
@endsection