<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function create(Request $request)
    {
        try {
            $student = $this->studentService->create($request->all());

            return response()->json([
                'message' => 'Estudiante creado exitosamente.',
                'student' => $student,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAll()
    {
        $students = $this->studentService->getAll();

        return response()->json($students, 200);
    }

    public function getOne($id)
    {
        try {
            $student = $this->studentService->getOne($id);

            return response()->json($student, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $student = $this->studentService->update($id, $request->all());

            return response()->json([
                'message' => 'Estudiante actualizado exitosamente.',
                'student' => $student,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        try {
            $response = $this->studentService->delete($id);

            return response()->json($response, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
