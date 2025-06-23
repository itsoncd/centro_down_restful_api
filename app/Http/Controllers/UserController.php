<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Mostrar todos los usuarios
    public function index()
    {
        $users = User::all();
        return ApiResponse::success($users);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'confirmed' => $request->confirmed ?? true,
            'isActive' => $request->isActive ?? true,
            'isVerified' => $request->isVerified ?? true,
        ]);

        $user->roles()->sync($validated['roles']);
        $user->load('roles');

        return ApiResponse::success($user, 'Usuario creado exitosamente', 201);
    } catch (ValidationException $e) {
        return ApiResponse::error($e->errors(), 'Error de validación', 422);
    }
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validar los campos que se envíen
    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $id,
        'password' => 'sometimes|string|min:8', // sin confirmed
        'roles' => 'sometimes|array',
        'roles.*' => 'exists:roles,id',
    ]);

    // Si se envió una nueva contraseña, hashearla
    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    // Actualizar usuario con los datos validados
    $user->update($validated);

    // Si se enviaron nuevos roles, sincronizarlos
    if (isset($validated['roles'])) {
        $user->roles()->sync($validated['roles']);
    }

    // Cargar roles para la respuesta
    $user->load('roles');

    return ApiResponse::success($user, 'Usuario actualizado exitosamente');
}


    
    // Desactivar un usuario
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->isActive = false;
        $user->save();
        
        return response()->json(['message' => 'Usuario desactivado exitosamente', 'user' => $user]);
    }
    
    // Activar un usuario
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->isActive = true;
        $user->save();
        
        return response()->json(['message' => 'Usuario activado exitosamente', 'user' => $user]);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::findOrFail($id);
        return ApiResponse::success($user);
    }
}
