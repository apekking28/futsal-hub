<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Field;
use App\Models\Gor;
use Illuminate\Database\Seeder;

class DummyTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 10 GOR Dummy
        $gors = Gor::factory(10)->create();

        // 2. Setiap GOR buat 2-4 Lapangan
        foreach ($gors as $gor) {
            $fields = Field::factory(rand(2, 4))->create([
                'gor_id' => $gor->id
            ]);

            // 3. Tambahkan beberapa foto dummy polimorfik
            foreach ($fields as $field) {
                $field->images()->create([
                    'path'       => 'fields/sample.webp',
                    'thumb_path' => 'fields/sample.webp',
                    'alt_text'   => $field->name,
                    'is_cover'   => true,
                    'sort_order' => 1,
                ]);

                // 4. Buat 5-10 Transaksi Booking per Lapangan untuk testing ketersediaan slot jam
                $bookings = Booking::factory(rand(5, 10))->create([
                    'field_id' => $field->id
                ]);

                foreach ($bookings as $booking) {
                    $startHour = rand(8, 20);
                    BookingDetail::create([
                        'booking_id' => $booking->id,
                        'start_time' => sprintf('%02d:00:00', $startHour),
                        'end_time'   => sprintf('%02d:00:00', $startHour + 1),
                        'price'      => $field->price_per_hour,
                    ]);
                }
            }
        }
    }
}
