<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskStatusController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(TaskStatus::all());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:task_statuses,name|max:255',
        ]);

        $status = TaskStatus::create(['name' => $request->name]);

        return response()->json($status, JsonResponse::HTTP_CREATED);
    }

    public function show(TaskStatus $taskStatus): JsonResponse
    {
        return response()->json($taskStatus);
    }

    public function update(Request $request, TaskStatus $taskStatus): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:task_statuses,name,' . $taskStatus->id,
        ]);

        $taskStatus->update(['name' => $request->name]);

        return response()->json($taskStatus);
    }

    public function destroy(TaskStatus $taskStatus): JsonResponse
    {
        $taskStatus->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
