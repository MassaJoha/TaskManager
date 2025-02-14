<?php

namespace App\Observers;

use App\Models\Subtask;
use App\Notifications\SubtasksCompleted;

class SubtaskObserver
{
    public function updated(Subtask $subtask)
    {
        $this->updateParentTaskStatus($subtask);
    }

    public function created(Subtask $subtask)
    {
        $this->updateParentTaskStatus($subtask);
    }

    public function deleted(Subtask $subtask)
    {
        $this->updateParentTaskStatus($subtask);
    }

   private function updateParentTaskStatus(Subtask $subtask)
   {
       $task = $subtask->task;
   
       if (!$task) {
           return;
       }
   
       // Eager load
       $task->load('subtasks');
   
       // Check if all subtasks are completed
       $allCompleted = $task->subtasks()->where('status', '!=', 'completed')->count() === 0;
       // Check if any subtasks are in progress or pending
       $anyInProgress = $task->subtasks()->whereIn('status', ['in_progress', 'pending'])->exists();
       
       if ($allCompleted) {
           $task->update(['status' => 'completed']);
           
           // Notify the user if the task has an associated user
           if ($task->user) {
               $task->user->notify(new SubtasksCompleted($task));
           }
       } elseif ($anyInProgress) {
           $task->update(['status' => 'in_progress']);
       } else {
           $task->update(['status' => 'pending']);
       }
    }   
}