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
        'expiresAt',
        'createdAt',
        'user_id',
        'tokenType',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}