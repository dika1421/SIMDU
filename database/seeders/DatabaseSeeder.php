<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * URUTAN PENTING! Perhatikan dependensi antar tabel
     */
    public function run()
    {
        $this->command->info('=========================================');
        $this->command->info('🚀 MEMULAI PROSES SEEDER DATABASE');
        $this->command->info('=========================================');
        
        // ==================== URUTAN SEEDER ====================
        // 1. Tabel independen (tidak bergantung pada tabel lain)
        $this->call(RoleLoginSeeder::class);
        $this->call(TahunAjaranSeeder::class);
        $this->call(JurusanSeeder::class);
        
        // 2. User (tergantung role, tapi tidak bergantung pada guru/siswa)
        $this->call(UserSeeder::class);
        
        // 3. Guru dan Siswa (tergantung user)
        $this->call(GuruSeeder::class);
        $this->call(SiswaSeeder::class);
        
        // 4. Kelas (tergantung jurusan, dan mungkin wali kelas dari guru)
        $this->call(KelasSeeder::class);
        
        // 5. Tabel lainnya (opsional)
        // $this->call(JadwalSeeder::class);
        // $this->call(MapelSeeder::class);
        // $this->call(NilaiSeeder::class);
        
        $this->command->info('=========================================');
        $this->command->info('✅ SEMUA SEEDER BERHASIL DIJALANKAN!');
        $this->command->info('=========================================');
        
        // Tampilkan informasi login
        $this->command->info('');
        $this->command->info('📋 INFORMASI LOGIN:');
        $this->command->info('-----------------------------------------');
        $this->command->info('🔑 ADMIN (Email): admin@sekolah.sch.id | Password: password');
        $this->command->info('🔑 KEPALA SEKOLAH (NUPTK): 195875365530062 | Password: simdu#40062');
        $this->command->info('🔑 GURU (NUPTK): 623775065320013 | Password: simdu#4013');
        $this->command->info('🔑 SISWA (NIS): 1234567890 | Password: simdu#47890');
        $this->command->info('🔑 SISWA (NIS): 1234567891 | Password: simdu#47891');
        $this->command->info('-----------------------------------------');
    }
}