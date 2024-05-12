<?php

namespace App\Services;

use App\Exceptions\UserCreationFailedException;
use App\Models\User;

class UserService implements IUserService
{
    public function createUser(array $attributes): ?array
    {
        $user = User::create($attributes);
        if ($user) {
            $token = $user->createToken('will-replace-by-ip-address')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        throw new UserCreationFailedException('Failed to create an user. Please try with valid data.');
    }
}
