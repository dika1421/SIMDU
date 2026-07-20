<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin (role: administrasi)
            [
                'name' => 'Administrator',
                'email' => 'admin@sekolah.sch.id',
                'role' => 'administrasi',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Kepala Sekolah (role: kepala_sekolah)
            [
                'name' => 'Hj. Jubaedah, SE',
                'email' => 'jubaedah@guru.sch.id',
                'role' => 'kepala_sekolah',
                'password' => Hash::make('simdu#10062'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Admin Tata Usaha (role: administrasi)
            [
                'name' => 'Nata Wijaya, S. Pd. I',
                'email' => 'nata.wijaya@guru.sch.id',
                'role' => 'administrasi',
                'password' => Hash::make('simdu#20003'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Operator (role: administrasi) - SYARIPUDIN
            [
                'name' => 'Syaripudin, S.Pd.I.,M.Ag.,Gr',
                'email' => 'syaripudin.operator@guru.sch.id',
                'role' => 'administrasi',
                'password' => Hash::make('simdu#20060'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Guru 1
            [
                'name' => 'H. Rojudin, S.Pd',
                'email' => 'rojudin@guru.sch.id',
                'role' => 'guru',
                'password' => Hash::make('simdu#30013'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Guru 2
            [
                'name' => 'Siti Sopiah, S. Pd',
                'email' => 'siti.sopiah@guru.sch.id',
                'role' => 'guru',
                'password' => Hash::make('simdu#30003'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Guru 3 - Nurlailah
            [
                'name' => 'Nurlailah Qadariah, S. Pd',
                'email' => 'nurlailah.qadariah@guru.sch.id',
                'role' => 'guru',
                'password' => Hash::make('simdu#30022'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Siswa 1
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@siswa.sch.id',
                'role' => 'siswa',
                'password' => Hash::make('simdu#47890'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Siswa 2
            [
                'name' => 'Siti Aisyah',
                'email' => 'siti@siswa.sch.id',
                'role' => 'siswa',
                'password' => Hash::make('simdu#47891'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            $exists = DB::table('users')->where('email', $user['email'])->exists();
            if (!$exists) {
                DB::table('users')->insert($user);
                $this->command->info("✅ User {$user['name']} berhasil dibuat!");
            } else {
                $this->command->warn("⚠️ User dengan email {$user['email']} sudah ada, dilewati.");
            }
        }

        $this->command->info('✅ Data users berhasil dibuat!');
        $this->command->info('📊 Total: ' . count($users) . ' user');
    }
}