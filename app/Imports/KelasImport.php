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
                
                // Ambil data dari row
                $namaKelas = trim($row['nama_kelas'] ?? '');
                $kodeKelas = trim($row['kode_kelas'] ?? '');
                $tingkat = trim($row['tingkat'] ?? '');
                $kodeJurusan = trim($row['kode_jurusan'] ?? '');
                $kapasitas = trim($row['kapasitas'] ?? '36');
                $status = trim($row['status'] ?? 'aktif');
                $keterangan = trim($row['keterangan'] ?? '');
                
                // Validasi required fields
                if (empty($namaKelas)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NAMA KELAS tidak boleh kosong.";
                    continue;
                }
                
                // Cek apakah nama kelas sudah ada
                if (Kelas::where('nama', $namaKelas)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Nama kelas '{$namaKelas}' sudah terdaftar.";
                    continue;
                }
                
                // Validasi tingkat
                $tingkatList = ['X', 'XI', 'XII', 'XIII'];
                if (empty($tingkat) || !in_array($tingkat, $tingkatList)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: TINGKAT harus X, XI, XII, atau XIII (ditemukan: '{$tingkat}').";
                    continue;
                }
                
                // Cari jurusan berdasarkan kode jurusan
                $jurusanId = null;
                if (!empty($kodeJurusan)) {
                    $jurusan = Jurusan::where('kode_jurusan', $kodeJurusan)->first();
                    if (!$jurusan) {
                        $this->failedCount++;
                        $this->errors[] = "Baris {$rowNumber}: Kode Jurusan '{$kodeJurusan}' tidak ditemukan.";
                        continue;
                    }
                    $jurusanId = $jurusan->id;
                }
                
                // Validasi kapasitas
                $kapasitasInt = (int) $kapasitas;
                if ($kapasitasInt < 1 || $kapasitasInt > 100) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: KAPASITAS harus antara 1-100 (ditemukan: '{$kapasitas}').";
                    continue;
                }
                
                // Validasi status
                if (!in_array($status, ['aktif', 'nonaktif'])) {
                    $status = 'aktif';
                }
                
                // Generate kode kelas jika kosong
                if (empty($kodeKelas)) {
                    $kodeKelas = strtoupper(substr($namaKelas, 0, 3)) . '-' . rand(10, 99);
                }
                
                // Cek apakah kode kelas sudah ada
                if (Kelas::where('kode_kelas', $kodeKelas)->exists()) {
                    $kodeKelas = $kodeKelas . '-' . rand(1, 9);
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
                        'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error('Import Kelas Error: ' . $e->getMessage());
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Error: " . $e->getMessage();
            Log::error('Import Kelas Error: ' . $e->getMessage());
        }
    }
    
    public function getSuccessCount()
    {
        return $this->successCount;
    }
    
    public function getFailedCount()
    {
        return $this->failedCount;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}