@extends('administrasi.layouts.header')

@section('title', 'Scan RFID - Absensi')

@section('content')
<style>
    .rfid-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }
    .rfid-input {
        font-size: 1.5rem;
        text-align: center;
        letter-spacing: 5px;
    }
    .scan-area {
        border: 3px dashed #ccc;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .scan-area:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .result-card {
        margin-top: 20px;
        display: none;
    }
    .result-card.show {
        display: block;
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .beep {
        animation: beep-animation 0.5s;
    }
    @keyframes beep-animation {
        0% { background-color: #fff; }
        50% { background-color: #d4edda; }
        100% { background-color: #fff; }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-rfid me-2"></i>
        Scan RFID - Absensi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-primary" id="btnSiswa">Siswa</button>
            <button type="button" class="btn btn-sm btn-secondary" id="btnGuru">Guru</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="rfid-container">
            <!-- Scan Area -->
            <div class="scan-area" id="scanArea">
                <i class="fas fa-rfid fa-4x text-muted mb-3"></i>
                <h4>Scan Kartu RFID</h4>
                <p class="text-muted">Dekatkan kartu ke pembaca RFID</p>
                <input type="text" 
                       id="rfidInput" 
                       class="form-control rfid-input" 
                       placeholder="Masukkan nomor RFID"
                       autofocus>
                <small class="text-muted mt-2 d-block">Atau ketik nomor RFID manual</small>
            </div>
            
            <!-- Result Card -->
            <div class="result-card card" id="resultCard">
                <div class="card-body text-center">
                    <div id="resultIcon">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h4 id="resultStatus" class="mt-2">Absensi Berhasil!</h4>
                    <div id="resultContent"></div>
                    <hr>
                    <p class="text-muted" id="resultMessage"></p>
                    <button class="btn btn-primary btn-sm" onclick="scanAgain()">Scan Lagi</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentType = 'siswa';
    let timeoutId = null;
    
    $(document).ready(function() {
        $('#rfidInput').focus();
        
        $('#btnSiswa').click(function() {
            currentType = 'siswa';
            $(this).addClass('btn-primary').removeClass('btn-secondary');
            $('#btnGuru').addClass('btn-secondary').removeClass('btn-primary');
            $('#rfidInput').val('');
            $('#rfidInput').focus();
        });
        
        $('#btnGuru').click(function() {
            currentType = 'guru';
            $(this).addClass('btn-primary').removeClass('btn-secondary');
            $('#btnSiswa').addClass('btn-secondary').removeClass('btn-primary');
            $('#rfidInput').val('');
            $('#rfidInput').focus();
        });
        
        $('#rfidInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                var rfid = $(this).val();
                if (rfid) {
                    processScan(rfid);
                }
            }
        });
        
        let buffer = '';
        $('#rfidInput').on('keyup', function(e) {
            clearTimeout(timeoutId);
            buffer += e.key;
            timeoutId = setTimeout(function() {
                if (buffer.length > 5) {
                    processScan(buffer);
                }
                buffer = '';
            }, 100);
        });
        
        $('#scanArea').click(function() {
            $('#rfidInput').focus();
        });
    });
    
    function processScan(rfid) {
        var tanggal = new Date().toISOString().split('T')[0];
        
        $.ajax({
            url: '{{ url("administrasi/rfid/scan") }}/' + currentType,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rfid: rfid,
                tanggal: tanggal
            },
            success: function(response) {
                if (response.success) {
                    showResult(true, response);
                    playBeep('success');
                } else {
                    showResult(false, response);
                    playBeep('error');
                }
                $('#rfidInput').val('');
                setTimeout(function() {
                    $('#resultCard').removeClass('show');
                    $('#rfidInput').focus();
                }, 3000);
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                showResult(false, response);
                playBeep('error');
                $('#rfidInput').val('');
                setTimeout(function() {
                    $('#resultCard').removeClass('show');
                    $('#rfidInput').focus();
                }, 3000);
            }
        });
    }
    
    function showResult(success, data) {
        if (success && data.data) {
            var html = '';
            if (currentType === 'siswa') {
                html = `
                    <p><strong>NIS:</strong> ${data.data.nis || '-'}</p>
                    <p><strong>Nama:</strong> ${data.data.nama || '-'}</p>
                    <p><strong>Kelas:</strong> ${data.data.kelas || '-'}</p>
                    <p><strong>Status:</strong> ${data.data.status || 'Hadir'}</p>
                    <p><strong>Jam:</strong> ${data.data.waktu_masuk || '-'}</p>
                `;
            } else {
                html = `
                    <p><strong>NIP:</strong> ${data.data.nip || '-'}</p>
                    <p><strong>Nama:</strong> ${data.data.nama || '-'}</p>
                    <p><strong>Status:</strong> ${data.data.status || 'Hadir'}</p>
                    <p><strong>Jam:</strong> ${data.data.waktu_masuk || '-'}</p>
                `;
            }
            $('#resultContent').html(html);
            $('#resultStatus').html('<i class="fas fa-check-circle"></i> Absensi Berhasil');
            $('#resultStatus').css('color', '#28a745');
            $('#resultIcon').html('<i class="fas fa-check-circle fa-3x text-success"></i>');
            data.message && $('#resultMessage').text(data.message);
        } else {
            $('#resultContent').html('<p class="text-danger">' + (data.message || 'RFID tidak terdaftar!') + '</p>');
            $('#resultStatus').html('<i class="fas fa-times-circle"></i> Absensi Gagal');
            $('#resultStatus').css('color', '#dc3545');
            $('#resultIcon').html('<i class="fas fa-times-circle fa-3x text-danger"></i>');
            $('#resultMessage').text('Silakan coba lagi');
        }
        $('#resultCard').addClass('show').addClass('beep');
        setTimeout(function() {
            $('#resultCard').removeClass('beep');
        }, 500);
    }
    
    function playBeep(type) {
        try {
            var audio = new Audio();
            if (type === 'success') {
                audio.src = 'https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3';
            } else {
                audio.src = 'https://www.soundjay.com/misc/sounds/error-01.mp3';
            }
            audio.play().catch(function(e) {
                console.log('Audio not supported');
            });
        } catch(e) {
            console.log('Beep not supported');
        }
    }
    
    function scanAgain() {
        $('#resultCard').removeClass('show');
        $('#rfidInput').val('');
        $('#rfidInput').focus();
    }
</script>
@endpush
@endsection