<?php

namespace App\Http\Controllers;

use App\Http\Requests\Investment\CreateInvestmentRequest;
use App\Http\Requests\Investment\UpdateInvestmentRequest;
use App\Services\InvestmentService;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    protected $investmentService;

    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return $this->investmentService->list($request);
    }

    public function listByCategory(Request $request)
    {
        return $this->investmentService->listByCategory($request);
    }

    public function listByCategoryName(Request $request)
    {
        return $this->investmentService->listByCategoryName($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateInvestmentRequest $createInvestmentRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateInvestmentRequest $createInvestmentRequest)
    {
        $body = json_decode($createInvestmentRequest->getContent());
        $createInvestmentRequest['investment_image'] = $body->investment_image;
        return $this->investmentService->create($createInvestmentRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return $this->investmentService->show($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGroup(Request $request)
    {
        return $this->investmentService->addPoolGroup($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addInvestmentToGroup(Request $request)
    {
        return $this->investmentService->addInvestmentToGroup($request);
    }

     /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvestmentGroup(Request $request)
    {
        return $this->investmentService->getInvestmentGroup($request);
    }
    

     /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPoolGroups(Request $request)
    {
        return $this->investmentService->getPoolGroups($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePoolGroup(Request $request)
    {
        return $this->investmentService->deletePoolGroup($request);
    }

    

     /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPoolGroup(Request $request)
    {
        return $this->investmentService->editPoolGroup($request);
    }

     /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFromGroup(Request $request)
    {
        return $this->investmentService->deleteInvestmentFromGroup($request);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvestmentRequest $updateInvestmentRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvestmentRequest $updateInvestmentRequest)
    {
        $body = json_decode($updateInvestmentRequest->getContent());
        $updateInvestmentRequest['investment_image'] = $body->investment_image;
        return $this->investmentService->update($updateInvestmentRequest);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endInvestment(Request $request)
    {
        $body = json_decode($request->getContent());
        $request = $body->investment_id;
        return $this->investmentService->endInvestment($request);
    }

    public function startInvestment(Request $request)
    {
        $body = json_decode($request->getContent());
        $request = $body->investment_id;
        return $this->investmentService->startInvestment($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function investmentPayoutUsers(Request $request)
    {
        $body = json_decode($request->getContent());
        $request = $body->investment_id;
        return $this->investmentService->investmentPayoutUsers($request);
    }
}
