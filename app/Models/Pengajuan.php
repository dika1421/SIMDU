<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    /**
     * NAMA TABEL - SESUAIKAN DENGAN DATABASE (pengajuan, BUKAN pengajuans)
     */
    protected $table = 'pengajuan';  // <-- TAMBAHKAN BARIS INI

    protected $fillable = [
        'nomor_pengajuan',
        'judul',
        'deskripsi',
        'tipe',
        'jumlah_anggaran',
        'pengaju_id',
        'status',
        'catatan',
        'disetujui_oleh',
        'tanggal_disetujui',
    ];

    protected $casts = [
        'jumlah_anggaran' => 'decimal:2',
        'tanggal_disetujui' => 'datetime',
    ];

    /**
     * Relasi ke pengaju (user)
     */
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    /**
     * Relasi ke user yang menyetujui
     */
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}