<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institucion_id' => 'required|exists:instituciones,id',
            'course_id' => 'required|exists:cursos,id',
            'teacher_id' => 'required|exists:docentes,id',
            'section_id' => 'required|exists:secciones,id',
            'date' => 'required|date',
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
            'institucion_id.required' => 'La institución es requerida',
            'course_id.required' => 'El curso es requerido',
            'teacher_id.required' => 'El docente es requerido',
            'section_id.required' => 'La sección es requerida',
            'date.required' => 'La fecha es requerida',
            'details.*.status.in' => 'El estado debe ser: present, absent, late o justified',
        ];
    }
}
