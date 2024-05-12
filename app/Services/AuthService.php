<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
{
    public function login(array $credentials): ?array
    {
        $user = User::whereEmail($credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('will-replace-by-ip-address')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
