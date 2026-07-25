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
                $namaKelas = trim($row['nama_kelas']?? $row['nama']?? $row['nama kelas']?? $row['kelas']?? '');
                $tingkat = trim($row['tingkat']?? '');
                $namaJurusan = trim($row['jurusan']?? $row['kode_jurusan']?? '');
                $kapasitas = trim($row['kapasitas']?? '40');

                if (empty($namaKelas)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Nama kosong";
                    continue;
                }

                // PAKSA pakai nama_kelas, jangan nama
                if (Kelas::where('nama_kelas', $namaKelas)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: '{$namaKelas}' sudah ada";
                    continue;
                }

                if (empty($tingkat)) $tingkat = explode(' ', $namaKelas)[0];
                $tingkat = strtoupper($tingkat);

                $jurusanId = null;
                if (!empty($namaJurusan)) {
                    $jur = Jurusan::where('nama', 'ILIKE', "%{$namaJurusan}%")
                           ->orWhere('kode_jurusan', 'ILIKE', "%{$namaJurusan}%")->first();
                    if ($jur) $jurusanId = $jur->id;
                }

                try {
                    Kelas::create([
                        'nama_kelas' => $namaKelas,
                        'tingkat' => $tingkat,
                        'jurusan_id' => $jurusanId,
                        'kapasitas' => (int)$kapasitas,
                        'status' => 'aktif',
                    ]);
                    $this->successCount++;
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: ".$e->getMessage();
                    Log::error($e->getMessage());
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Fatal: ".$e->getMessage();
        }
    }

    public function getSuccessCount(){ return $this->successCount; }
    public function getFailedCount(){ return $this->failedCount; }
    public function getErrors(){ return $this->errors; }
}