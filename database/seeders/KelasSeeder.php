<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            [
                'nama' => 'pemasaran 01',
                'kode_kelas' => 'PMN-01',
                'jurusan_id' => $this->getJurusanId('Pemasaran'),
                'wali_kelas_id' => null,
                'tingkat' => 'X',
                'kapasitas' => 36,
                'tahun_ajaran' => '2025/2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'tata boga 01',
                'kode_kelas' => 'TBG-01',
                'jurusan_id' => $this->getJurusanId('Tata Boga'),
                'wali_kelas_id' => null,
                'tingkat' => 'X',
                'kapasitas' => 36,
                'tahun_ajaran' => '2025/2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kelas as $data) {
            $exists = DB::table('kelas')->where('kode_kelas', $data['kode_kelas'])->exists();
            if (!$exists) {
                DB::table('kelas')->insert($data);
            }
        }

        $this->command->info('✅ Kelas berhasil dibuat!');
    }

    private function getJurusanId($namaJurusan)
    {
        $jurusan = DB::table('jurusan')->where('nama', $namaJurusan)->first();
        return $jurusan ? $jurusan->id : null;
    }
}