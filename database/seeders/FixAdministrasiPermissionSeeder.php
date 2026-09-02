<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixAdministrasiPermissionSeeder extends Seeder
{
    /**
     * Seeder ini:
     * 1. Memastikan permission role.* dan permission.* sudah ada di tabel permissions
     * 2. Meng-assign semua permission itu ke role 'administrasi'
     * 3. Memperbaiki users.role_id yang masih NULL untuk user dengan role = 'administrasi'
     *
     * Cara jalankan di VPS setelah git pull:
     *   php artisan db:seed --class=FixAdministrasiPermissionSeeder
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'role.view',          'display_name' => 'Lihat Role',              'description' => 'Melihat daftar role',            'group' => 'role'],
            ['name' => 'role.create',        'display_name' => 'Tambah Role',             'description' => 'Membuat role baru',              'group' => 'role'],
            ['name' => 'role.edit',          'display_name' => 'Edit Role',               'description' => 'Mengubah role',                  'group' => 'role'],
            ['name' => 'role.delete',        'display_name' => 'Hapus Role',              'description' => 'Menghapus role',                 'group' => 'role'],
            ['name' => 'role.permission',    'display_name' => 'Kelola Permission Role',  'description' => 'Assign permission ke role',      'group' => 'role'],
            ['name' => 'permission.view',    'display_name' => 'Lihat Permission',        'description' => 'Melihat daftar permission',      'group' => 'permission'],
            ['name' => 'permission.create',  'display_name' => 'Tambah Permission',       'description' => 'Membuat permission baru',        'group' => 'permission'],
            ['name' => 'permission.delete',  'display_name' => 'Hapus Permission',        'description' => 'Menghapus permission',           'group' => 'permission'],
        ];

        // 1. Insert permission kalau belum ada (aman dijalankan berkali-kali)
        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $perm['name']],
                [
                    'display_name' => $perm['display_name'],
                    'description'  => $perm['description'],
                    'group'        => $perm['group'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }

        // 2. Assign semua permission di atas ke role 'administrasi'
        $role = DB::table('roles')->where('name', 'administrasi')->first();

        if (! $role) {
            $this->command->error("Role 'administrasi' tidak ditemukan di tabel roles. Seeder dihentikan.");
            return;
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', array_column($permissions, 'name'))
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('role_permission')->updateOrInsert(
                [
                    'role_id'       => $role->id,
                    'permission_id' => $permissionId,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 3. Perbaiki role_id yang masih NULL untuk user dengan role = 'administrasi'
        DB::table('users')
            ->where('role', 'administrasi')
            ->whereNull('role_id')
            ->update(['role_id' => $role->id]);

        $this->command->info("Selesai: permission role & permission sudah di-assign ke role 'administrasi'.");
    }
}