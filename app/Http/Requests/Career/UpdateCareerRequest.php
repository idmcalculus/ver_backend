<?php

namespace App\Http\Requests\Career;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UpdateCareerRequest extends FormRequest
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
            'career_id'  => 'required|integer',
            'career_title'  => 'required|string|max:100',
            'career_description'  => 'required|string',
            'deadline' => 'required|date',
            'number_of_application' => 'required|integer',
            'position_type' => 'required|in:Full Time,Part Time',
            'career_responsibilities' => 'required|json',
        ];
    }
}
