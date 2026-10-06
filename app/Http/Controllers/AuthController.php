<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use App\Models\AdminModel;

class AuthController extends Controller
{
    public function login(Request $request)
    {   
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = AdminModel::where('email', $request->input('email'))->first();

        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $ttl = (int) env('JWT_TTL', 3600);
        $now = time();
        $payload = [
            'iss' => 'attendance-api',
            'sub' => $admin->id,
            'iat' => $now,
            'exp' => $now + $ttl,
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'expires_in' => env('JWT_TTL', 3600),
            'admin' => $admin,
        ]);
    }

    public function logout()
    {
        return response()->json(['message' => 'Logged out']);
    }
}