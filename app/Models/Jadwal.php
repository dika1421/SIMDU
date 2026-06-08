<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;
    
    protected $table = 'jadwals';
    
    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mata_pelajaran_id',
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
        'jam_selesai' => 'datetime'
    ];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    // PERBAIKAN: Relasi ke mapel dengan foreign key yang benar
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran_id', 'id');
    }
    
    // Alias untuk kompatibilitas
    public function mataPelajaran()
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran_id', 'id');
    }
}