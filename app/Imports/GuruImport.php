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
use Carbon\Carbon;

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
                $rowNumber = $index + 2;
                
                // ========== AMBIL DATA DARI EXCEL ==========
                $nama = trim($row['nama_guru'] ?? '');
                $jk = trim($row['jk'] ?? '');
                
                // 🔥 PERBAIKAN: Cek kolom 'tempat_tanggal_lahir' (gabungan) atau kolom terpisah
                $tempatLahir = trim($row['tempat_lahir'] ?? '');
                $tanggalLahir = trim($row['tanggal_lahir'] ?? '');
                
                // Jika kolom terpisah kosong, coba dari kolom gabungan
                if (empty($tempatLahir) && empty($tanggalLahir)) {
                    $tempatTanggalLahir = trim($row['tempat_tanggal_lahir'] ?? '');
                    if (!empty($tempatTanggalLahir) && strpos($tempatTanggalLahir, ',') !== false) {
                        $parts = explode(',', $tempatTanggalLahir, 2);
                        $tempatLahir = trim($parts[0]);
                        $tanggalLahir = trim($parts[1]);
                    } else {
                        $tempatLahir = $tempatTanggalLahir;
                    }
                }
                
                $alamat = trim($row['alamat_lengkap'] ?? '');
                $nuptk = trim(str_replace(' ', '', $row['nuptk'] ?? ''));
                $jabatan = trim($row['jabatan'] ?? '');
                $universitas = trim($row['nama_universitas'] ?? '');
                $jurusan = trim($row['jurusan'] ?? '');
                $tahunLulus = trim($row['tahun_lulus'] ?? '');
                $tmt = trim($row['tmt_smk_darul_ulum'] ?? '');
                $noTelepon = trim($row['no_telepon'] ?? '');
                
                // ========== VALIDASI ==========
                if (empty($nama) || empty($nuptk)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: Nama dan NUPTK wajib diisi.";
                    continue;
                }
                
                // Validasi JK
                $jk = strtoupper($jk);
                if (!in_array($jk, ['L', 'P'])) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: JK harus 'L' atau 'P'.";
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
                
                // ========== FORMAT TANGGAL ==========
                $tanggalLahirFormatted = null;
                if (!empty($tanggalLahir)) {
                    $tanggalLahirFormatted = $this->parseTanggal($tanggalLahir);
                    if (!$tanggalLahirFormatted) {
                        $this->failedCount++;
                        $this->errors[] = "Baris {$rowNumber}: Format TANGGAL LAHIR tidak valid (gunakan: Tempat, 26 Juni 1975).";
                        continue;
                    }
                }
                
                $tmtFormatted = null;
                if (!empty($tmt)) {
                    $tmtFormatted = $this->parseTanggal($tmt);
                }
                
                // ========== CEK DUPLIKAT NUPTK ==========
                $existingUser = User::where('nuptk', $nuptk)->first();
                if ($existingUser) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NUPTK '{$nuptk}' sudah terdaftar.";
                    continue;
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
                    
                    // ========== BUAT GURU ==========
                    Guru::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => $jk,
                        'tempat_lahir' => $tempatLahir,
                        'tanggal_lahir' => $tanggalLahirFormatted,
                        'alamat_lengkap' => $alamat,
                        'nuptk' => $nuptk,
                        'jabatan' => $jabatan,
                        'nama_universitas' => $universitas,
                        'jurusan_pendidikan' => $jurusan,
                        'tahun_lulus' => $tahunLulus,
                        'tmt' => $tmtFormatted,
                        'no_telepon' => $noTelepon,
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
     * Parse tanggal dari format "Bogor, 26 Juni 1975" menjadi "1975-06-26"
     */
    private function parseTanggal($tanggal)
    {
        if (empty($tanggal)) return null;
        
        // Coba format: "Bogor, 26 Juni 1975"
        if (strpos($tanggal, ',') !== false) {
            $parts = explode(',', $tanggal);
            if (count($parts) == 2) {
                $datePart = trim($parts[1]);
                return $this->parseDateString($datePart);
            }
        }
        
        // Coba langsung parse
        return $this->parseDateString($tanggal);
    }
    
    /**
     * Parse berbagai format tanggal ke YYYY-MM-DD
     */
    private function parseDateString($dateString)
    {
        $bulanIndonesia = [
            'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
            'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
            'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12',
            'Nopember' => '11', 'Pebruari' => '02'
        ];
        
        foreach ($bulanIndonesia as $nama => $angka) {
            if (stripos($dateString, $nama) !== false) {
                $dateString = str_ireplace($nama, $angka, $dateString);
            }
        }
        
        // Coba parse dengan Carbon
        try {
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
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