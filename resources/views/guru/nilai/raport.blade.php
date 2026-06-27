@extends('guru.layouts.header')

@section('title', 'Raport Siswa')

@section('content')
<style>
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table-raport th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding: 8px 6px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-raport td {
        padding: 8px 6px;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    .table-raport tbody tr:hover {
        background-color: #f0f7ff;
    }
    .table-raport .nilai-akhir {
        font-weight: 700;
        font-size: 0.95rem;
    }
    .table-raport .nilai-tinggi {
        color: #198754;
    }
    .table-raport .nilai-sedang {
        color: #ffc107;
    }
    .table-raport .nilai-rendah {
        color: #dc3545;
    }
    .table-raport .text-left {
        text-align: left !important;
    }
    .info-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .info-header .label {
        font-size: 0.75rem;
        opacity: 0.85;
        font-weight: 300;
    }
    .info-header .value {
        font-size: 0.95rem;
        font-weight: 600;
    }
    .btn-action {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .badge-kkm {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 20px;
        background: #e9ecef;
        color: #495057;
    }
    .badge-kkm.lulus {
        background: #d4edda;
        color: #155724;
    }
    .badge-kkm.tidak {
        background: #f8d7da;
        color: #721c24;
    }
    .table-wrapper {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    .table-wrapper table {
        margin-bottom: 0;
        width: 100% !important;
    }
    .table-wrapper thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .table-wrapper thead th.mapel-header {
        background: #e8ecf1;
        font-weight: 600;
        color: #2c3e50;
        text-align: center !important;
    }
    .table-wrapper thead th.komponen-header {
        background: #f1f3f5;
        font-weight: 500;
        color: #6c757d;
        font-size: 0.65rem;
        text-align: center !important;
    }
    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
    }
    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .alert-warning-custom {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
    }
    .alert-warning-custom i {
        font-size: 3rem;
        color: #ffc107;
        display: block;
        margin-bottom: 10px;
    }
    .detail-raport-modal .modal-dialog {
        max-width: 800px;
    }
    .detail-raport-modal .modal-content {
        border-radius: 16px;
    }
    
    /* Perbaikan untuk tabel agar rata kanan */
    .table-raport {
        table-layout: fixed;
        width: 100% !important;
    }
    .table-raport .col-no {
        width: 40px;
        min-width: 40px;
        max-width: 40px;
    }
    .table-raport .col-nis {
        width: 80px;
        min-width: 80px;
        max-width: 80px;
    }
    .table-raport .col-nama {
        width: 140px;
        min-width: 140px;
        max-width: 200px;
        text-align: left !important;
    }
    .table-raport .col-mapel {
        width: 100px;
        min-width: 100px;
    }
    .table-raport .col-nilai {
        width: 60px;
        min-width: 60px;
    }
    .table-raport .col-rata {
        width: 80px;
        min-width: 80px;
    }
    .table-raport .col-status {
        width: 80px;
        min-width: 80px;
    }
    .table-raport .col-aksi {
        width: 80px;
        min-width: 80px;
    }
    
    .table-raport td,
    .table-raport th {
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Perbaikan DataTables Pagination */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
        text-align: center;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 12px;
        margin: 0 3px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: white;
        color: #333 !important;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.85rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #667eea;
        color: white !important;
        border-color: #667eea;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #667eea;
        color: white !important;
        border-color: #667eea;
        font-weight: 600;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 10px;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 4px 8px;
        margin: 0 5px;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 5px 10px;
        margin-left: 5px;
    }
    .dataTables_wrapper .dataTables_processing {
        background: rgba(255,255,255,0.8);
        padding: 10px;
        border-radius: 6px;
    }
    
    /* Responsive untuk tabel */
    @media (max-width: 768px) {
        .table-raport .col-nama {
            min-width: 100px;
            max-width: 120px;
        }
        .table-raport .col-mapel {
            min-width: 70px;
        }
        .table-raport .col-nilai {
            min-width: 45px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 3px 8px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <div>
        <h1 class="h2 fw-bold mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            Raport Siswa
        </h1>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-action" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="info-header">
    <div class="row align-items-center">
        <div class="col-md-4">
            <div class="label">Pilih Kelas</div>
            <div class="value">
                <select class="filter-select" id="kelasSelector" style="width: 100%;">
                    @forelse($kelasDiAjar as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama ?? $k->nama_kelas ?? 'Kelas' }} - {{ $k->jurusan->nama ?? 'Tanpa Jurusan' }}
                        </option>
                    @empty
                        <option value="">Tidak ada kelas yang diajar</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="label">Tahun Ajaran</div>
            <div class="value">
                <select class="filter-select" id="tahunAjaran" style="width: 100%;">
                    @foreach($tahunAjaranList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunAjaran == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="label">Semester</div>
            <div class="value">
                <select class="filter-select" id="semester" style="width: 100%;">
                    <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                    <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="label">&nbsp;</div>
            <div class="value">
                <button class="btn btn-light btn-action w-100" id="filterBtn">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Error -->
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Tabel Raport -->
<div class="card card-modern">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-4">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-table me-2 text-primary"></i>
            Daftar Nilai Siswa
        </h5>
        <div>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2 me-2" id="totalSiswa">
                <i class="fas fa-user me-1"></i> {{ $siswa->count() }} Siswa
            </span>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2" id="totalMapel">
                <i class="fas fa-book me-1"></i> {{ $mapel->count() }} Mapel
            </span>
        </div>
    </div>
    <div class="card-body">
        @if($mapel->isEmpty() || $siswa->isEmpty())
        <div class="alert-warning-custom">
            <i class="fas fa-info-circle"></i>
            <h5>Belum Ada Data Raport</h5>
            <p class="text-muted mb-0">
                @if($mapel->isEmpty())
                    Anda belum mengajar mata pelajaran apapun di kelas ini.
                @elseif($siswa->isEmpty())
                    Tidak ada siswa di kelas ini.
                @endif
            </p>
        </div>
        @else
        <div class="table-wrapper">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-bordered table-raport" id="raportTable">
                    <thead>
                        <tr>
                            <th rowspan="2" class="col-no" style="width: 40px; vertical-align: middle; text-align: center;">No</th>
                            <th rowspan="2" class="col-nis" style="width: 80px; vertical-align: middle; text-align: center;">NIS</th>
                            <th rowspan="2" class="col-nama" style="min-width: 140px; vertical-align: middle; text-align: left;">Nama Siswa</th>
                            @foreach($mapel as $m)
                            <th colspan="3" class="mapel-header col-mapel text-center" style="min-width: 100px; text-align: center !important;">
                                {{ $m->nama_mapel ?? $m->nama ?? 'Mapel' }}
                                <br>
                                <small style="font-weight: 400; font-size: 0.6rem; opacity: 0.7;">KKM: {{ $m->kkm ?? 75 }}</small>
                            </th>
                            @endforeach
                            <th rowspan="2" class="col-rata" style="min-width: 80px; vertical-align: middle; text-align: center;">Rata-rata</th>
                            <th rowspan="2" class="col-status" style="min-width: 70px; vertical-align: middle; text-align: center;">Status</th>
                            <th rowspan="2" class="col-aksi" style="min-width: 80px; vertical-align: middle; text-align: center;">Aksi</th>
                        </tr>
                        <tr>
                            @foreach($mapel as $m)
                            <th class="komponen-header col-nilai" style="text-align: center !important;">Tugas</th>
                            <th class="komponen-header col-nilai" style="text-align: center !important;">UTS</th>
                            <th class="komponen-header col-nilai" style="text-align: center !important;">Akhir</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                        @php
                            $totalNilai = 0;
                            $jumlahMapel = 0;
                            $statusLulus = true;
                            $rataRata = $rataRataSiswa[$s->id] ?? 0;
                            $statusClass = $rataRata >= 75 ? 'lulus' : 'tidak';
                            $statusText = $rataRata >= 75 ? 'Lulus' : 'Tidak Lulus';
                            $namaSiswa = $s->nama_lengkap ?? $s->user->name ?? $s->nama ?? '-';
                            $nisSiswa = $s->nis ?? '-';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center"><span class="badge bg-light text-dark">{{ $nisSiswa }}</span></td>
                            <td class="text-left">
                                <span class="fw-semibold">{{ $namaSiswa }}</span>
                            </td>
                            @foreach($mapel as $m)
                                @php
                                    $nilai = $dataNilai[$s->id][$m->id] ?? ['tugas' => '-', 'uts' => '-', 'akhir' => '-'];
                                    $akhir = $nilai['akhir'];
                                    $isValid = $akhir !== '-' && $akhir !== null && $akhir > 0;
                                    $classNilai = '';
                                    if ($isValid) {
                                        if ($akhir >= 85) $classNilai = 'nilai-tinggi';
                                        elseif ($akhir >= 70) $classNilai = 'nilai-sedang';
                                        else $classNilai = 'nilai-rendah';
                                    }
                                    $tugas = $nilai['tugas'] ?? '-';
                                    $uts = $nilai['uts'] ?? '-';
                                @endphp
                                <td class="text-center">{{ $tugas !== '-' ? number_format($tugas, 1) : '-' }}</td>
                                <td class="text-center">{{ $uts !== '-' ? number_format($uts, 1) : '-' }}</td>
                                <td class="text-center nilai-akhir {{ $classNilai }}">
                                    {{ $isValid ? number_format($akhir, 2) : '-' }}
                                </td>
                            @endforeach
                            <td class="text-center fw-bold">
                                @if($rataRata > 0)
                                    <span class="badge-kkm {{ $statusClass }}">
                                        {{ number_format($rataRata, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($rataRata > 0)
                                    <span class="badge-kkm {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                @else
                                    <span class="badge-kkm">-</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-detail" 
                                        data-siswa="{{ $s->id }}"
                                        data-nama="{{ $namaSiswa }}"
                                        data-tahun="{{ $tahunAjaran }}"
                                        data-semester="{{ $semester }}"
                                        title="Lihat Detail Raport">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('guru.nilai.raport.cetak', $s->id) }}?tahun_ajaran={{ $tahunAjaran }}&semester={{ $semester }}" 
                                   class="btn btn-sm btn-success" 
                                   title="Cetak Raport" 
                                   target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Legenda -->
        <div class="mt-3 pt-3 border-top">
            <div class="row g-2">
                <div class="col-auto">
                    <span class="badge-kkm lulus">Lulus (≥75)</span>
                </div>
                <div class="col-auto">
                    <span class="badge-kkm tidak">Tidak Lulus (&lt;75)</span>
                </div>
                <div class="col-auto">
                    <span class="badge-kkm" style="background: #d4edda; color: #155724;">Nilai Tinggi (≥85)</span>
                </div>
                <div class="col-auto">
                    <span class="badge-kkm" style="background: #fff3cd; color: #856404;">Nilai Sedang (70-84)</span>
                </div>
                <div class="col-auto">
                    <span class="badge-kkm" style="background: #f8d7da; color: #721c24;">Nilai Rendah (&lt;70)</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Raport -->
<div class="modal fade detail-raport-modal" id="detailRaportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2 text-primary"></i>
                    Detail Raport - <span id="modalSiswaNama"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data raport...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="modalCetakBtn">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable dengan konfigurasi yang lebih baik
        var table = $('#raportTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            order: [[0, 'asc']],
            scrollX: true,
            scrollY: '400px',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3,
                rightColumns: 1
            },
            columnDefs: [
                { orderable: false, targets: '_all' },
                { className: 'text-center', targets: [0, 1, 4, 5, 6, 7, 8] },
                { className: 'text-left', targets: [2] }
            ],
            autoWidth: false,
            processing: true,
            stateSave: true,
            responsive: false,
            // Tambahan konfigurasi untuk pagination
            drawCallback: function() {
                // Memastikan pagination bekerja dengan baik
                $('.dataTables_paginate .paginate_button').on('click', function(e) {
                    e.preventDefault();
                });
            }
        });
        
        // Filter button
        $('#filterBtn').on('click', function() {
            var kelasId = $('#kelasSelector').val();
            var tahunAjaran = $('#tahunAjaran').val();
            var semester = $('#semester').val();
            
            if (!kelasId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih kelas terlebih dahulu!',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            var url = '{{ route("guru.nilai.raport") }}' + 
                     '?kelas_id=' + encodeURIComponent(kelasId) + 
                     '&tahun_ajaran=' + encodeURIComponent(tahunAjaran) + 
                     '&semester=' + encodeURIComponent(semester);
            
            window.location.href = url;
        });
        
        // Detail Raport
        $('.btn-detail').on('click', function() {
            var siswaId = $(this).data('siswa');
            var siswaNama = $(this).data('nama') || 'Siswa';
            var tahunAjaran = $(this).data('tahun') || $('#tahunAjaran').val() || '{{ date("Y") . "/" . (date("Y") + 1) }}';
            var semester = $(this).data('semester') || $('#semester').val() || 'ganjil';
            
            $('#modalSiswaNama').text(siswaNama);
            $('#modalBody').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data raport...</p>
                </div>
            `);
            $('#detailRaportModal').modal('show');
            
            // Build URL dengan parameter yang benar
            var url = '{{ route("guru.nilai.raport.detail", ["siswaId" => ":siswaId"]) }}';
            url = url.replace(':siswaId', siswaId);
            
            // Fetch data via AJAX
            $.ajax({
                url: url,
                data: {
                    tahun_ajaran: tahunAjaran,
                    semester: semester
                },
                success: function(response) {
                    var html = generateDetailHtml(response);
                    $('#modalBody').html(html);
                    
                    // Update tombol cetak
                    var cetakUrl = '{{ route("guru.nilai.raport.cetak", ["siswaId" => ":siswaId"]) }}';
                    cetakUrl = cetakUrl.replace(':siswaId', siswaId);
                    cetakUrl += '?tahun_ajaran=' + encodeURIComponent(tahunAjaran) + '&semester=' + encodeURIComponent(semester);
                    
                    $('#modalCetakBtn').attr('onclick', "window.open('" + cetakUrl + "', '_blank')");
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    $('#modalBody').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Gagal memuat data: ${errorMsg}
                        </div>
                    `);
                }
            });
        });
    });
    
    function generateDetailHtml(data) {
        // Default values untuk menghindari undefined
        var siswa = data.siswa || {};
        var nilai = data.nilai || [];
        var rataRata = data.rataRata || 0;
        var totalNilai = data.totalNilai || 0;
        var jumlahMapel = data.jumlahMapel || 0;
        var predikatKeseluruhan = data.predikatKeseluruhan || '-';
        var tahunAjaran = data.tahunAjaran || '-';
        var semester = data.semester || '-';
        
        var namaSiswa = siswa.nama_lengkap || (siswa.user ? siswa.user.name : '-');
        var nisSiswa = siswa.nis || '-';
        var namaKelas = siswa.kelas ? siswa.kelas.nama : '-';
        
        var html = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>: ${namaSiswa}</td>
                        </tr>
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>: ${nisSiswa}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: ${namaKelas}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6>Rata-rata Nilai</h6>
                            <h2 class="text-${rataRata >= 75 ? 'success' : 'danger'}">
                                ${parseFloat(rataRata).toFixed(2)}
                            </h2>
                            <span class="badge bg-${rataRata >= 90 ? 'success' : (rataRata >= 75 ? 'primary' : 'danger')}">
                                ${predikatKeseluruhan}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Nilai Akhir</th>
                            <th>Grade</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        if (nilai && nilai.length > 0) {
            nilai.forEach(function(n, i) {
                var mapel = n.mapel || {};
                var grade = n.grade || { warna: 'secondary', grade: '-' };
                var predikat = n.predikat_label || '-';
                
                html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${mapel.nama_mapel || '-'}</td>
                        <td class="text-center fw-bold">${n.nilai_akhir || 0}</td>
                        <td class="text-center">
                            <span class="badge bg-${grade.warna}">${grade.grade}</span>
                        </td>
                        <td>${predikat}</td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada data nilai untuk siswa ini.
                    </td>
                </tr>
            `;
        }
        
        html += `
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <td colspan="2" class="text-end fw-bold">Rata-rata</td>
                            <td class="text-center fw-bold">${parseFloat(rataRata).toFixed(2)}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p><strong>Tahun Ajaran:</strong> ${tahunAjaran}</p>
                    <p><strong>Semester:</strong> ${semester}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Jumlah Mapel:</strong> ${jumlahMapel}</p>
                    <p><strong>Total Nilai:</strong> ${parseFloat(totalNilai).toFixed(2)}</p>
                </div>
            </div>
        `;
        
        return html;
    }
</script>
@endpush
@endsection