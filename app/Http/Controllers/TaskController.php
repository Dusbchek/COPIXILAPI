<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * Constructor del controlador de tareas.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Lista todas las tareas filtradas por estado, fecha límite o equipo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status_id', 'due_date', 'team_id']);
        $tasks = $this->taskService->listTasks($filters);

        return response()->json($tasks);
    }

    /**
     * Crea una nueva tarea.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * Muestra una tarea específica con relaciones cargadas.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load(['users', 'teams.leader', 'status']));
    }

    /**
     * Actualiza una tarea existente.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json($task);
    }

    /**
     * Elimina una tarea.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
