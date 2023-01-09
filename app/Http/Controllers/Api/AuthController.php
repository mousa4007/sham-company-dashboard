<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:app_users',
            'balance' => 'required',
            'password' => 'required',
        ]);

        $user = AppUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'balance' => $request->balance,
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('user_register_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = AppUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'unauthenticated'
            ], 401);
        };

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function updateUserFcmToken(Request $request)
    {
        $request->validate(['fcm_token'=>'required']);
        $request->user()->update([
            'fcmToken'=> $request->fcm_token
        ]);
    }
}
