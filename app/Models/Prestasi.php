<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'nama_prestasi',
        'tingkat',
        'juara',
        'tahun',
        'penerima_type',
        'penerima_id',
        'sertifikat',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    /**
     * Relasi polymorphic ke penerima prestasi (bisa siswa atau guru)
     */
    public function penerima()
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan tingkat
     */
    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }
}