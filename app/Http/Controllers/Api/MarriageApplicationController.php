<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MarriageApplicationController extends Controller
{
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // 1. Generate Control Number
            $controlNumber = 'LCROML-' . strtoupper(Str::random(8));

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


    public function getApplications () {
        try {
            
            $applications = DB::table("marriage_applications")
            ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
            ->select("marriage_applications.*",
            "applicants.first_name",
            "applicants.last_name"
            )->paginate(10);

            // return response()->json("i got past db qery");

            if($applications->isEmpty()){
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

    public function viewApplication(int $application_id, string $control_number) {
        try {
            
            $applicants = DB::table("applicants")
            ->join("marriage_applications", "applicants.application_id", "=", "marriage_applications.id")
            ->select("applicants.*",
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
            ->select("documents.*",
            DB::raw("
                CASE 
                    WHEN documents.file_path IS NOT NULL 
                    THEN CONCAT('" . asset('storage') . "/', documents.file_path) 
                    ELSE NULL 
                END as document_url
            "))
            ->where("application_id", "=", $groom->application_id)
            ->where("owner_type", "=", $groom->applicant_type)
            ->get();
            
            $brideDocuments = DB::table("documents")
            ->select("documents.*",
            DB::raw("
                CASE 
                    WHEN documents.file_path IS NOT NULL 
                    THEN CONCAT('" . asset('storage') . "/', documents.file_path) 
                    ELSE NULL 
                END as document_url
            "))
            ->where("application_id", "=", $bride->application_id)
            ->where("owner_type", "=", $bride->applicant_type)
            ->get();

            if($applicants->isEmpty()){
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

    public function searchApplicants(Request $request){
        try {
            
            $searchTerm = $request->query('search'); // Get the search input from Vue

            $applications = DB::table("marriage_applications")
                ->join("applicants", "marriage_applications.id", "=", "applicants.application_id")
                ->select(
                    "marriage_applications.*",
                    "applicants.first_name",
                    "applicants.last_name"
                )
                ->where(function($query) use ($searchTerm) {
                    $query->where('applicants.first_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('applicants.last_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('marriage_applications.control_number', 'LIKE', "%{$searchTerm}%");
                })
                // Group by ID to avoid duplicate rows for Groom/Bride in the main list
                ->groupBy('marriage_applications.id') 
                ->paginate(10);

                if($applications->isEmpty()){
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

    public function getApplicationByStatus(Request $request, string $status, string $order) {
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
                $query->where(function($q) use ($search) {
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

    public function ApplicationAction(string $action, Request $request) {
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
}
