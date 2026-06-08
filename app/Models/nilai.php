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
        'mata_pelajaran_id',
        'guru_id',
        'kelas_id',          // <-- PASTIKAN INI ADA
        'tahun_ajaran',
        'semester',
        'kurikulum',
        'nilai_harian_1',
        'nilai_harian_2',
        'nilai_harian_3',
        'nilai_tugas_1',
        'nilai_tugas_2',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktek',
        'nilai_akhir',
        'predikat',
        'deskripsi',
        'status',
        'is_rapor',
        'catatan_guru',
        'catatan_wali'
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
        'nilai_akhir' => 'decimal:2',
        'is_rapor' => 'boolean'
    ];
    
    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran_id');
    }
    
    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    // Relasi ke kelas - TAMBAHKAN INI
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    // Scope untuk nilai yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    // Scope berdasarkan tahun ajaran
    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }
    
    // Scope berdasarkan semester
    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
    
    // Scope berdasarkan kelas
    public function scopeKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }
}