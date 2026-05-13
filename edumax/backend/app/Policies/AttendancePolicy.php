<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;

class AttendancePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'director', 'docente', 'padre']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $this->sameInstitution($user, $attendance);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'director', 'docente']);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $this->sameInstitution($user, $attendance) && $user->hasAnyRole(['admin', 'director', 'docente']);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $this->sameInstitution($user, $attendance) && $user->hasAnyRole(['admin', 'director']);
    }
}
