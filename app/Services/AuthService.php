<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    // Register a new user
    public function register(array $data)
    {
        // Check if the email already exists in the database
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        // Hash the password before saving the user
        $data['password'] = Hash::make($data['password']);
        
        // Create and return the user
        $user = User::create($data);

        // Create the token for the newly registered user
        $token = $user->createToken('TaskManager')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    // Login the user
    public function login(array $data)
    {
        // Find the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if user exists
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        // Check if the password is correct
        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Invalid credentials'],
            ]);
        }

        // Create and return the token for the authenticated user
        $token = $user->createToken('TaskManager')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    // Logout the user
    public function logout()
    {
        // Revoke the user's current token
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        return [
            'message' => 'Logout successful.',
        ];
    }
}
