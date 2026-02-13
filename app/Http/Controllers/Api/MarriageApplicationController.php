<?php

namespace App\Http\Controllers\Api;

use App\Exports\MarriageExport;
use App\Exports\MarriageExports;
use App\Http\Controllers\Controller;
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

    // For Downloading the form:

    public function printApplication($application_id, $control_number) {
        try {
            // 1. Get the data
            $applicants = DB::table("applicants")
                ->where("application_id", $application_id)
                ->get();

            $groom = $applicants->firstWhere('applicant_type', 'groom');
            $bride = $applicants->firstWhere('applicant_type', 'bride');

            // 2. Path to your high-fidelity template
            $templatePath = storage_path('app/private/templates/application.xlsx');
            
            if (!file_exists($templatePath)) {
                return response()->json(["message" => "Template file not found"], 404);
            }

            // 3. LOAD THE TEMPLATE (This preserves all merges and lines)
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // 4. INJECT DATA INTO RESPECTIVE CELLS
            // Groom Section
            $sheet->setCellValue('C12', $groom->first_name . ' ' . $groom->last_name);
            $sheet->setCellValue('C18', $groom->first_name);
            $sheet->setCellValue('C19', $groom->middle_name ?: " ");
            $sheet->setCellValue('C20', $groom->last_name);
            $sheet->setCellValue('B22', $groom->day);
            $sheet->setCellValue('D22', $groom->month);
            $sheet->setCellValue('H22', $groom->year);
            $sheet->setCellValue('J22', $groom->age);
            $sheet->setCellValue('B23', $groom->birth_city . " " . $groom->birth_province . " " . $groom->birth_country);
            $sheet->setCellValue('B24', $groom->sex);
            $sheet->setCellValue('G24', $groom->citizenship);
            $sheet->setCellValue('B25', $groom->residence_address);
            $sheet->setCellValue('B26', $groom->religion);
            $sheet->setCellValue('B27', $groom->civil_status);
            $sheet->setCellValue('B28', $groom->dissolution_details ?: "NOT APPLICABLE");
            $sheet->setCellValue('B30', $groom->dissolution_place ?: "NOT APPPLICABLE");
            $dissolutionDate = trim($groom->dissolution_day . " " . $groom->dissolution_month . " " . $groom->dissolution_year);
            $sheet->setCellValue('M32', !empty($dissolutionDate) ? $dissolutionDate : "NOT APPLICABLE");            $sheet->setCellValue('B33', $groom->relationship_degree ?: "NOT APPLICABLE");
            $sheet->setCellValue('B35', $groom->father_first_name . " " . ($groom->father_middle_name ?? "") . " " . $groom->father_last_name);                
            $sheet->setCellValue('B36', $groom->father_citizenship);
            $sheet->setCellValue('B38', $groom->father_residence);
            $sheet->setCellValue('B39', $groom->mother_first_name . " " . ($groom->mother_middle_name ?? "") . " " . $groom->mother_last_name);                
            $sheet->setCellValue('B40', $groom->mother_citizenship);
            $sheet->setCellValue('B42', $groom->mother_residence);
            $sheet->setCellValue('B43', "NOT APPLICABLE");
            $sheet->setCellValue('B44', "NOT APPLICABLE");
            $sheet->setCellValue('B45', "NOT APPLICABLE");
            $sheet->setCellValue('B46', "NOT APPLICABLE");

            $sheet->setCellValue('L12', $bride->first_name . ' ' . $bride->last_name);
            $sheet->setCellValue('N18', $bride->first_name);
            $sheet->setCellValue('N19', $bride->middle_name ?: " ");
            $sheet->setCellValue('N20', $bride->last_name);
            $sheet->setCellValue('M22', $bride->day);
            $sheet->setCellValue('O22', $bride->month);
            $sheet->setCellValue('P22', $bride->year);
            $sheet->setCellValue('R22', $bride->age);
            $sheet->setCellValue('M23', $bride->birth_city . " " . $bride->birth_province . " " . $bride->birth_country);
            $sheet->setCellValue('M24', $bride->sex);
            $sheet->setCellValue('P24', $bride->citizenship);
            $sheet->setCellValue('M25', $bride->residence_address);
            $sheet->setCellValue('M26', $bride->religion);
            $sheet->setCellValue('M27', $bride->civil_status);
            $sheet->setCellValue('M28', $bride->dissolution_details ?: "NOT APPLICABLE");
            $sheet->setCellValue('M30', $bride->dissolution_place ?: "NOT APPPLICABLE");
            $dissolutionDate = trim($bride->dissolution_day . " " . $bride->dissolution_month . " " . $bride->dissolution_year);
            $sheet->setCellValue('M32', !empty($dissolutionDate) ? $dissolutionDate : "NOT APPLICABLE");            $sheet->setCellValue('M33', $bride->relationship_degree ?: "NOT APPLICABLE");
            $sheet->setCellValue('M35', $bride->father_first_name . " " . ($bride->father_middle_name ?? "") . " " . $bride->father_last_name);                
            $sheet->setCellValue('M36', $bride->father_citizenship);
            $sheet->setCellValue('M38', $bride->father_residence);
            $sheet->setCellValue('M39', $bride->mother_first_name . " " . ($bride->mother_middle_name ?? "") . " " . $bride->mother_last_name);                
            $sheet->setCellValue('M40', $bride->mother_citizenship);
            $sheet->setCellValue('M42', $bride->mother_residence);
            $sheet->setCellValue('M43', "NOT APPLICABLE");
            $sheet->setCellValue('M44', "NOT APPLICABLE");
            $sheet->setCellValue('M45', "NOT APPLICABLE");
            $sheet->setCellValue('M46', "NOT APPLICABLE");

            // 5. STREAM THE FILE BACK TO VUE
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Marriage_App_' . $control_number . '.xlsx');

        } catch (\Exception $e) {
            return response()->json(["message" => "Server Error: " . $e->getMessage()], 500);
        }
    }


}
