<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Services\TeamService;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Requests\AddMembersRequest;
use App\Http\Requests\AssignLeaderRequest;

class TeamController extends Controller
{
    protected TeamService $teamService;

    /**
     * Constructor del controlador de equipos.
     *
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Muestra los equipos en los que participa o lidera el usuario autenticado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $teams = $user->teams->merge($user->ledTeams)->unique('id')->load(['members', 'teamLeaders', 'tasks']);
        return response()->json($teams, Response::HTTP_OK);
    }

    /**
     * Crea un nuevo equipo y agrega al usuario como miembro y líder.
     *
     * @param StoreTeamRequest $request
     * @return JsonResponse
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        $team = $this->teamService->create($request->validated(), Auth::user());
        return response()->json($team->load(['members', 'teamLeaders']), Response::HTTP_CREATED);
    }

    /**
     * Muestra los detalles de un equipo específico.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json($team->load(['members', 'teamLeaders', 'tasks']));
    }

    /**
     * Actualiza la información de un equipo existente.
     *
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        $team = $this->teamService->update($team, $request->validated());
        return response()->json($team->load(['members', 'teamLeaders']));
    }

    /**
     * Elimina un equipo.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function destroy(Team $team): JsonResponse
    {
        $this->teamService->delete($team);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Agrega nuevos miembros al equipo.
     *
     * @param AddMembersRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function addMembers(AddMembersRequest $request, Team $team): JsonResponse
    {
        $team = $this->teamService->addMembers($team, $request->validated()['user_ids']);
        return response()->json($team->load('members'));
    }

    /**
     * Elimina un miembro del equipo.
     *
     * @param Team $team
     * @param User $user
     * @return JsonResponse
     */
    public function removeMember(Team $team, User $user): JsonResponse
    {
        $team = $this->teamService->removeMember($team, $user);
        return response()->json($team->load('members'));
    }

    /**
     * Asigna un usuario como líder del equipo.
     *
     * @param AssignLeaderRequest $request
     * @param Team $team
     * @param User $user
     * @return JsonResponse
     */
    public function assignLeader(AssignLeaderRequest $request, Team $team, User $user): JsonResponse
    {
        $team = $this->teamService->assignLeader($team, $user);
        return response()->json($team->load('teamLeaders'));
    }

    /**
     * Elimina a un usuario de los líderes del equipo.
     *
     * @param Team $team
     * @param User $user
     * @return JsonResponse
     */
    public function removeLeader(Team $team, User $user): JsonResponse
    {
        $team = $this->teamService->removeLeader($team, $user);
        return response()->json($team->load('teamLeaders'));
    }

    /**
     * Asigna una tarea al equipo.
     *
     * @param Team $team
     * @param Task $task
     * @return JsonResponse
     */
    public function assignTask(Team $team, Task $task): JsonResponse
    {
        $team = $this->teamService->assignTask($team, $task);
        return response()->json($team->load('tasks'));
    }
}
