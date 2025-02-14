<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subtask;
use App\Services\SubtaskService;
use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class SubtaskController extends Controller
{
    protected $subtaskService;

    public function __construct(SubtaskService $subtaskService)
    {
        $this->subtaskService = $subtaskService;
    }

    /**
     * Store a newly created subtask.
     */
    public function store(StoreSubtaskRequest $request): JsonResponse
    {
        $subtask = $this->subtaskService->createSubtask($request->validated());
        return response()->json($subtask, 201);
    }

    /**
     * Display the specified subtask.
     */
    public function show(Subtask $subtask): JsonResponse
    {
        return response()->json($subtask);
    }

    /**
     * Update the specified subtask.
     */
    public function update(UpdateSubtaskRequest $request, Task $task, Subtask $subtask): JsonResponse
    {
        $validatedData = $request->validated();
        $subtask->update($validatedData);
        
        return response()->json($subtask);
    }

    /**
     * Remove the specified subtask.
     */
    public function destroy(Subtask $subtask): JsonResponse
    {
        $this->subtaskService->deleteSubtask($subtask);

        return response()->json(null, 204);
    }

    /**
     * Display all subtasks for a specific task.
     */
    public function index(Task $task): JsonResponse
    {
        $subtasks = $task->subtasks;

        return response()->json($subtasks);
    }
}