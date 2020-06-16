<?php

namespace App\Http\Controllers;

use App\Http\Requests\Investment\CreateUserInvestmentRequest;
use App\Http\Requests\Investment\UpdateInvestmentRequest;
use App\Services\UserInvestmentService;
use Illuminate\Http\Request;

class UserInvestmentController extends Controller
{
    protected $userInvestmentService;

    public function __construct(UserInvestmentService $userInvestmentService)
    {
        $this->userInvestmentService = $userInvestmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInvestmentUser(Request $request)
    {
        return $this->userInvestmentService->listInvestmentUser($request);
    }

   /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvestmentPerDay(Request $request)
    {
        return $this->userInvestmentService->getInvestmentPerday($request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInvestmentOfUser(Request $request)
    {
        return $this->userInvestmentService->listInvestmentOfUser(json_decode($request->getContent())->user_id);
    }

    public function pullOutOfInvestment(Request $request)
    {
        return $this->userInvestmentService->pullOutOfInvestment($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateUserInvestmentRequest $createUserInvestmentRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateUserInvestmentRequest $createUserInvestmentRequest)
    {
        return $this->userInvestmentService->create($createUserInvestmentRequest);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request)
    {
        return $this->userInvestmentService->withdraw($request);
    }
    
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function show(Request $request)
//    {
//        return $this->careerService->show($request);
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvestmentRequest $updateInvestmentRequest
     * @return \Illuminate\Http\JsonResponse
     */
//    public function update(UpdateInvestmentRequest $updateInvestmentRequest)
//    {
//        return $this->investmentService->update($updateInvestmentRequest);
//    }
}
