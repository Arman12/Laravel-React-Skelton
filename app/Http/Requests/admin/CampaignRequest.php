<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
            'name' => 'required|string',
            'sms_template_id' => 'required',
            'email_template_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'datafrom' => 'nullable',
            'type' => 'nullable',
            'recursion' => 'nullable',
            'hours' => 'nullable',
            'iterations' => 'nullable',
            'date' => 'nullable',
            'time' => 'nullable',
            'description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'sms_template_id.required' => 'The Sms template field is required.',
            'email_template_id.required' => 'The Email template field is required.',
            'start_time.required' => 'The start time field is required.',
            'end_time.required' => 'The end time field is required.',
        ];
    }
}
