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
                'email'          => $request->email !== 'undefined' ? $request->email : null,
                'contact_number' => $request->contact_number !== 'undefined' ? $request->contact_number : null,
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
                    'first_name'         => $data['firstName'],
                    'middle_name'        => $data['middleName'] ?? 'N/A',
                    'last_name'          => $data['lastName'],
                    'day'         => $data['day'],
                    'month'         => $data['month'],
                    'year'         => $data['year'],
                    'birth_city'         => $data['cityMunicipality'],
                    'birth_province'     => $data['province'],
                    'birth_country'      => $data['country'],
                    'age'                => $data['age'],
                    'sex'                => $data['sex'],
                    'citizenship'        => $data['citizenship'],
                    'religion'           => $data['religion'],
                    'civil_status'       => $data['civilStatus'],
                    'residence_address'  => $data['residence'],
                    // Dissolution Info (If not Single)
                    'dissolution_details' => $data["previousMarriageDissolve"] ?? 'N/A', 
                    'dissolution_place' => $data["previousMarriageDissolve"] ?? 'N/A', 
                    'dissolution_day' => $data['dissolvedDay'] ?? "N/A",
                    'dissolution_month' => $data['dissolvedMonth'] ?? "N/A",
                    'dissolution_year' => $data['dissolvedYear'] ?? "N/A",

                    "relationship_degree" => $data['degree'],

                    // Parental info
                    'father_first_name'  => $data['fatherFirstName'] ?? 'N/A',
                    'father_middle_name'  => $data['fatherMiddleName'] ?? 'N/A',
                    'father_last_name'   => $data['fatherLastName'] ?? 'N/A',
                    'father_citizenship' => $data['fatherCitizenship'] ?? 'N/A',
                    'father_residence'   => $data['fatherResidence'] ?? 'N/A',
                    'mother_first_name'  => $data['motherMaidenFirstName'] ?? 'N/A',
                    'mother_middle_name'  => $data['motherMiddleName'] ?? 'N/A',
                    'mother_last_name'   => $data['motherMaidenLastName'] ?? 'N/A',
                    'mother_citizenship' => $data['motherMaidenCitizenship'] ?? 'N/A',
                    'mother_residence'   => $data['motherMaidenResidence'] ?? 'N/A',
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
}
