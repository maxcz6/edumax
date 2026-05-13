<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'foto_perfil' => $this->foto_perfil,
            'institucion_id' => $this->institucion_id,
            'estado' => $this->estado,
            'roles' => $this->getRoleNames(),
            'ultimo_acceso' => $this->ultimo_acceso,
        ];
    }
}
