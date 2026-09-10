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

class UserPermissionController extends Controller
{
    /**
     * Daftar user dengan ringkasan override.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['role', 'userPermissions.permission']);

            if ($request->filled('role')) {
                $query->where('role_id', $request->role);
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

    /**
     * Form edit override permission user.
     */
    public function edit($id)
    {
        try {
            $user = User::with(['role', 'userPermissions.permission'])->findOrFail($id);

            // Semua permission dikelompokkan per group
            $allPermissions = Permission::orderBy('group')->orderBy('name')->get();

            // Permission yang didapat dari role
            $rolePermissions = $user->role
                ? $user->role->permissions->pluck('name')->toArray()
                : [];

            // Override per-user → key = permission_id, value = granted
            $userOverrides = $user->userPermissions
                ->pluck('granted', 'permission_id')
                ->toArray();

            return view('administrasi.user-permission.edit', compact(
                'user', 'allPermissions', 'rolePermissions', 'userOverrides'
            ));

        } catch (\Exception $e) {
            Log::error('Error user permission edit: ' . $e->getMessage());
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', 'User tidak ditemukan');
        }
    }

    /**
     * Simpan override permission.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::with('role')->findOrFail($id);

            $checkedIds = $request->input('permissions', []);

            // ID permission dari role
            $rolePermIds = $user->role
                ? $user->role->permissions->pluck('id')->toArray()
                : [];

            // Semua ID permission
            $allPermIds = Permission::pluck('id')->toArray();

            // Hapus override lama
            UserPermission::where('user_id', $user->id)->delete();

            // Simpan override baru jika berbeda dari role
            foreach ($allPermIds as $permId) {
                $isChecked = in_array($permId, $checkedIds);
                $fromRole  = in_array($permId, $rolePermIds);

                if ($isChecked !== $fromRole) {
                    UserPermission::create([
                        'user_id'       => $user->id,
                        'permission_id' => $permId,
                        'granted'       => $isChecked,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', 'Hak akses user ' . $user->name . ' berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update user permission: ' . $e->getMessage());
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Reset override user (kembali ke default role).
     */
    public function reset($id)
    {
        try {
            $user = User::findOrFail($id);
            UserPermission::where('user_id', $user->id)->delete();

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', 'Hak akses user ' . $user->name . ' berhasil direset ke default role');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}