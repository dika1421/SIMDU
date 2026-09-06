<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;
    
    protected $table = 'jadwal';
    
    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mapel_id',        // <-- PERUBAHAN: Ganti 'mata_pelajaran' menjadi 'mapel_id'
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
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    // PERBAIKAN: Relasi ke Mapel menggunakan 'mapel_id'
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
    
    // ==================== ACCESSORS ====================
    
    public function getNamaMapelAttribute()
    {
        return $this->mapel->nama_mapel ?? '-';
    }
    
    public function getNamaGuruAttribute()
    {
        return $this->guru->nama_lengkap ?? '-';
    }
    
    public function getNamaKelasAttribute()
    {
        return $this->kelas->nama_kelas ?? '-';
    }
    
    // ==================== SCOPES ====================
    
    public function scopeByGuru($query, $guruId)
    {
        if ($guruId) {
            return $query->where('guru_id', $guruId);
        }
        return $query;
    }
    
    public function scopeByKelas($query, $kelasId)
    {
        if ($kelasId) {
            return $query->where('kelas_id', $kelasId);
        }
        return $query;
    }
    
    public function scopeByHari($query, $hari)
    {
        if ($hari) {
            return $query->where('hari', $hari);
        }
        return $query;
    }
}