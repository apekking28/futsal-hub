<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Field;
use App\Models\Gor;
use Illuminate\Database\Seeder;

class GorAndFieldSeeder extends Seeder
{
    public function run(): void
    {
        $district = District::where('slug', 'cengkareng-barat')->first();

        if (!$district) {
            return;
        }

        // Dummy GOR
        $gor = Gor::firstOrCreate(
            ['slug' => 'cengkareng-futsal-center'],
            [
                'district_id'         => $district->id,
                'name'                => 'Cengkareng Futsal Center',
                'address'             => 'Jl. Raya Cengkareng No. 88, Jakarta Barat',
                'phone_number'        => '081234567890',
                'open_time'           => '08:00',
                'close_time'          => '23:00',
                'bank_name'           => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_holder' => 'PT Futsal Indonesia',
                'is_active'           => true,
            ]
        );

        // Dummy Lapangan
        Field::firstOrCreate(
            ['slug' => 'cengkareng-futsal-center-lapangan-a-vinyl'],
            [
                'gor_id'         => $gor->id,
                'name'           => 'Lapangan A (Vinyl)',
                'type'           => 'vinyl',
                'price_per_hour' => 150000,
                'is_active'      => true,
                'description'    => 'Lantai Vinyl standar internasional, pencahayaan LED terang.',
            ]
        );

        Field::firstOrCreate(
            ['slug' => 'cengkareng-futsal-center-lapangan-b-sintetis'],
            [
                'gor_id'         => $gor->id,
                'name'           => 'Lapangan B (Sintetis)',
                'type'           => 'sintetis',
                'price_per_hour' => 120000,
                'is_active'      => true,
                'description'    => 'Rumput sintetis halus dengan jaring pengaman penuh.',
            ]
        );
    }
}
