<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleLoginSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nama_role' => 'kepala_sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'administrasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'guru', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'siswa', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $role) {
            DB::table('role_login')->updateOrInsert(
                ['nama_role' => $role['nama_role']],
                $role
            );
        }

        $this->command->info('✅ Role login berhasil dibuat!');
    }
}