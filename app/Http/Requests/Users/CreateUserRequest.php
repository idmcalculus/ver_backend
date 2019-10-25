<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class CreateUserRequest extends FormRequest
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
            'password' => 'required|sometimes',
            'user_category' => 'required|in:Super Admin,Admin,User',
            'authentication_type' => 'required|in:E,Y,G,L',
        ];
    }
}
