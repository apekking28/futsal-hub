<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GorFactory extends Factory
{
    public function definition(): array
    {
        $name = 'GOR ' . $this->faker->city() . ' ' . $this->faker->company();
        return [
            'district_id'         => District::inRandomOrder()->first()?->id ?? District::factory(),
            'name'                => $name,
            'slug'                => Str::slug($name) . '-' . Str::random(4),
            'address'             => $this->faker->address(),
            'description'         => $this->faker->paragraph(),
            'maps_url'            => 'https://maps.google.com/?q=' . $this->faker->latitude() . ',' . $this->faker->longitude(),
            'phone_number'        => $this->faker->phoneNumber(),
            'open_time'           => '08:00',
            'close_time'          => '23:00',
            'bank_name'           => $this->faker->randomElement(['BCA', 'Mandiri', 'BRI', 'BNI']),
            'bank_account_number' => $this->faker->bankAccountNumber(),
            'bank_account_holder' => $this->faker->name(),
            'is_active'           => true,
        ];
    }
}
