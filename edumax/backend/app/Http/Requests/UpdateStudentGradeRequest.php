<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score' => 'sometimes|required|numeric|min:0|max:20',
            'comentarios' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'score.min' => 'La calificación debe ser mínimo 0',
            'score.max' => 'La calificación debe ser máximo 20',
        ];
    }
}
