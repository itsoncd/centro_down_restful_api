<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'        => 'required|exists:users,id',
                'fecha_cita'     => 'required|date',
                'correo'         => 'required|email|max:255',
                'nombre_alumno'  => 'required|string|max:255',
                'nombre_tutor'   => 'required|string|max:255'
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        }

        $user = User::with('roles')->find($validated['user_id']);
        if (!$user->roles->contains('name', 'director')) {
            return ApiResponse::error("Rol de Usuario Invalido", 'El usuario no tiene el rol de director.', 403);
        }

        $cita = Cita::create($validated);

        return ApiResponse::success($cita, 'Cita creada exitosamente', 201);
    }
}
