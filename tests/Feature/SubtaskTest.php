<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubtaskTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $task;
    private $subtask1;
    private $subtask2;

    protected function setUp(): void
    {
        parent::setUp();
        // Create user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Create a task and subtasks
        $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress',
            'title' => 'Main Task Title',
            'description' => 'Description of the main task.',
        ]);

        $this->subtask1 = Subtask::factory()->create([
            'task_id' => $this->task->id,
            'status' => 'pending',
            'title' => 'Subtask 1 Title',
            'description' => 'Description of subtask 1.',
        ]);

        $this->subtask2 = Subtask::factory()->create([
            'task_id' => $this->task->id,
            'status' => 'pending',
            'title' => 'Subtask 2 Title',
            'description' => 'Description of subtask 2.',
        ]);
    }

    public function test_task_status_updates_based_on_subtasks()
    {
        // Update first subtask to 'in_progress'
        $this->updateSubtaskStatus($this->subtask1, 'in_progress');

        // Assert first subtask status is 'in_progress'
        $this->assertSubtaskStatus($this->subtask1, 'in_progress');

        // Update both subtasks to 'completed'
        $this->updateSubtaskStatus($this->subtask1, 'completed');
        $this->updateSubtaskStatus($this->subtask2, 'completed');

        // Assert both subtasks are 'completed'
        $this->assertSubtaskStatus($this->subtask1, 'completed');
        $this->assertSubtaskStatus($this->subtask2, 'completed');

        // Assert that the task status is 'completed'
        $this->assertTaskStatus('completed');
    }

    private function updateSubtaskStatus(Subtask $subtask, string $status)
    {
        $this->patchJson("/api/subtasks/{$this->task->id}/{$subtask->id}", ['status' => $status])
             ->assertStatus(200);
    
        // Refresh the subtask to get the latest status
        $subtask->refresh();  
    }

    private function assertSubtaskStatus(Subtask $subtask, string $status)
    {
        $this->assertEquals($status, $subtask->status);
    }

    private function assertTaskStatus(string $status)
    {
        $this->task->refresh();
        $this->assertEquals($status, $this->task->status);
    }
}
