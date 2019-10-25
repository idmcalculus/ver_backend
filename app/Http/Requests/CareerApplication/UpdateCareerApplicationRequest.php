<?php

namespace App\Http\Requests\CareerApplication;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UpdateCareerApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'career_application_id'  => 'required|integer',
            'career_id'  => 'required|integer',
            'first_name'  => 'required|string',
            'last_name'  => 'last_name|string',
            'email' => 'required|email|max:191',
            'phone_number' => 'required|string|max:20',
            'career_brief' => 'required|string',
            'curriculum_vitae' => 'required|string',
        ];
    }
}
