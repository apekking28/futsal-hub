<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'start_time',
        'end_time',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relasi: Detail Slot Jam terikat pada 1 Booking Utama
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
