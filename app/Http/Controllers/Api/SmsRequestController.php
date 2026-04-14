<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmsRequestRequest;
use App\Http\Requests\UpdateSmsRequestRequest;
use App\Http\Resources\SmsRequestResource;
use App\Models\SmsRequest;
use Illuminate\Http\Request;

class SmsRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,sent,failed',
            'search' => 'nullable|string|max:255',
        ]);

        $query = SmsRequest::query()->orderByDesc('id');

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('phone_number', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        return SmsRequestResource::collection($query->get());
    }

    public function getPending(Request $request)
    {
        $messages = SmsRequest::query()
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return SmsRequestResource::collection($messages);
    }

    public function acknowledge(Request $request, SmsRequest $smsRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:sent,failed',
        ]);

        $smsRequest->status = $validated['status'];
        $smsRequest->save();

        return new SmsRequestResource($smsRequest);
    }

    public function store(StoreSmsRequestRequest $request)
    {
        $validated = $request->validated();

        $smsRequest = SmsRequest::create([
            'phone_number' => $validated['phone_number'],
            'message' => $validated['message'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        return (new SmsRequestResource($smsRequest))
            ->response()
            ->setStatusCode(201);
    }

    public function show(SmsRequest $smsRequest)
    {
        return new SmsRequestResource($smsRequest);
    }

    public function update(UpdateSmsRequestRequest $request, SmsRequest $smsRequest)
    {
        $smsRequest->update($request->validated());

        return new SmsRequestResource($smsRequest);
    }

    public function destroy(SmsRequest $smsRequest)
    {
        $smsRequest->delete();

        return response()->json([
            'message' => 'SMS request deleted successfully.',
        ], 200);
    }
}
