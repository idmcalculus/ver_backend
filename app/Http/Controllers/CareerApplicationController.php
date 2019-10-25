<?php

namespace App\Http\Controllers;

use App\Http\Requests\CareerApplication\CareerApplicationRequest;
use App\Services\CareerApplicationService;
use Illuminate\Http\Request;

class CareerApplicationController extends Controller
{
    protected $careerApplicationService;

    public function __construct(CareerApplicationService $careerApplicationService)
    {
        $this->careerApplicationService = $careerApplicationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->careerApplicationService->list();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_career_applications(Request $request)
    {
        return $this->careerApplicationService->list_career_applications($request['career_id']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CareerApplicationRequest $careerApplicationRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CareerApplicationRequest $careerApplicationRequest)
    {
        return $this->careerApplicationService->create($careerApplicationRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return $this->careerApplicationService->show($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        return $this->careerApplicationService->delete($request);
    }
}
