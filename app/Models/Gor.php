<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Gor extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name',
        'slug',
        'address',
        'description',
        'maps_url',
        'phone_number',
        'open_time',
        'close_time',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Auto cleanup foto dari storage disk saat GOR dihapus
     */
    protected static function booted()
    {
        static::deleting(function ($gor) {
            foreach ($gor->images as$image) {
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
     * Relasi Polimorfik: GOR memiliki banyak foto
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order', 'asc');
    }

    /**
     * Helper Accessor Foto Cover Utama
     */
    public function getCoverImageAttribute()
    {
        return $this->images->where('is_cover', true)->first() ?? $this->images->first();
    }

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
