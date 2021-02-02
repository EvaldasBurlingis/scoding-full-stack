<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Repository\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {

        if (Auth::guard()->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = $this->userRepository->findByEmail($request->email);
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'data'      => [
                    'user' => new UserResource($user),
                    'token' => $token
                ],
                'message'   => 'Success'
            ], 200);
        }

        return response()->json([
                'errors'  => 'Incorrect login details',
                'message' => 'Unauthenticated'
            ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        Auth::guard('web')->logout();

        return response()->json([], 204);
    }
}
