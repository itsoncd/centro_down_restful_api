<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();
        return ApiResponse::success($schedules, 'Horarios obtenidos exitosamente');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_time' => 'required|date',
                'end_time' => 'nullable|date|after_or_equal:start_time',
            ]);

            $schedule = Schedule::create($validated);

            return ApiResponse::created($schedule, 'Horario creado exitosamente');
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            $validated = $request->validate([
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|nullable|date|after_or_equal:start_time',
            ]);

            $schedule->update($validated);

            return ApiResponse::success($schedule, 'Horario actualizado exitosamente');
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 404);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();

            return ApiResponse::success(null, 'Horario eliminado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Horario no encontrado', 404);
        }
    }
}
