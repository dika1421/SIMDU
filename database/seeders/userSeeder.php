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
                'password' => Hash::make('simdu#40062'), // Akan diupdate nanti
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Guru 1
            [
                'name' => 'H. Rojudin, S.Pd',
                'email' => 'rojudin@guru.sch.id',
                'role' => 'guru',
                'password' => Hash::make('simdu#4013'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Guru 2 (contoh tambahan)
            [
                'name' => 'Siti Sopiah, S. Pd',
                'email' => 'siti.sopiah@guru.sch.id',
                'role' => 'guru',
                'password' => Hash::make('simdu#4003'),
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
            } else {
                $this->command->warn("⚠️ User dengan email {$user['email']} sudah ada, dilewati.");
            }
        }

        $this->command->info('✅ Data users berhasil dibuat!');
        $this->command->info('📊 Total: ' . count($users) . ' user');
    }
}