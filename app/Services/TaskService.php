<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function getAllTasks(Request $request)
    {
        $query = Task::query();

        // Filtering
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        return $query->paginate(10);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function getTask(Task $task)
    {
        return $task->load('subtasks');
    }

    public function updateTask(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}