<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',  // fecha y hora de entrada
        'end_time',    // fecha y hora de salida
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];
}
