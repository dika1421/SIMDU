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
    public function index(Request $request)
    {
        try {
            $query = User::with(['roles', 'userPermissions.permission']);

            if ($request->filled('role')) {
                $roleFilter = $request->role;
                $query->where(function ($q) use ($roleFilter) {
                    if (is_numeric($roleFilter)) {
                        $q->where('role_id', $roleFilter);
                    }
                    $q->orWhere('role', $roleFilter);
                    $q->orWhereHas('roles', function ($rq) use ($roleFilter) {
                        $rq->where('name', $roleFilter);
                    });
                });
            }

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
            Log::error('Error user permission index: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::with(['roles', 'userPermissions.permission'])
                        ->findOrFail($id);

            // ✅ FIX: Ambil role lewat role_id, bukan $user->role
            $role = $user->role_id
                ? Role::with('permissions')->find($user->role_id)
                : null;

            // Ambil semua permission
            $permissionQuery = Permission::query();
            if (Schema::hasColumn('permissions', 'group')) {
                $permissionQuery->orderBy('group');
            }
            $allPermissions = $permissionQuery->orderBy('name')->get();

            if ($allPermissions->isEmpty()) {
                return redirect()->route('administrasi.user-permission.index')
                    ->with('error', 'Belum ada data permission.');
            }

            // Permission dari single role
            $rolePermissions = [];
            if ($role) {
                $rolePermissions = $role->permissions->pluck('name')->toArray();
            }

            // Permission dari multi-role
            if ($user->roles()->count() > 0) {
                foreach ($user->roles as $r) {
                    foreach ($r->permissions as $perm) {
                        if (!in_array($perm->name, $rolePermissions)) {
                            $rolePermissions[] = $perm->name;
                        }
                    }
                }
            }

            // Override per-user
            $userOverrides = [];
            foreach ($user->userPermissions as $override) {
                $userOverrides[$override->permission_id] = (bool) $override->granted;
            }

            return view('administrasi.user-permission.edit', compact(
                'user', 'role', 'allPermissions', 'rolePermissions', 'userOverrides'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User dengan ID {$id} tidak ditemukan.");

        } catch (\Exception $e) {
            Log::error('Error user permission edit: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $checkedNames = $request->input('permissions', []);
            $checkedIds = Permission::whereIn('name', $checkedNames)
                                    ->pluck('id')
                                    ->toArray();

            // ✅ FIX: Ambil role lewat role_id
            $role = $user->role_id
                ? Role::with('permissions')->find($user->role_id)
                : null;

            $rolePermIds = [];
            if ($role) {
                $rolePermIds = $role->permissions->pluck('id')->toArray();
            }

            // Multi-role
            if ($user->roles()->count() > 0) {
                foreach ($user->roles as $r) {
                    foreach ($r->permissions as $perm) {
                        if (!in_array($perm->id, $rolePermIds)) {
                            $rolePermIds[] = $perm->id;
                        }
                    }
                }
            }

            $allPermIds = Permission::pluck('id')->toArray();

            UserPermission::where('user_id', $user->id)->delete();

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
            $message .= $overrideCount > 0
                ? " ({$overrideCount} override tersimpan)"
                : ' (semua sama dengan role)';

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update user permission: ' . $e->getMessage());
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    public function reset($id)
    {
        try {
            $user = User::findOrFail($id);
            $deletedCount = UserPermission::where('user_id', $user->id)->delete();

            $message = 'Hak akses user ' . $user->name . ' berhasil direset.';
            $message .= $deletedCount > 0 ? " ({$deletedCount} override dihapus)" : '';

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Gagal reset: ' . $e->getMessage());
            return back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}