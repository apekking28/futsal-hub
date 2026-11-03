<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_code'   => 'FTS-' . strtoupper(Str::random(6)),
            'field_id'       => Field::inRandomOrder()->first()?->id ?? Field::factory(),
            'customer_name'  => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'booking_date'   => $this->faker->dateTimeBetween('now', '+14 days')->format('Y-m-d'),
            'total_price'    => 150000,
            'payment_method' => $this->faker->randomElement(['transfer', 'cash']),
            'payment_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'booking_status' => $this->faker->randomElement(['reserved', 'confirmed', 'completed']),
        ];
    }
}
