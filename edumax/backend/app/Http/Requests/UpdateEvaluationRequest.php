<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:200',
            'tipo' => 'sometimes|required|in:examen,tarea,proyecto,participacion,trabajo_grupo,otro',
            'peso' => 'sometimes|required|integer|min:1|max:100',
            'fecha' => 'sometimes|required|date',
            'bimestre' => 'nullable|integer|min:1|max:4',
            'descripcion' => 'nullable|string|max:1000',
        ];
    }
}
