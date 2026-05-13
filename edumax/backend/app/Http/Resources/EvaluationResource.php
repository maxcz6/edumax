<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'institucion_id' => $this->institucion_id,
            'course_id' => $this->course_id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'peso' => $this->peso,
            'fecha' => $this->fecha,
            'bimestre' => $this->bimestre,
            'descripcion' => $this->descripcion,
            'promedio' => $this->getAverageScore(),
            'total_estudiantes' => $this->grades->count(),
            'course' => $this->whenLoaded('course', fn() => ['id' => $this->course?->id, 'nombre' => $this->course?->nombre]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
