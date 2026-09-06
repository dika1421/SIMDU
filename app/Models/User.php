<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // =============================================
    // RELASI
    // =============================================

    /**
     * 🔥 RELASI BARU: Many-to-Many ke Role (Multi-Role)
     * User bisa punya banyak role
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Relasi ke Role (Single Role - Backward Compatibility)
     * Untuk user yang hanya punya 1 role
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

    // =============================================
    // CEK ROLE
    // =============================================

    /**
     * Cek apakah user memiliki role tertentu.
     * Mendukung pengecekan via relasi Role model, atau fallback ke kolom 'role' di tabel users.
     *
     * @param string|array $roleName
     * @return bool
     */
    public function hasRole($roleName): bool
    {
        // 🔥 CEK DI MULTI-ROLE (Many-to-Many)
        if ($this->relationLoaded('roles') || $this->roles()->count() > 0) {
            if (is_array($roleName)) {
                return $this->roles()->whereIn('name', $roleName)->exists();
            }
            return $this->roles()->where('name', $roleName)->exists();
        }

        // CEK SINGLE ROLE (Backward Compatibility)
        if ($this->role && is_object($this->role)) {
            if (is_array($roleName)) {
                return in_array($this->role->name, $roleName);
            }
            return $this->role->name === $roleName;
        }

        // FALLBACK: cek field 'role' di tabel users (string)
        if (isset($this->attributes['role']) && !empty($this->attributes['role'])) {
            if (is_array($roleName)) {
                return in_array($this->attributes['role'], $roleName);
            }
            return $this->attributes['role'] === $roleName;
        }

        return false;
    }

    /**
     * Ambil semua nama role user (dalam string)
     */
    public function getRoleNamesAttribute(): string
    {
        $roleNames = [];

        // Ambil dari multi-role
        if ($this->relationLoaded('roles') || $this->roles()->count() > 0) {
            $roleNames = $this->roles->pluck('display_name')->toArray();
        }

        // Tambahkan dari single role
        if ($this->role) {
            $roleNames[] = $this->role->display_name;
        }

        // Fallback ke kolom 'role' string
        if (empty($roleNames) && isset($this->attributes['role'])) {
            $roleNames[] = ucfirst($this->attributes['role']);
        }

        return implode(', ', array_unique($roleNames));
    }

    /**
     * Cek apakah user memiliki permission tertentu (lewat relasi Role).
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName): bool
    {
        // 🔥 CEK DI MULTI-ROLE
        if ($this->relationLoaded('roles') || $this->roles()->count() > 0) {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permissionName)) {
                    return true;
                }
            }
        }

        // CEK SINGLE ROLE
        $roleModel = $this->role_id ? Role::find($this->role_id) : null;
        if ($roleModel && $roleModel->hasPermission($permissionName)) {
            return true;
        }

        // CEK KOLOM ROLE STRING (Fallback)
        if (isset($this->attributes['role'])) {
            $roleModel = Role::where('name', $this->attributes['role'])->first();
            if ($roleModel && $roleModel->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ambil semua permission user (dari semua role)
     */
    public function getAllPermissions(): array
    {
        $permissions = [];

        // Dari multi-role
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }

        // Dari single role
        if ($this->role) {
            foreach ($this->role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }

        return array_unique($permissions);
    }

    /**
     * Sync roles untuk user (multi-role)
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Assign role ke user (multi-role)
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove role dari user (multi-role)
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->detach($role->id);
    }
}