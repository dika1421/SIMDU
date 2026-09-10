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
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Relasi ke Role (Single Role - Backward Compatibility)
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
     * 🔥 RELASI BARU: Override permission per user
     */
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    // =============================================
    // CEK ROLE
    // =============================================

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

    public function getRoleNamesAttribute(): string
    {
        $roleNames = [];

        if ($this->relationLoaded('roles') || $this->roles()->count() > 0) {
            $roleNames = $this->roles->pluck('display_name')->toArray();
        }

        if ($this->role) {
            $roleNames[] = $this->role->display_name;
        }

        if (empty($roleNames) && isset($this->attributes['role'])) {
            $roleNames[] = ucfirst($this->attributes['role']);
        }

        return implode(', ', array_unique($roleNames));
    }

    // =============================================
    // CEK PERMISSION (DENGAN OVERRIDE PER USER)
    // =============================================

    /**
     * Cek apakah user memiliki permission tertentu.
     *
     * Prioritas:
     *  1. 🔥 Override per-user (tabel user_permissions) ← TERTINGGI
     *  2. Permission dari multi-role
     *  3. Permission dari single role (role_id)
     *  4. Permission dari kolom 'role' string (fallback)
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName): bool
    {
        // ⚡ 1. CEK OVERRIDE PER-USER (prioritas tertinggi)
        $override = $this->userPermissions()
            ->whereHas('permission', function ($q) use ($permissionName) {
                $q->where('name', $permissionName);
            })
            ->first();

        if ($override) {
            return (bool) $override->granted;
        }

        // ⚡ 2. CEK DI MULTI-ROLE
        if ($this->relationLoaded('roles') || $this->roles()->count() > 0) {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permissionName)) {
                    return true;
                }
            }
        }

        // ⚡ 3. CEK SINGLE ROLE
        $roleModel = $this->role_id ? Role::find($this->role_id) : null;
        if ($roleModel && $roleModel->hasPermission($permissionName)) {
            return true;
        }

        // ⚡ 4. CEK KOLOM ROLE STRING (Fallback)
        if (isset($this->attributes['role'])) {
            $roleModel = Role::where('name', $this->attributes['role'])->first();
            if ($roleModel && $roleModel->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ambil semua permission user (dari role + override per-user).
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

        // 🔥 Terapkan override per-user
        foreach ($this->userPermissions()->with('permission')->get() as $override) {
            if ($override->permission) {
                $permName = $override->permission->name;

                if ($override->granted) {
                    // Tambah kalau belum ada
                    if (!in_array($permName, $permissions)) {
                        $permissions[] = $permName;
                    }
                } else {
                    // Hapus kalau ada (karena di-revoke)
                    $key = array_search($permName, $permissions);
                    if ($key !== false) {
                        unset($permissions[$key]);
                    }
                }
            }
        }

        return array_values(array_unique($permissions));
    }

    /**
     * Cek apakah user punya override permission tertentu.
     */
    public function hasPermissionOverride($permissionName): bool
    {
        return $this->userPermissions()
            ->whereHas('permission', function ($q) use ($permissionName) {
                $q->where('name', $permissionName);
            })
            ->exists();
    }

    // =============================================
    // SYNC ROLES
    // =============================================

    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->detach($role->id);
    }
}