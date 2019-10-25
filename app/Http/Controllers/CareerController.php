<?php

namespace App\Http\Controllers;

use App\Http\Requests\Career\CareerRequest;
use App\Http\Requests\Career\UpdateCareerRequest;
use App\Services\CareerService;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    protected $careerService;

    public function __construct(CareerService $careerService)
    {
        $this->careerService = $careerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->careerService->list();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CareerRequest $careerRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CareerRequest $careerRequest)
    {
        return $this->careerService->create($careerRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return $this->careerService->show($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCareerRequest $updateCareerRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCareerRequest $updateCareerRequest)
    {
        return $this->careerService->update($updateCareerRequest);
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
        return $this->careerService->delete($request);
    }
}
