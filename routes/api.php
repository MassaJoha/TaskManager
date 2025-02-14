<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SubtaskController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// User Routes (with authorization and validation)
Route::middleware('auth:sanctum')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index'); // Get all users
    Route::post('/', [UserController::class, 'store'])->name('store'); // Create a new user

    Route::middleware('can:view,user')->get('/{user}', [UserController::class, 'show'])->name('show'); // Get a specific user
    Route::middleware('can:update,user')->put('/{user}', [UserController::class, 'update'])->name('update'); // Update a specific user
    Route::middleware('can:delete,user')->delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // Delete a specific user
});

// Task Routes
Route::middleware('auth:sanctum')->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index'); // Get all tasks
    Route::post('/', [TaskController::class, 'store'])->name('store'); // Create a new task
    Route::get('/{task}', [TaskController::class, 'show'])->name('show'); // Get a specific task
    Route::patch('/{task}', [TaskController::class, 'update'])->name('update'); // Update a specific task
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy'); // Delete a specific task
});

// Subtask Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/tasks/{task}/subtasks', [SubtaskController::class, 'index']); // Fetch all subtasks for a specific task
    Route::post('/subtasks', [SubtaskController::class, 'store']);
    Route::get('/subtasks/{subtask}', [SubtaskController::class, 'show']);
    Route::patch('/subtasks/{task}/{subtask}', [SubtaskController::class, 'update']);
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy']);
});