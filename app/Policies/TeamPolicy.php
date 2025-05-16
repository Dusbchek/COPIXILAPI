<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver el equipo.
     *
     * @param  \App\Models\User  $user El usuario que intenta ver el equipo.
     * @param  \App\Models\Team  $team El equipo a ver.
     * @return bool
     */
    public function view(User $user, Team $team): bool
    {
        return $team->members->contains($user) || $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede actualizar el equipo.
     *
     * @param  \App\Models\User  $user El usuario que intenta actualizar el equipo.
     * @param  \App\Models\Team  $team El equipo a actualizar.
     * @return bool
     */
    public function update(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede eliminar el equipo.
     * @param  \App\Models\User  $user El usuario que intenta eliminar el equipo.
     * @param  \App\Models\Team  $team El equipo a eliminar.
     * @return bool
     */
    public function delete(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede añadir un miembro al equipo.
     * @param  \App\Models\User  $user El usuario que intenta añadir un miembro al equipo.
     * @param  \App\Models\Team  $team El equipo al que se añadirá el miembro.
     * @return bool
     */
    public function addMember(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede eliminar un miembro del equipo.
     * @param  \App\Models\User  $user El usuario que intenta eliminar un miembro del equipo.
     * @param  \App\Models\Team  $team El equipo del que se eliminará el miembro.
     * @return bool
     */
    public function removeMember(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede asignar un líder al equipo.
     * @param  \App\Models\User  $user El usuario que intenta asignar un líder al equipo.
     * @param  \App\Models\Team  $team El equipo al que se asignará el líder.
     * @return bool
     */
    public function assignLeader(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede eliminar un líder del equipo.
     * @param  \App\Models\User  $user El usuario que intenta eliminar un líder del equipo.
     * @param  \App\Models\Team  $team El equipo del que se eliminará el líder.
     * @return bool
     */
    public function removeLeader(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }

    /**
     * Determina si el usuario puede asignar una tarea al equipo.
     *
     * @param  \App\Models\User  $user El usuario que intenta asignar la tarea.
     * @param  \App\Models\Team  $team El equipo al que se asignará la tarea.
     * @return bool
     */
    public function assignTask(User $user, Team $team): bool
    {
        return $team->teamLeaders->contains($user);
    }
}
