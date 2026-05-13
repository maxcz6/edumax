<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institucion_id' => 'sometimes|required|exists:instituciones,id',
            'course_id' => 'sometimes|required|exists:cursos,id',
            'teacher_id' => 'sometimes|required|exists:docentes,id',
            'section_id' => 'sometimes|required|exists:secciones,id',
            'date' => 'sometimes|required|date',
            'observations' => 'nullable|string|max:500',
            'details' => 'nullable|array',
            'details.*.student_id' => 'required|exists:estudiantes,id',
            'details.*.status' => 'required|in:present,absent,late,justified',
            'details.*.remarks' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'details.*.status.in' => 'El estado debe ser: present, absent, late o justified',
        ];
    }
}
