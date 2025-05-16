<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
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
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'roles' => 'required|array', // array de roles
                'roles.*' => 'exists:roles,id', // validar que cada id exista
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirmed' => $request->confirmed ?? false,
            'isActive' => $request->isActive ?? true,
            'isVerified' => $request->isVerified ?? false,
        ]);

        if ($request->has('roles') && count($request->roles) > 0) {
            $user->roles()->sync($request->roles);
        } else {
            $defaultRole = Role::where('name', 'user')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id);
            }
        }

        // Cargar roles para la respuesta
        $user->load('roles');
        return ApiResponse::success($user, 'Usuario creado exitosamente', 201);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::findOrFail($id);
        return ApiResponse::success($user);
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'confirmed' => $request->confirmed ?? $user->confirmed,
            'isActive' => $request->isActive ?? $user->isActive,
            'isVerified' => $request->isVerified ?? $user->isVerified,
        ]);

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
}
