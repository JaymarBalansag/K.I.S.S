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
    public function login(LoginRequest $request)
    {
        $user = $this->attemptLogin($request);

        if (!$user) {
            return response()->json(['message' => 'Incorrect credentials'], 401);
        }

        $token = $user->createToken($this->buildTokenName($user))->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function smsLogin(LoginRequest $request)
    {
        $user = $this->attemptLogin($request);

        if (!$user) {
            return response()->json(['message' => 'Incorrect credentials'], 401);
        }

        $expiresAt = now()->addHours(24);

        $token = $user->createToken(
            $this->buildTokenName($user, 'sms'),
            ['*'],
            $expiresAt
        )->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ]);
    }

    private function attemptLogin(LoginRequest $request): ?User
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    private function buildTokenName(User $user, string $suffix = ''): string
    {
        $roleSegment = $user->role === 'admin' ? 'admin' : 'staff';
        $base = $user->first_name . '_' . $user->last_name . '_' . $roleSegment;

        return $suffix === '' ? $base . '_token' : $base . '_' . $suffix . '_token';
    }

    // Admin is the only one who can add users/staffs/admin
    public function AddUser(AddUserRequest $request)
    {
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
