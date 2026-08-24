<?php
// app/Models/Jadwal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;
    
    // 🔥 PERBAIKAN: Nama tabel seharusnya 'jadwal' (tanpa 's')
    protected $table = 'jadwal';
    
    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mata_pelajaran',  // 🔥 PERBAIKAN: sesuai database, tanpa '_id'
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'tahun_ajaran',
        'semester',
        'status',
        'keterangan'
    ];
    
    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    /**
     * Relasi ke Guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    /**
     * 🔥 PERBAIKAN: Relasi ke Mapel
     * Gunakan 'mata_pelajaran' (sesuai database)
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran', 'id');
    }
    
    /**
     * Alias untuk mapel (kompatibilitas)
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran', 'id');
    }
    
    // ==================== ACCESSORS ====================
    
    /**
     * Get nama mata pelajaran
     */
    public function getNamaMapelAttribute()
    {
        return $this->mapel->nama_mapel ?? '-';
    }
    
    /**
     * Get nama guru
     */
    public function getNamaGuruAttribute()
    {
        return $this->guru->nama_lengkap ?? '-';
    }
    
    /**
     * Get nama kelas
     */
    public function getNamaKelasAttribute()
    {
        return $this->kelas->nama_kelas ?? '-';
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope by guru
     */
    public function scopeByGuru($query, $guruId)
    {
        if ($guruId) {
            return $query->where('guru_id', $guruId);
        }
        return $query;
    }
    
    /**
     * Scope by kelas
     */
    public function scopeByKelas($query, $kelasId)
    {
        if ($kelasId) {
            return $query->where('kelas_id', $kelasId);
        }
        return $query;
    }
    
    /**
     * Scope by hari
     */
    public function scopeByHari($query, $hari)
    {
        if ($hari) {
            return $query->where('hari', $hari);
        }
        return $query;
    }
}   