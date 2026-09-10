<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanSekolah extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'nuptk',
        'akreditasi',
        'kepala_sekolah',
        'alamat',
        'telepon',
        'email',
        'website',
        'logo',
        'two_factor',
        'notifikasi_login',
        'masa_berlaku_password',
        'batas_percobaan_login',
    ];

    protected $casts = [
        'two_factor' => 'boolean',
        'notifikasi_login' => 'boolean',
    ];

    /**
     * Ambil data pengaturan (hanya 1 baris).
     */
    public static function getPengaturan(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}