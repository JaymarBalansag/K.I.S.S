<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CohabitationPartnerRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $partnerType = $this->input('partner_type');

        $this->merge([
            'partner_type' => $partnerType === 'groom' ? 'groom' : 'bride',
        ]);
    }

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
            'partner_type' => 'required|in:groom,bride',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_type' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'issued_at' => 'required|string|max:255',
            'issued_on' => 'required|date',
        ];
    }
}
