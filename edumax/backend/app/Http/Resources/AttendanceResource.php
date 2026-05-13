<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'institucion_id' => $this->institucion_id,
            'course_id' => $this->course_id,
            'teacher_id' => $this->teacher_id,
            'section_id' => $this->section_id,
            'date' => $this->date,
            'observations' => $this->observations,
            'course' => $this->whenLoaded('course', fn() => ['id' => $this->course?->id, 'name' => $this->course?->nombre]),
            'teacher' => $this->whenLoaded('teacher', fn() => ['id' => $this->teacher?->id, 'name' => $this->teacher?->user?->nombres]),
            'section' => $this->whenLoaded('section', fn() => ['id' => $this->section?->id, 'name' => $this->section?->nombre]),
            'details' => AttendanceDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
