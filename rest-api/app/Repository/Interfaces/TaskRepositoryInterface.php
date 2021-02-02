<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{  
    public function create(CreateTaskRequest $request) : Model;

    public function update(CreateTaskRequest $request, int $id): void;

    public function updateStatus(int $id, UpdateTaskStatusRequest $request);
}