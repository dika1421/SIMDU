<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nilai extends Model
{
    use SoftDeletes;
    
    protected $table = 'nilai';
    
    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'guru_id',
        'kelas_id',  // 🔥 PASTIKAN INI ADA
        'nilai_harian_1',
        'nilai_harian_2',
        'nilai_harian_3',
        'nilai_tugas_1',
        'nilai_tugas_2',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktek',
        'nilai_lahir',
        'predikat',
        'deskripsi',
        'catatan_guru',
        'catatan_wali',
        'status',
        'is_rapor',
        'tahun_ajaran',
        'semester'
    ];
    
    protected $casts = [
        'nilai_harian_1' => 'decimal:2',
        'nilai_harian_2' => 'decimal:2',
        'nilai_harian_3' => 'decimal:2',
        'nilai_tugas_1' => 'decimal:2',
        'nilai_tugas_2' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_praktek' => 'decimal:2',
        'nilai_lahir' => 'decimal:2',
        'is_rapor' => 'boolean'
    ];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}