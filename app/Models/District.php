<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Relasi: Satu District (Daerah) memiliki banyak GOR
     */
    public function gors(): HasMany
    {
        return $this->hasMany(Gor::class);
    }
}
