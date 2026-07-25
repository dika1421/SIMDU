<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class KelasImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $failedCount = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                // === SUPPORT 2 FORMAT HEADER ===
                // Format 1 (punyamu yang lama): nama_kelas, kode_kelas, tingkat, kode_jurusan, kapasitas, status, keterangan
                // Format 2 (dari file 01_import_kelas.csv): nama, jurusan, tingkat
                $namaKelas = trim($row['nama_kelas']?? $row['nama']?? '');
                $kodeKelas = trim($row['kode_kelas']?? '');
                $tingkat = trim($row['tingkat']?? '');
                $kodeJurusan = trim($row['kode_jurusan']?? '');
                $namaJurusan = trim($row['jurusan']?? ''); // dari file baru
                $kapasitas = trim($row['kapasitas']?? '40');
                $status = trim($row['status']?? 'aktif');
                $keterangan = trim($row['keterangan']?? '');

                // Validasi required
                if (empty($namaKelas)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NAMA KELAS tidak boleh kosong.";
                    continue;
                }

                if (Kelas::where('nama', $namaKelas)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: '{$namaKelas}' sudah ada, dilewati.";
                    continue;
                }

                // Validasi tingkat - auto detect jika kosong
                if (empty($tingkat)) {
                    $tingkat = explode(' ', $namaKelas)[0]?? 'X';
                }
                $tingkat = strtoupper($tingkat);
                $tingkatList = ['X', 'XI', 'XII', 'XIII'];
                if (!in_array($tingkat, $tingkatList)) {
                    $tingkat = 'X';
                }

                // Cari jurusan: bisa dari kode_jurusan ATAU nama jurusan
                $jurusanId = null;
                if (!empty($kodeJurusan)) {
                    $jurusan = Jurusan::where('kode_jurusan', $kodeJurusan)->first();
                    if ($jurusan) $jurusanId = $jurusan->id;
                }
                if (!$jurusanId &&!empty($namaJurusan)) {
                    // Cari by nama jurusan (PEMASARAN / KULINER)
                    $jurusan = Jurusan::where('nama', 'LIKE', "%{$namaJurusan}%")->first();
                    if ($jurusan) {
                        $jurusanId = $jurusan->id;
                    } else {
                        // Auto create jurusan kalau belum ada
                        $jurusan = Jurusan::create([
                            'nama' => strtoupper($namaJurusan),
                            'kode_jurusan' => strtoupper(substr($namaJurusan,0,3)).rand(10,99),
                            'status' => 'aktif'
                        ]);
                        $jurusanId = $jurusan->id;
                    }
                }

                $kapasitasInt = (int) $kapasitas;
                if ($kapasitasInt < 1 || $kapasitasInt > 100) $kapasitasInt = 40;
                if (!in_array($status, ['aktif', 'nonaktif'])) $status = 'aktif';

                if (empty($kodeKelas)) {
                    $kodeKelas = strtoupper(str_replace(' ', '_', $namaKelas));
                }
                if (Kelas::where('kode_kelas', $kodeKelas)->exists()) {
                    $kodeKelas = $kodeKelas. '-'. rand(10,99);
                }

                try {
                    Kelas::create([
                        'nama' => $namaKelas,
                        'kode_kelas' => $kodeKelas,
                        'tingkat' => $tingkat,
                        'jurusan_id' => $jurusanId,
                        'kapasitas' => $kapasitasInt,
                        'status' => $status,
                        'keterangan' => $keterangan,
                        'tahun_ajaran' => date('Y'). '/'. (date('Y') + 1),
                    ]);
                    $this->successCount++;
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: ". $e->getMessage();
                    Log::error('Import Kelas Error: '. $e->getMessage());
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Fatal: ". $e->getMessage();
        }
    }

    public function getSuccessCount(){ return $this->successCount; }
    public function getFailedCount(){ return $this->failedCount; }
    public function getErrors(){ return $this->errors; }
}