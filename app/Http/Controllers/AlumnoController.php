<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;

class AlumnoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'edad' => 'required|integer',
                'sexo' => 'required|in:M,F,Otro',
                'num_hermanos' => 'nullable|integer',
                'num_hijos' => 'nullable|integer',
                'tipo_preescolar' => 'nullable|string|max:255',
                'nombre_preescolar' => 'nullable|string|max:255',
                'tipo_primaria' => 'nullable|string|max:255',
                'nombre_primaria' => 'nullable|string|max:255',
                'duracion_tiempo_preescolar' => 'nullable|string|max:255',
                'duracion_tiempo_primaria' => 'nullable|string|max:255',
                'tipo_secundaria' => 'nullable|string|max:255',
                'nombre_secundaria' => 'nullable|string|max:255',
                'duracion_tiempo_secundaria' => 'nullable|string|max:255',
                'nss' => 'nullable|string|max:255',
                'tipo_de_sangre' => 'nullable|string|max:10',
                'enfermedades' => 'nullable|string',
                'alergias' => 'nullable|string',
                'medicamentos' => 'nullable|string',
                'operaciones' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        }

        $alumno = Alumno::create($request->all());

        return ApiResponse::success($alumno, 'Alumno creado exitosamente', 201);
    }

    public function index()
    {
        $alumnos = Alumno::all();
        return ApiResponse::success($alumnos, 'Lista de alumnos');
    }

    // Agrega show, update, delete si quieres
}
