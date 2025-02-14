<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->getAllUsers();
    }

    public function store(StoreUserRequest $request)
    {
        return $this->userService->createUser($request->validated());
    }

    public function show(User $user)
    {
        return $this->userService->getUser($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->userService->updateUser($user, $request->validated());
    }

    public function destroy(User $user)
    {
        return $this->userService->deleteUser($user);
    }
}
