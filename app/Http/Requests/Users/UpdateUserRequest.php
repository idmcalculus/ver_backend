<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UpdateUserRequest extends FormRequest
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
            'first_name'  => 'required|max:191',
            'last_name'  => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone_number' => 'string|max:20',
            'home_address' => 'string',
            'country' => 'string|max:191',
            'profile_picture' => 'string',
            'gender' => 'in:Male,Female',
            'user_category' => 'required|in:Super Admin,Admin,User',
            'month_of_birth' => 'string',
            'year_of_birth' => 'integer',
            'day_of_birth' => 'integer',
            'where_you_work' => 'string',
            'average_monthly_income' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
