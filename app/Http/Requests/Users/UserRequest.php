<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UserRequest extends FormRequest
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
            'first_name'  => 'sometimes|required|max:191',
            'last_name'  => 'sometimes|required|max:191',
            'email' => 'required|email|max:191',
            'phone_number' => 'string|max:20',
            'password' => 'sometimes|required',
            'home_address' => 'string',
            'country' => 'string|max:191',
            'profile_picture' => 'string',
            'gender' => 'in:Male,Female',
            'user_category' => 'sometimes|required|in:SuperAdmin,Admin,User',
            'authentication_type' => 'sometimes|required|in:E,Y,G,L',
            'month_of_birth' => 'string',
            'year_of_birth' => 'integer',
            'day_of_birth' => 'integer',
            'email_is_verified' => 'boolean',
            'bank_name'  => 'string',
            'account_name'  => 'string',
            'account_number'  => 'string',

            'updates_on_new_plans'  => 'boolean',
            'email_updates_on_investment_process'  => 'boolean'
        ];
    }

//    public function messages()
//    {
//        return [
//            'email.required' => 'Email address is required!',
//            'first_name.required' => 'First name is required!',
//            'last_name.required' => 'Last name is required!',
//            'phone_number.required' => 'Phone number is required!',
//            'gender.required' => 'Gender is required!',
//            'password.required' => 'Password is required!',
//            'user_category.required' => 'User category is required!',
//            'authentication_type.required' => 'Authentication type is required!',
//        ];
//    }
}
