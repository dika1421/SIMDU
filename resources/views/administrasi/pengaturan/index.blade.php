@extends('administrasi.layouts.header')

@section('title', 'Pengaturan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-cog me-2 text-primary"></i> Pengaturan
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Halaman pengaturan sedang dalam pengembangan.
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6><i class="fas fa-globe text-primary me-2"></i> Pengaturan Umum</h6>
                                <p class="text-muted small">Nama sekolah, alamat, logo, dll.</p>
                                <span class="badge bg-secondary">Coming Soon</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6><i class="fas fa-bell text-warning me-2"></i> Notifikasi</h6>
                                <p class="text-muted small">Pengaturan notifikasi dan alert.</p>
                                <span class="badge bg-secondary">Coming Soon</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6><i class="fas fa-shield-alt text-success me-2"></i> Keamanan</h6>
                                <p class="text-muted small">Pengaturan keamanan dan akses.</p>
                                <span class="badge bg-secondary">Coming Soon</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6><i class="fas fa-database text-info me-2"></i> Backup & Restore</h6>
                                <p class="text-muted small">Backup dan restore data.</p>
                                <span class="badge bg-secondary">Coming Soon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection