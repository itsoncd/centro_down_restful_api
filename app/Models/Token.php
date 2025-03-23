<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verifica si el token aún es válido
    public function isValid(): bool
    {
        return Carbon::now()->lt($this->expires_at);
    }
}
