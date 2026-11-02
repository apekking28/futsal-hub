<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['name' => 'Cengkareng Barat', 'slug' => 'cengkareng-barat'],
            ['name' => 'Cengkareng Timur', 'slug' => 'cengkareng-timur'],
            ['name' => 'Rawa Buaya', 'slug' => 'rawa-buaya'],
            ['name' => 'Duri Kosambi', 'slug' => 'duri-kosambi'],
            ['name' => 'Kapuk', 'slug' => 'kapuk'],
            ['name' => 'Kedaung Kali Angke', 'slug' => 'kedaung-kali-angke'],
        ];

        foreach ($districts as $district) {
            District::firstOrCreate(['slug' => $district['slug']], $district);
        }
    }
}
