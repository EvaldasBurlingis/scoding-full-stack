<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{  
   public function find(int $id) : ?Model;

   public function all() : ?Collection;

   public function destroy(int $id): void;
}