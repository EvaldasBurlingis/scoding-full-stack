<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\UserCollection;
use App\Repository\Interfaces\TaskRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;

class LoginController extends Controller
{
    private $userRepository;
    private $taskRepository;

    public function __construct(UserRepositoryInterface $userRepository, TaskRepositoryInterface $taskRepository)
    {   
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard()->attempt($request->only('email', 'password')) && auth()->user()->hasRole('admin')) {
            $request->session()->regenerate();

            $users = $this->userRepository->all();
            $tasks = $this->taskRepository->all();
            $user = $this->userRepository->findByEmail($request->email);
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'data'      => [
                    'user' => new UserResource($user),
                    'users' => new UserCollection($users),
                    'tasks' => new TaskCollection($tasks),
                    'token' => $token
                ], 
                'message'   => 'Success'
            ], 200); 
        }

        return response()->json([
                'errors'  => 'Incorrect login details',
                'message' => 'Unauthorized'
            ], 403);
    }
}
