<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManualMarriageLicenseApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $personRules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'age' => 'required|integer|min:18|max:120',
            'birth_city' => 'required|string|max:255',
            'birth_province' => 'required|string|max:255',
            'birth_country' => 'required|string|max:255',
            'sex' => 'required|string|in:Male,Female',
            'citizenship' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'civil_status' => 'required|string|in:Single,Widowed,Divorced,Annulled',
            'residence_address' => 'required|string|max:1000',
            'dissolution_details' => 'nullable|string|max:1000',
            'dissolution_place' => 'nullable|string|max:255',
            'dissolution_date' => 'nullable|date|before_or_equal:today',
            'relationship_degree' => 'nullable|string|max:255',
            'father_first_name' => 'required|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_last_name' => 'required|string|max:255',
            'father_citizenship' => 'required|string|max:255',
            'father_residence' => 'required|string|max:1000',
            'mother_first_name' => 'required|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_citizenship' => 'required|string|max:255',
            'mother_residence' => 'required|string|max:1000',
            'consent_name' => 'nullable|string|max:255',
            'consent_relationship' => 'nullable|string|max:255',
            'consent_citizenship' => 'nullable|string|max:255',
            'consent_residence' => 'nullable|string|max:1000',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
        ];

        return [
            'phone_number' => ['nullable', 'regex:/^09\d{9}$/'],
            'groom' => 'required|array',
            'bride' => 'required|array',
            'groom.*' => 'nullable',
            'bride.*' => 'nullable',
            ...collect($personRules)->mapWithKeys(fn ($rule, $field) => ["groom.{$field}" => $rule])->all(),
            ...collect($personRules)->mapWithKeys(fn ($rule, $field) => ["bride.{$field}" => $rule])->all(),
        ];
    }
}
