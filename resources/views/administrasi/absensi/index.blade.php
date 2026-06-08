@extends('administrasi.layouts.header')

@section('title', 'Data Guru')

@section('content')
<style>
    /* Style untuk tab absensi sholat */
    .nav-tabs-custom {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #667eea;
        border-bottom: 2px solid #667eea;
    }
    .nav-tabs-custom .nav-link.active {
        color: #667eea;
        border-bottom: 2px solid #667eea;
        background: transparent;
    }
    
    /* Style untuk statistik sholat */
    .stats-sholat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 15px;
        color: white;
        margin-bottom: 15px;
        transition: transform 0.3s;
    }
    .stats-sholat-card:hover {
        transform: translateY(-3px);
    }
    .stats-sholat-number {
        font-size: 28px;
        font-weight: bold;
    }
    
    /* Style untuk tabel sholat */
    .sholat-badge-tepat-waktu {
        background: #28a745;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
    }
    .sholat-badge-terlambat {
        background: #ffc107;
        color: #333;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
    }
    .sholat-badge-tidak-hadir {
        background: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
    }
    .sholat-badge-izin {
        background: #17a2b8;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
    }
    
    .rekap-table th {
        background: #f8f9fa;
        vertical-align: middle;
    }
    .progress-sholat {
        height: 6px;
        border-radius: 3px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-user me-2"></i>
        Data Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.guru.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Guru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Tabs Navigation -->
<ul class="nav nav-tabs-custom" id="guruTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="data-guru-tab" data-bs-toggle="tab" data-bs-target="#dataGuru" type="button" role="tab">
            <i class="fas fa-users me-2"></i> Data Guru
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="absensi-sholat-tab" data-bs-toggle="tab" data-bs-target="#absensiSholat" type="button" role="tab">
            <i class="fas fa-mosque me-2"></i> Absensi Sholat Hari Ini
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="rekap-sholat-tab" data-bs-toggle="tab" data-bs-target="#rekapSholat" type="button" role="tab">
            <i class="fas fa-chart-line me-2"></i> Rekap Sholat Bulanan
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="guruTabsContent">
    
    <!-- Tab 1: Data Guru (Original Content) -->
    <div class="tab-pane fade show active" id="dataGuru" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i> Daftar Guru
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari guru...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="guruTable">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">NIP</th>
                                <th width="20%">Nama Guru</th>
                                <th width="15%">Email</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="15%">Mata Pelajaran</th>
                                <th width="10%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $index => $g)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $g->nip }}</td>
                                <td>
                                    <strong>{{ $g->nama_lengkap }}</strong>
                                    <br>
                                    <small class="text-muted">NUPTK: {{ $g->nuptk ?? '-' }}</small>
                                </td>
                                <td>{{ $g->user->email ?? '-' }}</td>
                                <td class="text-center">
                                    {{ $g->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </td>
                                <td>{{ $g->mata_pelajaran_utama ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $g->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($g->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('administrasi.guru.show', $g->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('administrasi.guru.edit', $g->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteGuru({{ $g->id }})"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-chalkboard-user fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data guru</p>
                                    <a href="{{ route('administrasi.guru.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Tambah Guru Sekarang
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 2: Absensi Sholat Hari Ini -->
    <div class="tab-pane fade" id="absensiSholat" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="fas fa-mosque me-2"></i> Absensi Sholat Guru Hari Ini
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <input type="date" id="filterTanggal" class="form-control" value="{{ date('Y-m-d') }}">
                            <button class="btn btn-primary" onclick="loadAbsensiSholat()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4" id="statsSholat">
                    <div class="col-md-3">
                        <div class="stats-sholat-card">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <div class="stats-sholat-number" id="totalGuru">0</div>
                            <small>Total Guru</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-sholat-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div class="stats-sholat-number" id="totalHadir">0</div>
                            <small>Hadir (Tepat Waktu)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-sholat-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <div class="stats-sholat-number" id="totalTerlambat">0</div>
                            <small>Terlambat</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-sholat-card" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <div class="stats-sholat-number" id="totalTidakHadir">0</div>
                            <small>Tidak Hadir</small>
                        </div>
                    </div>
                </div>
                
                <!-- Jadwal Sholat -->
                <div class="alert alert-info mb-4">
                    <div class="row text-center">
                        <div class="col">
                            <strong>Subuh</strong><br>
                            <span id="jadwalSubuh">04:30</span>
                        </div>
                        <div class="col">
                            <strong>Dzuhur</strong><br>
                            <span id="jadwalDzuhur">12:00</span>
                        </div>
                        <div class="col">
                            <strong>Ashar</strong><br>
                            <span id="jadwalAshar">15:30</span>
                        </div>
                        <div class="col">
                            <strong>Maghrib</strong><br>
                            <span id="jadwalMaghrib">18:00</span>
                        </div>
                        <div class="col">
                            <strong>Isya</strong><br>
                            <span id="jadwalIsya">19:30</span>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Absensi Per Sholat -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="20%">Nama Guru</th>
                                <th width="16%" class="text-center">Subuh</th>
                                <th width="16%" class="text-center">Dzuhur</th>
                                <th width="16%" class="text-center">Ashar</th>
                                <th width="16%" class="text-center">Maghrib</th>
                                <th width="16%" class="text-center">Isya</th>
                            </tr>
                        </thead>
                        <tbody id="absensiSholatTable">
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Loading data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 3: Rekap Sholat Bulanan -->
    <div class="tab-pane fade" id="rekapSholat" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i> Rekap Absensi Sholat Guru
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="row g-2">
                            <div class="col-auto">
                                <select id="filterBulan" class="form-select form-select-sm">
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto">
                                <select id="filterTahun" class="form-select form-select-sm">
                                    @for($i = date('Y')-2; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" onclick="loadRekapSholat()">
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success btn-sm" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover rekap-table" id="rekapSholatTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Guru</th>
                                <th class="text-center">Subuh</th>
                                <th class="text-center">Dzuhur</th>
                                <th class="text-center">Ashar</th>
                                <th class="text-center">Maghrib</th>
                                <th class="text-center">Isya</th>
                                <th class="text-center">Total Hadir</th>
                                <th class="text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody id="rekapSholatBody">
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Loading data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data guru ini?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    var dataTable;
    
    $(document).ready(function() {
        // Initialize DataTable
        dataTable = $('#guruTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });
        
        // Search functionality
        $('#searchInput').on('keyup', function() {
            dataTable.search($(this).val()).draw();
        });
        
        // Load absensi sholat when tab is clicked
        $('#absensi-sholat-tab').on('click', function() {
            loadAbsensiSholat();
        });
        
        // Load rekap sholat when tab is clicked
        $('#rekap-sholat-tab').on('click', function() {
            loadRekapSholat();
        });
    });
    
    function deleteGuru(id) {
        var form = document.getElementById('deleteForm');
        form.action = '/administrasi/guru/' + id;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    // Load Absensi Sholat
    function loadAbsensiSholat() {
        var tanggal = $('#filterTanggal').val();
        
        $.ajax({
            url: '{{ route("absensi-sholat.get-data") }}',
            method: 'GET',
            data: {
                role: 'guru',
                tanggal: tanggal
            },
            success: function(response) {
                if (response.success) {
                    updateStatsSholat(response.statistik);
                    updateJadwalSholat(response.jadwal);
                    updateAbsensiTable(response.data);
                } else {
                    showError('Gagal memuat data absensi');
                }
            },
            error: function() {
                showError('Terjadi kesalahan saat memuat data');
            }
        });
    }
    
    // Load Rekap Sholat
    function loadRekapSholat() {
        var bulan = $('#filterBulan').val();
        var tahun = $('#filterTahun').val();
        
        $.ajax({
            url: '{{ route("absensi-sholat.rekap-data") }}',
            method: 'GET',
            data: {
                role: 'guru',
                bulan: bulan,
                tahun: tahun
            },
            success: function(response) {
                if (response.success) {
                    updateRekapTable(response.data);
                } else {
                    showError('Gagal memuat data rekap');
                }
            },
            error: function() {
                showError('Terjadi kesalahan saat memuat data rekap');
            }
        });
    }
    
    function updateStatsSholat(statistik) {
        $('#totalGuru').text(statistik.totalUsers || 0);
        $('#totalHadir').text(statistik.tepatWaktu || 0);
        $('#totalTerlambat').text(statistik.terlambat || 0);
        $('#totalTidakHadir').text(statistik.tidakHadir || 0);
    }
    
    function updateJadwalSholat(jadwal) {
        $('#jadwalSubuh').text(jadwal.subuh || '04:30');
        $('#jadwalDzuhur').text(jadwal.dzuhur || '12:00');
        $('#jadwalAshar').text(jadwal.ashar || '15:30');
        $('#jadwalMaghrib').text(jadwal.maghrib || '18:00');
        $('#jadwalIsya').text(jadwal.isya || '19:30');
    }
    
    function updateAbsensiTable(data) {
        var html = '';
        
        if (data.length === 0) {
            html = '<tr><td colspan="6" class="text-center text-muted">Belum ada data absensi untuk tanggal ini</td></tr>';
        } else {
            $.each(data, function(index, guru) {
                html += '<tr>';
                html += '<td><strong>' + guru.nama + '</strong></td>';
                html += '<td class="text-center">' + getSholatStatus(guru.absensi, 'subuh') + '</td>';
                html += '<td class="text-center">' + getSholatStatus(guru.absensi, 'dzuhur') + '</td>';
                html += '<td class="text-center">' + getSholatStatus(guru.absensi, 'ashar') + '</td>';
                html += '<td class="text-center">' + getSholatStatus(guru.absensi, 'maghrib') + '</td>';
                html += '<td class="text-center">' + getSholatStatus(guru.absensi, 'isya') + '</td>';
                html += '</tr>';
            });
        }
        
        $('#absensiSholatTable').html(html);
    }
    
    function getSholatStatus(absensi, sholat) {
        var item = absensi.find(a => a.sholat === sholat);
        
        if (!item) {
            return '<span class="badge bg-secondary">Belum</span>';
        }
        
        if (item.status === 'tepat_waktu') {
            return '<span class="sholat-badge-tepat-waktu">✓ Tepat Waktu<br><small>' + item.waktu + '</small></span>';
        } else if (item.status === 'terlambat') {
            return '<span class="sholat-badge-terlambat">⚠ Terlambat<br><small>' + item.waktu + '</small></span>';
        } else if (item.status === 'izin') {
            return '<span class="sholat-badge-izin">Izin</span>';
        } else {
            return '<span class="sholat-badge-tidak-hadir">✗ Tidak Hadir</span>';
        }
    }
    
    function updateRekapTable(data) {
        var html = '';
        
        if (data.length === 0) {
            html = '<tr><td colspan="10" class="text-center text-muted">Belum ada data rekap untuk periode ini</td></tr>';
        } else {
            $.each(data, function(index, guru) {
                var persentase = guru.total_hadir > 0 ? ((guru.total_hadir / 25) * 100).toFixed(2) : 0;
                var warna = persentase >= 80 ? 'success' : (persentase >= 60 ? 'warning' : 'danger');
                
                html += '<tr>';
                html += '<td>' + (index + 1) + '</td>';
                html += '<td>' + (guru.nip || '-') + '</td>';
                html += '<td><strong>' + guru.nama + '</strong></td>';
                html += '<td class="text-center">' + getRekapStatus(guru.absensi, 'subuh') + '</td>';
                html += '<td class="text-center">' + getRekapStatus(guru.absensi, 'dzuhur') + '</td>';
                html += '<td class="text-center">' + getRekapStatus(guru.absensi, 'ashar') + '</td>';
                html += '<td class="text-center">' + getRekapStatus(guru.absensi, 'maghrib') + '</td>';
                html += '<td class="text-center">' + getRekapStatus(guru.absensi, 'isya') + '</td>';
                html += '<td class="text-center"><span class="badge bg-primary">' + (guru.total_hadir || 0) + '</span></td>';
                html += '<td class="text-center">';
                html += '<div class="progress progress-sholat">';
                html += '<div class="progress-bar bg-' + warna + '" style="width: ' + persentase + '%"></div>';
                html += '</div>';
                html += '<small>' + persentase + '%</small>';
                html += '</td>';
                html += '</tr>';
            });
        }
        
        $('#rekapSholatBody').html(html);
    }
    
    function getRekapStatus(absensi, sholat) {
        var item = absensi.find(a => a.sholat === sholat);
        
        if (!item) {
            return '<span class="badge bg-secondary">-</span>';
        }
        
        if (item.status === 'tepat_waktu') {
            return '<span class="badge bg-success">✓</span>';
        } else if (item.status === 'terlambat') {
            return '<span class="badge bg-warning text-dark">⚠</span>';
        } else if (item.status === 'izin') {
            return '<span class="badge bg-info">I</span>';
        } else {
            return '<span class="badge bg-danger">✗</span>';
        }
    }
    
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonColor: '#d33'
        });
    }
</script>
@endpush
@endsection