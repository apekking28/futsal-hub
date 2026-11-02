<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gor extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name',
        'slug',
        'address',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: GOR dimiliki oleh 1 District
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relasi: GOR memiliki banyak Lapangan (Fields)
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Relasi: GOR memiliki banyak User (Admin/Kasir)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
