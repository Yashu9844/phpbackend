<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // ✅ Import Validator
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $hashedPassword = Hash::make($request->password);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'Successfully signed up','user'=>$user], 201);
    }

    public function signin(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'All fields are required'], 400);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid password'], 400);
        }

        // Create JWT token
        $payload = [
            'id' => $user->id,
            'isAdmin' => $user->is_admin,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // 1 day expiration
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        // Store token in cookie
        $cookie = cookie('access_token', $token, 1440, '/', null, false, true);

        // Return user data without password
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
        ], 200)->withCookie($cookie);
    }
}
