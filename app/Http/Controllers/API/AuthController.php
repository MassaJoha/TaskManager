<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Try to register the user
            $user = $this->authService->register($request->validated());
            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other types of exceptions
            return response()->json(['message' => 'Something went wrong, please try again later.'], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Try to login the user
            $result = $this->authService->login($request->validated());
            return response()->json([
                'token' => $result['token'],
                'user' => $result['user']
            ], 200);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other types of exceptions
            return response()->json(['message' => 'Invalid credentials'], 400);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            // Try to logout the user
            $this->authService->logout();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during logout
            return response()->json(['message' => 'An error occurred while logging out'], 500);
        }
    }
}
