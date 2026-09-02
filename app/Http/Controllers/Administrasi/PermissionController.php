<?php
// app/Http/Controllers/Administrasi/PermissionController.php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('group')->orderBy('name')->get();
        return view('administrasi.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('administrasi.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
            'display_name' => 'required|max:255',
            'group' => 'required|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('administrasi.permissions.index')->with('success', 'Permission berhasil dibuat!');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('administrasi.permissions.index')->with('success', 'Permission berhasil dihapus!');
    }
}