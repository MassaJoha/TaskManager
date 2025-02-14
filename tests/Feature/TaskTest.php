<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_and_subtasks()
    {
       
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskResponse = $this->postJson('/api/tasks', [
            'title' => 'Main Task',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        $taskResponse->assertStatus(201);
        $taskId = $taskResponse->json('id');

        $subtaskResponse = $this->postJson("/api/subtasks", [
            'title' => 'Subtask 1',
            'status' => 'pending',
            'task_id' => $taskId 
        ]);

        $subtaskResponse->assertStatus(201);
        $this->assertDatabaseHas('subtasks', ['title' => 'Subtask 1']);
    }
}