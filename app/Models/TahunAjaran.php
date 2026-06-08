<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel - sesuaikan dengan database Anda
     */
    protected $table = 'tahun_ajaran';

    /**
     * Kolom yang bisa diisi
     */
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'semester',
        'is_aktif',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean',
    ];

    /**
     * Scope untuk mengambil tahun ajaran aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope untuk filter semester
     */
    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}