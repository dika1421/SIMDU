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
    
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                
                // ========== AMBIL DATA (SESUAI HEADER CSV) ==========
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
                
                // ========== PARSE TANGGAL LAHIR ==========
                $tempatLahir = '';
                $tanggalLahirFormatted = null;
                
                if (!empty($tempatTanggalLahir)) {
                    // Coba pisahkan dengan koma: "Bogor, 26 Juni 1975"
                    if (strpos($tempatTanggalLahir, ',') !== false) {
                        $parts = explode(',', $tempatTanggalLahir, 2);
                        $tempatLahir = trim($parts[0]);
                        $tanggalLahir = trim($parts[1]);
                        
                        // Parse tanggal ke format YYYY-MM-DD
                        $tanggalLahirFormatted = $this->parseTanggal($tanggalLahir);
                        
                        // Jika gagal, simpan sebagai string asli
                        if (!$tanggalLahirFormatted) {
                            $tanggalLahirFormatted = $tanggalLahir;
                        }
                    } else {
                        $tempatLahir = $tempatTanggalLahir;
                    }
                }
                
                // ========== PARSE TMT ==========
                $tmtFormatted = null;
                if (!empty($tmt)) {
                    $tmtFormatted = $this->parseTanggal($tmt);
                    if (!$tmtFormatted) {
                        $tmtFormatted = $tmt;
                    }
                }
                
                // ========== CEK DUPLIKAT ==========
                if (User::where('nuptk', $nuptk)->exists()) {
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
    
    /**
     * Parse tanggal dari format "26 Juni 1975" menjadi "1975-06-26"
     */
    private function parseTanggal($tanggal)
    {
        if (empty($tanggal)) return null;
        
        $tanggal = trim($tanggal);
        
        // Mapping bulan Indonesia ke angka
        $bulan = [
            'Januari' => '01', 'Jan' => '01',
            'Februari' => '02', 'Feb' => '02', 'Pebruari' => '02',
            'Maret' => '03', 'Mar' => '03',
            'April' => '04', 'Apr' => '04',
            'Mei' => '05',
            'Juni' => '06', 'Jun' => '06',
            'Juli' => '07', 'Jul' => '07',
            'Agustus' => '08', 'Ags' => '08',
            'September' => '09', 'Sep' => '09',
            'Oktober' => '10', 'Okt' => '10',
            'November' => '11', 'Nov' => '11', 'Nopember' => '11',
            'Desember' => '12', 'Des' => '12'
        ];
        
        // Coba format: 26 Juni 1975
        foreach ($bulan as $namaBulan => $angkaBulan) {
            if (stripos($tanggal, $namaBulan) !== false) {
                $parts = explode($namaBulan, $tanggal);
                $hari = trim($parts[0]);
                $tahun = trim($parts[1] ?? '');
                
                // Bersihkan dari karakter non-angka
                $hari = preg_replace('/[^0-9]/', '', $hari);
                $tahun = preg_replace('/[^0-9]/', '', $tahun);
                
                if (!empty($hari) && !empty($tahun) && strlen($tahun) == 4) {
                    $hari = str_pad($hari, 2, '0', STR_PAD_LEFT);
                    return $tahun . '-' . $angkaBulan . '-' . $hari;
                }
                break;
            }
        }
        
        // Fallback: coba Carbon
        try {
            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
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