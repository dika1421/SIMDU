<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KepalaSekolah\DashboardController as KepalaSekolahDashboard;
use App\Http\Controllers\KepalaSekolah\ManajemenSekolahController;
use App\Http\Controllers\KepalaSekolah\ManajemenGuruController;
use App\Http\Controllers\KepalaSekolah\PersetujuanController;
use App\Http\Controllers\KepalaSekolah\LaporanController;
use App\Http\Controllers\KepalaSekolah\ProfilController as KepalaSekolahProfil;
use App\Http\Controllers\KepalaSekolah\PengaturanController as KepalaSekolahPengaturan;
use App\Http\Controllers\Administrasi\DashboardController as AdministrasiDashboard;
use App\Http\Controllers\Administrasi\SiswaController;
use App\Http\Controllers\Administrasi\GuruController as AdministrasiGuruController;
use App\Http\Controllers\Administrasi\KelasController;
use App\Http\Controllers\Administrasi\JurusanController;
use App\Http\Controllers\Administrasi\AbsensiController as AdministrasiAbsensi;
use App\Http\Controllers\Administrasi\RfidController;
use App\Http\Controllers\Administrasi\KeuanganController;
use App\Http\Controllers\Administrasi\JadwalController;
use App\Http\Controllers\Administrasi\ArsipController;
use App\Http\Controllers\Administrasi\KomunikasiController as AdministrasiKomunikasi;
use App\Http\Controllers\Administrasi\AbsensiSholatController;
use App\Http\Controllers\Administrasi\MapelController;
use App\Http\Controllers\Administrasi\GaleriController;
use App\Http\Controllers\Administrasi\ProfilController as AdministrasiProfil;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\AbsensiSiswaController;
use App\Http\Controllers\Guru\KomunikasiController as GuruKomunikasi;
use App\Http\Controllers\Guru\KalenderController;
use App\Http\Controllers\Guru\KinerjaController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\NilaiController as SiswaNilai;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensi;
use App\Http\Controllers\Siswa\TugasController as SiswaTugas;
use App\Http\Controllers\Siswa\KalenderController as SiswaKalender;
use App\Http\Controllers\Siswa\ProfilController;
use App\Http\Controllers\Siswa\PembayaranController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

// ================== LANDING PAGE ==================
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/about', [LandingPageController::class, 'about'])->name('landing.about');
Route::get('/features', [LandingPageController::class, 'features'])->name('landing.features');
Route::get('/contact', [LandingPageController::class, 'contact'])->name('landing.contact');

// ================== AUTH ==================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/login/guru', [LoginController::class, 'loginGuru'])->name('login.guru');
Route::post('/login/siswa', [LoginController::class, 'loginSiswa'])->name('login.siswa');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ================== KEPALA SEKOLAH ==================
Route::middleware(['auth', 'check.role:kepala_sekolah'])->prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {
    Route::get('/dashboard', [KepalaSekolahDashboard::class, 'index'])->name('dashboard');
    
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('/struktur', [ManajemenSekolahController::class, 'struktur'])->name('struktur');
        Route::post('/struktur', [ManajemenSekolahController::class, 'strukturStore'])->name('struktur.store');
        Route::put('/struktur/{id}', [ManajemenSekolahController::class, 'strukturUpdate'])->name('struktur.update');
        Route::delete('/struktur/{id}', [ManajemenSekolahController::class, 'strukturDestroy'])->name('struktur.destroy');
        Route::get('/jurusan', [ManajemenSekolahController::class, 'jurusan'])->name('jurusan');
        Route::post('/jurusan', [ManajemenSekolahController::class, 'jurusanStore'])->name('jurusan.store');
        Route::put('/jurusan/{id}', [ManajemenSekolahController::class, 'jurusanUpdate'])->name('jurusan.update');
        Route::delete('/jurusan/{id}', [ManajemenSekolahController::class, 'jurusanDestroy'])->name('jurusan.destroy');
        Route::get('/tahun-ajaran', [ManajemenSekolahController::class, 'tahunAjaran'])->name('tahun-ajaran');
        Route::post('/tahun-ajaran', [ManajemenSekolahController::class, 'tahunAjaranStore'])->name('tahun-ajaran.store');
        Route::post('/tahun-ajaran/{id}/set-aktif', [ManajemenSekolahController::class, 'tahunAjaranSetAktif'])->name('tahun-ajaran.set-aktif');
    });
    
    Route::prefix('manajemen-guru')->name('manajemen-guru.')->group(function () {
        Route::get('/', [ManajemenGuruController::class, 'index'])->name('index');
        Route::get('/create', [ManajemenGuruController::class, 'create'])->name('create');
        Route::post('/', [ManajemenGuruController::class, 'store'])->name('store');
        Route::get('/absensi', [ManajemenGuruController::class, 'absensi'])->name('absensi');
        Route::get('/{id}', [ManajemenGuruController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ManajemenGuruController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ManajemenGuruController::class, 'update'])->name('update');
        Route::delete('/{id}', [ManajemenGuruController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/reset-password', [ManajemenGuruController::class, 'resetPassword'])->name('reset-password');
    });
    
    Route::prefix('persetujuan')->name('persetujuan.')->group(function () {
        Route::get('/dashboard', [PersetujuanController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [PersetujuanController::class, 'index'])->name('index');
        Route::get('/create', [PersetujuanController::class, 'create'])->name('create');
        Route::post('/', [PersetujuanController::class, 'store'])->name('store');
        Route::get('/export', [PersetujuanController::class, 'export'])->name('export');
        Route::post('/bulk-approve', [PersetujuanController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [PersetujuanController::class, 'bulkReject'])->name('bulk-reject');
        Route::get('/{id}', [PersetujuanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PersetujuanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PersetujuanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PersetujuanController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [PersetujuanController::class, 'print'])->name('print');
        Route::post('/{id}/approve', [PersetujuanController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PersetujuanController::class, 'reject'])->name('reject');
        Route::post('/{id}/revise', [PersetujuanController::class, 'revise'])->name('revise');
    });
    
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/absensi', [LaporanController::class, 'absensi'])->name('absensi');
        Route::get('/kinerja-guru', [LaporanController::class, 'kinerjaGuru'])->name('kinerja-guru');
        Route::get('/statistik-siswa', [LaporanController::class, 'statistikSiswa'])->name('statistik-siswa');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/export/{type}', [LaporanController::class, 'export'])->name('export');
    });
    
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/laporan', function() { return view('kepala-sekolah.keuangan.laporan'); })->name('laporan');
        Route::get('/spp', function() { return view('kepala-sekolah.keuangan.spp'); })->name('spp');
        Route::get('/pembayaran-lain', function() { return view('kepala-sekolah.keuangan.pembayaran-lain'); })->name('pembayaran-lain-view');
        Route::get('/rekapitulasi', function() { return view('kepala-sekolah.keuangan.rekapitulasi'); })->name('rekapitulasi');
    });
    
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [KepalaSekolahProfil::class, 'index'])->name('index');
        Route::get('/edit', [KepalaSekolahProfil::class, 'edit'])->name('edit');
        Route::put('/', [KepalaSekolahProfil::class, 'update'])->name('update');
        Route::post('/change-password', [KepalaSekolahProfil::class, 'changePassword'])->name('change-password');
    });
    
    Route::get('/pengaturan', [KepalaSekolahPengaturan::class, 'index'])->name('pengaturan');
});

// ================== ADMINISTRASI ==================
Route::middleware(['auth', 'check.role:administrasi'])->prefix('administrasi')->name('administrasi.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdministrasiDashboard::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
    
    // Manajemen Jurusan
    Route::resource('jurusan', JurusanController::class)
        ->middleware('permission:jurusan.view|jurusan.create|jurusan.edit|jurusan.delete');
    
    // Manajemen Kelas
    Route::post('/kelas/import', [KelasController::class, 'import'])
        ->middleware('permission:kelas.create')
        ->name('kelas.import');
    Route::get('/kelas/download-template', [KelasController::class, 'downloadTemplate'])
        ->middleware('permission:kelas.export')
        ->name('kelas.download-template');
    Route::get('/kelas/export', [KelasController::class, 'export'])
        ->middleware('permission:kelas.export')
        ->name('kelas.export');
    Route::resource('kelas', KelasController::class)
        ->middleware('permission:kelas.view|kelas.create|kelas.edit|kelas.delete');
    Route::get('/kelas/get-kelas-list', [KelasController::class, 'getKelasList'])
        ->middleware('permission:kelas.view')
        ->name('kelas.get-list');
    
    // Manajemen Mata Pelajaran
    Route::resource('mapel', MapelController::class)
        ->middleware('permission:mapel.view|mapel.create|mapel.edit|mapel.delete');
    Route::get('/mapel/get-mapel-list', [MapelController::class, 'getMapelList'])
        ->middleware('permission:mapel.view')
        ->name('mapel.get-list');
    Route::post('/mapel/import', [MapelController::class, 'import'])
        ->middleware('permission:mapel.create')
        ->name('mapel.import');
    Route::get('/mapel/download-template', [MapelController::class, 'downloadTemplate'])
        ->middleware('permission:mapel.export')
        ->name('mapel.download-template');
    Route::get('/mapel/export', [MapelController::class, 'export'])
        ->middleware('permission:mapel.export')
        ->name('mapel.export');
    
    // Manajemen Siswa
    Route::resource('siswa', SiswaController::class)
        ->middleware('permission:siswa.view|siswa.create|siswa.edit|siswa.delete');
    Route::post('/siswa/{siswa}/mutasi', [SiswaController::class, 'mutasi'])
        ->middleware('permission:siswa.edit')
        ->name('siswa.mutasi');
    Route::get('/siswa/{id}/reset-password', [SiswaController::class, 'resetPassword'])
        ->middleware('permission:siswa.edit')
        ->name('siswa.reset-password');
    Route::post('/siswa/import', [SiswaController::class, 'import'])
        ->middleware('permission:siswa.import')
        ->name('siswa.import');
    Route::get('/siswa/download-template', [SiswaController::class, 'downloadTemplate'])
        ->middleware('permission:siswa.import')
        ->name('siswa.download-template');
    Route::get('/siswa/export', [SiswaController::class, 'export'])
        ->middleware('permission:siswa.export')
        ->name('siswa.export');
    
    // Manajemen Guru
    Route::resource('guru', AdministrasiGuruController::class)
        ->middleware('permission:guru.view|guru.create|guru.edit|guru.delete');
    Route::post('/guru/import', [AdministrasiGuruController::class, 'import'])
        ->middleware('permission:guru.create')
        ->name('guru.import');
    Route::get('/guru/download-template', [AdministrasiGuruController::class, 'downloadTemplate'])
        ->middleware('permission:guru.export')
        ->name('guru.download-template');
    Route::get('/guru/export', [AdministrasiGuruController::class, 'export'])
        ->middleware('permission:guru.export')
        ->name('guru.export');
    
    // Manajemen Jadwal
    Route::get('/jadwal/kalender', [JadwalController::class, 'kalender'])
        ->middleware('permission:jadwal.view')
        ->name('jadwal.kalender');
    Route::post('/jadwal/copy', [JadwalController::class, 'copy'])
        ->middleware('permission:jadwal.create')
        ->name('jadwal.copy');
    Route::post('/jadwal/check-conflict', [JadwalController::class, 'checkConflict'])
        ->middleware('permission:jadwal.view')
        ->name('jadwal.check-conflict');
    Route::get('/jadwal/export', [JadwalController::class, 'export'])
        ->middleware('permission:jadwal.export')
        ->name('jadwal.export');
    Route::resource('jadwal', JadwalController::class)
        ->middleware('permission:jadwal.view|jadwal.create|jadwal.edit|jadwal.delete');
    
    // Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', function() { return redirect()->route('administrasi.absensi.scan'); })
            ->middleware('permission:absensi.view')
            ->name('index');
        Route::get('/scan', [RfidController::class, 'index'])
            ->middleware('permission:absensi.create')
            ->name('scan');
        Route::get('/siswa', [AdministrasiAbsensi::class, 'siswa'])
            ->middleware('permission:absensi.view')
            ->name('siswa');
        Route::post('/siswa/store', [AdministrasiAbsensi::class, 'storeSiswa'])
            ->middleware('permission:absensi.create')
            ->name('store-siswa');
        Route::get('/guru', [AdministrasiAbsensi::class, 'guru'])
            ->middleware('permission:absensi.view')
            ->name('guru');
        Route::post('/guru/store', [AdministrasiAbsensi::class, 'storeGuru'])
            ->middleware('permission:absensi.create')
            ->name('store-guru');
        Route::get('/rekap-siswa', [AdministrasiAbsensi::class, 'rekapSiswa'])
            ->middleware('permission:absensi.view')
            ->name('rekap-siswa');
        Route::get('/rekap-guru', [AdministrasiAbsensi::class, 'rekapGuru'])
            ->middleware('permission:absensi.view')
            ->name('rekap-guru');
        Route::get('/export-siswa', [AdministrasiAbsensi::class, 'exportSiswa'])
            ->middleware('permission:absensi.export')
            ->name('export-siswa');
        Route::get('/export-guru', [AdministrasiAbsensi::class, 'exportGuru'])
            ->middleware('permission:absensi.export')
            ->name('export-guru');
    });
    
    // Absensi Sholat
    Route::prefix('absensi-sholat')->name('absensi-sholat.')->group(function () {
        Route::get('/', [AbsensiSholatController::class, 'index'])
            ->middleware('permission:absensi.view')
            ->name('index');
        Route::get('/dashboard', [AbsensiSholatController::class, 'dashboard'])
            ->middleware('permission:absensi.view')
            ->name('dashboard');
        Route::get('/siswa', [AbsensiSholatController::class, 'siswa'])
            ->middleware('permission:absensi.view')
            ->name('siswa');
        Route::get('/guru', [AbsensiSholatController::class, 'guru'])
            ->middleware('permission:absensi.view')
            ->name('guru');
        Route::get('/rekap-siswa', [AbsensiSholatController::class, 'rekapSiswa'])
            ->middleware('permission:absensi.view')
            ->name('rekap-siswa');
        Route::get('/rekap-guru', [AbsensiSholatController::class, 'rekapGuru'])
            ->middleware('permission:absensi.view')
            ->name('rekap-guru');
        Route::get('/scan', [AbsensiSholatController::class, 'scan'])
            ->middleware('permission:absensi.create')
            ->name('scan');
        Route::post('/scan/store', [AbsensiSholatController::class, 'scanStore'])
            ->middleware('permission:absensi.create')
            ->name('scan-store');
        Route::get('/get-user-by-card', [AbsensiSholatController::class, 'getUserByCard'])
            ->middleware('permission:absensi.view')
            ->name('get-user-by-card');
        Route::post('/manual-store', [AbsensiSholatController::class, 'manualStore'])
            ->middleware('permission:absensi.create')
            ->name('manual-store');
        Route::get('/get-data', [AbsensiSholatController::class, 'getData'])
            ->middleware('permission:absensi.view')
            ->name('get-data');
        Route::get('/get-users', [AbsensiSholatController::class, 'getUsers'])
            ->middleware('permission:absensi.view')
            ->name('get-users');
        Route::get('/export-siswa', [AbsensiSholatController::class, 'exportSiswa'])
            ->middleware('permission:absensi.export')
            ->name('export-siswa');
        Route::get('/export-guru', [AbsensiSholatController::class, 'exportGuru'])
            ->middleware('permission:absensi.export')
            ->name('export-guru');
    });
    
    // RFID
    Route::prefix('rfid')->name('rfid.')->group(function () {
        Route::post('/scan/siswa', [RfidController::class, 'scanSiswa'])
            ->middleware('permission:absensi.create')
            ->name('scan.siswa');
        Route::post('/scan/guru', [RfidController::class, 'scanGuru'])
            ->middleware('permission:absensi.create')
            ->name('scan.guru');
        Route::get('/card-info', [RfidController::class, 'getCardInfo'])
            ->middleware('permission:absensi.view')
            ->name('card-info');
    });
    
    // Keuangan
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/get-siswa-by-kelas', [KeuanganController::class, 'getSiswaByKelas'])
            ->middleware('permission:keuangan.view')
            ->name('get-siswa-by-kelas');
        Route::get('/cari-siswa', [KeuanganController::class, 'cariSiswa'])
            ->middleware('permission:keuangan.view')
            ->name('cari-siswa');
        Route::get('/spp', [KeuanganController::class, 'sppIndex'])
            ->middleware('permission:keuangan.view')
            ->name('spp');
        Route::get('/spp/create', [KeuanganController::class, 'sppCreate'])
            ->middleware('permission:keuangan.create')
            ->name('spp.create');
        Route::post('/spp', [KeuanganController::class, 'sppStore'])
            ->middleware('permission:keuangan.create')
            ->name('spp.store');
        Route::get('/spp/{id}/edit', [KeuanganController::class, 'sppEdit'])
            ->middleware('permission:keuangan.edit')
            ->name('spp.edit');
        Route::put('/spp/{id}', [KeuanganController::class, 'sppUpdate'])
            ->middleware('permission:keuangan.edit')
            ->name('spp.update');
        Route::delete('/spp/{id}', [KeuanganController::class, 'sppDestroy'])
            ->middleware('permission:keuangan.delete')
            ->name('spp.destroy');
        Route::get('/spp/laporan', [KeuanganController::class, 'sppLaporan'])
            ->middleware('permission:keuangan.view')
            ->name('spp.laporan');
        Route::get('/pembayaran-lain', [KeuanganController::class, 'pembayaranLainIndex'])
            ->middleware('permission:keuangan.view')
            ->name('pembayaran-lain.index');
        Route::get('/pembayaran-lain/create', [KeuanganController::class, 'pembayaranLainCreate'])
            ->middleware('permission:keuangan.create')
            ->name('pembayaran-lain.create');
        Route::post('/pembayaran-lain', [KeuanganController::class, 'pembayaranLainStore'])
            ->middleware('permission:keuangan.create')
            ->name('pembayaran-lain.store');
        Route::get('/pembayaran-lain/{id}/edit', [KeuanganController::class, 'pembayaranLainEdit'])
            ->middleware('permission:keuangan.edit')
            ->name('pembayaran-lain.edit');
        Route::put('/pembayaran-lain/{id}', [KeuanganController::class, 'pembayaranLainUpdate'])
            ->middleware('permission:keuangan.edit')
            ->name('pembayaran-lain.update');
        Route::delete('/pembayaran-lain/{id}', [KeuanganController::class, 'pembayaranLainDestroy'])
            ->middleware('permission:keuangan.delete')
            ->name('pembayaran-lain.destroy');
        Route::get('/laporan', [KeuanganController::class, 'laporanKeuangan'])
            ->middleware('permission:keuangan.view')
            ->name('laporan');
        Route::get('/laporan/export', [KeuanganController::class, 'exportLaporan'])
            ->middleware('permission:keuangan.export')
            ->name('laporan.export');
    });
    
    // Arsip
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArsipController::class, 'index'])
            ->middleware('permission:arsip.view')
            ->name('index');
        Route::get('/create', [ArsipController::class, 'create'])
            ->middleware('permission:arsip.create')
            ->name('create');
        Route::post('/', [ArsipController::class, 'store'])
            ->middleware('permission:arsip.create')
            ->name('store');
        Route::get('/trash', [ArsipController::class, 'trash'])
            ->middleware('permission:arsip.view')
            ->name('trash');
        Route::get('/{id}', [ArsipController::class, 'show'])
            ->middleware('permission:arsip.view')
            ->name('show');
        Route::get('/{id}/edit', [ArsipController::class, 'edit'])
            ->middleware('permission:arsip.edit')
            ->name('edit');
        Route::put('/{id}', [ArsipController::class, 'update'])
            ->middleware('permission:arsip.edit')
            ->name('update');
        Route::delete('/{id}', [ArsipController::class, 'destroy'])
            ->middleware('permission:arsip.delete')
            ->name('destroy');
        Route::get('/{id}/download', [ArsipController::class, 'download'])
            ->middleware('permission:arsip.view')
            ->name('download');
        Route::post('/{id}/restore', [ArsipController::class, 'restore'])
            ->middleware('permission:arsip.edit')
            ->name('restore');
        Route::delete('/{id}/force-delete', [ArsipController::class, 'forceDelete'])
            ->middleware('permission:arsip.delete')
            ->name('force-delete');
    });
    
    // Komunikasi
    Route::prefix('komunikasi')->name('komunikasi.')->group(function () {
        Route::get('/', [AdministrasiKomunikasi::class, 'index'])
            ->middleware('permission:komunikasi.view')
            ->name('index');
        Route::get('/create', [AdministrasiKomunikasi::class, 'create'])
            ->middleware('permission:komunikasi.create')
            ->name('create');
        Route::post('/', [AdministrasiKomunikasi::class, 'store'])
            ->middleware('permission:komunikasi.create')
            ->name('store');
        Route::get('/{id}', [AdministrasiKomunikasi::class, 'show'])
            ->middleware('permission:komunikasi.view')
            ->name('show');
        Route::delete('/{id}', [AdministrasiKomunikasi::class, 'destroy'])
            ->middleware('permission:komunikasi.delete')
            ->name('destroy');
        Route::get('/broadcast', [AdministrasiKomunikasi::class, 'broadcastForm'])
            ->middleware('permission:komunikasi.create')
            ->name('broadcast');
        Route::post('/broadcast/send', [AdministrasiKomunikasi::class, 'sendBroadcast'])
            ->middleware('permission:komunikasi.create')
            ->name('send-broadcast');
        Route::get('/unread-count', [AdministrasiKomunikasi::class, 'getUnreadCount'])
            ->middleware('permission:komunikasi.view')
            ->name('unread-count');
    });

    // Galeri
    Route::prefix('galeri')->name('galeri.')->group(function () {
        Route::get('/', [GaleriController::class, 'index'])
            ->middleware('permission:galeri.view')
            ->name('index');
        Route::get('/create', [GaleriController::class, 'create'])
            ->middleware('permission:galeri.create')
            ->name('create');
        Route::post('/', [GaleriController::class, 'store'])
            ->middleware('permission:galeri.create')
            ->name('store');
        Route::get('/{id}/edit', [GaleriController::class, 'edit'])
            ->middleware('permission:galeri.edit')
            ->name('edit');
        Route::put('/{id}', [GaleriController::class, 'update'])
            ->middleware('permission:galeri.edit')
            ->name('update');
        Route::delete('/{id}', [GaleriController::class, 'destroy'])
            ->middleware('permission:galeri.delete')
            ->name('destroy');
        Route::post('/{id}/status', [GaleriController::class, 'updateStatus'])
            ->middleware('permission:galeri.edit')
            ->name('status');
    });

    // =============================================
    // PROFIL ADMINISTRASI
    // =============================================
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [AdministrasiDashboard::class, 'profil'])
            ->middleware('permission:profil.view')
            ->name('index');
        Route::get('/edit', [AdministrasiDashboard::class, 'editProfil'])
            ->middleware('permission:profil.edit')
            ->name('edit');
        Route::put('/', [AdministrasiDashboard::class, 'updateProfil'])
            ->middleware('permission:profil.edit')
            ->name('update');
        Route::post('/change-password', [AdministrasiDashboard::class, 'changePassword'])
            ->middleware('permission:profil.edit')
            ->name('change-password');
    });

    // =============================================
    // PENGATURAN ADMINISTRASI
    // =============================================
    Route::get('/pengaturan', function () { 
        return view('administrasi.pengaturan.index'); 
    })
    ->middleware('permission:pengaturan.view')
    ->name('pengaturan');
});

// ================== ADMIN ROLE & PERMISSION (Super Admin Only) ==================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Manajemen Role
    Route::resource('roles', RoleController::class)
        ->middleware('permission:role.view|role.create|role.edit|role.delete');
    Route::get('/roles/{id}/permissions', [RoleController::class, 'permissions'])
        ->middleware('permission:role.permission')
        ->name('roles.permissions');
    Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermissions'])
        ->middleware('permission:role.permission')
        ->name('roles.assign-permissions');
    
    // Manajemen Permission
    Route::resource('permissions', PermissionController::class)
        ->middleware('permission:permission.view|permission.create|permission.delete');
});

// ================== GURU ==================
Route::middleware(['auth', 'check.role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
    
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', [NilaiController::class, 'index'])
            ->middleware('permission:nilai.view')
            ->name('index');
        Route::get('/input', [NilaiController::class, 'input'])
            ->middleware('permission:nilai.create')
            ->name('input');
        Route::post('/save', [NilaiController::class, 'save'])
            ->middleware('permission:nilai.create')
            ->name('save');
        Route::post('/publish', [NilaiController::class, 'publish'])
            ->middleware('permission:nilai.edit')
            ->name('publish');
        Route::get('/raport', [NilaiController::class, 'raport'])
            ->middleware('permission:nilai.view')
            ->name('raport');
        Route::get('/raport/detail/{siswaId}', [NilaiController::class, 'raportDetail'])
            ->middleware('permission:nilai.view')
            ->name('raport.detail');
        Route::get('/raport/cetak/{siswaId}', [NilaiController::class, 'raportCetak'])
            ->middleware('permission:nilai.export')
            ->name('raport.cetak');
        Route::get('/export/{kelas_id?}', [NilaiController::class, 'export'])
            ->middleware('permission:nilai.export')
            ->name('export');
    });
    
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiSiswaController::class, 'index'])
            ->middleware('permission:absensi.view')
            ->name('index');
        Route::get('/scan', [AbsensiSiswaController::class, 'scan'])
            ->middleware('permission:absensi.create')
            ->name('scan');
        Route::post('/store', [AbsensiSiswaController::class, 'store'])
            ->middleware('permission:absensi.create')
            ->name('store');
        Route::get('/riwayat', [AbsensiSiswaController::class, 'riwayat'])
            ->middleware('permission:absensi.view')
            ->name('riwayat');
        Route::get('/laporan', [AbsensiSiswaController::class, 'laporan'])
            ->middleware('permission:absensi.view')
            ->name('laporan');
        Route::get('/export', [AbsensiSiswaController::class, 'export'])
            ->middleware('permission:absensi.export')
            ->name('export');
        Route::get('/rekap', [AbsensiSiswaController::class, 'rekap'])
            ->middleware('permission:absensi.view')
            ->name('rekap');
        Route::get('/get-siswa-by-card', [AbsensiSiswaController::class, 'getSiswaByCard'])
            ->middleware('permission:absensi.view')
            ->name('get-siswa-by-card');
        Route::post('/scan-store', [AbsensiSiswaController::class, 'scanStore'])
            ->middleware('permission:absensi.create')
            ->name('scan-store');
        Route::get('/get-mata-pelajaran', [AbsensiSiswaController::class, 'getMataPelajaranByKelas'])
            ->middleware('permission:absensi.view')
            ->name('get-mata-pelajaran');
    });
    
    Route::prefix('komunikasi')->name('komunikasi.')->group(function () {
        Route::get('/', [GuruKomunikasi::class, 'index'])
            ->middleware('permission:komunikasi.view')
            ->name('index');
        Route::get('/create', [GuruKomunikasi::class, 'create'])
            ->middleware('permission:komunikasi.create')
            ->name('create');
        Route::post('/', [GuruKomunikasi::class, 'store'])
            ->middleware('permission:komunikasi.create')
            ->name('store');
        Route::get('/{id}', [GuruKomunikasi::class, 'show'])
            ->middleware('permission:komunikasi.view')
            ->name('show');
        Route::delete('/{id}', [GuruKomunikasi::class, 'destroy'])
            ->middleware('permission:komunikasi.delete')
            ->name('destroy');
        Route::post('/{id}/mark-read', [GuruKomunikasi::class, 'markAsRead'])
            ->middleware('permission:komunikasi.edit')
            ->name('mark-read');
        Route::get('/mark-all-read', [GuruKomunikasi::class, 'markAllAsRead'])
            ->middleware('permission:komunikasi.edit')
            ->name('mark-all-read');
        Route::get('/unread-count', [GuruKomunikasi::class, 'getUnreadCount'])
            ->middleware('permission:komunikasi.view')
            ->name('unread-count');
        Route::post('/{id}/reply', [GuruKomunikasi::class, 'reply'])
            ->middleware('permission:komunikasi.create')
            ->name('reply');
    });
    
    Route::get('/kalender', [KalenderController::class, 'index'])
        ->middleware('permission:kalender.view')
        ->name('kalender');
    Route::get('/kalender/events', [KalenderController::class, 'getEvents'])
        ->middleware('permission:kalender.view')
        ->name('kalender.events');
    Route::post('/kalender/event', [KalenderController::class, 'storeEvent'])
        ->middleware('permission:kalender.create')
        ->name('kalender.event.store');
    Route::put('/kalender/event/{id}', [KalenderController::class, 'updateEvent'])
        ->middleware('permission:kalender.edit')
        ->name('kalender.event.update');
    Route::delete('/kalender/event/{id}', [KalenderController::class, 'destroyEvent'])
        ->middleware('permission:kalender.delete')
        ->name('kalender.event.destroy');
    
    Route::prefix('kinerja')->name('kinerja.')->group(function () {
        Route::get('/', [KinerjaController::class, 'index'])
            ->middleware('permission:kinerja.view')
            ->name('index');
        Route::get('/detail/{id}', [KinerjaController::class, 'detail'])
            ->middleware('permission:kinerja.view')
            ->name('detail');
        Route::get('/export', [KinerjaController::class, 'export'])
            ->middleware('permission:kinerja.export')
            ->name('export');
    });
    
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [GuruDashboard::class, 'profil'])
            ->middleware('permission:profil.view')
            ->name('index');
        Route::get('/edit', [GuruDashboard::class, 'editProfil'])
            ->middleware('permission:profil.edit')
            ->name('edit');
        Route::put('/', [GuruDashboard::class, 'updateProfil'])
            ->middleware('permission:profil.edit')
            ->name('update');
        Route::post('/change-password', [GuruDashboard::class, 'changePassword'])
            ->middleware('permission:profil.edit')
            ->name('change-password');
    });
});

// ================== SISWA ==================
Route::middleware(['auth', 'check.role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
    
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])
            ->middleware('permission:profil.view')
            ->name('index');
        Route::get('/edit', [ProfilController::class, 'edit'])
            ->middleware('permission:profil.edit')
            ->name('edit');
        Route::put('/', [ProfilController::class, 'update'])
            ->middleware('permission:profil.edit')
            ->name('update');
        Route::post('/change-password', [ProfilController::class, 'changePassword'])
            ->middleware('permission:profil.edit')
            ->name('change-password');
    });
    
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', [SiswaNilai::class, 'index'])
            ->middleware('permission:nilai.view')
            ->name('index');
        Route::get('/raport', [SiswaNilai::class, 'raport'])
            ->middleware('permission:nilai.view')
            ->name('raport');
        Route::get('/detail/{mapel_id}', [SiswaNilai::class, 'detail'])
            ->middleware('permission:nilai.view')
            ->name('detail');
    });
    
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [SiswaAbsensi::class, 'index'])
            ->middleware('permission:absensi.view')
            ->name('index');
        Route::get('/riwayat', [SiswaAbsensi::class, 'riwayat'])
            ->middleware('permission:absensi.view')
            ->name('riwayat');
        Route::get('/rekap', [SiswaAbsensi::class, 'rekap'])
            ->middleware('permission:absensi.view')
            ->name('rekap');
    });
    
    Route::prefix('tugas')->name('tugas.')->group(function () {
        Route::get('/', [SiswaTugas::class, 'index'])
            ->middleware('permission:tugas.view')
            ->name('index');
        Route::get('/{id}', [SiswaTugas::class, 'show'])
            ->middleware('permission:tugas.view')
            ->name('show');
        Route::post('/{id}/kumpul', [SiswaTugas::class, 'kumpul'])
            ->middleware('permission:tugas.create')
            ->name('kumpul');
        Route::delete('/{id}/batal', [SiswaTugas::class, 'batalKumpul'])
            ->middleware('permission:tugas.delete')
            ->name('batal');
    });
    
    Route::prefix('kalender')->name('kalender.')->group(function () {
        Route::get('/', [SiswaKalender::class, 'index'])
            ->middleware('permission:kalender.view')
            ->name('index');
        Route::get('/api/events', [SiswaKalender::class, 'getEvents'])
            ->middleware('permission:kalender.view')
            ->name('api.events');
    });
    
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])
            ->middleware('permission:pembayaran.view')
            ->name('index');
        Route::get('/riwayat', [PembayaranController::class, 'riwayat'])
            ->middleware('permission:pembayaran.view')
            ->name('riwayat');
        Route::get('/tagihan-tahunan', [PembayaranController::class, 'tagihanTahunan'])
            ->middleware('permission:pembayaran.view')
            ->name('tagihan-tahunan');
        Route::get('/cetak-struk/{id}', [PembayaranController::class, 'cetakStruk'])
            ->middleware('permission:pembayaran.export')
            ->name('cetak-struk');
        Route::get('/{id}', [PembayaranController::class, 'show'])
            ->middleware('permission:pembayaran.view')
            ->name('show');
    });
});

// ================== TEST ROUTE ==================
Route::middleware(['auth'])->get('/test-role', function () {
    return 'Role Anda: ' . auth()->user()->role;
});

// ================== FALLBACK ==================
Route::fallback(function () {
    return redirect()->route('landing')->with('error', 'Halaman tidak ditemukan');
});