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
        'name', 
        'email', 
        'password', 
        'role', 
        'status', 
        'nuptk',
        'no_telepon',
        'foto',
        'last_login'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /**
     * Relasi ke Guru
     */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    /**
     * Generate password berdasarkan role (4 digit terakhir)
     */
    public function generatePassword()
    {
        $prefix = 'simdu#';
        
        switch ($this->role) {
            case 'kepala_sekolah':
                return $this->getKepalaSekolahPassword($prefix);
            case 'administrasi':
                return $this->getAdminPassword($prefix);
            case 'guru':
                return $this->getGuruPassword($prefix);
            case 'siswa':
                return $this->getSiswaPassword($prefix);
            default:
                return $prefix . 'default' . substr((string)$this->id, -4);
        }
    }

    /**
     * Generate password untuk Kepala Sekolah (angka 1)
     */
    private function getKepalaSekolahPassword($prefix)
    {
        $guru = $this->guru;
        
        if ($guru && $guru->nuptk) {
            return $prefix . '1' . substr($guru->nuptk, -4);
        } elseif ($guru && $guru->nip) {
            return $prefix . '1' . substr($guru->nip, -4);
        }
        
        return $prefix . '1' . substr((string)$this->id, -4);
    }

    /**
     * Generate password untuk Admin (angka 2)
     */
    private function getAdminPassword($prefix)
    {
        $guru = $this->guru;
        
        if ($guru && $guru->nuptk) {
            return $prefix . '2' . substr($guru->nuptk, -4);
        } elseif ($guru && $guru->nip) {
            return $prefix . '2' . substr($guru->nip, -4);
        }
        
        // Jika admin tidak punya data guru, gunakan 4 digit dari email
        $emailClean = preg_replace('/[^a-zA-Z0-9]/', '', $this->email);
        return $prefix . '2' . substr($emailClean, 0, 4);
    }

    /**
     * Generate password untuk Guru (angka 3)
     */
    private function getGuruPassword($prefix)
    {
        $guru = $this->guru;
        
        if ($guru && $guru->nuptk) {
            return $prefix . '3' . substr($guru->nuptk, -4);
        } elseif ($guru && $guru->nip) {
            return $prefix . '3' . substr($guru->nip, -4);
        }
        
        return $prefix . '3' . substr((string)$this->id, -4);
    }

    /**
     * Generate password untuk Siswa (angka 4)
     */
    private function getSiswaPassword($prefix)
    {
        $siswa = $this->siswa;
        
        if ($siswa && $siswa->nis) {
            return $prefix . '4' . substr($siswa->nis, -4);
        }
        
        return $prefix . '4' . substr((string)$this->id, -4);
    }

    /**
     * Validate password (support multi-role)
     */
    public function validatePassword($password)
    {
        // 1. Cek dengan Hash::check (Bcrypt)
        if (Hash::check($password, $this->password)) {
            return true;
        }
        
        // 2. Cek apakah password masih MD5 (untuk kompatibilitas)
        if (md5($password) === $this->password) {
            // Update ke Bcrypt
            $this->password = Hash::make($password);
            $this->save();
            return true;
        }
        
        // 3. Generate expected password berdasarkan role
        $expectedPassword = $this->generatePassword();
        
        // 4. Cek apakah password sesuai dengan expected (plain text)
        if ($password === $expectedPassword) {
            // Hash password untuk下次 login
            if (!Hash::needsRehash($this->password) && !Hash::check($password, $this->password)) {
                $this->password = Hash::make($password);
                $this->save();
            }
            return true;
        }
        
        return false;
    }

    /**
     * Cek apakah user memiliki multi-role (Kepala Sekolah + Guru)
     */
    public function hasMultipleRoles()
    {
        if ($this->role !== 'kepala_sekolah') {
            return false;
        }
        
        $guru = $this->guru;
        if (!$guru) {
            return false;
        }
        
        // Cek apakah user ini juga memiliki data guru
        return $guru->exists();
    }

    /**
     * Get all roles for this user (for multi-role)
     */
    public function getAvailableRoles()
    {
        $roles = [$this->role];
        
        if ($this->hasMultipleRoles()) {
            $roles[] = 'guru';
        }
        
        return $roles;
    }

    /**
     * Login sebagai role tertentu (untuk multi-role)
     */
    public function loginAs($role)
    {
        if (!in_array($role, $this->getAvailableRoles())) {
            throw new \Exception("Role {$role} tidak tersedia untuk user ini");
        }
        
        session(['login_role' => $role]);
        return $this;
    }

    /**
     * Get current login role
     */
    public function getCurrentLoginRole()
    {
        return session('login_role', $this->role);
    }

    /**
     * Accessor untuk role label
     */
    public function getRoleLabelAttribute()
    {
        $labels = [
            'kepala_sekolah' => 'Kepala Sekolah',
            'administrasi' => 'Administrasi',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
        ];
        
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'nonaktif' => 'danger',
            'pending' => 'warning',
        ];
        
        $color = $badges[$this->status ?? 'aktif'] ?? 'secondary';
        $label = ucfirst($this->status ?? 'Aktif');
        
        return "<span class='badge bg-{$color}'>{$label}</span>";
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk user berdasarkan role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nuptk', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
}