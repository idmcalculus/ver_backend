<?php

namespace App\Http\Controllers;

use App\Services\InvestmentRequestService;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    protected $investmentRequestService;

    public function __construct(InvestmentRequestService $investmentRequestService)
    {
        $this->investmentRequestService = $investmentRequestService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAll()
    {
        return $this->investmentRequestService->listAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByInvestment(Request $request)
    {
        return $this->investmentRequestService->listByInvestment($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return $this->investmentRequestService->create($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request)
    {
        return $this->investmentRequestService->approve($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        return $this->investmentRequestService->delete($request);
    }
}
