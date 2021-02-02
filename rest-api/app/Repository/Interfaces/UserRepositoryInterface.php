<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{  

   public function findByEmail(String $email) : ?Model;

   public function create(CreateUserRequest $request) : Model;

   public function update(CreateUserRequest $request, int $id): void;

}