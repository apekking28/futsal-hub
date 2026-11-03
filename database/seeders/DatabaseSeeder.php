<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            DistrictSeeder::class,
            GorAndFieldSeeder::class,
            UserSeeder::class,

            // Tambahkan Seeder Data Testing Massal di sini
            DummyTestDataSeeder::class,
        ]);
    }
}
