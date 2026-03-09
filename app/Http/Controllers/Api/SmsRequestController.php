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
            'sent_from' => 'nullable|string|in:kiss,mads',
        ]);

        $query = SmsRequest::query()->orderByDesc('id');

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['sent_from'])) {
            $query->where('sent_from', $validated['sent_from']);
        }

        return response()->json($query->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:30',
            'message' => 'required|string',
            'status' => 'nullable|string|in:pending,sent,failed',
            'sent_from' => 'nullable|string|in:kiss,mads',
            'source' => 'nullable|string|max:50',
            'external_id' => 'nullable|string|max:100',
        ]);

        $smsRequest = SmsRequest::create([
            'phone_number' => $validated['phone_number'],
            'message' => $validated['message'],
            'status' => $validated['status'] ?? 'pending',
            'sent_from' => $validated['sent_from'] ?? 'kiss',
            'source' => $validated['source'] ?? null,
            'external_id' => $validated['external_id'] ?? null,
        ]);

        return response()->json($smsRequest, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,sent,failed',
            'sent_from' => 'nullable|string|in:kiss,mads',
            'error_message' => 'nullable|string',
        ]);

        $smsRequest = SmsRequest::findOrFail($id);

        $smsRequest->status = $validated['status'];
        $smsRequest->error_message = $validated['error_message'] ?? null;

        if (!empty($validated['sent_from'])) {
            $smsRequest->sent_from = $validated['sent_from'];
        }

        if ($validated['status'] === 'sent') {
            $smsRequest->sent_at = now();
        }

        $smsRequest->save();

        return response()->json($smsRequest, 200);
    }
}
