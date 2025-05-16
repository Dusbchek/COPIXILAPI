<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;

/**
 * Clase TeamService
 * Proporciona servicios para la gestión de equipos.
 */
class TeamService
{
    /**
     * Crea un nuevo equipo.
     *
     * @param array $data Datos para crear el equipo (debe contener 'name').
     * @param User $creator El usuario que crea el equipo.
     * @return Team El equipo recién creado.
     */
    public function create(array $data, User $creator): Team
{
    $team = Team::create(['name' => $data['name']]);

    $team->members()->attach($creator->id);
    $team->teamLeaders()->attach($creator->id);

    if (!empty($data['user_ids'])) {
        $team->members()->syncWithoutDetaching($data['user_ids']);
    }

    if (!empty($data['leader_ids'])) {
        $team->teamLeaders()->syncWithoutDetaching($data['leader_ids']);
    }

    return $team->load('members', 'teamLeaders');
}


    /**
     * Actualiza la información de un equipo existente.
     *
     * @param Team $team El equipo a actualizar.
     * @param array $data Los nuevos datos para el equipo.
     * @return Team El equipo actualizado.
     */
    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team;
    }

    /**
     * Elimina un equipo.
     *
     * @param Team $team El equipo a eliminar.
     * @return void
     */
    public function delete(Team $team): void
    {
        $team->delete();
    }

    /**
     * Agrega miembros a un equipo.
     *
     * @param Team $team El equipo al que se agregarán miembros.
     * @param array $userIds Los IDs de los usuarios a agregar.
     * @return Team El equipo con los nuevos miembros.
     */
    public function addMembers(Team $team, array $userIds): Team
    {
        // Sincroniza los miembros del equipo sin eliminar los existentes
        $team->members()->syncWithoutDetaching($userIds);
        return $team;
    }

    /**
     * Elimina un miembro de un equipo.
     *
     * @param Team $team El equipo del que se eliminará el miembro.
     * @param User $user El usuario a eliminar del equipo.
     * @return Team El equipo sin el miembro.
     */
    public function removeMember(Team $team, User $user): Team
    {
        $team->members()->detach($user->id);
        return $team;
    }

    /**
     * Asigna un líder a un equipo.
     *
     * @param Team $team El equipo al que se asignará el líder.
     * @param User $user El usuario que será asignado como líder.
     * @return Team El equipo con el nuevo líder asignado.
     */
    public function assignLeader(Team $team, User $user): Team
    {
        $team->teamLeaders()->syncWithoutDetaching($user->id);
        return $team;
    }

    /**
     * Elimina un líder de un equipo.
     *
     * @param Team $team El equipo del que se eliminará el líder.
     * @param User $user El usuario que será eliminado como líder.
     * @return Team El equipo sin el líder especificado.
     */
    public function removeLeader(Team $team, User $user): Team
    {
        $team->teamLeaders()->detach($user->id);
        return $team;
    }

    /**
     * Asigna una tarea a un equipo.
     *
     * @param Team $team El equipo al que se asignará la tarea.
     * @param Task $task La tarea a asignar.
     * @return Team El equipo con la tarea asignada.
     */
    public function assignTask(Team $team, Task $task): Team
    {
        $team->tasks()->syncWithoutDetaching($task->id);
        return $team;
    }
}
