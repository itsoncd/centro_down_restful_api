<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Listar todos los usuarios
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
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'roles'    => 'required|array',
                'roles.*'  => 'exists:roles,id',
            ]);

            $user = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'confirmed'  => $request->confirmed ?? true,
                'isActive'   => $request->isActive ?? true,
                'isVerified' => $request->isVerified ?? true,
            ]);

            $user->roles()->sync($validated['roles']);
            $user->load('roles');

            return ApiResponse::created($user, 'Usuario creado exitosamente');
        } catch (ValidationException $e) {
            return ApiResponse::error(json_encode($e->errors()), 422);
        }
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'roles'    => 'sometimes|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        $user->load('roles');

        return ApiResponse::success($user, 'Usuario actualizado exitosamente');
    }

    // Desactivar un usuario
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->isActive = false;
        $user->save();

        return ApiResponse::success($user, 'Usuario desactivado exitosamente');
    }

    // Activar un usuario
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->isActive = true;
        $user->save();

        return ApiResponse::success($user, 'Usuario activado exitosamente');
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::findOrFail($id);
        return ApiResponse::success($user);
    }
}
