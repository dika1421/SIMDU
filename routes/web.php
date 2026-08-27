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
    Route::get('/dashboard', [AdministrasiDashboard::class, 'index'])->name('dashboard');
    
    // Manajemen Jurusan
    Route::resource('jurusan', JurusanController::class);
    
    // Manajemen Kelas
    Route::post('/kelas/import', [KelasController::class, 'import'])->name('kelas.import');
    Route::get('/kelas/download-template', [KelasController::class, 'downloadTemplate'])->name('kelas.download-template');
    Route::get('/kelas/export', [KelasController::class, 'export'])->name('kelas.export');
    Route::resource('kelas', KelasController::class);
    Route::get('/kelas/get-kelas-list', [KelasController::class, 'getKelasList'])->name('kelas.get-list');
    
    // Manajemen Mata Pelajaran
    Route::resource('mapel', MapelController::class);
    Route::get('/mapel/get-mapel-list', [MapelController::class, 'getMapelList'])->name('mapel.get-list');
    Route::post('/mapel/import', [MapelController::class, 'import'])->name('mapel.import');
    Route::get('/mapel/download-template', [MapelController::class, 'downloadTemplate'])->name('mapel.download-template');
    Route::get('/mapel/export', [MapelController::class, 'export'])->name('mapel.export');
    
    // Manajemen Siswa
    Route::resource('siswa', SiswaController::class);
    Route::post('/siswa/{siswa}/mutasi', [SiswaController::class, 'mutasi'])->name('siswa.mutasi');
    Route::get('/siswa/{id}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.reset-password');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/download-template', [SiswaController::class, 'downloadTemplate'])->name('siswa.download-template');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    
    // Manajemen Guru
    Route::resource('guru', AdministrasiGuruController::class);
    Route::post('/guru/import', [AdministrasiGuruController::class, 'import'])->name('guru.import');
    Route::get('/guru/download-template', [AdministrasiGuruController::class, 'downloadTemplate'])->name('guru.download-template');
    Route::get('/guru/export', [AdministrasiGuruController::class, 'export'])->name('guru.export');
    
    // Manajemen Jadwal
    Route::get('/jadwal/kalender', [JadwalController::class, 'kalender'])->name('jadwal.kalender');
    Route::post('/jadwal/copy', [JadwalController::class, 'copy'])->name('jadwal.copy');
    Route::post('/jadwal/check-conflict', [JadwalController::class, 'checkConflict'])->name('jadwal.check-conflict');
    Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');
    Route::resource('jadwal', JadwalController::class);
    
    // Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', function() { return redirect()->route('administrasi.absensi.scan'); })->name('index');
        Route::get('/scan', [RfidController::class, 'index'])->name('scan');
        Route::get('/siswa', [AdministrasiAbsensi::class, 'siswa'])->name('siswa');
        Route::post('/siswa/store', [AdministrasiAbsensi::class, 'storeSiswa'])->name('store-siswa');
        Route::get('/guru', [AdministrasiAbsensi::class, 'guru'])->name('guru');
        Route::post('/guru/store', [AdministrasiAbsensi::class, 'storeGuru'])->name('store-guru');
        Route::get('/rekap-siswa', [AdministrasiAbsensi::class, 'rekapSiswa'])->name('rekap-siswa');
        Route::get('/rekap-guru', [AdministrasiAbsensi::class, 'rekapGuru'])->name('rekap-guru');
        Route::get('/export-siswa', [AdministrasiAbsensi::class, 'exportSiswa'])->name('export-siswa');
        Route::get('/export-guru', [AdministrasiAbsensi::class, 'exportGuru'])->name('export-guru');
    });
    
    // Absensi Sholat
    Route::prefix('absensi-sholat')->name('absensi-sholat.')->group(function () {
        Route::get('/', [AbsensiSholatController::class, 'index'])->name('index');
        Route::get('/dashboard', [AbsensiSholatController::class, 'dashboard'])->name('dashboard');
        Route::get('/siswa', [AbsensiSholatController::class, 'siswa'])->name('siswa');
        Route::get('/guru', [AbsensiSholatController::class, 'guru'])->name('guru');
        Route::get('/rekap-siswa', [AbsensiSholatController::class, 'rekapSiswa'])->name('rekap-siswa');
        Route::get('/rekap-guru', [AbsensiSholatController::class, 'rekapGuru'])->name('rekap-guru');
        Route::get('/scan', [AbsensiSholatController::class, 'scan'])->name('scan');
        Route::post('/scan/store', [AbsensiSholatController::class, 'scanStore'])->name('scan-store');
        Route::get('/get-user-by-card', [AbsensiSholatController::class, 'getUserByCard'])->name('get-user-by-card');
        Route::post('/manual-store', [AbsensiSholatController::class, 'manualStore'])->name('manual-store');
        Route::get('/get-data', [AbsensiSholatController::class, 'getData'])->name('get-data');
        Route::get('/get-users', [AbsensiSholatController::class, 'getUsers'])->name('get-users');
        Route::get('/export-siswa', [AbsensiSholatController::class, 'exportSiswa'])->name('export-siswa');
        Route::get('/export-guru', [AbsensiSholatController::class, 'exportGuru'])->name('export-guru');
    });
    
    // RFID
    Route::prefix('rfid')->name('rfid.')->group(function () {
        Route::post('/scan/siswa', [RfidController::class, 'scanSiswa'])->name('scan.siswa');
        Route::post('/scan/guru', [RfidController::class, 'scanGuru'])->name('scan.guru');
        Route::get('/card-info', [RfidController::class, 'getCardInfo'])->name('card-info');
    });
    
    // Keuangan
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/get-siswa-by-kelas', [KeuanganController::class, 'getSiswaByKelas'])->name('get-siswa-by-kelas');
        Route::get('/cari-siswa', [KeuanganController::class, 'cariSiswa'])->name('cari-siswa');
        Route::get('/spp', [KeuanganController::class, 'sppIndex'])->name('spp');
        Route::get('/spp/create', [KeuanganController::class, 'sppCreate'])->name('spp.create');
        Route::post('/spp', [KeuanganController::class, 'sppStore'])->name('spp.store');
        Route::get('/spp/{id}/edit', [KeuanganController::class, 'sppEdit'])->name('spp.edit');
        Route::put('/spp/{id}', [KeuanganController::class, 'sppUpdate'])->name('spp.update');
        Route::delete('/spp/{id}', [KeuanganController::class, 'sppDestroy'])->name('spp.destroy');
        Route::get('/spp/laporan', [KeuanganController::class, 'sppLaporan'])->name('spp.laporan');
        Route::get('/pembayaran-lain', [KeuanganController::class, 'pembayaranLainIndex'])->name('pembayaran-lain.index');
        Route::get('/pembayaran-lain/create', [KeuanganController::class, 'pembayaranLainCreate'])->name('pembayaran-lain.create');
        Route::post('/pembayaran-lain', [KeuanganController::class, 'pembayaranLainStore'])->name('pembayaran-lain.store');
        Route::get('/pembayaran-lain/{id}/edit', [KeuanganController::class, 'pembayaranLainEdit'])->name('pembayaran-lain.edit');
        Route::put('/pembayaran-lain/{id}', [KeuanganController::class, 'pembayaranLainUpdate'])->name('pembayaran-lain.update');
        Route::delete('/pembayaran-lain/{id}', [KeuanganController::class, 'pembayaranLainDestroy'])->name('pembayaran-lain.destroy');
        Route::get('/laporan', [KeuanganController::class, 'laporanKeuangan'])->name('laporan');
        Route::get('/laporan/export', [KeuanganController::class, 'exportLaporan'])->name('laporan.export');
    });
    
    // Arsip
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArsipController::class, 'index'])->name('index');
        Route::get('/create', [ArsipController::class, 'create'])->name('create');
        Route::post('/', [ArsipController::class, 'store'])->name('store');
        Route::get('/trash', [ArsipController::class, 'trash'])->name('trash');
        Route::get('/{id}', [ArsipController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArsipController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [ArsipController::class, 'download'])->name('download');
        Route::post('/{id}/restore', [ArsipController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [ArsipController::class, 'forceDelete'])->name('force-delete');
    });
    
    // Komunikasi
    Route::prefix('komunikasi')->name('komunikasi.')->group(function () {
        Route::get('/', [AdministrasiKomunikasi::class, 'index'])->name('index');
        Route::get('/create', [AdministrasiKomunikasi::class, 'create'])->name('create');
        Route::post('/', [AdministrasiKomunikasi::class, 'store'])->name('store');
        Route::get('/{id}', [AdministrasiKomunikasi::class, 'show'])->name('show');
        Route::delete('/{id}', [AdministrasiKomunikasi::class, 'destroy'])->name('destroy');
        Route::get('/broadcast', [AdministrasiKomunikasi::class, 'broadcastForm'])->name('broadcast');
        Route::post('/broadcast/send', [AdministrasiKomunikasi::class, 'sendBroadcast'])->name('send-broadcast');
        Route::get('/unread-count', [AdministrasiKomunikasi::class, 'getUnreadCount'])->name('unread-count');
    });

    // Galeri
    Route::prefix('galeri')->name('galeri.')->group(function () {
        Route::get('/', [GaleriController::class, 'index'])->name('index');
        Route::get('/create', [GaleriController::class, 'create'])->name('create');
        Route::post('/', [GaleriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GaleriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GaleriController::class, 'update'])->name('update');
        Route::delete('/{id}', [GaleriController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/status', [GaleriController::class, 'updateStatus'])->name('status');
    });

    // =============================================
    // PROFIL ADMINISTRASI
    // =============================================
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [AdministrasiDashboard::class, 'profil'])->name('index');
        Route::get('/edit', [AdministrasiDashboard::class, 'editProfil'])->name('edit');
        Route::put('/', [AdministrasiDashboard::class, 'updateProfil'])->name('update');
        Route::post('/change-password', [AdministrasiDashboard::class, 'changePassword'])->name('change-password');
    });

    // ✅ REDIRECT KE DASHBOARD (KARENA MENU SUDAH DIHAPUS)
    Route::get('/pengaturan', function () { 
        return redirect()->route('administrasi.dashboard')
            ->with('info', 'Halaman pengaturan sedang dalam pengembangan.'); 
    })->name('pengaturan');
});

// ================== GURU ==================
Route::middleware(['auth', 'check.role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', [NilaiController::class, 'index'])->name('index');
        Route::get('/input', [NilaiController::class, 'input'])->name('input');
        Route::post('/save', [NilaiController::class, 'save'])->name('save');
        Route::post('/publish', [NilaiController::class, 'publish'])->name('publish');
        Route::get('/raport', [NilaiController::class, 'raport'])->name('raport');
        Route::get('/raport/detail/{siswaId}', [NilaiController::class, 'raportDetail'])->name('raport.detail');
        Route::get('/raport/cetak/{siswaId}', [NilaiController::class, 'raportCetak'])->name('raport.cetak');
        Route::get('/export/{kelas_id?}', [NilaiController::class, 'export'])->name('export');
    });
    
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiSiswaController::class, 'index'])->name('index');
        Route::get('/scan', [AbsensiSiswaController::class, 'scan'])->name('scan');
        Route::post('/store', [AbsensiSiswaController::class, 'store'])->name('store');
        Route::get('/riwayat', [AbsensiSiswaController::class, 'riwayat'])->name('riwayat');
        Route::get('/laporan', [AbsensiSiswaController::class, 'laporan'])->name('laporan');
        Route::get('/export', [AbsensiSiswaController::class, 'export'])->name('export');
        Route::get('/rekap', [AbsensiSiswaController::class, 'rekap'])->name('rekap');
        Route::get('/get-siswa-by-card', [AbsensiSiswaController::class, 'getSiswaByCard'])->name('get-siswa-by-card');
        Route::post('/scan-store', [AbsensiSiswaController::class, 'scanStore'])->name('scan-store');
        Route::get('/get-mata-pelajaran', [AbsensiSiswaController::class, 'getMataPelajaranByKelas'])->name('get-mata-pelajaran');
    });
    
    Route::prefix('komunikasi')->name('komunikasi.')->group(function () {
        Route::get('/', [GuruKomunikasi::class, 'index'])->name('index');
        Route::get('/create', [GuruKomunikasi::class, 'create'])->name('create');
        Route::post('/', [GuruKomunikasi::class, 'store'])->name('store');
        Route::get('/{id}', [GuruKomunikasi::class, 'show'])->name('show');
        Route::delete('/{id}', [GuruKomunikasi::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-read', [GuruKomunikasi::class, 'markAsRead'])->name('mark-read');
        Route::get('/mark-all-read', [GuruKomunikasi::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [GuruKomunikasi::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{id}/reply', [GuruKomunikasi::class, 'reply'])->name('reply');
    });
    
    Route::get('/kalender', [KalenderController::class, 'index'])->name('kalender');
    Route::get('/kalender/events', [KalenderController::class, 'getEvents'])->name('kalender.events');
    Route::post('/kalender/event', [KalenderController::class, 'storeEvent'])->name('kalender.event.store');
    Route::put('/kalender/event/{id}', [KalenderController::class, 'updateEvent'])->name('kalender.event.update');
    Route::delete('/kalender/event/{id}', [KalenderController::class, 'destroyEvent'])->name('kalender.event.destroy');
    
    Route::prefix('kinerja')->name('kinerja.')->group(function () {
        Route::get('/', [KinerjaController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [KinerjaController::class, 'detail'])->name('detail');
        Route::get('/export', [KinerjaController::class, 'export'])->name('export');
    });
    
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [GuruDashboard::class, 'profil'])->name('index');
        Route::get('/edit', [GuruDashboard::class, 'editProfil'])->name('edit');
        Route::put('/', [GuruDashboard::class, 'updateProfil'])->name('update');
        Route::post('/change-password', [GuruDashboard::class, 'changePassword'])->name('change-password');
    });
});

// ================== SISWA ==================
Route::middleware(['auth', 'check.role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::get('/edit', [ProfilController::class, 'edit'])->name('edit');
        Route::put('/', [ProfilController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfilController::class, 'changePassword'])->name('change-password');
    });
    
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', [SiswaNilai::class, 'index'])->name('index');
        Route::get('/raport', [SiswaNilai::class, 'raport'])->name('raport');
        Route::get('/detail/{mapel_id}', [SiswaNilai::class, 'detail'])->name('detail');
    });
    
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [SiswaAbsensi::class, 'index'])->name('index');
        Route::get('/riwayat', [SiswaAbsensi::class, 'riwayat'])->name('riwayat');
        Route::get('/rekap', [SiswaAbsensi::class, 'rekap'])->name('rekap');
    });
    
    Route::prefix('tugas')->name('tugas.')->group(function () {
        Route::get('/', [SiswaTugas::class, 'index'])->name('index');
        Route::get('/{id}', [SiswaTugas::class, 'show'])->name('show');
        Route::post('/{id}/kumpul', [SiswaTugas::class, 'kumpul'])->name('kumpul');
        Route::delete('/{id}/batal', [SiswaTugas::class, 'batalKumpul'])->name('batal');
    });
    
    Route::prefix('kalender')->name('kalender.')->group(function () {
        Route::get('/', [SiswaKalender::class, 'index'])->name('index');
        Route::get('/api/events', [SiswaKalender::class, 'getEvents'])->name('api.events');
    });
    
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/riwayat', [PembayaranController::class, 'riwayat'])->name('riwayat');
        Route::get('/tagihan-tahunan', [PembayaranController::class, 'tagihanTahunan'])->name('tagihan-tahunan');
        Route::get('/cetak-struk/{id}', [PembayaranController::class, 'cetakStruk'])->name('cetak-struk');
        Route::get('/{id}', [PembayaranController::class, 'show'])->name('show');
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