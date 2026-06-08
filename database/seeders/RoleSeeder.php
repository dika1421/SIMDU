<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleLoginSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        DB::table('role_login')->truncate();

        // Insert data
        $users = [
            [
                'nama' => 'Dr. H. Ahmad Sudrajat, M.Pd',
                'email' => 'kepsek@sekolah.sch.id',
                'password' => Hash::make('password'),
                'role' => 'kepala_sekolah',
                'no_telepon' => '081234567890',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Nurhaliza, S.Kom',
                'email' => 'admin@sekolah.sch.id',
                'password' => Hash::make('password'),
                'role' => 'administrasi',
                'no_telepon' => '081234567891',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Drs. Bambang Wijaya',
                'email' => 'bambang@guru.sch.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'no_telepon' => '081234567892',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad@siswa.sch.id',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'no_telepon' => '081234567893',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('role_login')->insert($users);

        $this->command->info('✅ Data role_login berhasil dibuat!');
    }
}