<?php
// app/Models/Nilai.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nilai extends Model
{
    use SoftDeletes;
    
    protected $table = 'nilai';
    
    protected $fillable = [
        'siswa_id',
        'mapel_id',           // ← PERBAIKAN: gunakan mapel_id
        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',
        'jenis',
        'nilai',
        'nilai_akhir',
        'nilai_harian_1',
        'nilai_harian_2',
        'nilai_harian_3',
        'nilai_tugas_1',
        'nilai_tugas_2',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktek',
        'predikat',
        'deskripsi',
        'catatan',
        'catatan_guru',
        'catatan_wali',
        'status',
        'is_rapor',
        'kurikulum',
        'tahun_ajaran',
        'semester'
    ];
    
    protected $casts = [
        'nilai' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'nilai_harian_1' => 'decimal:2',
        'nilai_harian_2' => 'decimal:2',
        'nilai_harian_3' => 'decimal:2',
        'nilai_tugas_1' => 'decimal:2',
        'nilai_tugas_2' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_praktek' => 'decimal:2',
        'is_rapor' => 'boolean'
    ];
    
    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    
    /**
     * PERBAIKAN: Relasi ke mata pelajaran menggunakan mapel_id
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
    
    /**
     * Alias untuk kompatibilitas
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
    
    /**
     * Relasi ke guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    /**
     * Scope untuk nilai yang sudah dipublish
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    /**
     * Scope untuk nilai draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
    /**
     * Scope berdasarkan tahun ajaran
     */
    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }
    
    /**
     * Scope berdasarkan semester
     */
    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
    
    /**
     * Scope berdasarkan kelas
     */
    public function scopeKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }
    
    /**
     * Scope berdasarkan guru
     */
    public function scopeGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }
    
    /**
     * Scope berdasarkan mapel
     */
    public function scopeMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }
}