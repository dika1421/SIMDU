@extends('guru.layouts.header')

@section('title', 'Manajemen Nilai')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        border: none;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table-hover tbody tr:hover {
        background-color: #f0f7ff;
        cursor: pointer;
    }
    .badge-soft-success {
        background: #d4edda;
        color: #155724;
    }
    .badge-soft-warning {
        background: #fff3cd;
        color: #856404;
    }
    .badge-soft-danger {
        background: #f8d7da;
        color: #721c24;
    }
    .badge-soft-info {
        background: #d1ecf1;
        color: #0c5460;
    }
    .info-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        border-left: 4px solid #667eea;
    }
    .info-box h6 {
        color: #667eea;
        font-weight: 600;
    }
    .step-list {
        list-style: none;
        padding: 0;
        counter-reset: step-counter;
    }
    .step-list li {
        counter-increment: step-counter;
        padding: 10px 15px 10px 50px;
        position: relative;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .step-list li:hover {
        background: #f8f9fa;
        border-radius: 8px;
    }
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
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .info-item {
        background: white;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    .info-item .label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-item .value {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
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
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3">
    <h1 class="h2 fw-bold mb-0">
        <i class="fas fa-book me-2 text-primary"></i>
        Manajemen Nilai
    </h1>
    <div>
        <a href="{{ route('guru.nilai.input') }}" class="btn btn-primary btn-action">
            <i class="fas fa-plus-circle me-1"></i> Input Nilai
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Total Kelas</div>
                        <h3 class="mb-0 fw-bold">{{ $kelas->count() ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Mata Pelajaran</div>
                        <h3 class="mb-0 fw-bold">{{ $mapel->count() ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Total Siswa</div>
                        <h3 class="mb-0 fw-bold">
                            @php
                                $totalSiswa = 0;
                                foreach($kelas as $k) {
                                    $totalSiswa += $k->siswa->where('status', 'aktif')->count();
                                }
                            @endphp
                            {{ $totalSiswa }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Rata-rata Nilai</div>
                        <h3 class="mb-0 fw-bold">
                            @php
                                $rataAll = 0;
                                $countAll = 0;
                                foreach($statistik as $kelasStat) {
                                    foreach($kelasStat as $mapelStat) {
                                        $rataAll += $mapelStat['rata_rata'];
                                        $countAll++;
                                    }
                                }
                                echo $countAll > 0 ? number_format($rataAll / $countAll, 1) : '0';
                            @endphp
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Nilai per Kelas & Mapel -->
<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-4">
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
                        <table class="table table-hover" id="nilaiTable">
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
                                            <td>
                                                <strong>{{ $k->nama ?? $k->nama_kelas ?? '-' }}</strong>
                                            </td>
                                            <td>{{ $m->nama_mapel }}</td>
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
                                                    <span class="badge bg-success">Tersedia</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Input</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('guru.nilai.input', ['kelas_id' => $k->id, 'mapel_id' => $m->id]) }}" 
                                                   class="btn btn-sm btn-primary rounded-pill px-3">
                                                    <i class="fas fa-edit"></i> Input
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">Belum ada data nilai</h5>
                        <p class="text-muted">Silakan input nilai terlebih dahulu</p>
                        <a href="{{ route('guru.nilai.input') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus-circle"></i> Input Nilai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Informasi & Panduan -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    Informasi Penilaian
                </h5>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">KKM (Kriteria Ketuntasan Minimal)</div>
                        <div class="value text-success">75</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Predikat Nilai</div>
                        <div class="value">
                            <span class="badge bg-success">A (≥85)</span>
                            <span class="badge bg-primary">B (75-84)</span>
                            <span class="badge bg-warning text-dark">C (60-74)</span>
                            <span class="badge bg-danger">D (40-59)</span>
                            <span class="badge bg-dark">E (&lt;40)</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Bobot Penilaian</div>
                        <div class="value">
                            Harian: 20% | Tugas: 20% | UTS: 30% | UAS: 30%
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Status Nilai</div>
                        <div class="value">
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
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list-check me-2 text-primary"></i>
                    Langkah-langkah Input Nilai
                </h5>
            </div>
            <div class="card-body">
                <ol class="step-list">
                    <li>Klik tombol <strong>"Input Nilai"</strong> untuk memulai input nilai</li>
                    <li>Pilih <strong>kelas</strong>, <strong>mata pelajaran</strong>, tahun ajaran, dan semester</li>
                    <li>Masukkan nilai untuk setiap komponen (harian, tugas, UTS, UAS, praktek)</li>
                    <li>Simpan nilai, sistem akan menghitung <strong>nilai akhir</strong> secara otomatis</li>
                    <li>Setelah semua nilai selesai, <strong>publik</strong> nilai ke raport</li>
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
        $('#nilaiTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
@endsection