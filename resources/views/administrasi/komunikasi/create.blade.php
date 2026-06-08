@extends('administrasi.layouts.header')

@section('title', 'Tulis Pesan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-pen me-2"></i>
        Tulis Pesan Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.komunikasi.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.komunikasi.store') }}" method="POST" id="formKirimPesan">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Judul Pesan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tipe Pesan <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis" id="tipeIndividual" value="personal" {{ old('jenis', 'personal') == 'personal' ? 'checked' : '' }}>
                    <label class="form-check-label" for="tipeIndividual">
                        Pesan Individual
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis" id="tipeBroadcast" value="broadcast" {{ old('jenis') == 'broadcast' ? 'checked' : '' }}>
                    <label class="form-check-label" for="tipeBroadcast">
                        Broadcast (Semua Pengguna)
                    </label>
                </div>
            </div>
            
            <div class="mb-3" id="penerimaSection">
                <label class="form-label">Pilih Penerima <span class="text-danger">*</span></label>
                
                <div id="penerimaError" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> Silakan pilih minimal satu penerima!
                </div>
                
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#siswaTab">Siswa <span id="siswaCount" class="badge bg-secondary">0</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#guruTab">Guru <span id="guruCount" class="badge bg-secondary">0</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#adminTab">Administrasi <span id="adminCount" class="badge bg-secondary">0</span></a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Tab Siswa -->
                    <div class="tab-pane fade show active" id="siswaTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllSiswa" onchange="selectAll('siswa-checkbox', this.checked)">
                                    Pilih Semua Siswa
                                </label>
                            </div>
                            @forelse($siswa as $s)
                                @if($s && $s->user)
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox siswa-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="{{ $s->user->id }}"
                                           id="siswa_{{ $s->user->id }}"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="siswa_{{ $s->user->id }}">
                                        {{ $s->nis ?? '-' }} - {{ $s->user->name ?? '-' }}
                                        @if(isset($s->kelas))
                                            ({{ $s->kelas->nama_kelas ?? $s->kelas->nama ?? '-' }})
                                        @endif
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-muted">Tidak ada data siswa</div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Tab Guru -->
                    <div class="tab-pane fade" id="guruTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllGuru" onchange="selectAll('guru-checkbox', this.checked)">
                                    Pilih Semua Guru
                                </label>
                            </div>
                            @forelse($guru as $g)
                                @if($g && $g->user)
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox guru-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="{{ $g->user->id }}"
                                           id="guru_{{ $g->user->id }}"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="guru_{{ $g->user->id }}">
                                        {{ $g->nip ?? '-' }} - {{ $g->user->name ?? '-' }}
                                        @if(isset($g->mata_pelajaran))
                                            ({{ $g->mata_pelajaran }})
                                        @endif
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-muted">Tidak ada data guru</div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Tab Administrasi -->
                    <div class="tab-pane fade" id="adminTab">
                        <div class="border rounded p-3" style="max-height: 350px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="fw-bold">
                                    <input type="checkbox" id="selectAllAdmin" onchange="selectAll('admin-checkbox', this.checked)">
                                    Pilih Semua Administrasi
                                </label>
                            </div>
                            @forelse($administrasi as $a)
                                @if($a && $a->user)
                                <div class="form-check">
                                    <input class="form-check-input penerima-checkbox admin-checkbox" 
                                           type="checkbox" 
                                           name="penerima_id[]" 
                                           value="{{ $a->user->id }}"
                                           id="admin_{{ $a->user->id }}"
                                           onchange="updateCount()">
                                    <label class="form-check-label" for="admin_{{ $a->user->id }}">
                                        {{ $a->user->name ?? '-' }}
                                    </label>
                                </div>
                                @endif
                            @empty
                                <div class="text-muted">Tidak ada data administrasi</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="mt-3" id="selectedInfo" style="display: none;">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <span id="totalCount">0</span> penerima telah dipilih
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5" required>{{ old('isi') }}</textarea>
                @error('isi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_urgent" id="isPenting" value="1">
                    <label class="form-check-label" for="isPenting">
                        Tandai sebagai pesan penting
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-primary" id="btnKirim">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Fungsi update jumlah penerima yang dipilih
function updateCount() {
    // Hitung semua checkbox yang tercentang
    var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
    
    // Hitung per kategori
    var siswaChecked = document.querySelectorAll('.siswa-checkbox:checked').length;
    var guruChecked = document.querySelectorAll('.guru-checkbox:checked').length;
    var adminChecked = document.querySelectorAll('.admin-checkbox:checked').length;
    
    // Update badge
    document.getElementById('siswaCount').innerText = siswaChecked;
    document.getElementById('guruCount').innerText = guruChecked;
    document.getElementById('adminCount').innerText = adminChecked;
    document.getElementById('totalCount').innerText = totalChecked;
    
    // Tampilkan/sembunyikan info
    var selectedInfo = document.getElementById('selectedInfo');
    if (totalChecked > 0) {
        selectedInfo.style.display = 'block';
        document.getElementById('penerimaError').style.display = 'none';
    } else {
        selectedInfo.style.display = 'none';
    }
    
    // Update status checkbox "Pilih Semua"
    updateSelectAllStatus();
    
    // Debug
    console.log('Total checked:', totalChecked);
}

// Fungsi untuk select all per kategori
function selectAll(className, isChecked) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(cb) {
        cb.checked = isChecked;
    });
    updateCount();
}

// Update status checkbox "Pilih Semua"
function updateSelectAllStatus() {
    // Siswa
    var siswaTotal = document.querySelectorAll('.siswa-checkbox').length;
    var siswaChecked = document.querySelectorAll('.siswa-checkbox:checked').length;
    var selectAllSiswa = document.getElementById('selectAllSiswa');
    if (selectAllSiswa && siswaTotal > 0) {
        selectAllSiswa.checked = (siswaTotal === siswaChecked);
    }
    
    // Guru
    var guruTotal = document.querySelectorAll('.guru-checkbox').length;
    var guruChecked = document.querySelectorAll('.guru-checkbox:checked').length;
    var selectAllGuru = document.getElementById('selectAllGuru');
    if (selectAllGuru && guruTotal > 0) {
        selectAllGuru.checked = (guruTotal === guruChecked);
    }
    
    // Admin
    var adminTotal = document.querySelectorAll('.admin-checkbox').length;
    var adminChecked = document.querySelectorAll('.admin-checkbox:checked').length;
    var selectAllAdmin = document.getElementById('selectAllAdmin');
    if (selectAllAdmin && adminTotal > 0) {
        selectAllAdmin.checked = (adminTotal === adminChecked);
    }
}

// Toggle section penerima berdasarkan tipe pesan
function togglePenerimaSection() {
    var individual = document.getElementById('tipeIndividual');
    var broadcast = document.getElementById('tipeBroadcast');
    var section = document.getElementById('penerimaSection');
    
    if (broadcast && broadcast.checked) {
        section.style.display = 'none';
    } else {
        section.style.display = 'block';
    }
}

// Validasi sebelum submit
function validateForm() {
    var broadcast = document.getElementById('tipeBroadcast');
    
    // Jika broadcast, tidak perlu validasi penerima
    if (broadcast && broadcast.checked) {
        return true;
    }
    
    // Jika individual, cek apakah ada penerima terpilih
    var totalChecked = document.querySelectorAll('input[name="penerima_id[]"]:checked').length;
    
    if (totalChecked === 0) {
        document.getElementById('penerimaError').style.display = 'block';
        document.getElementById('penerimaSection').scrollIntoView({ behavior: 'smooth' });
        return false;
    }
    
    return true;
}

// Reset form
function resetForm() {
    var checkboxes = document.querySelectorAll('input[name="penerima_id[]"]');
    checkboxes.forEach(function(cb) {
        cb.checked = false;
    });
    updateCount();
    document.getElementById('penerimaError').style.display = 'none';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup radio button event
    var individual = document.getElementById('tipeIndividual');
    var broadcast = document.getElementById('tipeBroadcast');
    
    if (individual) {
        individual.addEventListener('change', togglePenerimaSection);
    }
    if (broadcast) {
        broadcast.addEventListener('change', togglePenerimaSection);
    }
    
    // Setup form submit
    var form = document.getElementById('formKirimPesan');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            } else {
                var btn = document.getElementById('btnKirim');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            }
        });
    }
    
    // Initial update
    togglePenerimaSection();
    updateCount();
});
</script>
@endsection