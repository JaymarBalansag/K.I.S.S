<?php

namespace App\Http\Controllers\Api;

use App\Exports\MarriageExport;
use App\Exports\MarriageExports;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MarriageApplicationController extends Controller
{
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $consentSource = json_decode($request->input('consentSource', '{}'), true) ?? [];
            $requirements = [
                'groom' => $request->input('groomRequirement'),
                'bride' => $request->input('brideRequirement'),
            ];

            // 1. Generate Control Number
            do {
                $controlNumber = strtoupper(Str::random(5));
            } while (DB::table('marriage_applications')->where('control_number', $controlNumber)->exists());

            // 2. Insert Main Application using Query Builder
            $applicationId = DB::table('marriage_applications')->insertGetId([
                'control_number' => $controlNumber,
                'status'         => 'pending',
                'foreigner_type' => $request->type,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            // 3. Process Groom and Bride Data
            foreach (['groom', 'bride'] as $role) {
                $data = json_decode($request->input($role), true);
                $source = $consentSource[$role] ?? [];

                // Insert Applicant Info using Query Builder
                DB::table('applicants')->insert([
                    'application_id'     => $applicationId,
                    'applicant_type'     => $role,
                    'first_name'         => $data['firstName'] ?? null,
                    'middle_name'        => $data['middleName'] ?? null,
                    'last_name'          => $data['lastName'] ?? null,
                    'day'                => $data['day'] ?? null,
                    'month'              => $data['month'] ?? null,
                    'year'               => $data['year'] ?? null,
                    'birth_city'         => $data['cityMunicipality'] ?? null,
                    'birth_province'     => $data['province'] ?? null,
                    'birth_country'      => $data['country'] ?? null,
                    'age'                => $data['age'] ?? null,
                    'sex'                => $data['sex'] ?? null,
                    'citizenship'        => $data['citizenship'] ?? null,
                    'religion'           => $data['religion'] ?? null,
                    'civil_status'       => $data['civilStatus'] ?? null,
                    'residence_address'  => $data['residence'] ?? null,
                    // Dissolution Info (If not Single)
                    'dissolution_details' => $data["previousMarriageDissolve"] ?? null,
                    'dissolution_place' => $data["previousMarriageDissolve"] ?? null,
                    'dissolution_day' => $data['dissolvedDay'] ?? null,
                    'dissolution_month' => $data['dissolvedMonth'] ?? null,
                    'dissolution_year' => $data['dissolvedYear'] ?? null,

                    "relationship_degree" => $data['degree'] ?? null,

                    // Parental info
                    'father_first_name'  => $data['fatherFirstName'] ?? null,
                    'father_middle_name'  => $data['fatherMiddleName'] ?? null,
                    'father_last_name'   => $data['fatherLastName'] ?? null,
                    'father_citizenship' => $data['fatherCitizenship'] ?? null,
                    'father_residence'   => $data['fatherResidence'] ?? null,
                    'mother_first_name'  => $data['motherMaidenFirstName'] ?? null,
                    'mother_middle_name'  => $data['motherMiddleName'] ?? null,
                    'mother_last_name'   => $data['motherMaidenLastName'] ?? null,
                    'mother_citizenship' => $data['motherMaidenCitizenship'] ?? null,
                    'mother_residence'   => $data['motherMaidenResidence'] ?? null,

                    // Consent/Advice source info per applicant (if required by age bracket)
                    'parental_requirement' => $requirements[$role] ?? null,
                    'source_first_name'    => $source['firstName'] ?? null,
                    'source_middle_name'   => $source['middleName'] ?? null,
                    'source_last_name'     => $source['lastName'] ?? null,
                    'source_citizenship'   => $source['citizenship'] ?? null,
                    'source_relationship'  => $source['relationship'] ?? null,
                    'source_residence'     => $source['residence'] ?? null,

                    'created_at'         => Carbon::now(),
                    'updated_at'         => Carbon::now(),
                ]);

                // 4. Handle File Uploads
                $this->uploadDocumentsWithQueryBuilder($request, $applicationId, $role, $controlNumber);
            }

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
                )->paginate(10);

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
                );

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

    public function trialPreview(Request $request)
    {
        $applicationId = $request->query('application_id');
        $controlNumber = $request->query('control_number');

        if ($applicationId && $controlNumber) {
            $data = $this->buildApplicationFormData($applicationId, $controlNumber);
            if ($data !== null) {
                return view('pdf.MarriageLicenseApplicationForm', $data);
            }
        }

        return view('pdf.MarriageLicenseApplicationForm', $this->buildPreviewData());
    }

    public function trialPreviewPdf(Request $request)
    {
        $applicationId = $request->query('application_id');
        $controlNumber = $request->query('control_number');

        if ($applicationId && $controlNumber) {
            $data = $this->buildApplicationFormData($applicationId, $controlNumber);
            if ($data !== null) {
                $pdf = Pdf::loadView('pdf.MarriageLicenseApplicationForm', $data)
                    ->setPaper([0, 0, 612, 936], 'portrait');

                return $pdf->stream('Marriage_Trial.pdf');
            }
        }

        $pdf = Pdf::loadView('pdf.MarriageLicenseApplicationForm', $this->buildPreviewData())
            ->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream('Marriage_Trial.pdf');
    }

    private function buildPreviewData()
    {
        return [
            'meta' => [
                'province' => 'LEYTE',
                'municipality' => 'ABUYOG',
                'received_by' => 'MUNICIPAL CIVIL REGISTRAR',
                'date' => Carbon::now()->format('M d, Y'),
            ],
            'groom' => [
                'full_name' => 'Groom Full Name',
                'name_first' => 'Groom',
                'name_middle' => 'Middle',
                'name_last' => 'Last',
                'birth_date' => '01 January, 2000',
                'age' => '26',
                'birthplace' => 'Abuyog Leyte, Philippines',
                'sex' => 'Male',
                'citizenship' => 'Filipino',
                'residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'religion' => 'Roman Catholic',
                'civil_status' => 'Single',
                'if_married' => 'NOT APPLICABLE',
                'place_dissolved' => 'NOT APPLICABLE',
                'date_dissolved' => 'NOT APPLICABLE',
                'relationship' => 'NOT APPLICABLE',
                'father_name' => 'Father Full Name',
                'father_citizenship' => 'Filipino',
                'father_residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'mother_name' => 'Mother Full Name',
                'mother_citizenship' => 'Filipino',
                'mother_residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'give_consent' => 'NOT APPLICABLE',
                'give_consent_relationship' => 'NOT APPLICABLE',
                'give_consent_citizenship' => 'NOT APPLICABLE',
                'give_consent_residence' => 'NOT APPLICABLE',
                'fullname_signature' => 'Groom Full Name',
                'place' => 'ABUYOG, LEYTE',
                'civil_registrar' => 'Madilyn Madolin-Merano',
            ],
            'bride' => [
                'full_name' => 'Bride Full Name',
                'name_first' => 'Bride',
                'name_middle' => 'Middle',
                'name_last' => 'Last',
                'birth_date' => '01 January, 2000',
                'age' => '26',
                'birthplace' => 'Abuyog Leyte, Philippines',
                'sex' => 'Female',
                'citizenship' => 'Filipino',
                'residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'religion' => 'Roman Catholic',
                'civil_status' => 'Single',
                'if_married' => 'NOT APPLICABLE',
                'place_dissolved' => 'NOT APPLICABLE',
                'date_dissolved' => 'NOT APPLICABLE',
                'relationship' => 'NOT APPLICABLE',
                'father_name' => 'Father Full Name',
                'father_citizenship' => 'Filipino',
                'father_residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'mother_name' => 'Mother Full Name',
                'mother_citizenship' => 'Filipino',
                'mother_residence' => 'Sample Address, Abuyog Leyte, Philippines',
                'give_consent' => 'NOT APPLICABLE',
                'give_consent_relationship' => 'NOT APPLICABLE',
                'give_consent_citizenship' => 'NOT APPLICABLE',
                'give_consent_residence' => 'NOT APPLICABLE',
                'fullname_signature' => 'Bride Full Name',
                'place' => 'ABUYOG, LEYTE',
                'civil_registrar' => 'Madilyn C. Madolin-Merano',
            ],
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
            return trim(implode(' ', array_filter([$city, $province, $country], function ($v) {
                return $v !== null && $v !== '';
            })));
        };

        $formatDate = function ($day, $month, $year, $withComma = false) {
            if ($day === null && $month === null && $year === null) {
                return null;
            }
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

        $metaDate = $applicants->first()->submitted_at ?: Carbon::now();
        $meta = [
            'province' => 'LEYTE',
            'municipality' => 'ABUYOG',
            'received_by' => 'MUNICIPAL CIVIL REGISTRAR',
            'date' => Carbon::parse($metaDate)->format('M d, Y'),
        ];

        $groomData = [
            'full_name' => $formatName($groom->first_name, $groom->middle_name, $groom->last_name),
            'name_first' => $groom->first_name,
            'name_middle' => $groom->middle_name,
            'name_last' => $groom->last_name,
            'birth_date' => $formatDate($groom->day, $groom->month, $groom->year, true),
            'age' => $groom->age,
            'birthplace' => $formatBirthplace($groom->birth_city, $groom->birth_province, $groom->birth_country),
            'sex' => $groom->sex,
            'citizenship' => $groom->citizenship,
            'residence' => $groom->residence_address,
            'religion' => $groom->religion,
            'civil_status' => $groom->civil_status,
            'if_married' => $groom->dissolution_details ?: 'NOT APPLICABLE',
            'place_dissolved' => $groom->dissolution_place ?: 'NOT APPLICABLE',
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
            'place' => 'ABUYOG, LEYTE',
            'civil_registrar' => 'Atty. Madilyn C. Madolin-Merano',
        ];

        $brideData = [
            'full_name' => $formatName($bride->first_name, $bride->middle_name, $bride->last_name),
            'name_first' => $bride->first_name,
            'name_middle' => $bride->middle_name,
            'name_last' => $bride->last_name,
            'birth_date' => $formatDate($bride->day, $bride->month, $bride->year, true),
            'age' => $bride->age,
            'birthplace' => $formatBirthplace($bride->birth_city, $bride->birth_province, $bride->birth_country),
            'sex' => $bride->sex,
            'citizenship' => $bride->citizenship,
            'residence' => $bride->residence_address,
            'religion' => $bride->religion,
            'civil_status' => $bride->civil_status,
            'if_married' => $bride->dissolution_details ?: 'NOT APPLICABLE',
            'place_dissolved' => $bride->dissolution_place ?: 'NOT APPLICABLE',
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
            'place' => 'ABUYOG, LEYTE',
            'civil_registrar' => 'Atty. Madilyn C. Madolin-Merano',
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
