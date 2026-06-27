<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guru extends Model
{
    use SoftDeletes;
    
    protected $table = 'gurus';
    
    protected $fillable = [
        'user_id',
        'nip',
        'rfid',
        'nuptk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'universitas',
        'tahun_lulus',
        'tmt_masuk',
        'status_kepegawaian',
        'golongan',
        'mata_pelajaran_utama',
        'keahlian_khusus',
        'status',
        'jabatan_id',
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_masuk' => 'date',
        'tahun_lulus' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Jabatan
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    
    /**
     * Relasi ke Jadwal
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }
    
    /**
     * Relasi ke Nilai
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
    
    /**
     * Relasi ke Kelas (sebagai wali kelas)
     */
    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'id');
    }
    
    /**
     * Relasi ke Absensi (menggunakan guru_id)
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'guru_id')->where('absensi_type', 'guru');
    }
    
    /**
     * Relasi ke Mapel melalui tabel jadwal (guru mengajar mapel)
     */
    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'jadwal', 'guru_id', 'mapel_id')
                    ->withPivot('kelas_id', 'hari', 'jam_mulai', 'jam_selesai')
                    ->withTimestamps();
    }
    
    /**
     * Alias untuk mataPelajaran (untuk kemudahan)
     */
    public function mapel(): BelongsToMany
    {
        return $this->mataPelajaran();
    }
    
    /**
     * Get daftar mata pelajaran yang diajar
     */
    public function getMataPelajaranListAttribute(): string
    {
        return $this->mataPelajaran->pluck('nama_mapel')->implode(', ');
    }
    
    /**
     * Get jumlah mata pelajaran yang diajar
     */
    public function getMataPelajaranCountAttribute(): int
    {
        return $this->mataPelajaran->count();
    }
    
    /**
     * Get nama lengkap
     */
    public function getNameAttribute(): string
    {
        return $this->nama_lengkap;
    }
    
    /**
     * Get nama jabatan
     */
    public function getNamaJabatanAttribute(): string
    {
        if ($this->jabatan) {
            return $this->jabatan->nama_jabatan;
        }
        return $this->status_kepegawaian ?? '-';
    }
    
    /**
     * Get umur
     */
    public function getUmurAttribute(): ?int
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }
        return null;
    }
    
    /**
     * Scope untuk guru aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nuptk', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}