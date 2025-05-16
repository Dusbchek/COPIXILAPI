<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    /**
     * Lista las tareas filtradas por usuario, equipo, estado o fecha de entrega.
     *
     * @param array $filters Filtros opcionales como status_id, due_date, team_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listTasks(array $filters = [])
    {
        $user = Auth::user();
        $userTeamIds = $user->teams->pluck('id')->toArray();

        $query = Task::query();

        $query->where(function ($q) use ($user, $userTeamIds) {
            $q->whereHas('users', fn($q) => $q->where('user_id', $user->id))
              ->orWhereHas('teams', fn($q) => $q->whereIn('id', $userTeamIds));
        });

        // Filtros adicionales
        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (isset($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        if (isset($filters['team_id'])) {
            $query->whereHas('teams', fn($q) => $q->where('id', $filters['team_id']));
        }

        return $query->with(['users', 'teams', 'status'])->get();
    }

    /**
     * Crea una nueva tarea con sus relaciones.
     *
     * @param array $data Datos validados para crear la tarea
     * @return Task
     */
    public function createTask(array $data): Task
    {
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status_id' => $data['status_id'],
            'due_date' => $data['due_date'],
            'user_id' => Auth::id(),
        ]);

        // Asociar usuarios a la tarea (incluye al creador)
        $userIds = array_unique(array_merge($data['user_ids'] ?? [], [Auth::id()]));
        $task->users()->attach($userIds);

        // Asociar equipos si se especifican
        if (isset($data['team_id'])) {
            $teamIds = is_array($data['team_id']) ? $data['team_id'] : [$data['team_id']];
            $task->teams()->attach($teamIds);
        }

        return $task->load(['users', 'teams', 'status']);
    }

    /**
     * Actualiza los campos de una tarea existente.
     *
     * @param Task $task La tarea a actualizar
     * @param array $data Datos validados
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        $updateFields = ['title', 'description', 'status_id', 'due_date'];
        foreach ($updateFields as $field) {
            if (array_key_exists($field, $data)) {
                $task->$field = $data[$field];
            }
        }
        $task->save();

        if (isset($data['user_ids'])) {
            $task->users()->sync($data['user_ids']);
        }

        if (isset($data['team_id'])) {
            $teamIds = is_array($data['team_id']) ? $data['team_id'] : [$data['team_id']];
            $task->teams()->sync($teamIds);
        }

        return $task->load(['users', 'teams', 'status']);
    }

    /**
     * Elimina una tarea.
     *
     * @param Task $task Tarea a eliminar
     * @return array Mensaje de confirmación
     */
    public function deleteTask(Task $task): array
    {
        $task->delete();

        return ['message' => "Tarea eliminada correctamente."];
    }
}
