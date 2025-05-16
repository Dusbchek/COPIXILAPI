<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determina si el usuario proporcionado puede ver la tarea especificada.
     * Esta política permite a un usuario ver una tarea si tiene acceso a ella a través de
     * asignación directa, pertenencia a un equipo asociado o liderazgo de un equipo asociado.
     *
     * @param  \App\Models\User  $user  El usuario autenticado que intenta ver la tarea.
     * @param  \App\Models\Task  $task  La instancia de la tarea que se intenta ver.
     * @return bool  Verdadero si el usuario está autorizado para ver la tarea, falso en caso contrario.
     */
    public function view(User $user, Task $task): bool
    {
        return $this->hasAccess($user, $task);
    }

    /**
     * Determina si el usuario proporcionado puede actualizar la tarea especificada.
     * Esta política permite a un usuario actualizar una tarea si tiene acceso a ella a través de
     * asignación directa, pertenencia a un equipo asociado o liderazgo de un equipo asociado.
     *
     * @param  \App\Models\User  $user  El usuario autenticado que intenta actualizar la tarea.
     * @param  \App\Models\Task  $task  La instancia de la tarea que se intenta actualizar.
     * @return bool  Verdadero si el usuario está autorizado para actualizar la tarea, falso en caso contrario.
     */
    public function update(User $user, Task $task): bool
    {
        return $this->hasAccess($user, $task);
    }

    /**
     * Determina si el usuario proporcionado puede eliminar la tarea especificada.
     * Esta política permite a un usuario eliminar una tarea si tiene acceso a ella a través de
     * asignación directa, pertenencia a un equipo asociado o liderazgo de un equipo asociado.
     *
     * @param  \App\Models\User  $user  El usuario autenticado que intenta eliminar la tarea.
     * @param  \App\Models\Task  $task  La instancia de la tarea que se intenta eliminar.
     * @return bool  Verdadero si el usuario está autorizado para eliminar la tarea, falso en caso contrario.
     */
    public function delete(User $user, Task $task): bool
    {
        return $this->hasAccess($user, $task);
    }

    /**
     * Verifica si el usuario dado tiene acceso a la tarea proporcionada.
     * Un usuario tiene acceso a una tarea si está directamente asignado a ella,
     * si pertenece a un equipo al que la tarea está asociada, o si es el líder
     * de un equipo al que la tarea está asociada.
     *
     * @param  \App\Models\User  $user  El usuario cuya autorización se está verificando.
     * @param  \App\Models\Task  $task  La tarea para la que se está verificando el acceso.
     * @return bool  Verdadero si el usuario tiene acceso a la tarea, falso en caso contrario.
     */
    protected function hasAccess(User $user, Task $task): bool
    {
        return
            $task->users->contains($user) ||
            $task->teams->intersect($user->teams)->isNotEmpty() ||
            $task->teams->intersect($user->leadingTeams)->isNotEmpty();
    }
}
