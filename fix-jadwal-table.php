    <?php
// fix-jadwal-table.php
// Ubah semua referensi jadwals menjadi jadwal

$files = [
    'app/Http/Controllers/Guru/AbsensiSiswaController.php',
    'app/Http/Controllers/Administrasi/JadwalController.php',
    // tambahkan file lain yang error
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $newContent = str_replace('jadwals', 'jadwal', $content);
        file_put_contents($file, $newContent);
        echo "✅ Fixed: $file\n";
    } else {
        echo "⚠️ File tidak ditemukan: $file\n";
    }
}

echo "\n🎉 Selesai! Jalankan php artisan cache:clear\n";