<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'nuptk'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Method untuk cek password (support MD5 dan Bcrypt)
    public function validatePassword($password)
    {
        // Cek apakah password menggunakan Bcrypt
        if (password_verify($password, $this->password)) {
            return true;
        }
        
        // Cek apakah password masih MD5 (untuk kompatibilitas)
        if (md5($password) === $this->password) {
            // Update ke Bcrypt
            $this->password = Hash::make($password);
            $this->save();
            return true;
        }
        
        return false;
    }
}