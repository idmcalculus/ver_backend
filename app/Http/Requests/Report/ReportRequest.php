<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserAuthenticationRequest.
 */
class ReportRequest extends FormRequest
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
            'title'  => 'required|max:191',
            'description'  => 'required',
            'investment_id'  => 'required',
            'report_id'  => 'required|sometimes',
            'returned_amount' => 'sometimes|required|regex:/^\d*(\.\d{1,2})?$/',
            'payment_type' => 'in:Credit,Debit',
        ];
    }
}
