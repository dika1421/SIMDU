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
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $baris = $index + 2;
                $nis = trim($row['nis'] ?? '');
                $nama = trim($row['nama'] ?? $row['nama_lengkap'] ?? '');
                $kelasNama = trim($row['kelas'] ?? $row['kelas_id'] ?? $row['nama_kelas'] ?? '');
                $rfid = trim($row['rfid'] ?? $row['rfid_card'] ?? '');

                if (empty($nis) || empty($nama)) {
                    $this->failed++;
                    $this->errors[] = "Baris {$baris}: NIS/Nama kosong";
                    continue;
                }

                if (Siswa::where('nis', $nis)->exists()) {
                    $this->failed++;
                    $this->errors[] = "Baris {$baris}: NIS {$nis} sudah ada";
                    continue;
                }

                $kelasId = null;
                if (!empty($kelasNama)) {
                    // kalau isinya ID angka, pakai langsung. Kalau nama, cari
                    if (is_numeric($kelasNama)) {
                        $kelasId = (int)$kelasNama;
                    } else {
                        $kelas = Kelas::where('nama_kelas', 'ILIKE', "%{$kelasNama}%")->first();
                        if ($kelas) $kelasId = $kelas->id;
                    }
                }

                // Buat user login
                $user = User::create([
                    'name' => $nama,
                    'email' => $nis . '@siswa.simdu.sch.id',
                    'password' => Hash::make($nis),
                ]);
                // kalau kamu pakai role di users
                // $user->assignRole('siswa');

                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas_id' => $kelasId,
                    'rfid_card' => $rfid ?: null,
                ]);
                $this->success++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Fatal Baris ".($baris??'?').": ".$e->getMessage();
        }
    }

    public function getSuccessCount(){ return $this->success; }
    public function getFailedCount(){ return $this->failed; }
    public function getErrors(){ return $this->errors; }
}