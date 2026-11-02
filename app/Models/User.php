<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // 1. Import Trait Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // 2. Tambahkan HasRoles di sini

    protected $fillable = [
        'gor_id',
        'name',
        'email',
        'password',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke GOR (Admin/Kasir terikat pada 1 GOR, NULL jika Super Admin).
     */
    public function gor(): BelongsTo
    {
        return $this->belongsTo(Gor::class);
    }
}
