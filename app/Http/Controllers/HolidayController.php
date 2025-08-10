<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return ApiResponse::success($holidays, 'Días inhábiles obtenidos exitosamente');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date|unique:holidays,date',
            ]);

            $holiday = Holiday::create($validated);

            return ApiResponse::created($holiday, 'Día inhábil creado exitosamente');
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        }
    }

    public function destroy($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $holiday->delete();

            return ApiResponse::success(null, 'Día inhábil eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Día inhábil no encontrado', 404);
        }
    }
}
