<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        $user = User::find($id);

        return $user;
    }

    public function findByEmail(String $email): ?User
    {

        return User::where('email', $email)->first();
    }

    public function create(CreateUserRequest $request): User
    {
        $user = User::create([
            'email'     => $request->email,
            'name'      => $request->name,
            'password'  => Hash::make($request->password)
        ]);

        $user->assignRole('employee');

        return $user;
    }

    public function update(CreateUserRequest $request, int $id): void
    {
        $user = User::find($id);

        User::where('id', $id)->update([
            'email'    => $request->email ? $request->email : $user->email,
            'name'     => $request->name,
            'password' => Hash::make($request->password)
        ]);
    }

    public function destroy(int $id): void
    {
        User::destroy($id);
    }
}
