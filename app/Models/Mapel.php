<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mapel extends Model
{
    use SoftDeletes;
    
    protected $table = 'mata_pelajarans';
    
    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'nama_singkat',
        'kelompok',
        'jam_per_minggu',
        'jenis',
        'kurikulum',
        'tingkat',
        'jurusan_id',
        'status',
        'is_wajib',
        'is_ujian_nasional',
        'deskripsi',
        'kompetensi_dasar',
        'silabus',
        'kkm'
    ];
    
    protected $casts = [
        'jam_per_minggu' => 'integer',
        'is_wajib' => 'boolean',
        'is_ujian_nasional' => 'boolean',
        'kkm' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    /**
     * Relasi ke jadwal (tabel jadwal yang ada)
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'mapel_id', 'id');
    }
    
    /**
     * Relasi ke nilai
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mapel_id', 'id');
    }
    
    /**
     * Relasi ke guru melalui jadwal
     */
    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'jadwal', 'mapel_id', 'guru_id')
                    ->withPivot('kelas_id', 'hari', 'jam_mulai', 'jam_selesai')
                    ->withTimestamps();
    }
    
    /**
     * Relasi ke kelas melalui jadwal
     */
    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'jadwal', 'mapel_id', 'kelas_id')
                    ->withPivot('guru_id', 'hari', 'jam_mulai', 'jam_selesai')
                    ->withTimestamps();
    }
    
    /**
     * Relasi ke jurusan
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    
    /**
     * Accessor untuk kolom 'nama' (untuk orderBy dan display)
     */
    public function getNamaAttribute(): string
    {
        return $this->nama_mapel;
    }
    
    /**
     * Accessor untuk nama lengkap mata pelajaran
     */
    public function getNamaLengkapAttribute(): string
    {
        $nama = $this->nama_mapel;
        if ($this->jurusan) {
            $nama .= ' (' . $this->jurusan->nama_jurusan . ')';
        }
        return $nama;
    }
    
    /**
     * Accessor untuk kode dan nama
     */
    public function getKodeDanNamaAttribute(): string
    {
        return $this->kode_mapel . ' - ' . $this->nama_mapel;
    }
    
    /**
     * Scope untuk mapel aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope berdasarkan jurusan
     */
    public function scopeByJurusan($query, $jurusanId)
    {
        if ($jurusanId) {
            return $query->where('jurusan_id', $jurusanId);
        }
        return $query;
    }
    
    /**
     * Scope pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_mapel', 'LIKE', "%{$search}%")
                  ->orWhere('kode_mapel', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
}