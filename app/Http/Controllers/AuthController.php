<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{


    public function register(RegisterRequest $request)
    {
        try {
            Log::info('Registration attempt started', ['email' => $request->input('email')]);
            
            $data = $request->validated();
            
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
            
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('token')->plainTextToken,
                'message' => 'User registered successfully'
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return response()->json([
                'message' => 'Registration failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if(!Auth::attempt($data))
        {
            return response([
                'errors' => ['Invalid credentials']
            ], 422);
        }

        $user = Auth::user();
        return [
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ];
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return [
            'user' => null,
        ];
    }
}
