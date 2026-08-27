<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'no_hp',
        'alamat',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    /**
     * Cek apakah user memiliki role tertentu
     * 
     * @param string|array $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        // Jika user memiliki relasi role
        if ($this->role) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }

        // Fallback: jika masih pakai field role di tabel users
        if (isset($this->attributes['role']) && !empty($this->attributes['role'])) {
            if (is_array($roleName)) {
                return in_array($this->attributes['role'], $roleName);
            }
            return $this->attributes['role'] === $roleName;
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