<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    /**
     * NAMA TABEL - SESUAIKAN DENGAN DATABASE
     */
    protected $table = 'struktur_organisasi';  // <-- TAMBAHKAN BARIS INI

    protected $fillable = [
        'nama',
        'jabatan',
        'parent_id',
        'guru_id',
        'deskripsi',
        'urutan',
        'foto',
    ];

    /**
     * Relasi ke parent (atasan)
     */
    public function parent()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'parent_id');
    }

    /**
     * Relasi ke children (bawahan)
     */
    public function children()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'parent_id');
    }

    /**
     * Relasi ke guru (jika dijabat oleh guru)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}