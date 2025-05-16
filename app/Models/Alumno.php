<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'fecha_nacimiento',
        'edad',
        'sexo',
        'num_hermanos',
        'num_hijos',
        'tipo_preescolar',
        'nombre_preescolar',
        'tipo_primaria',
        'nombre_primaria',
        'duracion_tiempo_preescolar',
        'duracion_tiempo_primaria',
        'tipo_secundaria',
        'nombre_secundaria',
        'duracion_tiempo_secundaria',
        'nss',
        'tipo_de_sangre',
        'enfermedades',
        'alergias',
        'medicamentos',
        'operaciones',
    ];
}
