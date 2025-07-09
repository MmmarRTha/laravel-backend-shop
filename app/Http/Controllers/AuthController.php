<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return [
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ];
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
