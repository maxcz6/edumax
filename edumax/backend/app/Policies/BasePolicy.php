<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Verificar si el usuario pertenece a la misma institución que el modelo.
     */
    protected function sameInstitution(User $user, $model): bool
    {
        // El Super Admin puede todo
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->institucion_id === $model->institucion_id;
    }
}
