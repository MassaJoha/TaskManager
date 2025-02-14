<?php

namespace App\Services;

use App\Models\Subtask;

class SubtaskService
{
    /**
     * Create a new subtask.
     */
    public function createSubtask(array $data)
    {
        return Subtask::create($data);
    }

    /**
     * Get a specific subtask.
     */
    public function getSubtask(Subtask $subtask)
    {
        return $subtask; 
    }

    /**
     * Update an existing subtask.
     */
    public function updateSubtask(array $data, Subtask $subtask)
    {
        $subtask->update($data);
        return $subtask;
    }

    /**
     * Delete a specific subtask.
     */
    public function deleteSubtask(Subtask $subtask)
    {
        return $subtask->delete();
    }
}
