<?php

namespace App\Services;

use App\Repositories\InvestmentRepository;
use App\Repositories\InvestmentRequestRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvestmentRequestService extends SmsService
{
    protected $investmentRequestRepository, $userInvestmentService, $investmentRepository;

    /**
     * UserService constructor.
     * @param InvestmentRequestRepository $investmentRequestRepository
     * @param UserInvestmentService $userInvestmentService
     * @param InvestmentRepository $investmentRepository
     */
    public function __construct(InvestmentRequestRepository $investmentRequestRepository,
                                UserInvestmentService $userInvestmentService,
                                InvestmentRepository $investmentRepository)
    {
        $this->investmentRequestRepository = $investmentRequestRepository;
        $this->userInvestmentService = $userInvestmentService;
        $this->investmentRepository = $investmentRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAll()
    {
        $request = $this->investmentRequestRepository->orderBy('id', 'desc')->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Request list was successfully fetched';
        $success['Data'] = $request;

        return response()->json(['success' => $success], 200);
    }

    public function listByInvestment($request)
    {
        $request = $this->investmentRequestRepository->where('investment_id', $request['investment_id'])
                                                    ->orderBy('id', 'desc')
                                                    ->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Request list was successfully fetched';
        $success['Data'] = $request;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($request)
    {
        $user = Auth::user();
        $requestData = [
            'investment_id' => $request['investment_id'],
            'user_id' => $user['email'],
        ];

        $investment = $this->investmentRepository->getById($request['investment_id']);

        if($investment['is_investment_started'] == 0)
        {
            $this->investmentRequestRepository->create($requestData);

            $success['StatusCode'] = 200;
            $success['Message'] = 'Request was successfully created';

            return response()->json(['success' => $success], 200);
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You cannot request to leave this investment slot because it has started.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($request)
    {
        $currentUser = Auth::user();
        if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
        {
            $data = $this->investmentRequestRepository->where('id', $request['request_id'])->get();

            if($data[0]['approved'] == 0)
            {
                $currentDate = Carbon::now()->toDateString();

                $requestData = [
                    'request_id' => $request['request_id'],
                    'approved' => 1,
                    'approved_date' => $currentDate,
                ];

                $d = $this->investmentRequestRepository->updateById($request['request_id'], $requestData);

                return $this->userInvestmentService->pullOutOfInvestment(['investment_id' => $d['investment_id']]);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Request has already being approved'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to approve request.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($request)
    {
        $data = $this->investmentRequestRepository->where('id', $request['request_id'])->get();

        if(count($data) > 0)
        {
            $this->investmentRequestRepository->deleteById($request['request_id']);

            $success['StatusCode'] = 200;
            $success['Message'] = 'Request was successfully deleted';

            return response()->json(['success' => $success], 200);
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Request does not exist'
            ];

            return response()->json(['error' => $error], 401);

        }
    }
}
