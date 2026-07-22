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
    
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                
                // ========== AMBIL DATA ==========
                $nama = trim($row['NAMA GURU'] ?? '');
                $jk = trim($row['JK'] ?? '');
                $tempatTanggalLahir = trim($row['TEMPAT TANGGAL LAHIR'] ?? '');
                $alamat = trim($row['ALAMAT LENGKAP'] ?? '');
                $nuptk = trim(str_replace(' ', '', $row['NUPTK'] ?? ''));
                $jabatan = trim($row['JABATAN'] ?? '');
                $universitas = trim($row['NAMA UNIVERSITAS'] ?? '');
                $jurusan = trim($row['JURUSAN'] ?? '');
                $tahunLulus = trim($row['TAHUN LULUS'] ?? '');
                $tmt = trim($row['TMT SMK DARUL  ULUM'] ?? '');
                
                // ========== VALIDASI (HANYA NAMA & NUPTK) ==========
                if (empty($nama) || empty($nuptk)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Nama dan NUPTK wajib diisi.";
                    continue;
                }
                
                // ========== TENTUKAN ROLE ==========
                $role = 'guru';
                $roleNumber = '3';
                if (stripos($jabatan, 'KEPALA SEKOLAH') !== false) {
                    $role = 'kepala_sekolah';
                    $roleNumber = '1';
                } elseif (stripos($jabatan, 'TATA USAHA') !== false || 
                          stripos($jabatan, 'OPERATOR') !== false) {
                    $role = 'administrasi';
                    $roleNumber = '2';
                }
                
                // ========== BUAT EMAIL ==========
                $email = strtolower(str_replace(' ', '.', $nama)) . '@guru.sch.id';
                $email = preg_replace('/[^a-zA-Z0-9.@]/', '', $email);
                $email = $this->generateUniqueEmail($email);
                
                // ========== CEK DUPLIKAT ==========
                if (User::where('nuptk', $nuptk)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NUPTK '{$nuptk}' sudah terdaftar.";
                    continue;
                }
                
                // ========== PISAHKAN TEMPAT & TANGGAL (TANPA VALIDASI) ==========
                $tempatLahir = '';
                $tanggalLahir = '';
                if (!empty($tempatTanggalLahir)) {
                    if (strpos($tempatTanggalLahir, ',') !== false) {
                        $parts = explode(',', $tempatTanggalLahir, 2);
                        $tempatLahir = trim($parts[0]);
                        $tanggalLahir = trim($parts[1]);
                    } else {
                        $tempatLahir = $tempatTanggalLahir;
                    }
                }
                
                // ========== BUAT USER ==========
                try {
                    $password = 'simdu#' . $roleNumber . substr($nuptk, -4);
                    
                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => $role,
                        'nuptk' => $nuptk,
                        'status' => 'aktif'
                    ]);
                    
                    Guru::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => $jk,
                        'tempat_lahir' => $tempatLahir,
                        'tanggal_lahir' => $tanggalLahir,
                        'alamat_lengkap' => $alamat,
                        'nuptk' => $nuptk,
                        'jabatan' => $jabatan,
                        'nama_universitas' => $universitas,
                        'jurusan_pendidikan' => $jurusan,
                        'tahun_lulus' => $tahunLulus,
                        'tmt' => $tmt,
                        'status' => 'aktif'
                    ]);
                    
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error('Import Error: ' . $e->getMessage());
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Error: " . $e->getMessage();
            Log::error('Import Error: ' . $e->getMessage());
        }
    }
    
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
    
    public function getSuccessCount() { return $this->successCount; }
    public function getFailedCount() { return $this->failedCount; }
    public function getErrors() { return $this->errors; }
}