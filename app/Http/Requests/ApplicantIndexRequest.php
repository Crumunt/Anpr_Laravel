<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantIndexRequest extends FormRequest
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
            'status' => 'nullable|string|max:50',
            'search' => 'nullable|string|max:255',
            'applicant_types' => 'nullable|array',
            'applicant_types.*' => 'string|max:50',
            'sort_by' => 'nullable|string|in:name',
            'per_page' => 'nullable|integer|min:5|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'sort_by.in' => 'Invalid sort option. Allowed values: name',
            'per_page.min' => 'Per page must be at least 5',
            'per_page.max' => 'Per page cannot exceed 100'
        ];
    }
}
