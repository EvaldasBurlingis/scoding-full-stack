<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\CreateUserRequest;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {   
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();

        return response()->json([
            'data' => new UserCollection($users),
            'message' => 'Success'
        ], 200);
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if(!$user) return response()->json(['message' => 'Not found!'], 404);
        
        return response()->json([
            'data' => new UserResource($user),
            'message' => 'Success'
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userRepository->create($request);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'New user created successfully'
        ], 201);
    }

    public function update(CreateUserRequest $request, $id)
    {
        $this->userRepository->update($request, $id);

        $user = $this->userRepository->find($id);

        return response()->json([
            'data'    => new UserResource($user),
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $this->userRepository->destroy($id);
        
        return response()->json([], 204);
    }
}
