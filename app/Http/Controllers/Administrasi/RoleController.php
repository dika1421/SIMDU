<?php
// app/Http/Controllers/Administrasi/RoleController.php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('administrasi.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('administrasi.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
            'display_name' => 'required|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('administrasi.roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        return view('administrasi.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'display_name' => 'required|max:255',
        ]);

        $role->update([
            'display_name' => $request->display_name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('administrasi.roles.index')->with('success', 'Role berhasil diupdate!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        if ($role->is_default) {
            return back()->with('error', 'Role default tidak bisa dihapus!');
        }

        $role->delete();
        return redirect()->route('administrasi.roles.index')->with('success', 'Role berhasil dihapus!');
    }

    public function permissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        return view('administrasi.roles.permissions', compact('role', 'permissions'));
    }

    public function assignPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('administrasi.roles.index')->with('success', 'Permission berhasil diassign!');
    }
}