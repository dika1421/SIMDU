<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = [
            [
                'user_id' => $this->getUserIdByEmail('ahmad@siswa.sch.id'),
                'nis' => '1234567890',
                'nisn' => '9876543210',
                'nama_lengkap' => 'Ahmad Fauzi',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-05-15',
                'agama' => 'Islam',
                'alamat' => 'Jl. Pendidikan No. 10, Jakarta',
                'kelas_id' => null, // Akan diupdate setelah kelas dibuat
                'tahun_masuk' => 2024,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $this->getUserIdByEmail('siti@siswa.sch.id'),
                'nis' => '1234567891',
                'nisn' => '9876543211',
                'nama_lengkap' => 'Siti Aisyah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-08-20',
                'agama' => 'Islam',
                'alamat' => 'Jl. Merdeka No. 5, Bandung',
                'kelas_id' => null,
                'tahun_masuk' => 2024,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($siswa as $data) {
            $exists = DB::table('siswa')->where('nis', $data['nis'])->exists();
            if (!$exists) {
                DB::table('siswa')->insert($data);
            }
        }

        $this->command->info('✅ Data siswa berhasil dibuat!');
    }

    private function getUserIdByEmail($email)
    {
        $user = DB::table('users')->where('email', $email)->first();
        return $user ? $user->id : null;
    }
}