<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResponseResource;
use App\Services\IAuthService;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __construct(
        private IAuthService $authService
    ) {
    }

    public function __invoke(LoginRequest $request)
    {
        $loginCredentials = $request->validated();
        $userAndToken = $this->authService->login($loginCredentials);

        return \response()->json(new LoginResponseResource($userAndToken), Response::HTTP_CREATED);
    }
}
