@extends('administrasi.layouts.header')

@section('title', 'Scan RFID Absensi Sholat')

@section('content')
<style>
    .rfid-container {
        max-width: 500px;
        margin: 0 auto;
    }
    .rfid-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        color: white;
        margin-bottom: 20px;
    }
    .rfid-icon {
        font-size: 60px;
        margin-bottom: 20px;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    .rfid-input {
        text-align: center;
        font-size: 24px;
        font-family: monospace;
        letter-spacing: 5px;
    }
    .wave {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
    }
    .wave:before, .wave:after {
        content: '';
        position: absolute;
        border-radius: 50%;
        border: 2px solid white;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        animation: wave 1.5s infinite;
    }
    .wave:after {
        animation-delay: 0.5s;
    }
    @keyframes wave {
        0% { transform: scale(0.5); opacity: 1; }
        100% { transform: scale(1.2); opacity: 0; }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-rss me-2"></i>
        Scan RFID Absensi Sholat
    </h1>
    <div class="btn-toolbar">
        <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="rfid-container">
    <div class="rfid-card">
        <div class="wave"></div>
        <i class="fas fa-id-card rfid-icon"></i>
        <h3>TEMPELKAN KARTU RFID</h3>
        <p class="mb-0">Dekatkan kartu ke reader untuk absensi</p>
        <div class="mt-2">
            <span id="rfidStatus" class="badge bg-warning text-dark">Menunggu Kartu...</span>
        </div>
    </div>

    <!-- Form Absensi -->
    <form action="{{ route('administrasi.absensi-sholat.scan-store') }}" method="POST" id="scanForm">
        @csrf
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-edit me-2"></i> Data Absensi
            </div>
            <div class="card-body">
                <!-- Nomor Kartu RFID -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-id-card me-1"></i> Nomor Kartu RFID
                    </label>
                    <input type="text" name="card_number" id="cardNumber" class="form-control rfid-input" 
                           placeholder="Tempelkan kartu ke reader..." required>
                    <small class="text-muted">Tempelkan kartu RFID ke reader, nomor akan otomatis terisi</small>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-tag me-1"></i> Role
                    </label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <!-- User ID (hidden) -->
                <input type="hidden" name="user_id" id="userId" value="">

                <!-- Pilihan Sholat -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-mosque me-1"></i> Sholat
                    </label>
                    <select name="sholat" class="form-select" required>
                        <option value="">-- Pilih Sholat --</option>
                        <option value="subuh">Subuh</option>
                        <option value="dzuhur">Dzuhur</option>
                        <option value="ashar">Ashar</option>
                        <option value="maghrib">Maghrib</option>
                        <option value="isya">Isya</option>
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle me-1"></i> Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                    <i class="fas fa-save me-2"></i> Simpan Absensi
                </button>
            </div>
        </div>
    </form>

    <!-- Informasi -->
    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            <i class="fas fa-list me-2"></i> Informasi
        </div>
        <div class="card-body">
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Cara Penggunaan:</strong>
                <ol class="mb-0 mt-2">
                    <li>Tempelkan kartu RFID ke reader</li>
                    <li>Pilih Role (Siswa/Guru)</li>
                    <li>Pilih sholat yang akan diabsensi</li>
                    <li>Klik Simpan Absensi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi untuk fokus ke input RFID
    $(document).ready(function() {
        $('#cardNumber').focus();
        
        // Auto submit ketika user_id sudah terisi (opsional)
        $('#cardNumber').on('change', function() {
            let cardNumber = $(this).val();
            if (cardNumber.length >= 8) {
                // Bisa tambahkan AJAX untuk cek kartu
                console.log('Kartu terbaca: ' + cardNumber);
            }
        });
    });
    
    // Submit form
    $('#scanForm').on('submit', function(e) {
        e.preventDefault();
        
        let cardNumber = $('#cardNumber').val();
        let role = $('#roleSelect').val();
        let sholat = $('select[name="sholat"]').val();
        
        if (!cardNumber) {
            Swal.fire('Error!', 'Silahkan tempelkan kartu RFID terlebih dahulu', 'error');
            return;
        }
        
        if (!role) {
            Swal.fire('Error!', 'Silahkan pilih role (Siswa/Guru)', 'error');
            return;
        }
        
        if (!sholat) {
            Swal.fire('Error!', 'Silahkan pilih sholat', 'error');
            return;
        }
        
        // Submit form
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
                        // Reset form
                        $('#scanForm')[0].reset();
                        $('#cardNumber').focus();
                        $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                    $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', msg, 'error');
                $('#btnSubmit').html('<i class="fas fa-save me-2"></i> Simpan Absensi').prop('disabled', false);
            }
        });
    });
</script>
@endpush
@endsection