@extends('kepala-sekolah.layouts.header')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-cog me-2"></i>
        Pengaturan Sistem
    </h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="#profil" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                <i class="fas fa-school me-2"></i> Profil Sekolah
            </a>
            <a href="#keamanan" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="fas fa-shield-alt me-2"></i> Keamanan
            </a>
            <a href="#hak-akses" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="fas fa-users-cog me-2"></i> Hak Akses
            </a>
            <a href="#backup" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="fas fa-database me-2"></i> Backup & Restore
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="tab-content">
            <!-- Profil Sekolah -->
            <div class="tab-pane active" id="profil">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Profil Sekolah</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" value="SMA Negeri 1 Jakarta">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NPSN</label>
                                    <input type="text" class="form-control" value="20123456">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Akreditasi</label>
                                    <select class="form-control">
                                        <option>A</option>
                                        <option>B</option>
                                        <option>C</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kepala Sekolah</label>
                                    <input type="text" class="form-control" value="Dr. H. Ahmad Sudrajat, M.Pd">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" rows="3">Jl. Merdeka No. 1, Jakarta Pusat</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" value="(021) 1234567">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="info@sman1jakarta.sch.id">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website</label>
                                    <input type="text" class="form-control" value="www.sman1jakarta.sch.id">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo Sekolah</label>
                                    <input type="file" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Keamanan -->
            <div class="tab-pane" id="keamanan">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Pengaturan Keamanan</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="twoFactor" checked>
                                    <label class="form-check-label" for="twoFactor">
                                        Aktifkan Two-Factor Authentication
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="loginNotif" checked>
                                    <label class="form-check-label" for="loginNotif">
                                        Notifikasi Login
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Masa Berlaku Password (hari)</label>
                                <input type="number" class="form-control" value="90">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Batasan Percobaan Login</label>
                                <input type="number" class="form-control" value="5">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Hak Akses -->
            <div class="tab-pane" id="hak-akses">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Manajemen Hak Akses</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Role</th>
                                        <th>Dashboard</th>
                                        <th>Siswa</th>
                                        <th>Guru</th>
                                        <th>Keuangan</th>
                                        <th>Laporan</th>
                                        <th>Pengaturan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kepala Sekolah</td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                        <td class="text-center"><input type="checkbox" checked disabled></td>
                                    </tr>
                                    <tr>
                                        <td>Administrasi</td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>Guru</td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>Siswa</td>
                                        <td class="text-center"><input type="checkbox" checked></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><input type="checkbox"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary">Simpan Hak Akses</button>
                    </div>
                </div>
            </div>
            
            <!-- Backup -->
            <div class="tab-pane" id="backup">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Backup & Restore Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Terakhir backup: {{ now()->format('d F Y, H:i') }} WIB
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                        <h5>Backup Database</h5>
                                        <p>Download backup database terbaru</p>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download Backup
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-upload fa-3x text-success mb-3"></i>
                                        <h5>Restore Database</h5>
                                        <p>Upload file backup untuk restore</p>
                                        <input type="file" class="form-control mb-2">
                                        <button class="btn btn-success">
                                            <i class="fas fa-upload me-2"></i>Restore
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection