<?php

namespace App\Http\Requests\Investment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class UpdateInvestmentRequest extends FormRequest
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
            'investment_id'  => 'required|integer',
            'category_id'  => 'required|integer',
            'title'  => 'required|max:191',
            'show_publicly'  => 'required|boolean',
            'description'  => 'required',
            'max_num_of_slots' => 'required|integer',
            'num_of_pools_taken' => 'integer',
            'duration' => 'required|integer',
            'investment_amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'expected_return_period' => 'required|in:Weekly,Monthly',
            'expected_return_amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
