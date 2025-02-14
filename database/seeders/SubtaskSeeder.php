<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subtask;
use App\Models\Task;

class SubtaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory(10)->create()->each(function ($task) {
            Subtask::factory(3)->create(['task_id' => $task->id]);
        });
    }
}
