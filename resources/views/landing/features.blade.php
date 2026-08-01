@extends('landing.layout')

@section('title', 'Fitur SIMDU')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="text-center" data-aos="fade-up">
        <h1 class="display-4 fw-bold" style="color: #1a237e;">Fitur <span style="color: #4caf50;">Unggulan</span></h1>
        <p class="text-muted">Semua fitur dirancang untuk memudahkan pengelolaan sekolah</p>
    </div>
    
    <div class="row g-4 mt-4">
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="0">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-user-graduate fa-2x" style="color: #1a237e;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Manajemen Siswa</h5>
                        <p class="text-muted">Kelola data siswa, absensi, nilai, dan rapor secara digital dan terintegrasi.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-chalkboard-teacher fa-2x" style="color: #4caf50;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Manajemen Guru</h5>
                        <p class="text-muted">Kelola data guru, jadwal mengajar, absensi, dan kinerja guru dengan mudah.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tambahkan fitur lainnya -->
    </div>
    
    <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="btn btn-primary-custom">Mulai Sekarang</a>
    </div>
</div>
@endsection