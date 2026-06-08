<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $guru = [
            [
                'user_id' => $this->getUserIdByEmail('jubaedah@guru.sch.id'),
                'nuptk' => '195875365530062',
                'nip' => '19750626202501001',
                'nama_lengkap' => 'Hj. Jubaedah, SE',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '1975-06-26',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'S1',
                'jurusan_pendidikan' => 'Ekonomi Manajemen',
                'tanggal_masuk' => '2007-01-01',
                'alamat_lengkap' => 'Jl. H. Suhaemi No. 30 RT 04/08 Duren Mekar, Bojongsari Depok',
                'jabatan' => 'KEPALA SEKOLAH',
                'nama_universitas' => 'Universitas Muhammadiyyah Jakarta',
                'tahun_lulus' => 1998,
                'tmt' => '2007-01-01',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $this->getUserIdByEmail('rojudin@guru.sch.id'),
                'nuptk' => '623775065320013',
                'nip' => '19720905202501002',
                'nama_lengkap' => 'H. Rojudin, S.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '1972-09-05',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'S1',
                'jurusan_pendidikan' => 'Peranian (Akta IV)',
                'tanggal_masuk' => '2014-01-01',
                'alamat_lengkap' => 'Bojongsari RT. 02/03 Bojongsari Kota Depok',
                'jabatan' => 'WAKABIDKUR',
                'nama_universitas' => 'Universitas Muhammadiyyah Jakarta',
                'tahun_lulus' => 1998,
                'tmt' => '2014-01-01',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($guru as $data) {
            $exists = DB::table('guru')->where('nuptk', $data['nuptk'])->exists();
            if (!$exists) {
                DB::table('guru')->insert($data);
            }
        }

        $this->command->info('✅ Data guru berhasil dibuat!');
    }

    private function getUserIdByEmail($email)
    {
        $user = DB::table('users')->where('email', $email)->first();
        return $user ? $user->id : null;
    }
}