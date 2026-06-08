@extends('administrasi.layouts.header')

@section('title', 'Input Pembayaran Lain')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle me-2"></i>
        Input Pembayaran Lain
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- PERBAIKAN: Gunakan route index dengan .index -->
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.keuangan.pembayaran-lain.store') }}" method="POST" id="formPembayaranLain">
            @csrf
            
            <div class="row">
                <!-- Pencarian NIS -->
                <div class="col-md-12 mb-3">
                    <div class="card bg-primary bg-opacity-10">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search"></i> Cari berdasarkan NIS
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" name="nis" id="nis" class="form-control form-control-lg" 
                                               placeholder="Masukkan NIS siswa..." autocomplete="off" value="{{ old('nis') }}">
                                        <button type="button" class="btn btn-primary" id="btnCariNis">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                    <small class="text-muted">Masukkan NIS dan klik cari, maka data siswa dan kelas akan otomatis terisi</small>
                                </div>
                                <div class="col-md-6" id="infoSiswa" style="display: none;">
                                    <div class="alert alert-success mt-2 mt-md-0 mb-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-user-graduate fa-2x"></i>
                                            </div>
                                            <div>
                                                <strong id="infoNama"></strong><br>
                                                <small><i class="fas fa-school"></i> Kelas: <span id="infoKelas"></span></small><br>
                                                <small><i class="fas fa-id-card"></i> NIS: <span id="infoNIS"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-school"></i> Pilih Kelas 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @if(isset($kelas) && $kelas->count() > 0)
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" 
                                        data-nama-kelas="{{ $k->nama_kelas ?? $k->nama ?? $k->kelas ?? 'Kelas ' . $k->id }}">
                                    {{ $k->nama_kelas ?? $k->nama ?? $k->kelas ?? 'Kelas ' . $k->id }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Tidak ada data kelas</option>
                        @endif
                    </select>
                    <small class="text-muted">Kelas akan otomatis terisi setelah NIS ditemukan</small>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pilih Siswa -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Siswa 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @if(isset($siswa) && $siswa->count() > 0)
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}" 
                                        data-nis="{{ $s->nis }}" 
                                        data-kelas-id="{{ $s->kelas_id }}"
                                        data-kelas="{{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? '-' }}"
                                        {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis }} - {{ $s->user->name ?? $s->nama_lengkap }} 
                                    ({{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? 'Tidak ada kelas' }})
                                </option>
                            @endforeach
                        @else
                            <option value="">Tidak ada data siswa aktif</option>
                        @endif
                    </select>
                    <small class="text-muted">Siswa akan otomatis terisi setelah NIS ditemukan</small>
                    @error('siswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags"></i> Kategori Pembayaran 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Pendaftaran" {{ old('kategori') == 'Pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                        <option value="Uang Gedung" {{ old('kategori') == 'Uang Gedung' ? 'selected' : '' }}>Uang Gedung</option>
                        <option value="Laboratorium" {{ old('kategori') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                        <option value="Perpustakaan" {{ old('kategori') == 'Perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                        <option value="Ekstrakurikuler" {{ old('kategori') == 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                        <option value="Seragam" {{ old('kategori') == 'Seragam' ? 'selected' : '' }}>Seragam</option>
                        <option value="Buku" {{ old('kategori') == 'Buku' ? 'selected' : '' }}>Buku</option>
                        <option value="Kegiatan" {{ old('kategori') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="Lain-lain" {{ old('kategori') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-money-bill-wave"></i> Jumlah 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               placeholder="Masukkan jumlah" value="{{ old('jumlah') }}" required min="1000">
                    </div>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="metode_bayar" id="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                        <option value="">Pilih Metode</option>
                        <option value="Tunai" {{ old('metode_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Virtual Account" {{ old('metode_bayar') == 'Virtual Account' ? 'selected' : '' }}>Virtual Account</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="EDC" {{ old('metode_bayar') == 'EDC' ? 'selected' : '' }}>EDC</option>
                    </select>
                    @error('metode_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Tanggal Bayar
                    </label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                           value="{{ old('tanggal_bayar', date('Y-m-d')) }}">
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle"></i> Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" 
                              placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary" onclick="resetForm()">
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
$(document).ready(function() {
    let originalSiswaOptions = $('#siswa_id').html();
    
    $('#siswa_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kelasId = selectedOption.data('kelas-id');
        var nis = selectedOption.data('nis');
        var nama = selectedOption.text().split(' - ')[1];
        var kelas = selectedOption.data('kelas');
        
        if ($(this).val() && $(this).val() !== '') {
            $('#infoSiswa').show();
            $('#infoNama').text(nama || '');
            $('#infoKelas').text(kelas || '-');
            $('#infoNIS').text(nis || '-');
            
            if (kelasId && kelasId !== '') {
                $('#kelas_id').val(kelasId);
            }
            if (nis) {
                $('#nis').val(nis);
            }
        } else {
            $('#infoSiswa').hide();
        }
    });
    
    $('#btnCariNis').on('click', function() {
        var nis = $('#nis').val();
        
        if (!nis) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Masukkan NIS terlebih dahulu!'
            });
            $('#nis').focus();
            return;
        }
        
        $('#btnCariNis').html('<i class="fas fa-spinner fa-spin"></i> Mencari...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("administrasi.keuangan.cari-siswa") }}',
            type: 'GET',
            data: {nis: nis},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var siswa = response.data;
                    
                    $('#infoSiswa').show();
                    $('#infoNama').text(siswa.nama);
                    $('#infoKelas').text(siswa.kelas_nama || 'Tidak ada kelas');
                    $('#infoNIS').text('NIS: ' + siswa.nis);
                    
                    if (siswa.kelas_id && siswa.kelas_id !== null) {
                        $('#kelas_id').val(siswa.kelas_id);
                        $('#kelas_id').trigger('change');
                        
                        setTimeout(function() {
                            $('#siswa_id').val(siswa.id).trigger('change');
                        }, 500);
                    } else {
                        $('#siswa_id').val(siswa.id).trigger('change');
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Siswa Ditemukan',
                        html: `<strong>${siswa.nama}</strong><br>Kelas: ${siswa.kelas_nama || 'Tidak ada kelas'}<br>NIS: ${siswa.nis}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#kategori').focus();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Siswa Tidak Ditemukan',
                        text: response.message || 'Siswa dengan NIS ' + nis + ' tidak ditemukan!'
                    });
                    $('#infoSiswa').hide();
                    $('#nis').focus();
                    $('#nis').select();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal mencari siswa!'
                });
            },
            complete: function() {
                $('#btnCariNis').html('<i class="fas fa-search"></i> Cari').prop('disabled', false);
            }
        });
    });
    
    $('#kelas_id').on('change', function() {
        var kelasId = $(this).val();
        var siswaSelect = $('#siswa_id');
        
        if (kelasId && kelasId !== '') {
            var filteredOptions = '<option value="">-- Pilih Siswa --</option>';
            $(originalSiswaOptions).filter('option').each(function() {
                if ($(this).val() !== '' && $(this).data('kelas-id') == kelasId) {
                    filteredOptions += '<option value="' + $(this).val() + '" data-nis="' + $(this).data('nis') + '" data-kelas-id="' + $(this).data('kelas-id') + '" data-kelas="' + $(this).data('kelas') + '">' + $(this).text() + '</option>';
                }
            });
            siswaSelect.html(filteredOptions);
        } else {
            siswaSelect.html(originalSiswaOptions);
        }
        
        $('#infoSiswa').hide();
        $('#nis').val('');
    });
    
    $('#nis').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btnCariNis').click();
        }
    });
    
    $('#formPembayaranLain').on('submit', function(e) {
        var siswa = $('#siswa_id').val();
        var kategori = $('#kategori').val();
        var jumlah = $('#jumlah').val();
        var metode = $('#metode_bayar').val();
        
        if (!siswa) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih siswa terlebih dahulu!' });
            return false;
        }
        if (!kategori) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih kategori pembayaran!' });
            $('#kategori').focus();
            return false;
        }
        if (!jumlah || jumlah < 1000) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Jumlah pembayaran minimal Rp 1.000!' });
            $('#jumlah').focus();
            return false;
        }
        if (!metode) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Validasi', text: 'Silakan pilih metode pembayaran!' });
            $('#metode_bayar').focus();
            return false;
        }
        
        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        return true;
    });
});

function resetForm() {
    $('#kelas_id').val('');
    $('#siswa_id').val('');
    $('#kategori').val('');
    $('#jumlah').val('');
    $('#metode_bayar').val('');
    $('#tanggal_bayar').val('{{ date("Y-m-d") }}');
    $('#keterangan').val('');
    $('#nis').val('');
    $('#infoSiswa').hide();
    $('#siswa_id').html($('#siswaSelectOriginal').html());
    $('#nis').focus();
}
</script>

<select id="siswaSelectOriginal" style="display: none;">
    @if(isset($siswa) && $siswa->count() > 0)
        @foreach($siswa as $s)
            <option value="{{ $s->id }}" 
                    data-nis="{{ $s->nis }}" 
                    data-kelas-id="{{ $s->kelas_id }}"
                    data-kelas="{{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? '-' }}">
                {{ $s->nis }} - {{ $s->user->name ?? $s->nama_lengkap }} 
                ({{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? $s->kelas->kelas ?? 'Tidak ada kelas' }})
            </option>
        @endforeach
    @endif
</select>
@endpush
@endsection