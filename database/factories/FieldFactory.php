<?php

namespace Database\Factories;

use App\Models\Gor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FieldFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Lapangan ' . $this->faker->randomElement(['A', 'B', 'C', 'Utama', 'Vip']) . ' (' . $this->faker->randomElement(['Vinyl', 'Sintetis', 'Parquet']) . ')';
        return [
            'gor_id'         => Gor::inRandomOrder()->first()?->id ?? Gor::factory(),
            'name'           => $name,
            'slug'           => Str::slug($name) . '-' . Str::random(5),
            'type'           => $this->faker->randomElement(['vinyl', 'sintetis', 'parquet']),
            'price_per_hour' => $this->faker->randomElement([100000, 120000, 150000, 180000, 200000]),
            'is_active'      => true,
            'description'    => $this->faker->sentence(),
        ];
    }
}
