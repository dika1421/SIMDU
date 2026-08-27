<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Cek apakah user memiliki role
     */
    public function hasRole($roleName): bool
    {
        if ($this->role) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }
        return false;
    }

    /**
     * Cek apakah user memiliki permission
     */
    public function hasPermission($permissionName): bool
    {
        if ($this->role) {
            return $this->role->hasPermission($permissionName);
        }
        return false;
    }
}