<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanSekolah;

class PengaturanSekolahSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanSekolah::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah'           => 'SMA Negeri 1 Jakarta',
                'nuptk'                  => '20123456',
                'akreditasi'             => 'A',
                'kepala_sekolah'         => 'Dr. H. Ahmad Sudrajat, M.Pd',
                'alamat'                 => 'Jl. Merdeka No. 1, Jakarta Pusat',
                'telepon'                => '(021) 1234567',
                'email'                  => 'info@sman1jakarta.sch.id',
                'website'                => 'www.sman1jakarta.sch.id',
                'two_factor'             => true,
                'notifikasi_login'       => true,
                'masa_berlaku_password'  => 90,
                'batas_percobaan_login'  => 5,
            ]
        );
    }
}