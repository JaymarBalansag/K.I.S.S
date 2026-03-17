<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * GET /api/Appointments/availability
     * Public endpoint for calendar slot counts.
     */
    public function availability(Request $request)
    {
        $validated = $request->validate([
            'appointment_type' => 'nullable|string|in:PMOC,PMO,CIVIL_WEDDING,Civil Wedding',
            'year' => 'nullable|integer|min:1900|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $rawAppointmentType = $validated['appointment_type'] ?? 'PMOC';
        $appointmentType = $rawAppointmentType === 'CIVIL_WEDDING' ? 'Civil Wedding' : $rawAppointmentType;

        if ($appointmentType === 'Civil Wedding') {
            $query = Appointment::query()
                ->select(['appointment_type', 'requested_date'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) {
                    $q->whereRaw("LOWER(REPLACE(appointment_type, '_', ' ')) = 'civil wedding'")
                        ->orWhere(function ($q2) {
                            $q2->whereRaw("LOWER(appointment_type) LIKE '%civil%'")
                                ->whereRaw("LOWER(appointment_type) LIKE '%wedding%'");
                        });
                });

            if (!empty($validated['year'])) {
                $query->whereYear('requested_date', $validated['year']);
            }

            if (!empty($validated['month'])) {
                $query->whereMonth('requested_date', $validated['month']);
            }

            $rows = $query->get();
            $counts = [];

            foreach ($rows as $row) {
                $date = Carbon::parse($row->requested_date);

                // Legacy fix: old records were saved one day earlier due to timezone handling.
                if ($date->dayOfWeek === Carbon::SUNDAY) {
                    $date = $date->copy()->addDay();
                }

                $key = $date->format('Y-m-d');
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        } else {
            $query = Appointment::query()
                ->selectRaw('DATE(requested_date) as date_key, COUNT(*) as total')
                ->where('status', '!=', 'cancelled')
                ->where('appointment_type', $appointmentType);

            if (!empty($validated['year'])) {
                $query->whereYear('requested_date', $validated['year']);
            }

            if (!empty($validated['month'])) {
                $query->whereMonth('requested_date', $validated['month']);
            }

            $counts = $query
                ->groupBy('date_key')
                ->pluck('total', 'date_key')
                ->toArray();
        }

        return response()->json(['counts' => $counts], 200);
    }

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
            'appointment_type' => 'nullable|string|in:PMOC,PMO,CIVIL_WEDDING,Civil Wedding',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone_number'   => 'required|string|size:10',
            'requested_date' => 'required|date',
        ]);

        try {
            $result = DB::transaction(function () use ($request, $validated) {
                $rawAppointmentType = $validated['appointment_type'] ?? 'PMOC';
                $appointmentType = $rawAppointmentType === 'CIVIL_WEDDING' ? 'Civil Wedding' : $rawAppointmentType;
                if ($appointmentType === 'Civil Wedding') {
                    $targetDate = Carbon::parse($validated['requested_date'])->startOfDay();
                    $legacyDate = $targetDate->copy()->subDay();

                    $activeCount = Appointment::where(function ($q) {
                        $q->whereRaw("LOWER(REPLACE(appointment_type, '_', ' ')) = 'civil wedding'")
                            ->orWhere(function ($q2) {
                                $q2->whereRaw("LOWER(appointment_type) LIKE '%civil%'")
                                    ->whereRaw("LOWER(appointment_type) LIKE '%wedding%'");
                            });
                    })
                        ->where(function ($q) use ($targetDate, $legacyDate) {
                            $q->whereDate('requested_date', $targetDate->toDateString())
                                ->orWhereDate('requested_date', $legacyDate->toDateString());
                        })
                        ->where('status', '!=', 'cancelled')
                        ->lockForUpdate()
                        ->count();

                    if ($activeCount >= 3) {
                        throw ValidationException::withMessages([
                            'requested_date' => 'Selected date is already full (3/3). Please choose another date.',
                        ]);
                    }
                }
                
                do {
                    $controlNo = strtoupper(Str::random(5));
                } while (Appointment::where('control_number', $controlNo)->exists());

                $appointment = Appointment::create([
                    'application_id'   => $validated['application_id'] ?? null,
                    'appointment_type' => $appointmentType,
                    'first_name'       => $validated['first_name'],
                    'last_name'        => $validated['last_name'],
                    'middle_name'      => $request->middle_name,
                    'extension'        => $request->extension,
                    'phone_number'     => '63' . $validated['phone_number'],
                    'control_number'   => $controlNo,
                    'requested_date'   => $validated['requested_date'],
                    'status'           => 'pending',
                ]);

                if (in_array($appointmentType, ['PMOC', 'PMO'], true)) {
                    $nameParts = array_filter([
                        $appointment->first_name,
                        $appointment->middle_name,
                        $appointment->last_name,
                        $appointment->extension,
                    ], function ($value) {
                        return $value !== null && trim((string) $value) !== '';
                    });

                    $fullName = implode(' ', $nameParts);
                    $message = "Good day {$fullName}. Your {$appointmentType} appointment request is received. Control No.: {$controlNo}. Requested date: {$appointment->requested_date}. Please keep this for reference. - MCR";

                    DB::table('sms_requests')->insert([
                        'phone_number' => $appointment->phone_number,
                        'message' => $message,
                        'status' => 'pending',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                return ['controlNumber' => $controlNo];
            });

            return response()->json([
                'success' => true,
                'controlNumber' => $result['controlNumber'],
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $firstMessage = collect($errors)->flatten()->first() ?: 'Validation failed.';

            return response()->json([
                'success' => false,
                'message' => $firstMessage,
                'errors' => $errors,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Submission Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
