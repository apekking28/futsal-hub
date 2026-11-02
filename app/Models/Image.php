<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'path',
        'thumb_path',
        'alt_text',
        'is_cover',
        'sort_order',
    ];

    protected $casts = [
        'is_cover'   => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relasi Polimorfik (Bisa milik Field / Gor)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
