<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
    'gor_id',
    'name',
    'type',
    'price_per_hour',
    'is_active',
    'description',
];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
    ];

    /**
     * Relasi: Lapangan dimiliki oleh 1 GOR
     */
    public function gor(): BelongsTo
    {
        return $this->belongsTo(Gor::class);
    }

    /**
     * Relasi: Lapangan memiliki banyak Booking
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
