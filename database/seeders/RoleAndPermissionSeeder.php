<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Cache Permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Daftar Permission Berdasarkan Modul (Format: nama_modul.aksi)
        $permissions = [
            // Modul Booking
            'bookings.view',
            'bookings.checkin',
            'bookings.approve',
            'bookings.reject',
            'bookings.cancel',
            'bookings.update-status',

            // Modul Lapangan (Fields)
            'fields.view',
            'fields.create',
            'fields.edit',
            'fields.delete',

            // Modul GOR
            'gors.view',
            'gors.create',
            'gors.edit',
            'gors.delete',

            // Modul User & RBAC
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.manage',
            'permissions.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name'       => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        // 3. Buat / Ambil Roles & Assign Permission

        // Super Admin (Semua Hak Akses)
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleSuperAdmin->syncPermissions(Permission::all());

        // Admin GOR (Manajemen Booking, Lapangan, & Monitoring GOR)
        $roleAdminGor = Role::firstOrCreate(['name' => 'admin-gor']);
        $roleAdminGor->syncPermissions([
            'bookings.view',
            'bookings.checkin',
            'bookings.approve',
            'bookings.reject',
            'bookings.cancel',
            'bookings.update-status',
            'fields.view',
            'fields.create',
            'fields.edit',
            'fields.delete',
            'gors.view',
        ]);

        // Kasir (Check-in & View Booking di tempat)
        $roleKasir = Role::firstOrCreate(['name' => 'kasir']);
        $roleKasir->syncPermissions([
            'bookings.view',
            'bookings.checkin',
        ]);
    }
}
