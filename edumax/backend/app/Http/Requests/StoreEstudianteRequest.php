<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'codigo_estudiante' => 'required|string|max:50|unique:estudiantes',
            'dni' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:M,F,Otro',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:activo,retirado,egresado',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El usuario es requerido',
            'codigo_estudiante.required' => 'El código de estudiante es requerido',
            'codigo_estudiante.unique' => 'Este código de estudiante ya existe',
        ];
    }
}
