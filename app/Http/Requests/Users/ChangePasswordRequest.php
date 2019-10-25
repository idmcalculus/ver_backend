<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class ChangePasswordRequest extends FormRequest
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
            'password' => 'required'
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
