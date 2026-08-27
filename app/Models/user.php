<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'no_hp',
        'alamat',
        'role', // Untuk field role lama (jika masih ada)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // ================================================================
    // ✅ TAMBAHKAN METHOD INI
    // ================================================================

    /**
     * Cek apakah user memiliki role tertentu
     * 
     * @param string|array $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        // Cek dari relasi role (jika pakai role_id)
        if ($this->role) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }

        // Fallback: cek dari field role (jika masih pakai field role di tabel users)
        if (isset($this->role) && is_string($this->role)) {
            if (is_array($roleName)) {
                return in_array($this->role, $roleName);
            }
            return $this->role === $roleName;
        }

        return false;
    }

    /**
     * Cek apakah user memiliki permission
     * 
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        if ($this->role) {
            return $this->role->hasPermission($permissionName);
        }
        return false;
    }
}