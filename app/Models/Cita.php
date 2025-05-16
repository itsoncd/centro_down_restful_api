<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_cita',
        'correo',
        'nombre_alumno',
        'nombre_tutor'
    ];

    // Relación con el usuario que organizó la cita
    public function organizador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
