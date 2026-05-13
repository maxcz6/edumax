<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attendance_id' => $this->attendance_id,
            'student_id' => $this->student_id,
            'status' => $this->status,
            'remarks' => $this->remarks,
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
