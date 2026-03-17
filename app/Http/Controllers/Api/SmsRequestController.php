<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsRequest;
use Illuminate\Http\Request;

class SmsRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,sent,failed',
        ]);

        $query = SmsRequest::query()->orderByDesc('id');

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        return response()->json($query->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:30',
            'message' => 'required|string',
            'status' => 'nullable|string|in:pending,sent,failed',
        ]);

        $smsRequest = SmsRequest::create([
            'phone_number' => $validated['phone_number'],
            'message' => $validated['message'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        return response()->json($smsRequest, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,sent,failed',
        ]);

        $smsRequest = SmsRequest::findOrFail($id);

        $smsRequest->status = $validated['status'];
        $smsRequest->save();

        return response()->json($smsRequest, 200);
    }
}
