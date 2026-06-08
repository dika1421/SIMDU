@extends('guru.layouts.header')

@section('title', 'Manajemen Nilai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-book me-2"></i>
        Manajemen Nilai
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.nilai.raport') }}" class="btn btn-sm btn-info me-2">
            <i class="fas fa-print"></i> Cetak Raport
        </a>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#inputNilaiModal">
            <i class="fas fa-plus"></i> Input Nilai
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Modal Input Nilai -->
<div class="modal fade" id="inputNilaiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Form Input Nilai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('guru.nilai.input') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas ?? $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="{{ date('Y') . '/' . (date('Y') + 1) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Input Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistik Nilai -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-chart-line me-2"></i>
            Statistik Nilai
        </h5>
    </div>
    <div class="card-body">
        @if(isset($statistik) && count($statistik) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="statistikTable">
                    <thead class="table-primary">
                        <tr>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Rata-rata</th>
                            <th>Nilai Tertinggi</th>
                            <th>Nilai Terendah</th>
                            <th>Jumlah Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas as $k)
                            @foreach($mapel as $m)
                                @if(isset($statistik[$k->id][$m->id]))
                                    <tr>
                                        <td>
                                            <strong>{{ $k->nama_kelas ?? $k->nama }}</strong>
                                            @if($k->jurusan)
                                                <br><small class="text-muted">{{ $k->jurusan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $m->nama_mapel }}
                                            <br><small class="text-muted">KKM: {{ $m->kkm ?? 75 }}</small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $rata = $statistik[$k->id][$m->id]['rata_rata'];
                                                $color = $rata >= 75 ? 'text-success' : ($rata >= 60 ? 'text-warning' : 'text-danger');
                                            @endphp
                                            <span class="{{ $color }} fw-bold">
                                                {{ number_format($rata, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center text-success">
                                            {{ number_format($statistik[$k->id][$m->id]['nilai_tertinggi'], 2) }}
                                        </td>
                                        <td class="text-center text-danger">
                                            {{ number_format($statistik[$k->id][$m->id]['nilai_terendah'], 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                {{ $statistik[$k->id][$m->id]['jumlah_siswa'] }} Siswa
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Data Statistik</h5>
                <p class="text-muted">Silakan input nilai terlebih dahulu untuk melihat statistik.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputNilaiModal">
                    <i class="fas fa-plus"></i> Input Nilai Sekarang
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>KKM (Kriteria Ketuntasan Minimal):</strong> 75
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        <strong>Predikat Nilai:</strong>
                        <br>A (≥85), B (75-84), C (60-74), D (40-59), E (<40)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calculator text-warning me-2"></i>
                        <strong>Bobot Penilaian:</strong>
                        <br>Harian: 20%, Tugas: 20%, UTS: 30%, UAS: 30%
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Langkah-langkah
                </h5>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li>Klik tombol <strong>"Input Nilai"</strong> untuk memulai input nilai</li>
                    <li>Pilih kelas, mata pelajaran, tahun ajaran, dan semester</li>
                    <li>Masukkan nilai untuk setiap komponen (harian, tugas, UTS, UAS, praktek)</li>
                    <li>Simpan nilai, sistem akan menghitung nilai akhir secara otomatis</li>
                    <li>Setelah semua nilai selesai, publish nilai ke raport</li>
                    <li>Cetak raport siswa melalui menu <strong>"Cetak Raport"</strong></li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inisialisasi DataTable jika ada
    if (document.getElementById('statistikTable')) {
        $(document).ready(function() {
            $('#statistikTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                pageLength: 10,
                ordering: true
            });
        });
    }
    
    // Auto close alert setelah 5 detik
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
@endsection