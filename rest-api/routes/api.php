<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/admin-login', [AdminLoginController::class, 'login']);

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users', [AdminUserController::class, 'store']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::put('/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);

    Route::get('/tasks', [AdminTaskController::class, 'index']);
    Route::post('/tasks', [AdminTaskController::class, 'store']);
    Route::get('/tasks/{id}', [AdminTaskController::class, 'show']);
    Route::put('/tasks/{id}', [AdminTaskController::class, 'update']);
    Route::delete('/tasks/{id}', [AdminTaskController::class, 'destroy']);
});

// User
Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum', 'role:employee']], function () {
    Route::get('/{id}/tasks', [UserController::class, 'show_tasks']);
    Route::patch('/{user_id}/tasks/{task_id}', [UserController::class, 'edit_task']);
});
