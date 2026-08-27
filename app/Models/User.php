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

    // ✅ METHOD INI YANG DITAMBAHKAN
    public function hasRole($roleName)
    {
        if ($this->role) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }
        return false;
    }

    public function hasPermission($permissionName)
    {
        if ($this->role) {
            return $this->role->hasPermission($permissionName);
        }
        return false;
    }
}