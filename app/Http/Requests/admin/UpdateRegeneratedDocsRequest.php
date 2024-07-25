<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegeneratedDocsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'dob_day' => 'required',
            'dob_month' => 'required',
            'dob_year' => 'required',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'address_line3' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'postcode' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'firstname.required' => 'The first name field is required.',
            'lastname.required' => 'The last name field is required.',
            'dob_day.required' => 'The dob day field is required.',
            'dob_month.required' => 'The dob month field is required.',
            'dob_year.required' => 'The dob year field is required.',
        ];
    }
}
