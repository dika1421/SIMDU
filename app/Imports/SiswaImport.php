<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class SiswaImport implements ToCollection, WithHeadingRow
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
                $nis = trim($row['nis'] ?? '');
                $nisn = trim($row['nisn'] ?? '');
                $namaSiswa = trim($row['nama_siswa'] ?? '');
                $jk = trim($row['jenis_kelamin'] ?? '');
                $tempatLahir = trim($row['tempat_lahir'] ?? '');
                $tanggalLahir = trim($row['tanggal_lahir'] ?? '');
                $alamat = trim($row['alamat'] ?? '');
                $agama = trim($row['agama'] ?? 'Islam');
                $namaAyah = trim($row['nama_ayah'] ?? '');
                $namaIbu = trim($row['nama_ibu'] ?? '');
                $noTelpOrtu = trim($row['no_telp_ortu'] ?? '');
                $tahunMasuk = trim($row['tahun_masuk'] ?? date('Y'));
                $kelasNama = trim($row['kelas'] ?? '');
                
                // Validasi required fields
                if (empty($nis)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NIS tidak boleh kosong.";
                    continue;
                }
                
                if (empty($namaSiswa)) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NAMA SISWA tidak boleh kosong.";
                    continue;
                }
                
                if (empty($jk) || !in_array(strtoupper($jk), ['L', 'P'])) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: JENIS KELAMIN harus 'L' atau 'P'.";
                    continue;
                }
                
                // Cek apakah NIS sudah ada
                if (Siswa::where('nis', $nis)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NIS '{$nis}' sudah terdaftar.";
                    continue;
                }
                
                // Validasi NISN unik jika diisi
                if (!empty($nisn) && Siswa::where('nisn', $nisn)->exists()) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: NISN '{$nisn}' sudah terdaftar.";
                    continue;
                }
                
                // ========== 🔥 PERBAIKAN: SIMPAN TANGGAL APA ADANYA (TANPA VALIDASI) ==========
                $tanggalLahirFormatted = !empty($tanggalLahir) ? $tanggalLahir : null;
                
                // Cari kelas berdasarkan nama
                $kelasId = null;
                if (!empty($kelasNama)) {
                    $kelas = Kelas::where('nama', 'LIKE', "%{$kelasNama}%")->first();
                    if (!$kelas) {
                        $this->failedCount++;
                        $this->errors[] = "Baris {$rowNumber}: Kelas '{$kelasNama}' tidak ditemukan.";
                        continue;
                    }
                    $kelasId = $kelas->id;
                }
                
                try {
                    // Generate email
                    $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaSiswa)) . '@siswa.sch.id';
                    $counter = 1;
                    while (User::where('email', $email)->exists()) {
                        $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaSiswa)) . $counter . '@siswa.sch.id';
                        $counter++;
                    }
                    
                    // Buat user
                    $user = User::create([
                        'name' => $namaSiswa,
                        'email' => $email,
                        'password' => Hash::make($nis),
                        'role' => 'siswa',
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    // Buat siswa
                    Siswa::create([
                        'user_id' => $user->id,
                        'nis' => $nis,
                        'nisn' => $nisn ?: null,
                        'nama_lengkap' => $namaSiswa,
                        'jenis_kelamin' => strtoupper($jk),
                        'tempat_lahir' => $tempatLahir ?: null,
                        'tanggal_lahir' => $tanggalLahirFormatted,
                        'alamat' => $alamat ?: null,
                        'agama' => $agama,
                        'nama_ayah' => $namaAyah ?: null,
                        'nama_ibu' => $namaIbu ?: null,
                        'no_telp_ortu' => $noTelpOrtu ?: null,
                        'kelas_id' => $kelasId,
                        'tahun_masuk' => $tahunMasuk ? (int)$tahunMasuk : date('Y'),
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error('Import Siswa Error: ' . $e->getMessage());
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Error: " . $e->getMessage();
            Log::error('Import Siswa Error: ' . $e->getMessage());
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