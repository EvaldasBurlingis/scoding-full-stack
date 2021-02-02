<?php

namespace App\Repository\Eloquent;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection {
        return Task::all();
    }

    public function find(int $id) : ?Task {
        $user = Task::find($id);

        return $user;
    }

    public function destroy(int $id): void
    {
        Task::destroy($id);
    }

    public function create(CreateTaskRequest $request): Task
    {
        $task = Task::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);

        return $task;
    }

    public function update(CreateTaskRequest $request, int $id): void
    {
        Task::where('id', $id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);
    }

    public function updateStatus(int $id, UpdateTaskStatusRequest $request): void
    {
        $task = Task::find($id);
        $task->status = $request->status;
        $task->save();
    }
}