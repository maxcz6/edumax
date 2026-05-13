<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evaluation_id' => 'required|exists:evaluations,id',
            'student_id' => 'required|exists:estudiantes,id',
            'score' => 'required|numeric|min:0|max:20',
            'comentarios' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'evaluation_id.required' => 'La evaluación es requerida',
            'student_id.required' => 'El estudiante es requerido',
            'score.required' => 'La calificación es requerida',
            'score.min' => 'La calificación debe ser mínimo 0',
            'score.max' => 'La calificación debe ser máximo 20',
        ];
    }
}
