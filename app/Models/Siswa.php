<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    // FIX 1: Tabel kamu namanya 'siswa', bukan 'siswas'
    protected $table = 'siswa';

    // FIX 2: Hapus SoftDeletes karena di DB gak ada deleted_at
    // use SoftDeletes;

    // FIX 3: Fillable harus SAMA PERSIS dengan kolom real di DB
    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas_id',
        'rfid_card',
    ];

    // Kalau pakai create_at / update_at yang custom nama
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // FIX 4: Scope disesuaikan kolom real
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('nis', 'ILIKE', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'ILIKE', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}