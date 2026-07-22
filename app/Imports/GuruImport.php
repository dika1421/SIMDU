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
                $nama = trim($row['NAMA GURU'] ?? $row['nama_guru'] ?? '');
                $jk = trim($row['JK'] ?? $row['jk'] ?? '');
                $tempatTanggalLahir = trim($row['TEMPAT TANGGAL LAHIR'] ?? $row['tempat_tanggal_lahir'] ?? '');
                $alamat = trim($row['ALAMAT LENGKAP'] ?? $row['alamat_lengkap'] ?? '');
                $nuptk = trim(str_replace(' ', '', $row['NUPTK'] ?? $row['nuptk'] ?? ''));
                $jabatan = trim($row['JABATAN'] ?? $row['jabatan'] ?? '');
                $universitas = trim($row['NAMA UNIVERSITAS'] ?? $row['nama_universitas'] ?? '');
                $jurusan = trim($row['JURUSAN'] ?? $row['jurusan'] ?? '');
                $tahunLulus = trim($row['TAHUN LULUS'] ?? $row['tahun_lulus'] ?? '');
                $tmt = trim($row['TMT SMK DARUL ULUM'] ?? $row['tmt_smk_darul_ulum'] ?? '');
                
                // ========== VALIDASI MINIMAL ==========
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
                
                // ========== SIMPAN TANGGAL APA ADANYA (TANPA VALIDASI) ==========
                // Tanggal lahir disimpan sebagai string (tanpa konversi)
                // Jika format tidak sesuai, simpan null atau string asli
                $tanggalLahirFormatted = null;
                if (!empty($tempatTanggalLahir)) {
                    // Pisahkan tempat dan tanggal
                    if (strpos($tempatTanggalLahir, ',') !== false) {
                        $parts = explode(',', $tempatTanggalLahir, 2);
                        $tempatLahir = trim($parts[0]);
                        $tanggalLahir = trim($parts[1]);
                        // Simpan tanggal sebagai string (tanpa validasi)
                        $tanggalLahirFormatted = $tanggalLahir;
                    } else {
                        $tempatLahir = $tempatTanggalLahir;
                    }
                } else {
                    $tempatLahir = '';
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
                        'tempat_lahir' => $tempatLahir ?? '',
                        'tanggal_lahir' => $tanggalLahirFormatted,
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