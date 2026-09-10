<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
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
     * Form edit role user (multi-role).
     */
    public function edit($id)
    {
        try {
            $user = User::with(['role', 'roles', 'userPermissions'])->findOrFail($id);

            // Semua role untuk checkbox
            $roles = Role::orderBy('name')->get();

            // Role ID yang dimiliki user saat ini
            // Gabungkan dari relasi many-to-many + fallback ke role_id & role string
            $selectedRoleIds = $user->roles->pluck('id')->map(fn($v) => (int) $v)->toArray();

            if (empty($selectedRoleIds)) {
                // Fallback: kalau belum ada di pivot, cek role_id
                if ($user->role_id) {
                    $selectedRoleIds[] = (int) $user->role_id;
                } elseif (!empty($user->role)) {
                    // Fallback: cek role string
                    $r = Role::where('name', $user->role)->first();
                    if ($r) {
                        $selectedRoleIds[] = (int) $r->id;
                    }
                }
            }

            // Info role yang sedang dimiliki (untuk ditampilkan)
            $currentRoles = Role::whereIn('id', $selectedRoleIds)->orderBy('name')->get();

            return view('administrasi.user-permission.edit', compact(
                'user',
                'roles',
                'selectedRoleIds',
                'currentRoles'
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
     * Update role user (multi-role).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role_ids'   => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:roles,id',
        ], [
            'role_ids.required' => 'Pilih minimal 1 role.',
            'role_ids.min'      => 'Pilih minimal 1 role.',
            'role_ids.*.exists' => 'Role yang dipilih tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $roleIds = array_map('intval', $request->role_ids);

            // Ambil role-role baru
            $newRoles = Role::whereIn('id', $roleIds)->get();

            if ($newRoles->isEmpty()) {
                throw new \Exception('Role yang dipilih tidak ditemukan.');
            }

            // Sync ke pivot table role_user
            $user->roles()->sync($roleIds);

            // Update role_id di tabel users → pakai role pertama sebagai default
            // (untuk backward compatibility dengan kolom role_id & role string)
            $primaryRole = $newRoles->first();
            $user->role_id = $primaryRole->id;
            $user->role = $primaryRole->name;
            $user->save();

            // Reset semua override permission karena role berubah
            UserPermission::where('user_id', $user->id)->delete();

            DB::commit();

            $roleNames = $newRoles->pluck('name')->map(fn($n) => ucfirst($n))->implode(', ');

            return redirect()->route('administrasi.user-permission.index')
                ->with('success', "Role user {$user->name} berhasil diubah menjadi: {$roleNames}");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('administrasi.user-permission.index')
                ->with('error', "User dengan ID {$id} tidak ditemukan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;

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
     * Reset override user.
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