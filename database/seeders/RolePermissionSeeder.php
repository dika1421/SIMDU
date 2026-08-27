<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ===== PERMISSIONS =====
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'Lihat Dashboard', 'group' => 'Dashboard'],
            
            // Manajemen Role
            ['name' => 'role.view', 'display_name' => 'Lihat Role', 'group' => 'Role'],
            ['name' => 'role.create', 'display_name' => 'Tambah Role', 'group' => 'Role'],
            ['name' => 'role.edit', 'display_name' => 'Edit Role', 'group' => 'Role'],
            ['name' => 'role.delete', 'display_name' => 'Hapus Role', 'group' => 'Role'],
            ['name' => 'role.permission', 'display_name' => 'Atur Permission Role', 'group' => 'Role'],
            
            // Manajemen Permission
            ['name' => 'permission.view', 'display_name' => 'Lihat Permission', 'group' => 'Permission'],
            ['name' => 'permission.create', 'display_name' => 'Tambah Permission', 'group' => 'Permission'],
            ['name' => 'permission.delete', 'display_name' => 'Hapus Permission', 'group' => 'Permission'],
            
            // Manajemen Siswa
            ['name' => 'siswa.view', 'display_name' => 'Lihat Siswa', 'group' => 'Siswa'],
            ['name' => 'siswa.create', 'display_name' => 'Tambah Siswa', 'group' => 'Siswa'],
            ['name' => 'siswa.edit', 'display_name' => 'Edit Siswa', 'group' => 'Siswa'],
            ['name' => 'siswa.delete', 'display_name' => 'Hapus Siswa', 'group' => 'Siswa'],
            ['name' => 'siswa.import', 'display_name' => 'Import Siswa', 'group' => 'Siswa'],
            ['name' => 'siswa.export', 'display_name' => 'Export Siswa', 'group' => 'Siswa'],
            
            // Manajemen Guru
            ['name' => 'guru.view', 'display_name' => 'Lihat Guru', 'group' => 'Guru'],
            ['name' => 'guru.create', 'display_name' => 'Tambah Guru', 'group' => 'Guru'],
            ['name' => 'guru.edit', 'display_name' => 'Edit Guru', 'group' => 'Guru'],
            ['name' => 'guru.delete', 'display_name' => 'Hapus Guru', 'group' => 'Guru'],
            
            // Manajemen Kelas
            ['name' => 'kelas.view', 'display_name' => 'Lihat Kelas', 'group' => 'Kelas'],
            ['name' => 'kelas.create', 'display_name' => 'Tambah Kelas', 'group' => 'Kelas'],
            ['name' => 'kelas.edit', 'display_name' => 'Edit Kelas', 'group' => 'Kelas'],
            ['name' => 'kelas.delete', 'display_name' => 'Hapus Kelas', 'group' => 'Kelas'],
            
            // Manajemen Jurusan
            ['name' => 'jurusan.view', 'display_name' => 'Lihat Jurusan', 'group' => 'Jurusan'],
            ['name' => 'jurusan.create', 'display_name' => 'Tambah Jurusan', 'group' => 'Jurusan'],
            ['name' => 'jurusan.edit', 'display_name' => 'Edit Jurusan', 'group' => 'Jurusan'],
            ['name' => 'jurusan.delete', 'display_name' => 'Hapus Jurusan', 'group' => 'Jurusan'],
            
            // Absensi
            ['name' => 'absensi.view', 'display_name' => 'Lihat Absensi', 'group' => 'Absensi'],
            ['name' => 'absensi.create', 'display_name' => 'Tambah Absensi', 'group' => 'Absensi'],
            ['name' => 'absensi.edit', 'display_name' => 'Edit Absensi', 'group' => 'Absensi'],
            ['name' => 'absensi.delete', 'display_name' => 'Hapus Absensi', 'group' => 'Absensi'],
            ['name' => 'absensi.export', 'display_name' => 'Export Absensi', 'group' => 'Absensi'],
            
            // Keuangan
            ['name' => 'keuangan.view', 'display_name' => 'Lihat Keuangan', 'group' => 'Keuangan'],
            ['name' => 'keuangan.create', 'display_name' => 'Tambah Keuangan', 'group' => 'Keuangan'],
            ['name' => 'keuangan.edit', 'display_name' => 'Edit Keuangan', 'group' => 'Keuangan'],
            ['name' => 'keuangan.delete', 'display_name' => 'Hapus Keuangan', 'group' => 'Keuangan'],
            ['name' => 'keuangan.export', 'display_name' => 'Export Keuangan', 'group' => 'Keuangan'],
            
            // Laporan
            ['name' => 'laporan.view', 'display_name' => 'Lihat Laporan', 'group' => 'Laporan'],
            ['name' => 'laporan.export', 'display_name' => 'Export Laporan', 'group' => 'Laporan'],
            
            // Pengaturan
            ['name' => 'pengaturan.view', 'display_name' => 'Lihat Pengaturan', 'group' => 'Pengaturan'],
            ['name' => 'pengaturan.edit', 'display_name' => 'Edit Pengaturan', 'group' => 'Pengaturan'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // ===== ROLES =====
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Akses penuh ke semua fitur', 'is_default' => false],
            ['name' => 'administrasi', 'display_name' => 'Administrasi', 'description' => 'Manajemen data sekolah', 'is_default' => false],
            ['name' => 'kepala_sekolah', 'display_name' => 'Kepala Sekolah', 'description' => 'Akses laporan dan persetujuan', 'is_default' => false],
            ['name' => 'guru', 'display_name' => 'Guru', 'description' => 'Manajemen nilai dan absensi', 'is_default' => false],
            ['name' => 'siswa', 'display_name' => 'Siswa', 'description' => 'Akses terbatas untuk siswa', 'is_default' => true],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // ===== ASSIGN PERMISSIONS KE ROLE =====
        // Super Admin: semua permission
        $superAdmin = Role::where('name', 'super_admin')->first();
        $allPermissions = Permission::all()->pluck('id')->toArray();
        $superAdmin->syncPermissions($allPermissions);

        // Administrasi: permission untuk manajemen data
        $administrasi = Role::where('name', 'administrasi')->first();
        $adminPermissions = Permission::whereIn('group', [
            'Dashboard', 'Siswa', 'Guru', 'Kelas', 'Jurusan', 
            'Absensi', 'Keuangan', 'Laporan'
        ])->get()->pluck('id')->toArray();
        $administrasi->syncPermissions($adminPermissions);

        // Kepala Sekolah: permission laporan dan persetujuan
        $kepsek = Role::where('name', 'kepala_sekolah')->first();
        $kepsekPermissions = Permission::whereIn('group', [
            'Dashboard', 'Laporan', 'Keuangan'
        ])->get()->pluck('id')->toArray();
        $kepsek->syncPermissions($kepsekPermissions);

        // Guru: permission nilai dan absensi
        $guru = Role::where('name', 'guru')->first();
        $guruPermissions = Permission::whereIn('group', [
            'Dashboard', 'Absensi'
        ])->get()->pluck('id')->toArray();
        $guru->syncPermissions($guruPermissions);

        // ===== CREATE SUPER ADMIN USER =====
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@simdu.id',
            'password' => Hash::make('password'),
            'role_id' => $superAdmin->id,
        ]);

        // ===== CREATE ADMIN USER =====
        $adminUser = User::create([
            'name' => 'Administrasi',
            'email' => 'admin@simdu.id',
            'password' => Hash::make('password'),
            'role_id' => $administrasi->id,
        ]);

        $this->command->info('✅ Role, Permission, dan User berhasil dibuat!');
        $this->command->info('📧 Email: superadmin@simdu.id | Password: password');
        $this->command->info('📧 Email: admin@simdu.id | Password: password');
    }
}