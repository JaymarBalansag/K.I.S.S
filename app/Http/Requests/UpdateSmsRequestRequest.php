<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmsRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone_number' => 'required|string|max:30',
            'message' => 'required|string|max:1000',
            'status' => 'required|string|in:pending,sent,failed',
        ];
    }
}
