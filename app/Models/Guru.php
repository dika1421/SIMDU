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
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }
    
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
    
    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'id');
    }
    
    // HAPUS method ini jika ada (polymorphic)
    // public function absensi()
    // {
    //     return $this->morphMany(Absensi::class, 'absensi');
    // }
    
    // HAPUS atau COMMENT method ini
    // public function absensi(): HasMany
    // {
    //     return $this->hasMany(Absensi::class, 'guru_id', 'id');
    // }
    
    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'jadwal', 'guru_id', 'mapel_id')
                    ->withPivot('kelas_id', 'hari', 'jam_mulai', 'jam_selesai')
                    ->withTimestamps();
    }
    
    public function getMataPelajaranListAttribute(): string
    {
        return $this->mataPelajaran->pluck('nama_mapel')->implode(', ');
    }
    
    public function getMataPelajaranCountAttribute(): int
    {
        return $this->mataPelajaran->count();
    }
    
    public function getNameAttribute(): string
    {
        return $this->nama_lengkap;
    }
    
    public function getNamaJabatanAttribute(): string
    {
        if ($this->jabatan) {
            return $this->jabatan->nama_jabatan;
        }
        return $this->status_kepegawaian ?? '-';
    }
    
    public function getUmurAttribute(): ?int
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }
        return null;
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
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