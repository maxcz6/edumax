<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentGradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'student_id' => $this->student_id,
            'score' => $this->score,
            'comentarios' => $this->comentarios,
            'evaluation' => $this->whenLoaded('evaluation', fn() => [
                'id' => $this->evaluation?->id,
                'nombre' => $this->evaluation?->nombre,
                'tipo' => $this->evaluation?->tipo,
                'peso' => $this->evaluation?->peso,
                'fecha' => $this->evaluation?->fecha,
            ]),
            'student' => $this->whenLoaded('student', fn() => [
                'id' => $this->student?->id,
                'codigo' => $this->student?->codigo_estudiante,
                'nombre' => $this->student?->user?->nombres,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
