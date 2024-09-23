<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:2|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Mengembalikan token dan nama pengguna
        return response()->json([
            'token' => $token,
            'name' => $user->name,
        ]);
    }

    public function verifyToken(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Cari token dalam database
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (!$personalAccessToken || !$personalAccessToken->tokenable) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Ambil pengguna yang terkait dengan token
        $user = $personalAccessToken->tokenable;

        // Log info token yang diverifikasi
        Log::info('Token verified', ['user_id' => $user->id]);

        return response()->json([
            'message' => 'Token is valid',
            'user' => $user,
        ]);
    }
}
