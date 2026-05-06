<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CohabitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'applicant_id' => 'required|integer',
            'residence' => 'required|string|max:255',
            'cohabitation_start_date' => 'required|date',
        ];
    }
}
