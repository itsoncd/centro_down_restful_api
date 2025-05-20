<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentService
{
    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'school_id' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $student = Student::create($validator->validated());

        return $student;
    }

    public function getAll()
    {
        return Student::all();
    }

    public function getOne($id)
    {
        $student = Student::find($id);

        if (!$student) {
            throw new \Exception('Estudiante no encontrado.', 404);
        }

        return $student;
    }

    public function update($id, array $data)
    {
        $student = Student::find($id);

        if (!$student) {
            throw new \Exception('Estudiante no encontrado.', 404);
        }

        $validator = Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'age' => 'sometimes|required|integer|min:1',
            // Valida aquí más campos si tienes
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $student->update($validator->validated());

        return $student;
    }

    public function delete($id)
    {
        $student = Student::find($id);

        if (!$student) {
            throw new \Exception('Estudiante no encontrado.', 404);
        }

        $student->delete();

        return [
            'message' => 'Estudiante eliminado correctamente.'
        ];
    }
}
