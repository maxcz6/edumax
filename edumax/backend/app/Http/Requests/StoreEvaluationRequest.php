<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
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
            'nombre' => 'required|string|max:200',
            'tipo' => 'required|in:examen,tarea,proyecto,participacion,trabajo_grupo,otro',
            'peso' => 'required|integer|min:1|max:100',
            'fecha' => 'required|date',
            'bimestre' => 'nullable|integer|min:1|max:4',
            'descripcion' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la evaluación es requerido',
            'tipo.required' => 'El tipo de evaluación es requerido',
            'peso.required' => 'El peso es requerido',
            'fecha.required' => 'La fecha es requerida',
        ];
    }
}
