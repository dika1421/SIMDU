<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    private $success = 0;
    private $failed = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $baris = $index + 2;
            $nis = trim((string)($row['nis'] ?? ''));
            $nama = trim((string)($row['nama'] ?? $row['nama_lengkap'] ?? ''));
            $kelasNama = trim((string)($row['kelas'] ?? ''));
            $rfid = trim((string)($row['rfid'] ?? ''));

            // bersihkan .0 dari excel
            $nis = str_replace('.0', '', $nis);

            if (empty($nis) || empty($nama)) {
                $this->failed++;
                $this->errors[] = "Baris {$baris}: NIS/Nama kosong";
                continue;
            }

            // Cek duplikat HANYA di kolom nis (karena nisn tidak ada)
            if (Siswa::where('nis', $nis)->exists()) {
                $this->failed++;
                $this->errors[] = "Baris {$baris}: NIS {$nis} sudah ada";
                continue;
            }

            $kelasId = null;
            if (!empty($kelasNama)) {
                if (is_numeric($kelasNama)) {
                    $kelasId = (int)$kelasNama;
                } else {
                    $kelas = Kelas::where('nama_kelas', 'ILIKE', "%{$kelasNama}%")->first();
                    if ($kelas) $kelasId = $kelas->id;
                }
            }

            try {
                DB::beginTransaction();

                // Cek email user duplikat
                $email = $nis . '@siswa.simdu.sch.id';
                if (User::where('email', $email)->exists()) {
                    $email = $nis . '_' . time() . rand(1,9) . '@siswa.simdu.sch.id';
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nis),
                ]);

                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas_id' => $kelasId,
                    'rfid_card' => $rfid ?: null,
                ]);

                DB::commit();
                $this->success++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->failed++;
                $this->errors[] = "Baris {$baris}: " . $e->getMessage();
            }
        }
    }

    public function getSuccessCount(){ return $this->success; }
    public function getFailedCount(){ return $this->failed; }
    public function getErrors(){ return $this->errors; }
}