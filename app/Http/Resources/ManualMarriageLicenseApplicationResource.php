<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManualMarriageLicenseApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $groomName = trim(implode(' ', array_filter([
            $this->groom_first_name,
            $this->groom_middle_name,
            $this->groom_last_name,
        ])));
        $brideName = trim(implode(' ', array_filter([
            $this->bride_first_name,
            $this->bride_middle_name,
            $this->bride_last_name,
        ])));

        return [
            'id' => $this->id,
            'control_number' => $this->control_number,
            'phone_number' => $this->phone_number,
            'groom_name' => $groomName,
            'bride_name' => $brideName,
            'couple_names' => trim($groomName . ' & ' . $brideName),
            'preview_pdf_url' => url('/api/manual-applications/preview-pdf?id=' . $this->id . '&control_number=' . urlencode($this->control_number)),
            'groom' => [
                'first_name' => $this->groom_first_name,
                'middle_name' => $this->groom_middle_name,
                'last_name' => $this->groom_last_name,
                'birth_date' => optional($this->groom_birth_date)?->toDateString(),
                'age' => $this->groom_age,
                'birth_city' => $this->groom_birth_city,
                'birth_province' => $this->groom_birth_province,
                'birth_country' => $this->groom_birth_country,
                'sex' => $this->groom_sex,
                'citizenship' => $this->groom_citizenship,
                'religion' => $this->groom_religion,
                'civil_status' => $this->groom_civil_status,
                'residence_address' => $this->groom_residence_address,
                'dissolution_details' => $this->groom_dissolution_details,
                'dissolution_place' => $this->groom_dissolution_place,
                'dissolution_date' => optional($this->groom_dissolution_date)?->toDateString(),
                'relationship_degree' => $this->groom_relationship_degree,
                'father_first_name' => $this->groom_father_first_name,
                'father_middle_name' => $this->groom_father_middle_name,
                'father_last_name' => $this->groom_father_last_name,
                'father_citizenship' => $this->groom_father_citizenship,
                'father_residence' => $this->groom_father_residence,
                'mother_first_name' => $this->groom_mother_first_name,
                'mother_middle_name' => $this->groom_mother_middle_name,
                'mother_last_name' => $this->groom_mother_last_name,
                'mother_citizenship' => $this->groom_mother_citizenship,
                'mother_residence' => $this->groom_mother_residence,
                'consent_name' => $this->groom_consent_name,
                'consent_relationship' => $this->groom_consent_relationship,
                'consent_citizenship' => $this->groom_consent_citizenship,
                'consent_residence' => $this->groom_consent_residence,
                'id_type' => $this->groom_id_type,
                'id_number' => $this->groom_id_number,
            ],
            'bride' => [
                'first_name' => $this->bride_first_name,
                'middle_name' => $this->bride_middle_name,
                'last_name' => $this->bride_last_name,
                'birth_date' => optional($this->bride_birth_date)?->toDateString(),
                'age' => $this->bride_age,
                'birth_city' => $this->bride_birth_city,
                'birth_province' => $this->bride_birth_province,
                'birth_country' => $this->bride_birth_country,
                'sex' => $this->bride_sex,
                'citizenship' => $this->bride_citizenship,
                'religion' => $this->bride_religion,
                'civil_status' => $this->bride_civil_status,
                'residence_address' => $this->bride_residence_address,
                'dissolution_details' => $this->bride_dissolution_details,
                'dissolution_place' => $this->bride_dissolution_place,
                'dissolution_date' => optional($this->bride_dissolution_date)?->toDateString(),
                'relationship_degree' => $this->bride_relationship_degree,
                'father_first_name' => $this->bride_father_first_name,
                'father_middle_name' => $this->bride_father_middle_name,
                'father_last_name' => $this->bride_father_last_name,
                'father_citizenship' => $this->bride_father_citizenship,
                'father_residence' => $this->bride_father_residence,
                'mother_first_name' => $this->bride_mother_first_name,
                'mother_middle_name' => $this->bride_mother_middle_name,
                'mother_last_name' => $this->bride_mother_last_name,
                'mother_citizenship' => $this->bride_mother_citizenship,
                'mother_residence' => $this->bride_mother_residence,
                'consent_name' => $this->bride_consent_name,
                'consent_relationship' => $this->bride_consent_relationship,
                'consent_citizenship' => $this->bride_consent_citizenship,
                'consent_residence' => $this->bride_consent_residence,
                'id_type' => $this->bride_id_type,
                'id_number' => $this->bride_id_number,
            ],
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
