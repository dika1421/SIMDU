<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapelList = [
            // Kelompok A (Umum/Wajib)
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama dan Budi Pekerti', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'PRAK-IBADAH', 'nama' => 'Praktik Ibadah (PAI Mulok)', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'PPKN', 'nama' => 'PPKn', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'BING', 'nama' => 'Bahasa Inggris', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'BAR', 'nama' => 'Bahasa Arab', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'BSU', 'nama' => 'Bahasa Sunda', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'MTK', 'nama' => 'Matematika', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'SJRH', 'nama' => 'Sejarah', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'SB', 'nama' => 'Seni Budaya', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'PJOK', 'nama' => 'Penjaskes (PJOK)', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'INF', 'nama' => 'Informatika', 'kelompok' => 'A', 'status' => 'aktif'],
            ['kode' => 'PJ-IPAS', 'nama' => 'Projek IPAS', 'kelompok' => 'A', 'status' => 'aktif'],
            
            // Kelompok B (Muatan Lokal)
            ['kode' => 'AGM-MULOK', 'nama' => 'Agama Mulok', 'kelompok' => 'B', 'status' => 'aktif'],
            
            // Kelompok C (Kejuruan)
            ['kode' => 'PKK', 'nama' => 'Produk Kreatif dan Kewirausahaan', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ADM-TRANS', 'nama' => 'Administrasi Transaksi', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'PENGEM-PROD', 'nama' => 'Pengemasan dan Pendistribusian Produk', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'BISNIS-ON', 'nama' => 'Bisnis Online', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'KOM-BIS', 'nama' => 'Komunikasi Bisnis', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'SMVM', 'nama' => 'Strategi Marketing Visual Merchandising', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'CS', 'nama' => 'Customer Service', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'MRKT', 'nama' => 'Marketing', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'DESGRAF', 'nama' => 'Desain Grafis', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'PBR', 'nama' => 'Pengelolaan Bisnis Ritel', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'PASTRY', 'nama' => 'Produk Pastry dan Bakery (Elemen 2)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'CAKE', 'nama' => 'Produk Cake dan Kue Indo (Elemen 1)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'PENY-MAKAN-5', 'nama' => 'Penyajian Makanan (Elemen 5)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'PENY-MAKAN-34', 'nama' => 'Penyajian Makanan (Elemen 3-4)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'SHK3', 'nama' => 'Sanitasi Higiene K3', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'BOGA-DASAR', 'nama' => 'Boga Dasar (Elemen 2 dan 6)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'FP', 'nama' => 'Food Product', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-134', 'nama' => 'Elemen 1, 3 dan 4 (BDP)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-12', 'nama' => 'Elemen 1 dan 2 (BDP)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-36', 'nama' => 'Elemen 3 dan 6 (BDP)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-4', 'nama' => 'Elemen 4 (BDP)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-57', 'nama' => 'Elemen 5 dan 7 (BDP)', 'kelompok' => 'C', 'status' => 'aktif'],
            ['kode' => 'ELEMEN-8', 'nama' => 'Elemen 8 Pemasaran', 'kelompok' => 'C', 'status' => 'aktif'],
        ];

        foreach ($mapelList as $mapel) {
            // Cek apakah sudah ada berdasarkan kode atau nama
            $existing = DB::table('mata_pelajarans')
                ->where('kode_mapel', $mapel['kode'])
                ->orWhere('nama_mapel', $mapel['nama'])
                ->first();
            
            if (!$existing) {
                DB::table('mata_pelajarans')->insert([
                    'kode_mapel' => $mapel['kode'],
                    'nama_mapel' => $mapel['nama'],
                    'kelompok' => $mapel['kelompok'],
                    'status' => $mapel['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Log::info("Mata pelajaran {$mapel['nama']} berhasil ditambahkan");
            } else {
                Log::info("Mata pelajaran {$mapel['nama']} sudah ada, skip insert");
            }
        }
    }
}