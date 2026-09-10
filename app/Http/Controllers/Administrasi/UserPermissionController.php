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
     * Daftar user dengan ringkasan role & override.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['role', 'roles', 'userPermissions']);

            if ($request->filled('role')) {
                $roleFilter = $request->role;
                $query->where(function ($q) use ($roleFilter) {
                    $q->where('role_id', $roleFilter)
                      ->orWhere('role', $roleFilter)
                      ->orWhereHas('roles', function ($rq) use ($roleFilter) {
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

    /**
     * Form edit role user.
     */
    public function edit($id)
    {
        try {
            $user = User::with(['role', 'roles', 'userPermissions'])->findOrFail($id);

            // Semua role untuk dropdown
            $roles = Role::orderBy('name')->get();

            // Role user saat ini (prioritas: role_id, lalu role string, lalu multi-role pertama)
            $currentRoleId = $user->role_id;
            $currentRoleName = $user->role;

            if (!$currentRoleId && $user->roles->count() > 0) {
                $currentRoleId = $user->roles->first()->id;
                $currentRoleName = $user->roles->first()->name;
            }

            if (!$currentRoleName && $currentRoleId) {
                $r = Role::find($currentRoleId);
                $currentRoleName = $r ? $r->name : null;
            }

            return view('administrasi.user-permission.edit', compact(
                'user',
                'roles',
                'currentRoleId',
                'currentRoleName'
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

    /**
     * Update role user.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ], [
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists'   => 'Role yang dipilih tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $newRole = Role::findOrFail($request->role_id);

            // Update role_id di tabel users
            $user->role_id = $newRole->id;
            // Sinkronkan kolom 'role' string (untuk backward compatibility)
            $user->role = $newRole->name;
            $user->save();

            // Sinkronkan tabel pivot role_user (kalau kamu pakai multi-role)
            try {
                $user->roles()->sync([$newRole->id]);
            } catch (\Exception $e) {
                // Kalau tabel role_user tidak ada, abaikan
                Log::info('Sync role_user pivot gagal: ' . $e->getMessage());
            }

            // Reset semua override permission user (karena role berubah)
            // Permission akan mengikuti role baru
            UserPermission::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', 'Role user ' . $user->name . ' berhasil diubah menjadi ' . ucfirst($newRole->name) . '. Permission mengikuti role baru.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User atau Role dengan ID {$id} tidak ditemukan.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update role user: ' . $e->getMessage(), [
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

            $message = 'Override hak akses user ' . $user->name . ' berhasil direset.';
            $message .= $deletedCount > 0 ? " ({$deletedCount} override dihapus)" : '';

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Gagal reset: ' . $e->getMessage());
            return back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}