<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'gor_id',
        'name',
        'slug',
        'type',
        'price_per_hour',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    /**
     * Auto cleanup foto dari storage saat model Field dihapus
     */
    protected static function booted()
    {
        static::deleting(function ($field) {
            foreach ($field->images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                if ($image->thumb_path && Storage::disk('public')->exists($image->thumb_path)) {
                    Storage::disk('public')->delete($image->thumb_path);
                }
                $image->delete();
            }
        });
    }

    /**
     * Relasi Polimorfik: Lapangan memiliki banyak foto
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order', 'asc');
    }

    /**
     * Accessor Foto Cover Utama
     */
    public function getCoverImageAttribute()
    {
        return $this->images->where('is_cover', true)->first() ?? $this->images->first();
    }

    public function gor(): BelongsTo
    {
        return $this->belongsTo(Gor::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
