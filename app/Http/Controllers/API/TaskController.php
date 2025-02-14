<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        // Define a unique cache key
        $cacheKey = 'task_list';

        // Attempt to retrieve the tasks from the cache
        $tasks = Cache::remember($cacheKey, 300, function () use ($request) {
            // If not cached, fetch from the service
            return $this->taskService->getAllTasks($request);
        });

        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());

        // Clear the cache after creating a task
        Cache::forget('task_list');

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return $this->taskService->getTask($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $updatedTask = $this->taskService->updateTask($task, $request->validated());

        // Clear the cache after updating a task
        Cache::forget('task_list');

        return response()->json($updatedTask);
    }

    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);

        // Clear the cache after deleting a task
        Cache::forget('task_list');

        return response()->json(null, 204);
    }
}