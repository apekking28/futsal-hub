<?php

namespace Database\Seeders;

use App\Models\Gor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $gor = Gor::first();

        // 1. Akun Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@futsalhub.id'],
            [
                'gor_id'       => null,
                'name'         => 'Super Admin FutsalHub',
                'password'     => Hash::make('password123'),
                'phone_number' => '081111111111',
            ]
        );
        $superAdmin->assignRole('super-admin');

        // 2. Akun Admin GOR
        $adminGor = User::firstOrCreate(
            ['email' => 'admin.cfc@futsalhub.id'],
            [
                'gor_id'       => $gor?->id,
                'name'         => 'Admin CFC Cengkareng',
                'password'     => Hash::make('password123'),
                'phone_number' => '082222222222',
            ]
        );
        $adminGor->assignRole('admin-gor');

        // 3. Akun Kasir
        $kasir = User::firstOrCreate(
            ['email' => 'kasir.cfc@futsalhub.id'],
            [
                'gor_id'       => $gor?->id,
                'name'         => 'Kasir CFC Cengkareng',
                'password'     => Hash::make('password123'),
                'phone_number' => '083333333333',
            ]
        );
        $kasir->assignRole('kasir');
    }
}
