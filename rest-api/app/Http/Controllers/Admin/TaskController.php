<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Http\Requests\CreateTaskRequest;
use App\Repository\Interfaces\TaskRepositoryInterface;

class TaskController extends Controller
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {   
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $tasks = $this->taskRepository->all();

        return response()->json([
            'data' => new TaskCollection($tasks),
            'message' => 'Success'
        ], 200);
    }

    public function show($id)
    {
        $task = $this->taskRepository->find($id);

        if(!$task) return response()->json(['message' => 'Not found!'], 404);
        
        return response()->json([
            'data' => new TaskResource($task),
            'message' => 'Success'
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        $task = $this->taskRepository->create($request);

        return response()->json([
            'data' => new TaskResource($task),
            'message' => 'Task created successfully'
        ], 201);
    }

    public function update(CreateTaskRequest $request, $id)
    {
        $this->taskRepository->update($request, $id);

        $task = $this->taskRepository->find($id);

        return response()->json([
            'data'    => new TaskResource($task),
            'message' => 'Task updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $this->taskRepository->destroy($id);

        return response()->json([], 204);
    }
}
