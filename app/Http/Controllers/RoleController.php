<?php


namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Role;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return ApiResponse::success($role, 'Rol creado exitosamente', 201);
    }

    // Mostrar un rol específico
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return ApiResponse::success($role, 'Rol obtenido exitosamente');
    }

    // Actualizar un rol existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        return ApiResponse::success($role, 'Rol actualizado exitosamente');
    }

    // Eliminar un rol
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return ApiResponse::success(null, 'Rol eliminado exitosamente');
    }
}
