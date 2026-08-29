<?php

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
        'role',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relasi ke Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    /**
     * Cek apakah user memiliki role tertentu.
     * Mendukung pengecekan via relasi Role model, atau fallback ke kolom 'role' di tabel users.
     *
     * @param string|array $roleName
     * @return bool
     */
    public function hasRole($roleName): bool
    {
        // Cek relasi role Model
        if ($this->role && is_object($this->role)) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }

        // Fallback: cek field 'role' di tabel users
        if (isset($this->attributes['role']) && !empty($this->attributes['role'])) {
            if (is_array($roleName)) {
                return in_array($this->attributes['role'], $roleName);
            }
            return $this->attributes['role'] === $roleName;
        }

        return false;
    }

    /**
     * Cek apakah user memiliki permission tertentu (lewat relasi Role).
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        if ($this->role && is_object($this->role)) {
            return $this->role->hasPermission($permissionName);
        }
        return false;
    }
}