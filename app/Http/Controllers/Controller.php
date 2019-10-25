<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success(array $items = null, $status = 200)
    {
        $data = ['status' => 'success'];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items) {
            foreach($items as $key => $item) {
                $data[$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * Return error response with specified messages.
     *
     * @param array $items
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($items = null, $status = 422)
    {
        $data = ['status' => 'error', 'messages' => []];

        if ($items) {
            foreach($items as $key => $item) {
                $data['messages'][$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * Format the validation errors to be returned.
     *
     * @param  Validator  $validator
     * @return array
     */
    protected function formatValidationErrors(Validator $validator)
    {
        return BaseFormRequest::formatValidationErrors($validator);
    }
}
