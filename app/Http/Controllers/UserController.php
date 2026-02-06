<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * GET /api/staff
     * Optimized using Query Builder
     */
    public function index(Request $request)
    {
        $query = DB::table('users');

        // Optional: Filter by search term (name or email)
        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    /**
     * POST /api/staff
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
            'role'       => ['required', Rule::in(['admin', 'staff'])],
        ]);

        $userId = DB::table('users')->insertGetId([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = DB::table('users')->where('id', $userId)->first();

        return response()->json(['status' => 'success', 'data' => $user], 201);
    }

    /**
     * GET /api/staff/{id}
     */
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * PUT /api/staff/{id}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($id)],
            'role'       => ['required', Rule::in(['admin', 'staff'])],
            'password'   => 'nullable|min:8',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'role'       => $validated['role'],
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $updated = DB::table('users')->where('id', $id)->update($updateData);

        if (!$updated && !DB::table('users')->where('id', $id)->exists()) {
            return response()->json(['message' => 'Update failed, staff not found'], 404);
        }

        $user = DB::table('users')->where('id', $id)->first();
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    /**
     * DELETE /api/staff/{id}
     */
    public function destroy($id)
    {
        $deleted = DB::table('users')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Staff not found or already deleted'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }
}
