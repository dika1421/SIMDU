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
                
                // ========== AMBIL DATA (LANGSUNG DARI INDEX) ==========
                $namaGuru = trim($row[1] ?? '');
                $jk = trim($row[2] ?? '');
                $tempatTanggalLahir = trim($row[3] ?? '');
                $alamat = trim($row[4] ?? '');
                $nuptk = trim(str_replace(' ', '', $row[5] ?? ''));
                $jabatan = trim($row[6] ?? '');
                $universitas = trim($row[7] ?? '');
                $jurusan = trim($row[8] ?? '');
                $tahunLulus = trim($row[9] ?? '');
                $tmt = trim($row[10] ?? '');
                $mataPelajaranList = trim($row[11] ?? '');
                
                // ========== VALIDASI DASAR ==========
                if (empty($namaGuru)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NAMA GURU tidak boleh kosong.";
                    continue;
                }
                
                if (empty($jk) || !in_array(strtoupper($jk), ['L', 'P'])) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: JK harus 'L' atau 'P'";
                    continue;
                }
                
                if (empty($jabatan)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: JABATAN tidak boleh kosong.";
                    continue;
                }
                
                // ========== PARSE TEMPAT & TANGGAL (TANPA VALIDASI!) ==========
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
                
                // ========== CEK DUPLIKAT NUPTK ==========
                if (!empty($nuptk) && Guru::where('nuptk', $nuptk)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NUPTK '{$nuptk}' sudah terdaftar.";
                    continue;
                }
                
                // ========== GENERATE NIP (TANPA TANGGAL!) ==========
                $nip = 'NIP' . date('Ymd') . str_pad($rowNumber, 4, '0', STR_PAD_LEFT);
                while (Guru::where('nip', $nip)->exists()) {
                    $nip = 'NIP' . date('Ymd') . str_pad($rowNumber . rand(1, 99), 4, '0', STR_PAD_LEFT);
                }
                
                // ========== GENERATE EMAIL ==========
                $email = strtolower(str_replace(' ', '.', $namaGuru)) . '@guru.sch.id';
                $email = preg_replace('/[^a-zA-Z0-9.@]/', '', $email);
                $email = $this->generateUniqueEmail($email);
                
                // ========== TENTUKAN ROLE ==========
                $role = 'guru';
                $roleNumber = '3';
                if (stripos($jabatan, 'KEPALA SEKOLAH') !== false) {
                    $role = 'kepala_sekolah';
                    $roleNumber = '1';
                } elseif (stripos($jabatan, 'TATA USAHA') !== false || stripos($jabatan, 'OPERATOR') !== false) {
                    $role = 'administrasi';
                    $roleNumber = '2';
                }
                
                $password = 'simdu#' . $roleNumber . substr($nuptk, -4);
                
                // ========== BUAT USER ==========
                $user = User::create([
                    'name' => $namaGuru,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => $role,
                    'nuptk' => $nuptk,
                    'status' => 'aktif'
                ]);
                
                // ========== BUAT GURU (TANPA PARSE TANGGAL!) ==========
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'nuptk' => $nuptk,
                    'nama_lengkap' => $namaGuru,
                    'jenis_kelamin' => strtoupper($jk),
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'alamat' => $alamat,
                    'pendidikan_terakhir' => $this->getPendidikanTerakhir($tahunLulus),
                    'jurusan_pendidikan' => $jurusan,
                    'universitas' => $universitas,
                    'tahun_lulus' => is_numeric($tahunLulus) ? (int)$tahunLulus : null,
                    'tmt_masuk' => $tmt,
                    'tmt' => $tmt,
                    'status_kepegawaian' => 'aktif',
                    'agama' => 'Islam',
                    'status' => 'aktif'
                ]);
                
                // ========== PROSES MATA PELAJARAN ==========
                if (!empty($mataPelajaranList)) {
                    $mapelNames = array_map('trim', explode(',', $mataPelajaranList));
                    $mapelIds = [];
                    
                    foreach ($mapelNames as $mapelName) {
                        $mapel = \App\Models\Mapel::where('nama_mapel', 'LIKE', "%{$mapelName}%")->first();
                        if ($mapel) {
                            $mapelIds[] = $mapel->id;
                        }
                    }
                    
                    if (!empty($mapelIds)) {
                        $guru->mataPelajaran()->sync($mapelIds);
                    }
                }
                
                $this->successCount++;
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());
            $this->errors[] = "Error: " . $e->getMessage();
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
    
    private function getPendidikanTerakhir($tahunLulus)
    {
        if (empty($tahunLulus)) return 'S1';
        $tahun = (int)$tahunLulus;
        if ($tahun >= 2020) return 'S2';
        if ($tahun >= 2010) return 'S1';
        if ($tahun >= 2000) return 'S1';
        if ($tahun >= 1990) return 'D4';
        if ($tahun >= 1980) return 'D3';
        return 'SMA';
    }
    
    public function getSuccessCount() { return $this->successCount; }
    public function getFailedCount() { return $this->failedCount; }
    public function getErrors() { return $this->errors; }
}