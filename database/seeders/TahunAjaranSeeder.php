<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        $tahunAjaran = [
            [
                'nama' => '2024/2025',
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2025-06-30',
                'semester' => 'ganjil',
                'is_aktif' => false,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => '2025/2026',
                'tanggal_mulai' => '2025-07-15',
                'tanggal_selesai' => '2026-06-30',
                'semester' => 'ganjil',
                'is_aktif' => true,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tahunAjaran as $ta) {
            DB::table('tahun_ajaran')->updateOrInsert(
                ['nama' => $ta['nama']],
                $ta
            );
        }

        $this->command->info('✅ Tahun ajaran berhasil dibuat!');
    }
}