@extends('guru.layouts.header')

@section('title', 'Manajemen Nilai')

@section('content')
<style>
    /* ===== Stat Card ===== */
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        border: none;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .stat-card:hover::after { opacity: 1; }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.15);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.1;
        margin: 0;
        color: #1e293b;
    }
    .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        font-weight: 700;
    }

    /* ===== Card Modern ===== */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        transition: box-shadow 0.3s ease;
    }
    .card-modern:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .card-modern .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    /* ===== Table ===== */
    .table-hover tbody tr:hover {
        background-color: #f0f7ff;
        cursor: pointer;
    }

    /* ===== Info Grid ===== */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .info-item {
        background: #fff;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    .info-item .label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .info-item .value {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
    }

    /* ===== Step List ===== */
    .step-list {
        list-style: none;
        padding: 0;
        counter-reset: step-counter;
        margin: 0;
    }
    .step-list li {
        counter-increment: step-counter;
        padding: 10px 15px 10px 50px;
        position: relative;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }
    .step-list li:hover {
        background: #f8f9fa;
        border-radius: 8px;
    }
    .step-list li:last-child { border-bottom: none; }
    .step-list li::before {
        content: counter(step-counter);
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    /* ===== Buttons ===== */
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

    /* ===== Alerts Modern ===== */
    .alert-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-weight: 600;
        font-size: .9rem;
        border: none;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .alert-modern.alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    .alert-modern.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    .alert-modern.alert-warning {
        background: #fffbeb;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }
    .alert-modern.alert-info {
        background: #eff6ff;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    .alert-modern .btn-close {
        margin-left: auto;
    }

    /* ===== Empty State ===== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }
    .empty-state h5 {
        color: #475569;
        font-weight: 700;
        margin: 0 0 6px;
    }
    .empty-state p {
        color: #94a3b8;
        font-size: .9rem;
        margin: 0 0 16px;
    }

    /* ===== Badges ===== */
    .badge-soft-success {
        background: #d4edda;
        color: #155724;
        padding: 5px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: .7rem;
    }
    .badge-soft-secondary {
        background: #e9ecef;
        color: #495057;
        padding: 5px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: .7rem;
    }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .stat-number { font-size: 1.5rem; }
    }
</style>

<!-- ============================================================
     PAGE HEADER
     ============================================================ -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <div>
        <h1 class="h2 fw-bold mb-0">
            <i class="fas fa-book me-2 text-primary"></i>
            Manajemen Nilai
        </h1>
        <p class="text-muted small mb-0 mt-1">Kelola nilai siswa berdasarkan kelas dan mata pelajaran</p>
    </div>
    <div>
        <a href="{{ route('guru.nilai.input') }}" class="btn btn-primary btn-action">
            <i class="fas fa-plus-circle me-1"></i> Input Nilai
        </a>
    </div>
</div>

<!-- ============================================================
     ALERTS (hanya muncul jika benar-benar ada session)
     ============================================================ -->
@if(session('success'))
    <div class="alert-modern alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert-modern alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ✅ FIX: Warning hanya muncul kalau ada redirect dari input dengan error --}}
@if(session('warning') && session('warning_from_input'))
    <div class="alert-modern alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i>
        <div>{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ============================================================
     STATISTIK CARDS
     ============================================================ -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Kelas</div>
                        <div class="stat-number mt-1">{{ $kelas->count() ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Mata Pelajaran</div>
                        <div class="stat-number mt-1">{{ $mapel->count() ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-number mt-1">
                            @php
                                $totalSiswa = 0;
                                foreach($kelas as $k) {
                                    $totalSiswa += $k->siswa->where('status', 'aktif')->count();
                                }
                            @endphp
                            {{ $totalSiswa }}
                        </div>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Rata-rata Nilai</div>
                        <div class="stat-number mt-1">
                            @php
                                $rataAll = 0;
                                $countAll = 0;
                                foreach($statistik as $kelasStat) {
                                    foreach($kelasStat as $mapelStat) {
                                        if (($mapelStat['jumlah_siswa'] ?? 0) > 0) {
                                            $rataAll += $mapelStat['rata_rata'];
                                            $countAll++;
                                        }
                                    }
                                }
                                echo $countAll > 0 ? number_format($rataAll / $countAll, 1) : '0';
                            @endphp
                        </div>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     STATISTIK NILAI
     ============================================================ -->
<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    Statistik Nilai
                </h5>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                    <i class="far fa-calendar me-1"></i> Semester Ini
                </span>
            </div>
            <div class="card-body">
                @if($kelas->count() > 0 && $mapel->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="nilaiTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Jumlah Siswa</th>
                                    <th class="text-center">Rata-rata</th>
                                    <th class="text-center">Nilai Tertinggi</th>
                                    <th class="text-center">Nilai Terendah</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas as $k)
                                    @foreach($mapel as $m)
                                        @php
                                            $stat = $statistik[$k->id][$m->id] ?? null;
                                            $nilaiCount = $stat['jumlah_siswa'] ?? 0;
                                            $rata = $stat['rata_rata'] ?? 0;
                                            $tertinggi = $stat['nilai_tertinggi'] ?? 0;
                                            $terendah = $stat['nilai_terendah'] ?? 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $k->nama ?? $k->nama_kelas ?? '-' }}</strong></td>
                                            <td>{{ $m->nama_mapel ?? $m->nama ?? '-' }}</td>
                                            <td class="text-center">{{ $nilaiCount }}</td>
                                            <td class="text-center">
                                                @if($nilaiCount > 0)
                                                    <span class="fw-bold text-{{ $rata >= 75 ? 'success' : ($rata >= 60 ? 'warning' : 'danger') }}">
                                                        {{ number_format($rata, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($nilaiCount > 0)
                                                    <span class="text-success fw-bold">{{ number_format($tertinggi, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($nilaiCount > 0)
                                                    <span class="text-danger fw-bold">{{ number_format($terendah, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($nilaiCount > 0)
                                                    <span class="badge-soft-success">
                                                        <i class="fas fa-check"></i> Tersedia
                                                    </span>
                                                @else
                                                    <span class="badge-soft-secondary">
                                                        Belum Input
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('guru.nilai.input', ['kelas_id' => $k->id, 'mapel_id' => $m->id]) }}"
                                                   class="btn btn-sm btn-primary rounded-pill px-3">
                                                    <i class="fas fa-edit me-1"></i> Input
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <h5>Belum ada data nilai</h5>
                        <p>Silakan input nilai terlebih dahulu</p>
                        <a href="{{ route('guru.nilai.input') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-plus-circle me-1"></i> Input Nilai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     INFORMASI & PANDUAN
     ============================================================ -->
<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    Informasi Penilaian
                </h5>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">KKM</div>
                        <div class="value text-success">75</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Predikat</div>
                        <div class="value" style="font-size:.75rem;">
                            <span class="badge bg-success">A ≥85</span>
                            <span class="badge bg-primary">B 75-84</span>
                            <span class="badge bg-warning text-dark">C 60-74</span>
                            <span class="badge bg-danger">D &lt;60</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Bobot Penilaian</div>
                        <div class="value" style="font-size:.8rem;">
                            Harian 20% | Tugas 20% | UTS 30% | UAS 30%
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Status Nilai</div>
                        <div class="value" style="font-size:.8rem;">
                            <span class="badge bg-warning text-dark">Draft</span>
                            <span class="badge bg-success">Published</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list-check me-2 text-primary"></i>
                    Langkah Input Nilai
                </h5>
            </div>
            <div class="card-body">
                <ol class="step-list">
                    <li>Klik tombol <strong>"Input Nilai"</strong> untuk memulai</li>
                    <li>Pilih <strong>kelas</strong>, <strong>mata pelajaran</strong>, tahun ajaran, dan semester</li>
                    <li>Masukkan nilai setiap komponen (harian, tugas, UTS, UAS, praktek)</li>
                    <li>Simpan nilai — sistem menghitung <strong>nilai akhir</strong> otomatis</li>
                    <li>Setelah selesai, <strong>publik</strong> nilai ke raport</li>
                    <li>Cetak raport siswa melalui menu <strong>"Cetak Raport"</strong></li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        if ($('#nilaiTable').length && !$.fn.DataTable.isDataTable('#nilaiTable')) {
            $('#nilaiTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [7] }
                ]
            });
        }

        // Auto dismiss alert setelah 5 detik
        setTimeout(function() {
            $('.alert-modern').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
@endsection