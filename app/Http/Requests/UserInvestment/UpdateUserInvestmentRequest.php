<?php

namespace App\Http\Requests\Investment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UpdateUserInvestmentRequest extends FormRequest
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
            'user_investment_id'  => 'required|integer',
            'investment_id'  => 'required|integer',
            'number_of_pools'  => 'required|integer',
            'amount_paid' => 'required|regex:/^[+]?\d+\.\d+, ?[+]?\d+\.\d+$/',
            'payment_reference' => 'required',
        ];
    }
}
