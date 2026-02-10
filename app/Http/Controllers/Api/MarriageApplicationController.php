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
            ->select("documents.*")
            ->where("application_id", "=", $groom->application_id)
            ->where("owner_type", "=", $groom->applicant_type)
            ->get();
            
            $brideDocuments = DB::table("documents")
            ->select("documents.*")
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
}
