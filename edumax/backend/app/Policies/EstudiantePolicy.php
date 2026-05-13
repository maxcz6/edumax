<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Estudiante;

class EstudiantePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'director', 'docente']);
    }

    public function view(User $user, Estudiante $estudiante): bool
    {
        return $this->sameInstitution($user, $estudiante) && 
               ($user->hasAnyRole(['admin', 'director', 'docente']) || $user->id === $estudiante->user_id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'director']);
    }

    public function update(User $user, Estudiante $estudiante): bool
    {
        return $this->sameInstitution($user, $estudiante) && $user->hasAnyRole(['admin', 'director']);
    }

    public function delete(User $user, Estudiante $estudiante): bool
    {
        return $this->sameInstitution($user, $estudiante) && $user->hasAnyRole(['admin', 'director']);
    }
}
