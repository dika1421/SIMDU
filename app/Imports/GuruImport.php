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
use Carbon\Carbon; // <-- Tambah ini

class GuruImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $failedCount = 0;
    private $errors = [];
    private $rowData = [];
    private $isDebug = true;

    // ARRAY BULAN INDONESIA
    private $bulanIndo = [
        'Januari' => '01', 'Februari' => '02', 'Maret' => '03',
        'April' => '04', 'Mei' => '05', 'Juni' => '06',
        'Juli' => '07', 'Agustus' => '08', 'September' => '09',
        'Oktober' => '10', 'November' => '11', 'Desember' => '12'
    ];

    public function collection(Collection $rows)
    {
        $this->logToFile("=== START IMPORT GURU ===");
        $this->logToFile("Total data: ". $rows->count());

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                $this->rowData = [
                    'row' => $rowNumber,
                    'nama_guru' => trim($row[1]?? ''),
                    'jk' => trim($row[2]?? ''),
                    'tempat_tanggal_lahir' => trim($row[3]?? ''),
                    'alamat' => trim($row[4]?? ''),
                    'nuptk' => trim(str_replace(' ', '', $row[5]?? '')),
                    'jabatan' => trim($row[6]?? ''),
                    'universitas' => trim($row[7]?? ''),
                    'jurusan' => trim($row[8]?? ''),
                    'tahun_lulus' => trim($row[9]?? ''),
                    'tmt' => trim($row[10]?? ''),
                    'mata_pelajaran' => trim($row[11]?? '')
                ];

                if ($this->isDebug) {
                    $this->logToFile("Processing row {$rowNumber}: ". json_encode($this->rowData));
                }

                $errors = $this->validateRow();

                if (!empty($errors)) {
                    $this->failedCount++;
                    foreach ($errors as $error) {
                        $this->errors[] = "Baris {$rowNumber}: {$error}";
                    }
                    $this->logToFile("Row {$rowNumber}: VALIDATION FAILED - ". implode(', ', $errors));
                    continue;
                }

                if (!$this->checkDuplicates()) {
                    $this->failedCount++;
                    continue;
                }

                try {
                    $result = $this->processRow();

                    if ($result['success']) {
                        $this->successCount++;
                        $this->logToFile("Row {$rowNumber}: SUCCESS - {$this->rowData['nama_guru']}");
                    } else {
                        $this->failedCount++;
                        $this->errors[] = "Baris {$rowNumber}: ". $result['message'];
                        $this->logToFile("Row {$rowNumber}: ERROR - ". $result['message']);
                    }

                } catch (\Exception $e) {
                    $this->failedCount++;
                    $this->errors[] = "Baris {$rowNumber}: ". $e->getMessage();
                    $this->logToFile("Row {$rowNumber}: EXCEPTION - ". $e->getMessage());
                    DB::rollBack();
                    DB::beginTransaction();
                }
            }

            DB::commit();
            $this->logToFile("=== IMPORT SELESAI ===");
            $this->logToFile("✅ Berhasil: {$this->successCount}");
            $this->logToFile("❌ Gagal: {$this->failedCount}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->logToFile("❌ CRITICAL ERROR: ". $e->getMessage());
            $this->errors[] = "Error: ". $e->getMessage();
            throw $e;
        }
    }

    private function validateRow()
    {
        $errors = [];
        if (empty($this->rowData['nama_guru'])) {
            $errors[] = "NAMA GURU tidak boleh kosong";
        }
        $jk = strtoupper($this->rowData['jk']);
        if (empty($jk) ||!in_array($jk, ['L', 'P'])) {
            $errors[] = "JK harus 'L' atau 'P' (ditemukan: {$this->rowData['jk']})";
        }
        if (empty($this->rowData['jabatan'])) {
            $errors[] = "JABATAN tidak boleh kosong";
        }
        if (!empty($this->rowData['tahun_lulus']) &&!is_numeric($this->rowData['tahun_lulus'])) {
            $errors[] = "TAHUN LULUS harus berupa angka";
        }
        return $errors;
    }

    private function checkDuplicates()
    {
        $rowNumber = $this->rowData['row'];
        if (!empty($this->rowData['nuptk'])) {
            $existing = Guru::where('nuptk', $this->rowData['nuptk'])->first();
            if ($existing) {
                $this->errors[] = "Baris {$rowNumber}: NUPTK '{$this->rowData['nuptk']}' sudah terdaftar";
                return false;
            }
        }
        return true;
    }

    private function processRow()
    {
        try {
            list($tempatLahir, $tanggalLahir) = $this->parseTempatTanggal($this->rowData['tempat_tanggal_lahir']);

            $email = $this->generateUniqueEmail($this->generateEmail($this->rowData['nama_guru']));
            $nip = $this->generateNIP($this->rowData['row']);
            list($role, $roleNumber) = $this->determineRole($this->rowData['jabatan']);
            $password = $this->generatePassword($roleNumber, $this->rowData['nuptk']);

            $user = User::create([
                'name' => $this->rowData['nama_guru'],
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'nuptk' => $this->rowData['nuptk']?: null,
                'status' => 'aktif'
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nuptk' => $this->rowData['nuptk']?: null,
                'nama_lengkap' => $this->rowData['nama_guru'],
                'jenis_kelamin' => strtoupper($this->rowData['jk']),
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalLahir, // <-- ini udah format Y-m-d
                'alamat' => $this->rowData['alamat'],
                'pendidikan_terakhir' => $this->getPendidikanTerakhir($this->rowData['tahun_lulus']),
                'jurusan_pendidikan' => $this->rowData['jurusan'],
                'universitas' => $this->rowData['universitas'],
                'tahun_lulus' => is_numeric($this->rowData['tahun_lulus'])? (int)$this->rowData['tahun_lulus'] : null,
                'tmt_masuk' => $this->convertDateIndo($this->rowData['tmt']), // <-- TMT juga di convert
                'tmt' => $this->convertDateIndo($this->rowData['tmt']),
                'status_kepegawaian' => 'aktif',
                'agama' => 'Islam',
                'status' => 'aktif',
                'jabatan' => $this->rowData['jabatan']
            ]);

            if (!empty($this->rowData['mata_pelajaran'])) {
                $this->processMataPelajaran($guru, $this->rowData['mata_pelajaran']);
            }

            return ['success' => true, 'message' => 'OK'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function parseTempatTanggal($tempatTanggalLahir)
    {
        $tempatLahir = '';
        $tanggalLahir = null;

        if (empty($tempatTanggalLahir)) return [$tempatLahir, $tanggalLahir];

        if (strpos($tempatTanggalLahir, ',')!== false) {
            $parts = explode(',', $tempatTanggalLahir, 2);
            $tempatLahir = trim($parts[0]);
            $tanggalString = trim($parts[1]?? '');
            if (!empty($tanggalString)) {
                $tanggalLahir = $this->convertDateIndo($tanggalString); // <-- pake fungsi baru
            }
        } else {
            $parsed = $this->convertDateIndo($tempatTanggalLahir);
            if ($parsed) {
                $tanggalLahir = $parsed;
            } else {
                $tempatLahir = $tempatTanggalLahir;
            }
        }
        return [$tempatLahir, $tanggalLahir];
    }

    /**
     * FUNGSI BARU: Convert tanggal Indonesia ke Y-m-d
     * Bisa: 15 Agustus 1973, 15/08/1973, 1973-08-15
     */
    private function convertDateIndo($dateString)
    {
        if (empty($dateString)) return null;
        $dateString = trim($dateString);

        // 1. Format: 15 Agustus 1973
        if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $monthName = ucfirst(strtolower($matches[2]));
            $year = $matches[3];

            if (isset($this->bulanIndo[$monthName])) {
                $month = $this->bulanIndo[$monthName];
                if (checkdate($month, $day, $year)) {
                    return "$year-$month-$day";
                }
            }
        }

        // 2. Format: d/m/Y atau d-m-Y
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            if (checkdate($month, $day, $year)) {
                return "$year-$month-$day";
            }
        }

        // 3. Format: Y-m-d
        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $dateString, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            if (checkdate($month, $day, $year)) {
                return "$year-$month-$day";
            }
        }

        $this->logToFile("⚠️ Format tanggal tidak dikenal: {$dateString}");
        return null;
    }

    private function generateNIP($rowNumber)
    {
        $nip = 'NIP'. date('Ymd'). str_pad($rowNumber, 4, '0', STR_PAD_LEFT);
        $counter = 1;
        while (Guru::where('nip', $nip)->exists()) {
            $nip = 'NIP'. date('Ymd'). str_pad($rowNumber. $counter, 4, '0', STR_PAD_LEFT);
            $counter++;
        }
        return $nip;
    }

    private function generateEmail($nama)
    {
        $email = strtolower(trim($nama));
        $email = str_replace([' ', '.', ',', "'"], '.', $email);
        $email = preg_replace('/[^a-zA-Z0-9.]/', '', $email);
        return $email. '@guru.sch.id';
    }

    private function generateUniqueEmail($email)
    {
        $original = $email;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $parts = explode('@', $original);
            $email = $parts[0]. $counter. '@'. $parts[1];
            $counter++;
        }
        return $email;
    }

    private function determineRole($jabatan)
    {
        $jabatanUpper = strtoupper($jabatan);
        if (strpos($jabatanUpper, 'KEPALA')!== false) return ['kepala_sekolah', '1'];
        if (strpos($jabatanUpper, 'TATA USAHA')!== false) return ['administrasi', '2'];
        return ['guru', '3'];
    }

    private function generatePassword($roleNumber, $nuptk)
    {
        $suffix =!empty($nuptk)? substr($nuptk, -4) : '0000';
        return 'simdu#'. $roleNumber. $suffix;
    }

    private function processMataPelajaran($guru, $mataPelajaranList)
    {
        $mapelNames = array_map('trim', explode(',', $mataPelajaranList));
        $mapelIds = [];
        foreach ($mapelNames as $mapelName) {
            if (!empty($mapelName)) {
                $mapel = Mapel::firstOrCreate(
                    ['nama_mapel' => $mapelName],
                    ['kode_mapel' => strtoupper(substr($mapelName, 0, 3)). '_'. uniqid()]
                );
                $mapelIds[] = $mapel->id;
            }
        }
        if (!empty($mapelIds)) $guru->mataPelajaran()->sync($mapelIds);
    }

    private function getPendidikanTerakhir($tahunLulus)
    {
        if (empty($tahunLulus)) return 'S1';
        $tahun = (int)$tahunLulus;
        if ($tahun >= 2023) return 'S3';
        if ($tahun >= 2019) return 'S2';
        return 'S1';
    }

    private function logToFile($message)
    {
        Log::info($message);
        $logFile = storage_path('logs/import_'. date('Y-m-d'). '.log');
        file_put_contents($logFile, '['. date('Y-m-d H:i:s'). '] '. $message. PHP_EOL, FILE_APPEND);
    }

    public function getSuccessCount() { return $this->successCount; }
    public function getFailedCount() { return $this->failedCount; }
    public function getErrors() { return $this->errors; }
}