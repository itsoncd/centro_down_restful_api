<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Token extends Model
{
    use HasFactory; // Aquí usamos el trait HasFactory

    public $timestamps = false;

    protected $fillable = [
        'token',
        'expires_at',
        'created_at',
        'user_id',
        'tokenType',
    ];

    protected $casts = [
    'expires_at' => 'datetime',
    'created_at' => 'datetime',
];


    public function isValid(): bool
{
    return $this->expires_at && now()->lessThanOrEqualTo($this->expires_at);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}