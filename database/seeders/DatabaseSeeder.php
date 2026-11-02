<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Field;
use App\Models\Gor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Reset Cache Permission Spatie (Penting agar tidak berbenturan saat fresh migrate)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Roles Spatie RBAC Sesuai Spesifikasi (super-admin, admin-gor, kasir)
        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        $roleAdminGor   = Role::create(['name' => 'admin-gor']);
        $roleKasir      = Role::create(['name' => 'kasir']);

        // 3. Buat Data Wilayah / Districts di Area Cengkareng
        $districts = [
            ['name' => 'Cengkareng Barat', 'slug' => 'cengkareng-barat'],
            ['name' => 'Cengkareng Timur', 'slug' => 'cengkareng-timur'],
            ['name' => 'Rawa Buaya', 'slug' => 'rawa-buaya'],
            ['name' => 'Duri Kosambi', 'slug' => 'duri-kosambi'],
            ['name' => 'Kapuk', 'slug' => 'kapuk'],
            ['name' => 'Kedaung Kali Angke', 'slug' => 'kedaung-kali-angke'],
        ];

        foreach ($districts as $districtData) {
            District::create($districtData);
        }

        // Ambil salah satu contoh district untuk dummy GOR
        $districtCFC = District::where('slug', 'cengkareng-barat')->first();

        // 4. Buat Dummy GOR (Cengkareng Futsal Center)
        $gor = Gor::create([
            'district_id'  => $districtCFC->id,
            'name'         => 'Cengkareng Futsal Center',
            'slug'         => 'cengkareng-futsal-center',
            'address'      => 'Jl. Raya Cengkareng No. 88, Jakarta Barat',
            'phone_number' => '081234567890',
            'is_active'    => true,
        ]);

        // 5. Buat Dummy Lapangan (Fields)
        Field::create([
            'gor_id'         => $gor->id,
            'name'           => 'Lapangan A (Vinyl)',
            'type'           => 'vinyl',
            'price_per_hour' => 150000,
            'description'    => 'Lantai Vinyl standar internasional, pencahayaan LED terang.',
        ]);

        Field::create([
            'gor_id'         => $gor->id,
            'name'           => 'Lapangan B (Sintetis)',
            'type'           => 'sintetis',
            'price_per_hour' => 120000,
            'description'    => 'Rumput sintetis halus dengan jaring pengaman penuh.',
        ]);

        // 6. Buat Akun Super Admin (Akses Global, gor_id = NULL)
        $superAdmin = User::create([
            'gor_id'       => null,
            'name'         => 'Super Admin FutsalHub',
            'email'        => 'admin@futsalhub.id',
            'password'     => Hash::make('password123'),
            'phone_number' => '081111111111',
        ]);
        $superAdmin->assignRole($roleSuperAdmin);

        // 7. Buat Akun Admin GOR (Terikat pada GOR CFC)
        $adminGor = User::create([
            'gor_id'       => $gor->id,
            'name'         => 'Admin CFC Cengkareng',
            'email'        => 'admin.cfc@futsalhub.id',
            'password'     => Hash::make('password123'),
            'phone_number' => '082222222222',
        ]);
        $adminGor->assignRole($roleAdminGor);

        // 8. Buat Akun Kasir (Terikat pada GOR CFC)
        $kasir = User::create([
            'gor_id'       => $gor->id,
            'name'         => 'Kasir CFC Cengkareng',
            'email'        => 'kasir.cfc@futsalhub.id',
            'password'     => Hash::make('password123'),
            'phone_number' => '083333333333',
        ]);
        $kasir->assignRole($roleKasir);
    }
}
