@extends('administrasi.layouts.header')

@section('title', 'Detail Kelas')

@section('content')
<style>
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }
    
    .stat-card h3 {
        font-size: 28px;
        margin-bottom: 5px;
        color: #667eea;
    }
    
    .stat-card p {
        margin-bottom: 0;
        color: #666;
    }
    
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-aktif {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }
    
    .info-card table td {
        color: white !important;
    }
    
    .info-card hr {
        border-color: rgba(255,255,255,0.2);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-school me-2"></i>
        Detail Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.kelas.edit', $kelas->id) }}" class="btn btn-sm btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('administrasi.kelas.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Info Card -->
        <div class="info-card">
            <div class="text-center mb-3">
                <i class="fas fa-school fa-3x"></i>
            </div>
            <h4 class="text-center">{{ $kelas->nama ?? $kelas->nama_kelas ?? $kelas->kelas ?? '-' }}</h4>
            <div class="text-center mt-2">
                @php
                    $statusKelas = $kelas->status ?? 'aktif';
                @endphp
                @if($statusKelas == 'aktif')
                    <span class="badge-status badge-aktif">✓ Aktif</span>
                @else
                    <span class="badge-status badge-nonaktif">✗ Non Aktif</span>
                @endif
            </div>
            <hr>
            <table class="table table-sm text-white">
                <tr>
                    <td width="40%"><i class="fas fa-layer-group me-2"></i> Tingkat</td>
                    <td width="10%">:</td>
                    <td>{{ $kelas->tingkat ?? '-' }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-graduation-cap me-2"></i> Jurusan</td>
                    <td>:</td>
                    <td>
                        {{-- PERBAIKAN UTAMA: Menampilkan nama jurusan dengan benar --}}
                        @if($kelas->jurusan)
                            {{ $kelas->jurusan->nama ?? $kelas->jurusan->nama_jurusan ?? 'Jurusan tidak lengkap' }}
                        @else
                            <span class="text-warning">Jurusan tidak ditemukan</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><i class="fas fa-chalkboard-user me-2"></i> Wali Kelas</td>
                    <td>:</td>
                    <td>
                        @if($kelas->waliKelas)
                            {{ $kelas->waliKelas->user->name ?? $kelas->waliKelas->nama_lengkap ?? $kelas->waliKelas->nama ?? '-' }}
                        @else
                            <span class="text-warning">Belum ditentukan</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><i class="fas fa-users me-2"></i> Kapasitas</td>
                    <td>:</td>
                    <td>{{ $kelas->kapasitas ?? 36 }} Siswa</td>
                </tr>
                <tr>
                    <td><i class="fas fa-calendar-alt me-2"></i> Tahun Ajaran</td>
                    <td>:</td>
                    <td>{{ $kelas->tahun_ajaran ?? date('Y') . '/' . (date('Y')+1) }}</td>
                </tr>
                <tr>
                    <td><i class="fas fa-hashtag me-2"></i> Kode Kelas</td>
                    <td>:</td>
                    <td>{{ $kelas->kode_kelas ?? '-' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Statistik Siswa -->
        <div class="card">
            <div class="card-header bg-white">
                <i class="fas fa-chart-pie me-2 text-primary"></i> 
                <strong>Statistik Siswa</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="stat-card">
                            <h3>{{ $totalSiswa ?? ($kelas->siswa ? $kelas->siswa->count() : 0) }}</h3>
                            <p>Total Siswa</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h3>{{ $siswaAktif ?? ($kelas->siswa ? $kelas->siswa->where('status', 'aktif')->count() : 0) }}</h3>
                            <p>Siswa Aktif</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h3>{{ $siswaLaki ?? ($kelas->siswa ? $kelas->siswa->where('jenis_kelamin', 'L')->count() : 0) }}</h3>
                            <p>Laki-laki</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h3>{{ $siswaPerempuan ?? ($kelas->siswa ? $kelas->siswa->where('jenis_kelamin', 'P')->count() : 0) }}</h3>
                            <p>Perempuan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Daftar Siswa -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2 text-primary"></i> 
                    <strong>Daftar Siswa</strong>
                </div>
                <span class="badge bg-primary">{{ $kelas->siswa ? $kelas->siswa->count() : 0 }} Siswa</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="siswaTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIS</th>
                                <th width="35%">Nama Siswa</th>
                                <th width="20%">Jenis Kelamin</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $siswaList = $kelas->siswa ?? collect();
                            @endphp
                            @forelse($siswaList as $key => $siswa)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $siswa->nis ?? $siswa->nis ?? '-' }}</td>
                                <td>
                                    {{ $siswa->user->name ?? $siswa->nama_lengkap ?? $siswa->nama ?? '-' }}
                                 </td>
                                <td>
                                    @php
                                        $jk = $siswa->jenis_kelamin ?? '';
                                    @endphp
                                    @if($jk == 'L')
                                        Laki-laki
                                    @elseif($jk == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                 </td>
                                <td>
                                    @php
                                        $statusSiswa = $siswa->status ?? 'aktif';
                                    @endphp
                                    @if($statusSiswa == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($statusSiswa == 'nonaktif')
                                        <span class="badge bg-danger">Non Aktif</span>
                                    @elseif($statusSiswa == 'lulus')
                                        <span class="badge bg-info">Lulus</span>
                                    @elseif($statusSiswa == 'dropout')
                                        <span class="badge bg-dark">Drop Out</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $statusSiswa }}</span>
                                    @endif
                                 </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-2x text-muted mb-2 d-block"></i>
                                    Belum ada siswa di kelas ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Informasi Jadwal -->
        <div class="card mt-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> 
                    <strong>Jadwal Pelajaran</strong>
                </div>
                <span class="badge bg-info">{{ $kelas->jadwal ? $kelas->jadwal->count() : 0 }} Jadwal</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="15%">Hari</th>
                                <th width="20%">Jam</th>
                                <th width="30%">Mata Pelajaran</th>
                                <th width="25%">Guru</th>
                                <th width="10%">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $jadwalList = $kelas->jadwal ?? collect();
                                $sortedJadwal = $jadwalList->sortBy(function($item) {
                                    $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    return array_search($item->hari, $hariOrder);
                                });
                            @endphp
                            @forelse($sortedJadwal as $jadwal)
                            <tr>
                                <td>{{ $jadwal->hari ?? '-' }}</td>
                                <td>
                                    @if($jadwal->jam_mulai && $jadwal->jam_selesai)
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    @else
                                        {{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}
                                    @endif
                                 </td>
                                <td>{{ $jadwal->mataPelajaran->nama_mapel ?? $jadwal->nama_mapel ?? '-' }}</td>
                                <td>
                                    @if($jadwal->guru)
                                        {{ $jadwal->guru->user->name ?? $jadwal->guru->nama_lengkap ?? $jadwal->guru->nama ?? '-' }}
                                    @else
                                        -
                                    @endif
                                 </td>
                                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-clock fa-2x text-muted mb-2 d-block"></i>
                                    Belum ada jadwal untuk kelas ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Cek apakah tabel siswa memiliki data
    var siswaTable = $('#siswaTable');
    if (siswaTable.find('tbody tr').length > 0 && siswaTable.find('tbody tr td[colspan]').length === 0) {
        siswaTable.DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                processing: "Sedang memproses...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Terakhir"
                }
            },
            pageLength: 10,
            responsive: true,
            ordering: true,
            columnDefs: [
                { orderable: false, targets: 0 } // No column tidak bisa di-sort
            ]
        });
    }
});
</script>
@endpush