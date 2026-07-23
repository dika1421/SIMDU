<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\Mapel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GuruImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $failedCount = 0;
    private $errors = [];
    private $rowData = [];
    private $isDebug = true; // Set false di production
    
    public function collection(Collection $rows)
    {
        // ========== LOG UNTUK VPS ==========
        $this->logToFile("=== START IMPORT GURU ===");
        $this->logToFile("Total data: " . $rows->count());
        $this->logToFile("Memory usage: " . memory_get_usage() / 1024 / 1024 . " MB");
        
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                
                // ========== AMBIL DATA ==========
                $this->rowData = [
                    'row' => $rowNumber,
                    'nama_guru' => trim($row[1] ?? ''),
                    'jk' => trim($row[2] ?? ''),
                    'tempat_tanggal_lahir' => trim($row[3] ?? ''),
                    'alamat' => trim($row[4] ?? ''),
                    'nuptk' => trim(str_replace(' ', '', $row[5] ?? '')),
                    'jabatan' => trim($row[6] ?? ''),
                    'universitas' => trim($row[7] ?? ''),
                    'jurusan' => trim($row[8] ?? ''),
                    'tahun_lulus' => trim($row[9] ?? ''),
                    'tmt' => trim($row[10] ?? ''),
                    'mata_pelajaran' => trim($row[11] ?? '')
                ];
                
                // ========== LOG PER ROW (DEBUG) ==========
                if ($this->isDebug) {
                    $this->logToFile("Processing row {$rowNumber}: " . json_encode($this->rowData));
                }
                
                // ========== VALIDASI SEDERHANA ==========
                $errors = $this->validateRow();
                
                if (!empty($errors)) {
                    $this->failedCount++;
                    foreach ($errors as $error) {
                        $this->errors[] = "Baris {$rowNumber}: {$error}";
                    }
                    $this->logToFile("Row {$rowNumber}: VALIDATION FAILED - " . implode(', ', $errors));
                    continue;
                }
                
                // ========== CEK DUPLIKAT ==========
                if (!$this->checkDuplicates()) {
                    $this->failedCount++;
                    continue;
                }
                
                // ========== PROSES DATA ==========
                try {
                    $result = $this->processRow();
                    
                    if ($result['success']) {
                        $this->successCount++;
                        $this->logToFile("Row {$rowNumber}: SUCCESS - {$this->rowData['nama_guru']}");
                    } else {
                        $this->failedCount++;
                        $this->errors[] = "Baris {$rowNumber}: " . $result['message'];
                        $this->logToFile("Row {$rowNumber}: ERROR - " . $result['message']);
                    }
                    
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    $this->logToFile("Row {$rowNumber}: EXCEPTION - " . $e->getMessage());
                    $this->logToFile("Trace: " . $e->getTraceAsString());
                    
                    // Rollback per row
                    DB::rollBack();
                    DB::beginTransaction();
                }
            }
            
            DB::commit();
            
            // ========== LOG HASIL ==========
            $this->logToFile("=== IMPORT SELESAI ===");
            $this->logToFile("✅ Berhasil: {$this->successCount}");
            $this->logToFile("❌ Gagal: {$this->failedCount}");
            $this->logToFile("Memory usage: " . memory_get_usage() / 1024 / 1024 . " MB");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logToFile("❌ CRITICAL ERROR: " . $e->getMessage());
            $this->logToFile("Trace: " . $e->getTraceAsString());
            $this->errors[] = "Error: " . $e->getMessage();
            throw $e;
        }
    }
    
    /**
     * Validasi per row
     */
    private function validateRow()
    {
        $errors = [];
        
        // Validasi Nama
        if (empty($this->rowData['nama_guru'])) {
            $errors[] = "NAMA GURU tidak boleh kosong";
        }
        
        // Validasi JK
        $jk = strtoupper($this->rowData['jk']);
        if (empty($jk) || !in_array($jk, ['L', 'P'])) {
            $errors[] = "JK harus 'L' atau 'P' (ditemukan: {$this->rowData['jk']})";
        }
        
        // Validasi Jabatan
        if (empty($this->rowData['jabatan'])) {
            $errors[] = "JABATAN tidak boleh kosong";
        }
        
        // Validasi Tahun Lulus
        if (!empty($this->rowData['tahun_lulus'])) {
            if (!is_numeric($this->rowData['tahun_lulus'])) {
                $errors[] = "TAHUN LULUS harus berupa angka";
            } else {
                $tahun = (int)$this->rowData['tahun_lulus'];
                if ($tahun < 1950 || $tahun > date('Y')) {
                    $errors[] = "TAHUN LULUS harus antara 1950 - " . date('Y');
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Cek duplikat
     */
    private function checkDuplicates()
    {
        $rowNumber = $this->rowData['row'];
        
        if (!empty($this->rowData['nuptk'])) {
            $existing = Guru::where('nuptk', $this->rowData['nuptk'])->first();
            
            if ($existing) {
                $this->errors[] = "Baris {$rowNumber}: NUPTK '{$this->rowData['nuptk']}' sudah terdaftar";
                $this->logToFile("Row {$rowNumber}: DUPLICATE NUPTK - {$this->rowData['nuptk']}");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Proses satu row
     */
    private function processRow()
    {
        try {
            // Parse tanggal
            list($tempatLahir, $tanggalLahir) = $this->parseTempatTanggal(
                $this->rowData['tempat_tanggal_lahir']
            );
            
            // Generate data
            $email = $this->generateUniqueEmail($this->generateEmail($this->rowData['nama_guru']));
            $nip = $this->generateNIP($this->rowData['row']);
            list($role, $roleNumber) = $this->determineRole($this->rowData['jabatan']);
            $password = $this->generatePassword($roleNumber, $this->rowData['nuptk']);
            
            // Buat User
            $user = User::create([
                'name' => $this->rowData['nama_guru'],
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'nuptk' => $this->rowData['nuptk'] ?: null,
                'status' => 'aktif'
            ]);
            
            // Buat Guru
            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nuptk' => $this->rowData['nuptk'] ?: null,
                'nama_lengkap' => $this->rowData['nama_guru'],
                'jenis_kelamin' => strtoupper($this->rowData['jk']),
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalLahir,
                'alamat' => $this->rowData['alamat'],
                'pendidikan_terakhir' => $this->getPendidikanTerakhir($this->rowData['tahun_lulus']),
                'jurusan_pendidikan' => $this->rowData['jurusan'],
                'universitas' => $this->rowData['universitas'],
                'tahun_lulus' => is_numeric($this->rowData['tahun_lulus']) ? (int)$this->rowData['tahun_lulus'] : null,
                'tmt_masuk' => $this->rowData['tmt'],
                'tmt' => $this->rowData['tmt'],
                'status_kepegawaian' => 'aktif',
                'agama' => 'Islam',
                'status' => 'aktif',
                'jabatan' => $this->rowData['jabatan']
            ]);
            
            // Proses Mata Pelajaran
            if (!empty($this->rowData['mata_pelajaran'])) {
                $this->processMataPelajaran($guru, $this->rowData['mata_pelajaran']);
            }
            
            return ['success' => true, 'message' => 'OK'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Parse tempat dan tanggal lahir
     */
    private function parseTempatTanggal($tempatTanggalLahir)
    {
        $tempatLahir = '';
        $tanggalLahir = null;
        
        if (empty($tempatTanggalLahir)) {
            return [$tempatLahir, $tanggalLahir];
        }
        
        // Pisahkan dengan koma
        if (strpos($tempatTanggalLahir, ',') !== false) {
            $parts = explode(',', $tempatTanggalLahir, 2);
            $tempatLahir = trim($parts[0]);
            $tanggalString = trim($parts[1] ?? '');
            
            if (!empty($tanggalString)) {
                $tanggalLahir = $this->parseDate($tanggalString);
            }
        } else {
            // Coba parse sebagai tanggal
            $parsed = $this->parseDate($tempatTanggalLahir);
            if ($parsed) {
                $tanggalLahir = $parsed;
            } else {
                $tempatLahir = $tempatTanggalLahir;
            }
        }
        
        return [$tempatLahir, $tanggalLahir];
    }
    
    /**
     * Parse berbagai format tanggal
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;
        
        $dateString = trim($dateString);
        
        // Format: d/m/Y atau d-m-Y
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];
            
            if (checkdate($month, $day, $year)) {
                return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            }
        }
        
        // Format: Y-m-d
        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $dateString, $matches)) {
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];
            
            if (checkdate($month, $day, $year)) {
                return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            }
        }
        
        // Coba dengan Carbon
        try {
            $date = \Carbon\Carbon::parse($dateString);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Invalid date
        }
        
        $this->logToFile("⚠️ Format tanggal tidak dikenal: {$dateString}");
        return null;
    }
    
    /**
     * Generate NIP unik
     */
    private function generateNIP($rowNumber)
    {
        $nip = 'NIP' . date('Ymd') . str_pad($rowNumber, 4, '0', STR_PAD_LEFT);
        
        $counter = 1;
        while (Guru::where('nip', $nip)->exists()) {
            $nip = 'NIP' . date('Ymd') . str_pad($rowNumber . $counter, 4, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $nip;
    }
    
    /**
     * Generate email dari nama
     */
    private function generateEmail($nama)
    {
        $email = strtolower(trim($nama));
        $email = str_replace([' ', '.', ',', "'"], '.', $email);
        $email = preg_replace('/[^a-zA-Z0-9.]/', '', $email);
        $email = preg_replace('/\.+/', '.', $email);
        $email = trim($email, '.');
        $email = substr($email, 0, 50);
        
        return $email . '@guru.sch.id';
    }
    
    /**
     * Generate email unik
     */
    private function generateUniqueEmail($email)
    {
        $original = $email;
        $counter = 1;
        
        while (User::where('email', $email)->exists()) {
            $parts = explode('@', $original);
            $email = $parts[0] . $counter . '@' . $parts[1];
            $counter++;
            
            if ($counter > 100) {
                $email = $parts[0] . '_' . uniqid() . '@' . $parts[1];
                break;
            }
        }
        
        return $email;
    }
    
    /**
     * Tentukan role
     */
    private function determineRole($jabatan)
    {
        $jabatanUpper = strtoupper($jabatan);
        
        if (strpos($jabatanUpper, 'KEPALA') !== false) {
            return ['kepala_sekolah', '1'];
        }
        
        if (strpos($jabatanUpper, 'TATA USAHA') !== false || 
            strpos($jabatanUpper, 'OPERATOR') !== false ||
            strpos($jabatanUpper, 'ADMIN') !== false) {
            return ['administrasi', '2'];
        }
        
        return ['guru', '3'];
    }
    
    /**
     * Generate password
     */
    private function generatePassword($roleNumber, $nuptk)
    {
        $suffix = !empty($nuptk) ? substr($nuptk, -4) : '0000';
        return 'simdu#' . $roleNumber . $suffix;
    }
    
    /**
     * Process mata pelajaran
     */
    private function processMataPelajaran($guru, $mataPelajaranList)
    {
        $mapelNames = array_map('trim', explode(',', $mataPelajaranList));
        $mapelIds = [];
        
        foreach ($mapelNames as $mapelName) {
            if (!empty($mapelName)) {
                $mapel = Mapel::where('nama_mapel', 'LIKE', "%{$mapelName}%")->first();
                
                if ($mapel) {
                    $mapelIds[] = $mapel->id;
                } else {
                    // Buat mapel baru jika tidak ditemukan
                    $newMapel = Mapel::create([
                        'nama_mapel' => $mapelName,
                        'kode_mapel' => strtoupper(substr($mapelName, 0, 3)) . '_' . uniqid()
                    ]);
                    $mapelIds[] = $newMapel->id;
                    $this->logToFile("📚 Mapel baru dibuat: {$mapelName}");
                }
            }
        }
        
        if (!empty($mapelIds)) {
            $guru->mataPelajaran()->sync($mapelIds);
        }
    }
    
    /**
     * Tentukan pendidikan terakhir
     */
    private function getPendidikanTerakhir($tahunLulus)
    {
        if (empty($tahunLulus)) return 'S1';
        
        $tahun = (int)$tahunLulus;
        
        if ($tahun >= 2023) return 'S3';
        if ($tahun >= 2019) return 'S2';
        if ($tahun >= 2005) return 'S1';
        if ($tahun >= 1995) return 'D4';
        if ($tahun >= 1985) return 'D3';
        if ($tahun >= 1975) return 'D1';
        
        return 'SMA';
    }
    
    /**
     * Log ke file untuk VPS
     */
    private function logToFile($message)
    {
        // Log ke laravel
        Log::info($message);
        
        // Log ke file custom
        $logFile = storage_path('logs/import_' . date('Y-m-d') . '.log');
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    // ========== GETTER ==========
    public function getSuccessCount() { return $this->successCount; }
    public function getFailedCount() { return $this->failedCount; }
    public function getErrors() { return $this->errors; }
}