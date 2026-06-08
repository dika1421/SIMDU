<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        $jurusan = [
            ['nama' => 'Pemasaran', 'kode_jurusan' => 'PMN', 'kepala_jurusan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Tata Boga', 'kode_jurusan' => 'TBG', 'kepala_jurusan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jasa Boga', 'kode_jurusan' => 'JBG', 'kepala_jurusan' => null, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($jurusan as $j) {
            DB::table('jurusan')->updateOrInsert(
                ['kode_jurusan' => $j['kode_jurusan']],
                $j
            );
        }

        $this->command->info('✅ Jurusan berhasil dibuat!');
    }
}