<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadsRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'firstName' => ['required', 'string'],
            'surName' => ['required', 'string'],
            'email' => ['sometimes', 'nullable', 'string', 'email', 'required_without:telephoneNumber'],
            'telephoneNumber' => ['sometimes', 'nullable', 'string', 'required_without:email'],
        ];
    }
}