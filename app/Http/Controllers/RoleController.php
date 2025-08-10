<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    // Mostrar todos los roles
    public function index()
    {
        $roles = Role::all();
        return ApiResponse::success($roles, 'Roles obtenidos exitosamente');
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:roles,name|max:255',
            ]);
            
            $role = Role::create([
                'name' => $request->name,
            ]);

            return ApiResponse::success($role, 'Rol creado exitosamente', 201);

        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al crear rol', 500);
        }
    }

    // Mostrar un rol específico
    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);
            return ApiResponse::success($role, 'Rol obtenido exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Rol no encontrado', 'Error', 404);
        }
    }

    // Actualizar un rol existente
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
            ]);

            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
            ]);

            return ApiResponse::success($role, 'Rol actualizado exitosamente');

        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 'Error de validación', 422);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error al actualizar rol', 500);
        }
    }

    // Eliminar un rol
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return ApiResponse::success(null, 'Rol eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Rol no encontrado o no se pudo eliminar', 'Error', 404);
        }
    }
}
