<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * GET /api/appointments
     * Fetch all records for the management table
     */
    public function index()
    {
        try {
            // Retrieve appointments, latest first
            $appointments = Appointment::orderBy('requested_date', 'desc')->get();

            // Return as a clean JSON array
            return response()->json($appointments, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * PATCH /api/appointments/{id}
     * Update the status (e.g., from pending to confirmed)
     */
    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            // Validate that we are receiving a valid status
            $validated = $request->validate([
                'status' => 'required|string|in:pending,confirmed,cancelled'
            ]);

            $appointment->update([
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'status' => $appointment->status
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * POST /api/appointments
     * Your existing store method
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'nullable|exists:marriage_applications,id',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone_number'   => 'required|string|size:10',
            'requested_date' => 'required|date',
        ]);

        try {
            $result = DB::transaction(function () use ($request, $validated) {
                do {
                    $controlNo = 'LCROPMOC-' . strtoupper(Str::random(8));
                } while (Appointment::where('control_number', $controlNo)->exists());

                $appointment = Appointment::create([
                    'application_id'   => $validated['application_id'] ?? null,
                    'appointment_type' => 'PMOC',
                    'first_name'       => $validated['first_name'],
                    'last_name'        => $validated['last_name'],
                    'middle_name'      => $request->middle_name,
                    'extension'        => $request->extension,
                    'phone_number'     => '63' . $validated['phone_number'],
                    'control_number'   => $controlNo,
                    'requested_date'   => $validated['requested_date'],
                    'status'           => 'pending',
                ]);

                return ['controlNumber' => $controlNo];
            });

            return response()->json([
                'success' => true,
                'controlNumber' => $result['controlNumber'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Submission Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
