<?php

namespace App\Http\Controllers\Api;

use App\Exports\MarriageExport;
use App\Exports\MarriageExports;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MarriageApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:filipino,groom,bride,both',
            'groomRequirement' => 'nullable|in:parental-consent,parental-advise,no-need',
            'brideRequirement' => 'nullable|in:parental-consent,parental-advise,no-need',
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'groom' => 'required|string',
            'bride' => 'required|string',
            'consentSource' => 'nullable|string',
            'documents_groom' => 'required|array|min:1',
            'documents_bride' => 'required|array|min:1',
            'documents_groom.*' => 'required|file|mimes:pdf|max:15360',
            'documents_bride.*' => 'required|file|mimes:pdf|max:15360',
        ]);

        $groomPayload = json_decode($request->input('groom', '{}'), true) ?? [];
        $bridePayload = json_decode($request->input('bride', '{}'), true) ?? [];
        $type = strtolower((string) ($request->input('type') ?? 'filipino'));
        $groomRequirement = strtolower((string) ($request->input('groomRequirement') ?? 'no-need'));
        $brideRequirement = strtolower((string) ($request->input('brideRequirement') ?? 'no-need'));
        $groomStatus = strtolower((string) ($groomPayload['civilStatus'] ?? ''));

        $uploadedDocKeys = [
            'groom' => array_keys($request->file('documents_groom', [])),
            'bride' => array_keys($request->file('documents_bride', [])),
        ];
        $requiredDocKeys = [
            'groom' => ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate'],
            'bride' => ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate'],
        ];

        if ($groomRequirement === 'parental-consent') {
            $requiredDocKeys['groom'][] = 'parentalConsent';
        } elseif ($groomRequirement === 'parental-advise') {
            $requiredDocKeys['groom'][] = 'parentalAdvise';
        }
        if ($brideRequirement === 'parental-consent') {
            $requiredDocKeys['bride'][] = 'parentalConsent';
        } elseif ($brideRequirement === 'parental-advise') {
            $requiredDocKeys['bride'][] = 'parentalAdvise';
        }

        if (in_array($type, ['groom', 'both'], true)) {
            $requiredDocKeys['groom'] = array_merge($requiredDocKeys['groom'], ['legalCapacity', 'validPassport']);
            if ($groomStatus === 'widowed') {
                $requiredDocKeys['groom'][] = 'apostilled';
            }
            if (in_array($groomStatus, ['divorced', 'annulled'], true)) {
                $requiredDocKeys['groom'][] = 'divorceDecree';
            }
        }
        if (in_array($type, ['bride', 'both'], true)) {
            $requiredDocKeys['bride'] = array_merge($requiredDocKeys['bride'], ['legalCapacity', 'validPassport']);
        }

        $govtIssuedDateMin = Carbon::create(1950, 1, 1)->startOfDay();
        $today = Carbon::today();
        $validationErrors = [];

        foreach (['groom', 'bride'] as $role) {
            $missing = array_values(array_diff(array_unique($requiredDocKeys[$role]), $uploadedDocKeys[$role]));
            if (!empty($missing)) {
                $validationErrors["documents_{$role}"] = [
                    ucfirst($role) . " is missing required document(s): " . implode(', ', $missing) . ".",
                ];
            }
        }

        foreach (['groom' => $groomPayload, 'bride' => $bridePayload] as $role => $payload) {
            $label = ucfirst($role);
            if (empty($payload['govtIssuedIdType'])) {
                $validationErrors["{$role}.govtIssuedIdType"] = ["{$label} Government ID type is required."];
            }
            if (empty($payload['govtIssuedIdNumber'])) {
                $validationErrors["{$role}.govtIssuedIdNumber"] = ["{$label} Government ID number is required."];
            }
            if (empty($payload['govtIssuedIdIssuedAt'])) {
                $validationErrors["{$role}.govtIssuedIdIssuedAt"] = ["{$label} Government ID issued-at value is required."];
            }
            if (empty($payload['govtIssuedIdIssuedOn'])) {
                $validationErrors["{$role}.govtIssuedIdIssuedOn"] = ["{$label} Government ID issued-on date is required."];
                continue;
            }

            try {
                $issuedOn = Carbon::createFromFormat('Y-m-d', (string) $payload['govtIssuedIdIssuedOn'])->startOfDay();
                if ($issuedOn->lt($govtIssuedDateMin) || $issuedOn->gt($today)) {
                    $validationErrors["{$role}.govtIssuedIdIssuedOn"] = ["{$label} Government ID issued-on date must be between 1950-01-01 and today."];
                }
            } catch (\Exception $e) {
                $validationErrors["{$role}.govtIssuedIdIssuedOn"] = ["{$label} Government ID issued-on date format is invalid."];
            }
        }

        if (!empty($validationErrors)) {
            throw ValidationException::withMessages($validationErrors);
        }

        return DB::transaction(function () use ($request, $groomPayload, $bridePayload) {
            $consentSource = json_decode($request->input('consentSource', '{}'), true) ?? [];
            $requirements = [
                'groom' => $request->input('groomRequirement'),
                'bride' => $request->input('brideRequirement'),
            ];
            $buildFullName = function (array $payload, string $fallback): string {
                $parts = array_values(array_filter([
                    trim((string) ($payload['firstName'] ?? '')),
                    trim((string) ($payload['middleName'] ?? '')),
                    trim((string) ($payload['lastName'] ?? '')),
                ], fn($part) => $part !== ''));

                return !empty($parts) ? implode(' ', $parts) : $fallback;
            };
            $hasGovernmentIdType = Schema::hasColumn('applicants', 'government_id_type');
            $hasGovernmentIdNumber = Schema::hasColumn('applicants', 'government_id_number');
            $hasGovernmentIdIssuedAt = Schema::hasColumn('applicants', 'government_id_issued_at');
            $hasGovernmentIdIssuedOn = Schema::hasColumn('applicants', 'government_id_issued_on');
            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];
            $normalizeMonth = function ($value) use ($monthNames) {
                if ($value === null) return null;
                $raw = trim((string) $value);
                if ($raw === '') return null;
                if (ctype_digit($raw)) {
                    $index = (int) $raw;
                    return $monthNames[$index] ?? $raw;
                }
                return ucfirst(strtolower($raw));
            };
            $resolveCitizenship = function (array $payload, string $key, ?string $otherKey = null) {
                $value = $payload[$key] ?? null;
                if ($value === 'Others' && $otherKey && !empty($payload[$otherKey])) {
                    return $payload[$otherKey];
                }
                return $value;
            };

            // 1. Generate Control Number
            do {
                $controlNumber = strtoupper(Str::random(5));
            } while (DB::table('marriage_applications')->where('control_number', $controlNumber)->exists());

            // 2. Insert Main Application using Query Builder
            $applicationId = DB::table('marriage_applications')->insertGetId([
                'control_number' => $controlNumber,
                'status'         => 'pending',
                'foreigner_type' => $request->type,
                'phone_number'   => $request->input('contact_number'),
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            // 3. Process Groom and Bride Data
            foreach (['groom', 'bride'] as $role) {
                $data = json_decode($request->input($role, '{}'), true) ?? [];
                $source = $consentSource[$role] ?? [];
                $residenceAddress = ($value = trim((string) ($data['residence'] ?? ''))) !== '' ? $value : null;
                $fatherResidence = ($value = trim((string) ($data['fatherResidence'] ?? ''))) !== '' ? $value : null;
                $motherResidence = ($value = trim((string) ($data['motherMaidenResidence'] ?? ''))) !== '' ? $value : null;
                $sourceResidence = ($value = trim((string) ($source['residence'] ?? ''))) !== '' ? $value : null;

                // Insert Applicant Info using Query Builder
                $applicantInsert = [
                    'application_id'     => $applicationId,
                    'applicant_type'     => $role,
                    'first_name'         => $data['firstName'] ?? null,
                    'middle_name'        => $data['middleName'] ?? null,
                    'last_name'          => $data['lastName'] ?? null,
                    'day'                => $data['day'] ?? null,
                    'month'              => $normalizeMonth($data['month'] ?? null),
                    'year'               => $data['year'] ?? null,
                    'birth_city'         => $data['cityMunicipality'] ?? null,
                    'birth_province'     => $data['province'] ?? null,
                    'birth_country'      => $data['country'] ?? null,
                    'age'                => $data['age'] ?? null,
                    'sex'                => $data['sex'] ?? null,
                    'citizenship'        => $resolveCitizenship($data, 'citizenship', 'citizenshipOther'),
                    'religion'           => $data['religion'] ?? null,
                    'civil_status'       => $data['civilStatus'] ?? null,
                    'residence_address'  => $residenceAddress,
                    // Dissolution Info (If not Single)
                    'dissolution_details' => $data["previousMarriageDissolve"] ?? null,
                    'dissolution_place' => $data["dissolvedCityMunicipality"] ?? null,
                    'dissolution_day' => $data['dissolvedDay'] ?? null,
                    'dissolution_month' => $normalizeMonth($data['dissolvedMonth'] ?? null),
                    'dissolution_year' => $data['dissolvedYear'] ?? null,

                    "relationship_degree" => $data['degree'] ?? null,

                    // Parental info
                    'father_first_name'  => $data['fatherFirstName'] ?? null,
                    'father_middle_name'  => $data['fatherMiddleName'] ?? null,
                    'father_last_name'   => $data['fatherLastName'] ?? null,
                    'father_citizenship' => $resolveCitizenship($data, 'fatherCitizenship', 'fatherCitizenshipOther'),
                    'father_residence'   => $fatherResidence,
                    'mother_first_name'  => $data['motherMaidenFirstName'] ?? null,
                    'mother_middle_name'  => $data['motherMaidenMiddleName'] ?? null,
                    'mother_last_name'   => $data['motherMaidenLastName'] ?? null,
                    'mother_citizenship' => $resolveCitizenship($data, 'motherMaidenCitizenship', 'motherMaidenCitizenshipOther'),
                    'mother_residence'   => $motherResidence,

                    // Consent/Advice source info per applicant (if required by age bracket)
                    'parental_requirement' => $requirements[$role] ?? null,
                    'source_first_name'    => $source['firstName'] ?? null,
                    'source_middle_name'   => $source['middleName'] ?? null,
                    'source_last_name'     => $source['lastName'] ?? null,
                    'source_citizenship'   => $resolveCitizenship($source, 'citizenship', 'citizenshipOther'),
                    'source_relationship'  => $source['relationship'] ?? null,
                    'source_residence'     => $sourceResidence,

                    'created_at'         => Carbon::now(),
                    'updated_at'         => Carbon::now(),
                ];

                if ($hasGovernmentIdType) {
                    $applicantInsert['government_id_type'] = $data['govtIssuedIdType'] ?? null;
                }
                if ($hasGovernmentIdNumber) {
                    $applicantInsert['government_id_number'] = $data['govtIssuedIdNumber'] ?? null;
                }
                if ($hasGovernmentIdIssuedAt) {
                    $applicantInsert['government_id_issued_at'] = $data['govtIssuedIdIssuedAt'] ?? null;
                }
                if ($hasGovernmentIdIssuedOn) {
                    $applicantInsert['government_id_issued_on'] = $data['govtIssuedIdIssuedOn'] ?? null;
                }

                DB::table('applicants')->insert($applicantInsert);

                // 4. Handle File Uploads
                $this->uploadDocumentsWithQueryBuilder($request, $applicationId, $role, $controlNumber);
            }

            $groomFullName = $buildFullName($groomPayload, 'Groom');
            $brideFullName = $buildFullName($bridePayload, 'Bride');
            $preMadeMessage = "Good day {$groomFullName} and {$brideFullName}. This is the Municipal Civil Registrar (MCR). Your control number is {$controlNumber}. Please present this at the office. Thank you.";

            DB::table('sms_requests')->insert([
                'phone_number'   => $request->input('contact_number'),
                'message'        => $preMadeMessage,
                'status'         => 'pending',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            return response()->json([
                'status' => 'success',
                'control_number' => $controlNumber
            ], 201);
        });
    }

    private function uploadDocumentsWithQueryBuilder($request, $appId, $role, $controlNo)
    {
        $fileKey = "documents_{$role}";

        if ($request->hasFile($fileKey)) {
            foreach ($request->file($fileKey) as $docType => $file) {
                if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
                    throw ValidationException::withMessages([
                        "{$fileKey}.{$docType}" => ['Only PDF files are allowed for uploaded documents.'],
                    ]);
                }

                // Store physical file
                $path = $file->store("applications/{$controlNo}/{$role}", 'public');

                // Insert Record using Query Builder
                DB::table('documents')->insert([
                    'application_id' => $appId,
                    'owner_type'     => $role,
                    'doc_type'       => $docType,
                    'file_path'      => $path,
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ]);
            }
        }
    }


    public function getApplications()
    {
        try {

            $applications = DB::table("marriage_applications")
                ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
                ->select(
                    "marriage_applications.*",
                    "applicants.first_name",
                    "applicants.last_name"
                )
                ->whereNull("marriage_applications.deleted_at")
                ->paginate(10);

            // return response()->json("i got past db qery");

            if ($applications->isEmpty()) {
                return response()->json([
                    "message" => "No marriage applications available",
                    "data" => $applications
                ]);
            }

            return response()->json([
                "message" => "Marriage applications found",
                "data" => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Somethings wrong with the server" . $e->getMessage(),
                "data" => [],
            ]);
        }
    }

    public function viewApplication(int $application_id, string $control_number)
    {
        try {

            $applicants = DB::table("applicants")
                ->join("marriage_applications", "applicants.application_id", "=", "marriage_applications.id")
                ->select(
                    "applicants.*",
                    "marriage_applications.control_number",
                    "marriage_applications.status",
                    "marriage_applications.submitted_at",
                )
                ->where("application_id", "=", $application_id)
                ->where("control_number", "=", $control_number)
                ->whereNull("marriage_applications.deleted_at")
                ->get();

            $groom = null;
            $bride = null;

            // The foreach loop to check the type
            foreach ($applicants as $person) {
                if ($person->applicant_type === 'groom') {
                    $groom = $person;
                } elseif ($person->applicant_type === 'bride') {
                    $bride = $person;
                }
            }

            $groomDocuments = DB::table("documents")
                ->select(
                    "documents.*",
                    DB::raw("
                CASE
                    WHEN documents.file_path IS NOT NULL
                    THEN CONCAT('" . asset('storage') . "/', documents.file_path)
                    ELSE NULL
                END as document_url
            ")
                )
                ->where("application_id", "=", $groom->application_id)
                ->where("owner_type", "=", $groom->applicant_type)
                ->get();

            $brideDocuments = DB::table("documents")
                ->select(
                    "documents.*",
                    DB::raw("
                CASE
                    WHEN documents.file_path IS NOT NULL
                    THEN CONCAT('" . asset('storage') . "/', documents.file_path)
                    ELSE NULL
                END as document_url
            ")
                )
                ->where("application_id", "=", $bride->application_id)
                ->where("owner_type", "=", $bride->applicant_type)
                ->get();

            if ($applicants->isEmpty()) {
                return response()->json([
                    "message" => "No applicants found",
                    "applicants" => [],
                    "groomDocuments" => [],
                    "brideDocuments" => [],
                ]);
            }

            return response()->json([
                "message" => "Applicants found",
                "applicants" => $applicants,
                "groomDocuments" => $groomDocuments,
                "brideDocuments" => $brideDocuments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Somethings wrong with server" . $e->getMessage(),
                "applicants" => [],
                "groomDocuments" => [],
                "brideDocuments" => [],
            ]);
        }
    }

    public function searchApplicants(Request $request)
    {
        try {

            $searchTerm = $request->query('search'); // Get the search input from Vue

            $applications = DB::table("marriage_applications")
                ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
                ->select(
                    "marriage_applications.*",
                    "applicants.first_name",
                    "applicants.last_name"
                )
                ->whereNull("marriage_applications.deleted_at")
                ->where(function ($query) use ($searchTerm) {
                    $query->where('applicants.first_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('applicants.last_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('marriage_applications.control_number', 'LIKE', "%{$searchTerm}%");
                })
                // Group by ID to avoid duplicate rows for Groom/Bride in the main list
                ->groupBy('marriage_applications.id')
                ->paginate(10);

            if ($applications->isEmpty()) {
                return response()->json([
                    "message" => "No application found",
                    "data" => [],
                ]);
            }

            return response()->json([
                "message" => "Application Found",
                "data" => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Something's wrong with the server" . $e->getMessage(),
                "data" => [],
            ]);
        }
    }

    public function getApplicationByStatus(Request $request, string $status, string $order)
    {
        try {
            $query = DB::table("marriage_applications")
                ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
                ->select(
                    "marriage_applications.id",
                    "marriage_applications.control_number",
                    "marriage_applications.status",
                    "marriage_applications.created_at",
                    // Combine the names here
                    DB::raw("GROUP_CONCAT(CONCAT(applicants.first_name, ' ', applicants.last_name) SEPARATOR ' & ') as applicant_names")
                )
                ->whereNull("marriage_applications.deleted_at");

            if ($status !== 'all') {
                $query->where("marriage_applications.status", "=", trim($status));
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('applicants.first_name', 'LIKE', "%$search%")
                        ->orWhere('applicants.last_name', 'LIKE', "%$search%")
                        ->orWhere('marriage_applications.control_number', 'LIKE', "%$search%");
                });
            }

            $statOrder = $order == "asc" ? "asc" : "desc";


            // Add the columns to the GROUP BY as well to satisfy the strict error
            $data = $query->groupBy(
                'marriage_applications.id',
                'marriage_applications.control_number',
                'marriage_applications.status',
                'marriage_applications.created_at'
            )
                ->orderBy('marriage_applications.created_at', $statOrder)
                ->paginate(5);

            return response()->json([
                "message" => "Applicants found",
                "data" => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Something's wrong: " . $e->getMessage(),
                "data" => [],
            ], 500);
        }
    }

    public function ApplicationAction(string $action, Request $request)
    {
        try {
            $control_number = $request->input("control_number");
            $applicationId = $request->input("application_id");

            // Perform the update directly on the Query Builder
            $affected = DB::table("marriage_applications")
                ->where("id", $applicationId)
                ->where("control_number", $control_number)
                ->whereNull("deleted_at")
                ->update([
                    "status" => $action,
                    "updated_at" => now() // Manually update timestamp for Query Builder
                ]);

            // If $affected is 0, it means either the record doesn't exist
            // OR the status was already set to that action value.
            if ($affected === 0) {
                // Optional: Check if it actually exists to give a specific message
                $exists = DB::table("marriage_applications")->where("id", $applicationId)->exists();

                return response()->json([
                    "message" => $exists ? "No changes were made." : "Application not found."
                ], $exists ? 200 : 404);
            }

            return response()->json([
                "message" => "Application has been successfully " . $action
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Something's wrong with the server: " . $e->getMessage(),
            ], 500);
        }
    }

    public function trash(Request $request)
    {
        try {
            $query = DB::table("marriage_applications")
                ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
                ->select(
                    "marriage_applications.id",
                    "marriage_applications.control_number",
                    "marriage_applications.status",
                    "marriage_applications.created_at",
                    "marriage_applications.deleted_at",
                    DB::raw("GROUP_CONCAT(CONCAT(applicants.first_name, ' ', applicants.last_name) SEPARATOR ' & ') as applicant_names")
                )
                ->whereNotNull("marriage_applications.deleted_at");

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('applicants.first_name', 'LIKE', "%$search%")
                        ->orWhere('applicants.last_name', 'LIKE', "%$search%")
                        ->orWhere('marriage_applications.control_number', 'LIKE', "%$search%");
                });
            }

            $data = $query->groupBy(
                'marriage_applications.id',
                'marriage_applications.control_number',
                'marriage_applications.status',
                'marriage_applications.created_at',
                'marriage_applications.deleted_at'
            )
                ->orderBy('marriage_applications.deleted_at', 'desc')
                ->paginate(5);

            return response()->json([
                "message" => "Trashed applications found",
                "data" => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Something's wrong: " . $e->getMessage(),
                "data" => [],
            ], 500);
        }
    }

    public function destroy(int $application_id)
    {
        $affected = DB::table("marriage_applications")
            ->where("id", $application_id)
            ->whereNull("deleted_at")
            ->update([
                "deleted_at" => now(),
                "updated_at" => now(),
            ]);

        if ($affected === 0) {
            return response()->json([
                "message" => "Application not found or already trashed."
            ], 404);
        }

        return response()->json([
            "message" => "Application moved to trash."
        ], 200);
    }

    public function restore(int $application_id)
    {
        $affected = DB::table("marriage_applications")
            ->where("id", $application_id)
            ->whereNotNull("deleted_at")
            ->update([
                "deleted_at" => null,
                "updated_at" => now(),
            ]);

        if ($affected === 0) {
            return response()->json([
                "message" => "Application not found in trash."
            ], 404);
        }

        return response()->json([
            "message" => "Application restored."
        ], 200);
    }

    public function adminUpdate(Request $request, int $application_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected,issued',
            'phone_number' => 'nullable|string|max:30',
            'foreigner_type' => 'nullable|in:filipino,groom,bride,both',
            'groom.first_name' => 'required|string|max:255',
            'groom.middle_name' => 'nullable|string|max:255',
            'groom.last_name' => 'required|string|max:255',
            'bride.first_name' => 'required|string|max:255',
            'bride.middle_name' => 'nullable|string|max:255',
            'bride.last_name' => 'required|string|max:255',
        ]);

        $exists = DB::table("marriage_applications")
            ->where("id", $application_id)
            ->whereNull("deleted_at")
            ->exists();

        if (!$exists) {
            return response()->json([
                "message" => "Application not found."
            ], 404);
        }

        DB::transaction(function () use ($application_id, $validated) {
            DB::table("marriage_applications")
                ->where("id", $application_id)
                ->update([
                    "status" => $validated["status"],
                    "phone_number" => $validated["phone_number"] ?? null,
                    "foreigner_type" => $validated["foreigner_type"] ?? null,
                    "updated_at" => now(),
                ]);

            DB::table("applicants")
                ->where("application_id", $application_id)
                ->where("applicant_type", "groom")
                ->update([
                    "first_name" => $validated["groom"]["first_name"],
                    "middle_name" => $validated["groom"]["middle_name"] ?? null,
                    "last_name" => $validated["groom"]["last_name"],
                    "updated_at" => now(),
                ]);

            DB::table("applicants")
                ->where("application_id", $application_id)
                ->where("applicant_type", "bride")
                ->update([
                    "first_name" => $validated["bride"]["first_name"],
                    "middle_name" => $validated["bride"]["middle_name"] ?? null,
                    "last_name" => $validated["bride"]["last_name"],
                    "updated_at" => now(),
                ]);
        });

        return response()->json([
            "message" => "Application updated successfully."
        ], 200);
    }

    public function forceDestroy(int $application_id)
    {
        $exists = DB::table("marriage_applications")
            ->where("id", $application_id)
            ->whereNotNull("deleted_at")
            ->exists();

        if (!$exists) {
            return response()->json([
                "message" => "Application not found in trash."
            ], 404);
        }

        DB::table("marriage_applications")
            ->where("id", $application_id)
            ->delete();

        return response()->json([
            "message" => "Application deleted permanently."
        ], 200);
    }

    public function preview8x13(Request $request)
    {
        $applicationId = $request->query('application_id');
        $controlNumber = $request->query('control_number');

        if ($applicationId && $controlNumber) {
            $data = $this->buildApplicationFormData($applicationId, $controlNumber);
            if ($data !== null) {
                return view('pdf.8x13', $data);
            }
        }

        return view('pdf.8x13', $this->buildPreviewData());
    }

    public function preview8x13Pdf(Request $request)
    {
        $applicationId = $request->query('application_id');
        $controlNumber = $request->query('control_number');
        $paperDimensions = [0, 0, 612, 936];

        if ($applicationId && $controlNumber) {
            $data = $this->buildApplicationFormData($applicationId, $controlNumber);
            if ($data !== null) {
                $pdf = Pdf::loadView('pdf.8x13', $data)
                    ->setPaper($paperDimensions, 'portrait');

                return $pdf->stream('Marriage_8x13.pdf');
            }
        }

        $pdf = Pdf::loadView('pdf.8x13', $this->buildPreviewData())
            ->setPaper($paperDimensions, 'portrait');

        return $pdf->stream('Marriage_8x13.pdf');
    }

    private function buildPreviewData()
    {
        $meta = [
            'province' => 'LEYTE',
            'municipality' => 'ABUYOG',
            'received_by' => 'MUNICIPAL CIVIL REGISTRAR',
            'date' => 'Feb 10, 2026',
        ];

        $groom = [
            'full_name' => 'Ruwilson Taburada Hipos',
            'name_first' => 'Ruwilson',
            'name_middle' => 'Taburada',
            'name_last' => 'Hipos',
            'birth_date' => '26 November, 2003',
            'age' => '22',
            'birthplace' => 'Abuyog Leyte, Philippines',
            'sex' => 'Male',
            'citizenship' => 'Filipino',
            'residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'religion' => 'Roman Catholic',
            'civil_status' => 'Single',
            'if_married' => 'Divorced',
            'place_dissolved' => 'Abuyog Leyte, Philippines',
            'date_dissolved' => '10 February, 2026',
            'relationship' => 'Not Applicable',
            'father_name' => 'Jaymar Sabillo Balansag',
            'father_citizenship' => 'Filipino',
            'father_residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'mother_name' => 'Lorena Limos Taburada',
            'mother_citizenship' => 'Filipino',
            'mother_residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'give_consent' => 'NOT APPLICABLE',
            'give_consent_relationship' => 'NOT APPLICABLE',
            'give_consent_citizenship' => 'NOT APPLICABLE',
            'give_consent_residence' => 'NOT APPLICABLE',
            'day_today' => '24th',
            'month_today' => 'FEBRUARY',
            'year_today' => '2026',
            'place' => 'Abuyog, Leyte',
            'id' => 'PHIL.ID: 5047-3248-5342-6083',
            'issued_on' => 'February 10, 2026',
            'issued_at' => 'Abuyog, Leyte',
            'civil_registrar' => 'Atty. Madilyn Madolin-Merano',
            'fullname_signature' => 'Russell Limos Taburada',
        ];

        $bride = [
            'full_name' => 'Russell Limos Taburada',
            'name_first' => 'Ruwilson',
            'name_middle' => 'Taburada',
            'name_last' => 'Hipos',
            'birth_date' => '26 November 2003',
            'age' => '22',
            'birthplace' => 'Abuyog Leyte Philippines',
            'sex' => 'Male',
            'citizenship' => 'Filipino',
            'residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'religion' => 'Roman Catholic',
            'civil_status' => 'Single',
            'if_married' => 'Divorced',
            'place_dissolved' => 'Abuyog Leyte Philippines',
            'date_dissolved' => '10 February 2026',
            'relationship' => 'Not Applicable',
            'father_name' => 'Jaymar Sabillo Balansag',
            'father_citizenship' => 'Filipino',
            'father_residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'mother_name' => 'Lorena Limos Taburada',
            'mother_citizenship' => 'Filipino',
            'mother_residence' => '340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines',
            'give_consent' => 'NOT APPLICABLE',
            'give_consent_relationship' => 'NOT APPLICABLE',
            'give_consent_citizenship' => 'NOT APPLICABLE',
            'give_consent_residence' => 'NOT APPLICABLE',
            'day_today' => '24th',
            'month_today' => 'FEBRUARY',
            'year_today' => '2026',
            'place' => 'Abuyog, Leyte',
            'id' => 'PHIL.ID: 5047-3248-5342-6083',
            'issued_on' => 'February 10, 2026',
            'issued_at' => 'Abuyog, Leyte',
            'civil_registrar' => 'Atty. Madilyn Madolin-Merano',
            'fullname_signature' => 'Ruwilson Taburada Hipos',
        ];

        return [
            'meta' => $meta,
            'groom' => $groom,
            'bride' => $bride,
        ];
    }

    private function buildApplicationFormData($application_id, $control_number)
    {
        $applicants = DB::table("applicants")
            ->join("marriage_applications", "applicants.application_id", "=", "marriage_applications.id")
            ->select(
                "applicants.*",
                "marriage_applications.control_number",
                "marriage_applications.submitted_at"
            )
            ->where("applicants.application_id", "=", $application_id)
            ->where("marriage_applications.control_number", "=", $control_number)
            ->whereNull("marriage_applications.deleted_at")
            ->get();

        if ($applicants->isEmpty()) {
            return null;
        }

        $groom = $applicants->firstWhere('applicant_type', 'groom');
        $bride = $applicants->firstWhere('applicant_type', 'bride');

        if (!$groom || !$bride) {
            return null;
        }

        $formatName = function ($first, $middle, $last) {
            return trim(implode(' ', array_filter([$first, $middle, $last], function ($v) {
                return $v !== null && $v !== '';
            })));
        };

        $formatBirthplace = function ($city, $province, $country) {
            return trim(implode(', ', array_filter([$city, $province, $country], function ($v) {
                return $v !== null && $v !== '';
            })));
        };

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        $normalizeMonth = function ($value) use ($monthNames) {
            if ($value === null) return null;
            $raw = trim((string) $value);
            if ($raw === '') return null;
            if (ctype_digit($raw)) {
                $index = (int) $raw;
                return $monthNames[$index] ?? $raw;
            }
            return ucfirst(strtolower($raw));
        };
        $formatDate = function ($day, $month, $year, $withComma = false) use ($normalizeMonth) {
            if ($day === null && $month === null && $year === null) {
                return null;
            }
            $month = $normalizeMonth($month);
            if ($withComma && $day && $month && $year) {
                return trim($day . ' ' . $month . ', ' . $year);
            }
            return trim(implode(' ', array_filter([$day, $month, $year], function ($v) {
                return $v !== null && $v !== '';
            })));
        };

        $consentValue = function ($applicant, $field, $fallback = 'NOT APPLICABLE') {
            if (($applicant->parental_requirement ?? 'no-need') === 'no-need') {
                return $fallback;
            }
            return $field ?: $fallback;
        };
        $firstNonEmpty = function (...$values) {
            foreach ($values as $value) {
                if ($value !== null && trim((string) $value) !== '') {
                    return $value;
                }
            }
            return null;
        };
        $resolveId = function ($applicant) use ($firstNonEmpty) {
            $idType = $firstNonEmpty(
                data_get($applicant, 'government_id_type'),
                data_get($applicant, 'id_type')
            );
            $idNumber = $firstNonEmpty(
                data_get($applicant, 'government_id_number'),
                data_get($applicant, 'identification_number'),
                data_get($applicant, 'id_number'),
                data_get($applicant, 'valid_id'),
                data_get($applicant, 'government_id'),
                data_get($applicant, 'phil_id')
            );

            if ($idType && $idNumber) {
                return $idType . ': ' . $idNumber;
            }

            return $idNumber ?: 'N/A';
        };
        $toOrdinalDay = function ($day) {
            $dayInt = (int) $day;
            if ($dayInt <= 0) {
                return '';
            }
            if ($dayInt % 100 >= 11 && $dayInt % 100 <= 13) {
                return $dayInt . 'th';
            }
            return match ($dayInt % 10) {
                1 => $dayInt . 'st',
                2 => $dayInt . 'nd',
                3 => $dayInt . 'rd',
                default => $dayInt . 'th',
            };
        };

        $metaDate = $applicants->first()->submitted_at ?: Carbon::now();
        $submittedDate = Carbon::parse($metaDate);
        $meta = [
            'province' => 'LEYTE',
            'municipality' => 'ABUYOG',
            'received_by' => 'MUNICIPAL CIVIL REGISTRAR',
            'date' => $submittedDate->format('M d, Y'),
        ];

        $groomData = [
            'full_name' => $formatName($groom->first_name, $groom->middle_name, $groom->last_name),
            'name_first' => $groom->first_name,
            'name_middle' => $groom->middle_name,
            'name_last' => $groom->last_name,
            'birth_date' => $firstNonEmpty(
                $formatDate($groom->day, $groom->month, $groom->year, true),
                $formatDate($groom->day, $groom->month, $groom->year, false)
            ),
            'age' => $groom->age,
            'birthplace' => $firstNonEmpty(
                $formatBirthplace($groom->birth_city, $groom->birth_province, $groom->birth_country),
                trim(implode(' ', array_filter([$groom->birth_city, $groom->birth_province, $groom->birth_country])))
            ),
            'sex' => $groom->sex,
            'citizenship' => $groom->citizenship,
            'residence' => $groom->residence_address,
            'religion' => $groom->religion,
            'civil_status' => $groom->civil_status,
            'if_married' => $groom->dissolution_details ?: 'NOT APPLICABLE',
            'place_dissolved' => $firstNonEmpty($groom->dissolution_place, 'NOT APPLICABLE'),
            'date_dissolved' => $formatDate($groom->dissolution_day, $groom->dissolution_month, $groom->dissolution_year, true) ?: 'NOT APPLICABLE',
            'relationship' => $groom->relationship_degree ?: 'NOT APPLICABLE',
            'father_name' => $formatName($groom->father_first_name, $groom->father_middle_name, $groom->father_last_name),
            'father_citizenship' => $groom->father_citizenship,
            'father_residence' => $groom->father_residence,
            'mother_name' => $formatName($groom->mother_first_name, $groom->mother_middle_name, $groom->mother_last_name),
            'mother_citizenship' => $groom->mother_citizenship,
            'mother_residence' => $groom->mother_residence,
            'give_consent' => $consentValue($groom, trim(($groom->source_first_name ?? '') . ' ' . ($groom->source_middle_name ?? '') . ' ' . ($groom->source_last_name ?? ''))),
            'give_consent_relationship' => $consentValue($groom, $groom->source_relationship ?? null),
            'give_consent_citizenship' => $consentValue($groom, $groom->source_citizenship ?? null),
            'give_consent_residence' => $consentValue($groom, $groom->source_residence ?? null),
            'fullname_signature' => $formatName($groom->first_name, $groom->middle_name, $groom->last_name),
            'day_today' => $toOrdinalDay($submittedDate->day),
            'month_today' => strtoupper($submittedDate->format('F')),
            'year_today' => $submittedDate->format('Y'),
            'place' => 'Abuyog, Leyte',
            'id' => $resolveId($groom),
            'issued_on' => $submittedDate->format('F d, Y'),
            'issued_at' => 'Abuyog, Leyte',
            'civil_registrar' => 'Atty. Madilyn Madolin-Merano',
        ];

        $brideData = [
            'full_name' => $formatName($bride->first_name, $bride->middle_name, $bride->last_name),
            'name_first' => $bride->first_name,
            'name_middle' => $bride->middle_name,
            'name_last' => $bride->last_name,
            'birth_date' => $firstNonEmpty(
                $formatDate($bride->day, $bride->month, $bride->year, true),
                $formatDate($bride->day, $bride->month, $bride->year, false)
            ),
            'age' => $bride->age,
            'birthplace' => $firstNonEmpty(
                $formatBirthplace($bride->birth_city, $bride->birth_province, $bride->birth_country),
                trim(implode(' ', array_filter([$bride->birth_city, $bride->birth_province, $bride->birth_country])))
            ),
            'sex' => $bride->sex,
            'citizenship' => $bride->citizenship,
            'residence' => $bride->residence_address,
            'religion' => $bride->religion,
            'civil_status' => $bride->civil_status,
            'if_married' => $bride->dissolution_details ?: 'NOT APPLICABLE',
            'place_dissolved' => $firstNonEmpty($bride->dissolution_place, 'NOT APPLICABLE'),
            'date_dissolved' => $formatDate($bride->dissolution_day, $bride->dissolution_month, $bride->dissolution_year, true) ?: 'NOT APPLICABLE',
            'relationship' => $bride->relationship_degree ?: 'NOT APPLICABLE',
            'father_name' => $formatName($bride->father_first_name, $bride->father_middle_name, $bride->father_last_name),
            'father_citizenship' => $bride->father_citizenship,
            'father_residence' => $bride->father_residence,
            'mother_name' => $formatName($bride->mother_first_name, $bride->mother_middle_name, $bride->mother_last_name),
            'mother_citizenship' => $bride->mother_citizenship,
            'mother_residence' => $bride->mother_residence,
            'give_consent' => $consentValue($bride, trim(($bride->source_first_name ?? '') . ' ' . ($bride->source_middle_name ?? '') . ' ' . ($bride->source_last_name ?? ''))),
            'give_consent_relationship' => $consentValue($bride, $bride->source_relationship ?? null),
            'give_consent_citizenship' => $consentValue($bride, $bride->source_citizenship ?? null),
            'give_consent_residence' => $consentValue($bride, $bride->source_residence ?? null),
            'fullname_signature' => $formatName($bride->first_name, $bride->middle_name, $bride->last_name),
            'day_today' => $toOrdinalDay($submittedDate->day),
            'month_today' => strtoupper($submittedDate->format('F')),
            'year_today' => $submittedDate->format('Y'),
            'place' => 'Abuyog, Leyte',
            'id' => $resolveId($bride),
            'issued_on' => $submittedDate->format('F d, Y'),
            'issued_at' => 'Abuyog, Leyte',
            'civil_registrar' => 'Atty. Madilyn Madolin-Merano',
        ];

        return [
            'meta' => $meta,
            'groom' => $groomData,
            'bride' => $brideData,
        ];
    }

    public function printApplicationPdf($application_id, $control_number)
    {
        try {
            $data = $this->buildApplicationFormData($application_id, $control_number);
            if ($data === null) {
                return response()->json(["message" => "Application not found"], 404);
            }

            $pdf = Pdf::loadView('pdf.MarriageLicenseApplicationForm', $data)
                ->setPaper([0, 0, 612, 936], 'portrait');

            return $pdf->stream('Marriage_License_Application.pdf');
        } catch (\Exception $e) {
            return response()->json(["message" => "Server Error: " . $e->getMessage()], 500);
        }
    }
}
