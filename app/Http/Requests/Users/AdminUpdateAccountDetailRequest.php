<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class AdminUpdateAccountDetailRequest extends FormRequest
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
            'account_name'  => 'required|string',
            'account_number'  => 'required|string',
            'bank_name'  => 'required|string',
            'user_id'  => 'required',
//            'bank_code'  => 'required|string',
        ];
    }
}
