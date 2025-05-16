<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;

class TutorController extends Controller
{
    // Crear tutor
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'correo' => 'required|email|unique:tutors,correo',
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        }

        $tutor = Tutor::create($request->all());

        return ApiResponse::success($tutor, 'Tutor creado exitosamente', 201);
    }

    // Listar todos los tutores
    public function index()
    {
        $tutores = Tutor::all();
        return ApiResponse::success($tutores, 'Lista de tutores');
    }

    // Puedes agregar show, update, delete si quieres
}
