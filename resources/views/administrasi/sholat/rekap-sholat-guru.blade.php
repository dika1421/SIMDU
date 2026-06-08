@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        Rekap Absensi Sholat Guru
    </h1>
    <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Halaman rekap absensi sholat guru.
</div>

<div class="card">
    <div class="card-body">
        <p>Tabel rekap absensi sholat guru akan ditampilkan di sini.</p>
    </div>
</div>
@endsectiona@extends('administrasi.layouts.header')

@section('title', 'Rekap Absensi Sholat Guru')

@section('content')
<div class="d-flex justify-content-between pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-bar me-2"></i> Rekap Absensi Sholat Guru</h1>
    <a href="{{ route('administrasi.absensi-sholat.dashboard') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>

<div class="alert alert-info">Fitur rekap absensi guru - Data akan ditampilkan di sini</div>
@endsection