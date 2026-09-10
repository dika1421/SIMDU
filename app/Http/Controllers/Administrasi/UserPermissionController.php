<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UserPermissionController extends Controller
{
    /**
     * Daftar user dengan ringkasan override.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['role', 'roles', 'userPermissions.permission']);

            // Filter berdasarkan role (support single role_id dan multi-role name)
            if ($request->filled('role')) {
                $roleFilter = $request->role;

                $query->where(function ($q) use ($roleFilter) {
                    // Cek di kolom role_id (single role) — kalau roleFilter numeric
                    if (is_numeric($roleFilter)) {
                        $q->where('role_id', $roleFilter);
                    }

                    // Cek di kolom role string (fallback)
                    $q->orWhere('role', $roleFilter);

                    // Cek di relasi multi-role
                    $q->orWhereHas('roles', function ($rq) use ($roleFilter) {
                        $rq->where('name', $roleFilter);
                    });
                });
            }

            // Pencarian nama / email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('name')->paginate(15)->withQueryString();
            $roles = Role::orderBy('name')->get();

            return view('administrasi.user-permission.index', compact('users', 'roles'));

        } catch (\Exception $e) {
            Log::error('Error user permission index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Form edit override permission user.
     */
    public function edit($id)
    {
        try {
            // Cari user dengan relasi lengkap
            $user = User::with(['role', 'roles', 'userPermissions.permission'])
                        ->findOrFail($id);

            // Ambil semua permission — cek dulu apakah kolom 'group' ada
            $permissionQuery = Permission::query();
            if (Schema::hasColumn('permissions', 'group')) {
                $permissionQuery->orderBy('group');
            }
            $allPermissions = $permissionQuery->orderBy('name')->get();

            // Kalau tidak ada permission sama sekali, kasih pesan
            if ($allPermissions->isEmpty()) {
                return redirect()->route('administrasi.user-permission.index')
                    ->with('error', 'Belum ada data permission. Silakan tambahkan permission terlebih dahulu di menu Data Permission.');
            }

            // Permission yang didapat dari single role
            $rolePermissions = $user->role
                ? $user->role->permissions->pluck('name')->toArray()
                : [];

            // Permission dari multi-role (kalau ada)
            if ($user->relationLoaded('roles') || $user->roles()->count() > 0) {
                foreach ($user->roles as $role) {
                    foreach ($role->permissions as $perm) {
                        if (!in_array($perm->name, $rolePermissions)) {
                            $rolePermissions[] = $perm->name;
                        }
                    }
                }
            }

            // Override per-user → key = permission_id, value = granted
            // Kita juga bikin mapping name → granted untuk view
            $userOverrides = [];
            $userOverridesByName = [];

            foreach ($user->userPermissions as $override) {
                $userOverrides[$override->permission_id] = (bool) $override->granted;

                if ($override->permission) {
                    $userOverridesByName[$override->permission->name] = (bool) $override->granted;
                }
            }

            return view('administrasi.user-permission.edit', compact(
                'user',
                'allPermissions',
                'rolePermissions',
                'userOverrides',
                'userOverridesByName'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // User benar-benar tidak ada
            Log::warning("User dengan ID {$id} tidak ditemukan di database");
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User dengan ID {$id} tidak ditemukan di database.");

        } catch (\Exception $e) {
            // Error lain — tampilkan pesan asli supaya tidak menyesatkan
            Log::error('Error user permission edit: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('administrasi.user-permission.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan override permission.
     *
     * View mengirim `permissions[]` berisi NAMA permission (bukan ID),
     * jadi kita harus konversi dari name → id dulu.
     */
    public function update(Request $request, $id)
    {
        // Validasi — kita terima array of string (nama permission)
        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'permissions.*.exists' => 'Permission :input tidak ditemukan di database.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::with(['role', 'roles'])->findOrFail($id);

            // Ambil nama permission yang di-check dari form
            $checkedNames = $request->input('permissions', []);

            // Konversi nama → ID
            $checkedIds = Permission::whereIn('name', $checkedNames)
                                    ->pluck('id')
                                    ->toArray();

            // ID permission dari single role
            $rolePermIds = $user->role
                ? $user->role->permissions->pluck('id')->toArray()
                : [];

            // ID permission dari multi-role
            if ($user->relationLoaded('roles') || $user->roles()->count() > 0) {
                foreach ($user->roles as $role) {
                    foreach ($role->permissions as $perm) {
                        if (!in_array($perm->id, $rolePermIds)) {
                            $rolePermIds[] = $perm->id;
                        }
                    }
                }
            }

            // Semua ID permission
            $allPermIds = Permission::pluck('id')->toArray();

            // Hapus override lama
            UserPermission::where('user_id', $user->id)->delete();

            // Simpan override baru HANYA jika berbeda dari role
            $overrideCount = 0;
            foreach ($allPermIds as $permId) {
                $isChecked = in_array($permId, $checkedIds);
                $fromRole  = in_array($permId, $rolePermIds);

                if ($isChecked !== $fromRole) {
                    UserPermission::create([
                        'user_id'       => $user->id,
                        'permission_id' => $permId,
                        'granted'       => $isChecked,
                    ]);
                    $overrideCount++;
                }
            }

            DB::commit();

            $message = 'Hak akses user ' . $user->name . ' berhasil diperbarui.';
            if ($overrideCount > 0) {
                $message .= " ({$overrideCount} override tersimpan)";
            } else {
                $message .= ' (semua permission sama dengan role, tidak ada override)';
            }

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', $message);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User dengan ID {$id} tidak ditemukan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update user permission: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'Gagal update: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Reset override user (kembali ke default role).
     */
    public function reset($id)
    {
        try {
            $user = User::findOrFail($id);

            $deletedCount = UserPermission::where('user_id', $user->id)->delete();

            $message = 'Hak akses user ' . $user->name . ' berhasil direset ke default role.';
            if ($deletedCount > 0) {
                $message .= " ({$deletedCount} override dihapus)";
            } else {
                $message .= ' (tidak ada override yang perlu dihapus)';
            }

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', $message);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User dengan ID {$id} tidak ditemukan.");

        } catch (\Exception $e) {
            Log::error('Gagal reset user permission: ' . $e->getMessage());
            return back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}