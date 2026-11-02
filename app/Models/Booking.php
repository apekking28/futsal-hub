<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'field_id',
        'customer_name',
        'customer_phone',
        'booking_date',
        'total_price',
        'payment_method',
        'payment_status',
        'booking_status',
        'proof_of_payment',
        'rejection_reason',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price'  => 'decimal:2',
    ];

    /**
     * Relasi: Booking ditujukan untuk 1 Lapangan
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Relasi: Booking memiliki banyak Detail Slot Jam
     */
    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }
}
