@extends('guru.layouts.header')

@section('title', 'Scan RFID Absensi Siswa')

@section('content')
<style>
    .scan-container {
        max-width: 500px;
        margin: 0 auto;
    }
    .scan-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        color: white;
    }
    .scan-icon {
        font-size: 80px;
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
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-rss me-2"></i>
        Scan RFID Absensi Siswa
    </h1>
    <!-- PERBAIKAN: Menggunakan route guru.absensi.index -->
    <a href="{{ route('guru.absensi.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="scan-container">
    <div class="scan-card">
        <div class="scan-icon">
            <i class="fas fa-id-card"></i>
        </div>
        <h3>TEMPELKAN KARTU RFID</h3>
        <p>Dekatkan kartu siswa ke reader</p>
        <input type="text" id="rfidCardNumber" class="form-control rfid-input mt-3" 
               placeholder="Menunggu kartu..." readonly>
    </div>
    
    <div id="siswaInfo" class="card mt-4" style="display: none;">
        <div class="card-body">
            <h5><i class="fas fa-user-graduate me-2"></i> Informasi Siswa</h5>
            <table class="table table-borderless">
                <tr><td width="30%">Nama</td><td width="70%"><strong id="siswaNama"></strong></td></tr>
                <tr><td>NIS</td><td><strong id="siswaNis"></strong></td></tr>
                <tr><td>Kelas</td><td><strong id="siswaKelas"></strong></td></tr>
            </table>
        </div>
    </div>
    
    <div id="absenResult" class="alert mt-3" style="display: none;"></div>
</div>

@push('scripts')
<script>
    let rfidBuffer = '';
    let rfidTimer = null;
    let currentSiswaId = null;
    
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
    
    function processCard(cardNumber) {
        $('#rfidCardNumber').val(cardNumber);
        
        // PERBAIKAN: Menggunakan route guru.absensi.get-siswa-by-card
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
                        $('#absenResult').removeClass('alert-success alert-warning').addClass('alert-warning')
                            .html('<i class="fas fa-info-circle me-2"></i> Siswa sudah melakukan absensi hari ini.')
                            .fadeIn();
                    } else {
                        autoAbsen(response.data.id);
                    }
                } else {
                    $('#siswaInfo').hide();
                    $('#absenResult').removeClass('alert-success alert-warning').addClass('alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> ' + response.message)
                        .fadeIn();
                }
            },
            error: function() {
                $('#absenResult').removeClass('alert-success alert-warning').addClass('alert-danger')
                    .html('<i class="fas fa-exclamation-circle me-2"></i> Terjadi kesalahan, silakan coba lagi.')
                    .fadeIn();
            }
        });
    }
    
    function autoAbsen(siswaId) {
        // PERBAIKAN: Menggunakan route guru.absensi.scan-store
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
                    $('#absenResult').removeClass('alert-danger alert-warning').addClass('alert-success')
                        .html('<i class="fas fa-check-circle me-2"></i> ' + response.message + '<br>Waktu: ' + response.data.waktu)
                        .fadeIn();
                    playBeep();
                    setTimeout(function() {
                        resetForm();
                        location.reload();
                    }, 2000);
                } else {
                    $('#absenResult').removeClass('alert-success alert-warning').addClass('alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> ' + response.message)
                        .fadeIn();
                }
            },
            error: function(xhr) {
                $('#absenResult').removeClass('alert-success alert-warning').addClass('alert-danger')
                    .html('<i class="fas fa-exclamation-circle me-2"></i> Terjadi kesalahan, silakan coba lagi.')
                    .fadeIn();
            }
        });
    }
    
    function playBeep() {
        try {
            const audio = new Audio('data:audio/wav;base64,U3RlYWx0aCBpcyBub3QgcmVxdWlyZWQ=');
            audio.play();
        } catch(e) {}
    }
    
    function resetForm() {
        $('#rfidCardNumber').val('');
        $('#siswaInfo').hide();
        $('#absenResult').hide().empty();
        rfidBuffer = '';
        currentSiswaId = null;
    }
</script>
@endpush
@endsection