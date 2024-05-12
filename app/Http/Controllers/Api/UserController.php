<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Services\IUserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private IUserService $userService,
    ) {
    }

    public function store(UserStoreRequest $request)
    {
        $userAttributes = $request->validated();
        $user = $this->userService->createUser($userAttributes);

        return response()->json($user, Response::HTTP_CREATED);
    }
}
