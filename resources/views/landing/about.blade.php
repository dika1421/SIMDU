@extends('landing.layout')

@section('title', 'Tentang Kami')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <h1 class="text-center" style="color: #1a237e;">Tentang SIMDU</h1>
    <p class="text-center text-muted">Sistem Informasi Manajemen SMK Darul Ulum</p>
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <p>SIMDU adalah platform manajemen sekolah yang dikembangkan untuk memudahkan seluruh aktivitas administrasi dan akademik di SMK Darul Ulum.</p>
            <p>Dengan sistem ini, seluruh data sekolah terintegrasi dalam satu platform, mulai dari data siswa, guru, keuangan, hingga laporan analitik.</p>
            <a href="{{ route('landing') }}" class="btn btn-primary-custom"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>
</div>
@endsection