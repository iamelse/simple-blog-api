<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required', 'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['token' => $user->createToken('token')->plainTextToken]);
    }

    public function login(Request $request): JsonResponse
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        return response()->json(['token' => $user->createToken('token')->plainTextToken]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
