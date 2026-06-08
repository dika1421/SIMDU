<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $failedCount = 0;
    private $errors = [];
    
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena baris 1 adalah header
                
                // Validasi required fields
                $requiredFields = ['nama_guru', 'jk', 'tempat_lahir', 'tanggal_lahir', 'alamat_lengkap', 'nuptk', 'jabatan'];
                $missingFields = [];
                
                foreach ($requiredFields as $field) {
                    if (empty($row[$field])) {
                        $fieldName = strtoupper(str_replace('_', ' ', $field));
                        $missingFields[] = $fieldName;
                    }
                }
                
                if (!empty($missingFields)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Field " . implode(', ', $missingFields) . " wajib diisi.";
                    continue;
                }
                
                // Validasi JK (Jenis Kelamin)
                $jk = strtoupper(trim($row['jk']));
                if (!in_array($jk, ['L', 'P'])) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: JK harus bernilai 'L' atau 'P'.";
                    continue;
                }
                
                // Validasi NUPTK unik
                if (Guru::where('nuptk', $row['nuptk'])->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NUPTK '{$row['nuptk']}' sudah terdaftar.";
                    continue;
                }
                
                // Validasi tanggal lahir
                if (!strtotime($row['tanggal_lahir'])) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Format TANGGAL LAHIR tidak valid (gunakan YYYY-MM-DD).";
                    continue;
                }
                
                try {
                    // Buat user
                    $email = strtolower(str_replace(' ', '.', $row['nama_guru'])) . '@guru.sch.id';
                    $email = $this->generateUniqueEmail($email);
                    
                    $user = User::create([
                        'name' => $row['nama_guru'],
                        'email' => $email,
                        'password' => Hash::make('password123'),
                        'role' => 'guru',
                        'status' => 'aktif'
                    ]);
                    
                    // Buat guru
                    Guru::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $row['nama_guru'],
                        'jenis_kelamin' => $jk,
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => date('Y-m-d', strtotime($row['tanggal_lahir'])),
                        'alamat_lengkap' => $row['alamat_lengkap'],
                        'nuptk' => $row['nuptk'],
                        'jabatan' => $row['jabatan'],
                        'nama_universitas' => $row['nama_universitas'] ?? null,
                        'jurusan_pendidikan' => $row['jurusan'] ?? null,
                        'tahun_lulus' => $row['tahun_lulus'] ?? null,
                        'tmt' => !empty($row['tmt_smk_darul_ulum']) ? date('Y-m-d', strtotime($row['tmt_smk_darul_ulum'])) : null,
                        'no_telepon' => $row['no_telepon'] ?? null,
                        'status' => 'aktif'
                    ]);
                    
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error('Import Guru Error: ' . $e->getMessage());
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Error: " . $e->getMessage();
            Log::error('Import Guru Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate unique email
     */
    private function generateUniqueEmail($email)
    {
        $original = $email;
        $counter = 1;
        
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@guru.sch.id', $counter . '@guru.sch.id', $original);
            $counter++;
        }
        
        return $email;
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