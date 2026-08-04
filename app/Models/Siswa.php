<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'agama',
        'kelas_id',
        'rfid_card',
        'status',
        'tahun_masuk',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke Nilai
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    // Relasi ke Absensi
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    // Relasi ke Pembayaran
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'siswa_id');
    }

    // Scope Search
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('nis', 'ILIKE', "%{$search}%")
                  ->orWhere('nama_lengkap', 'ILIKE', "%{$search}%");
            });
        }
        return $query;
    }

    // Accessor untuk nama lengkap (fallback)
    public function getNamaLengkapAttribute()
    {
        return $this->nama_lengkap ?? $this->nama ?? 'Siswa';
    }
}