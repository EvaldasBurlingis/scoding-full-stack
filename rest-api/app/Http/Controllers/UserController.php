<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Repository\Interfaces\TaskRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private $userRepository;
    private $taskRepository;

    public function __construct(UserRepositoryInterface $userRepository, TaskRepositoryInterface $taskRepository)
    {
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    public function show_tasks($id)
    {
        if(auth()->user()->id == $id) {

            $user = $this->userRepository->find($id);

            return response()->json([
                'data' => [
                    new TaskCollection($user->tasks)
                ]
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function edit_task(UpdateTaskStatusRequest $request, $user_id, $task_id)
    {
        if(auth()->user()->id == $user_id) {

            $this->taskRepository->updateStatus($task_id, $request);
            $user = $this->userRepository->find($user_id);

            return response()->json([
                'data' => [
                    'tasks' => $user->tasks
                ],
                'message' => 'Task status updated successfully'
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
