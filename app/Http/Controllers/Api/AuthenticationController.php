<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request){
        $validated = $request->validated();
        $user = User::where("email", $validated['email'])->first();

        if(!$user || !Hash::check($validated["password"], $user->password)){
            return response()->json(["message" => "Incorrect credentials"], 401);
        }

        if($user->role === 'admin') {
            $tokenName = $user->first_name . "_" . $user->last_name . "_admin" .  "_token";
        } else {
            $tokenName = $user->first_name . "_" . $user->last_name . "_staff" .  "_token";
        }

        // Create a token with NO expiration date
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }   

    // Admin is the only one who can add users/staffs/admin
    public function AddUser(AddUserRequest $request) {
        // Validate input
        $validated = $request->validated();
        // Create user
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'User Added Successfully',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()
        ->tokens()
        ->where('id', $request->user()->currentAccessToken()->id)
        ->delete();

        return response()->json(['message' => 'Logged out']);
    }
}


