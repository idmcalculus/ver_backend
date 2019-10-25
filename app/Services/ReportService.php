<?php

namespace App\Services;

use App\Repositories\CareerApplicationRepository;
use App\Repositories\InvestmentRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserInvestmentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ReportService extends SmsService
{
    protected $reportRepository, $careerApplicationRepository, $investmentRepository, $userRepository, $userInvestmentRepository;

    /**
     * UserService constructor.
     * @param ReportRepository $reportRepository
     * @param CareerApplicationRepository $careerApplicationRepository
     * @param InvestmentRepository $investmentRepository
     * @param UserInvestmentRepository $userInvestmentRepository
     * @param UserRepository $userRepository
     */
    public function __construct(ReportRepository $reportRepository,
                                CareerApplicationRepository $careerApplicationRepository,
                                InvestmentRepository $investmentRepository,
                                UserInvestmentRepository $userInvestmentRepository,
                                UserRepository $userRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->careerApplicationRepository = $careerApplicationRepository;
        $this->investmentRepository = $investmentRepository;
        $this->userRepository = $userRepository;
        $this->userInvestmentRepository = $userInvestmentRepository;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($request)
    {
        $report = $this->reportRepository->orderBy('id', 'desc')
                                        ->where('investment_id', $request['investment_id'])
                                        ->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Report list was successfully fetched';
        $success['Data'] = $report;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($request)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to create report.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {

            $requestData = [
                'title' => $request['title'],
                'description' => $request['description'],
                'investment_id' => $request['investment_id'],
                'user_id' => $user['email'],
                'returned_amount' => $request['returned_amount'],
                'payment_type' => $request['payment_type'],
            ];

            $this->reportRepository->create($requestData);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Report was successfully created';

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($request)
    {
        $currentUser = Auth::user();
        if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
        {
            $data = $this->reportRepository->where('id', $request['report_id'])->get();

            if(count($data) > 0)
            {
                $requestData = [
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'investment_id' => $request['investment_id'],
                    'user_id' => $currentUser['email'],
                    'returned_amount' => $request['returned_amount'],
                    'payment_type' => $request['payment_type'],
                ];

                $this->reportRepository->updateById($request['report_id'], $requestData);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Report was successfully updated';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Report does not exist'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to update Report.'
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
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to delete report.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $data = $this->reportRepository->where('id', $request['report_id'])->get();

            if(count($data) > 0)
            {
                $this->reportRepository->deleteById($request['report_id']);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Report was successfully deleted';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Application does not exist'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminDashboard()
    {
        // Total users
        $all_users = $this->reportRepository->fetch_all_users_count();

        // Total unverified users
        $unverified_users = $this->reportRepository->fetch_unverified_users_count();

        // Total Investments
        $all_investment = $this->reportRepository->fetch_all_investment_count();

        // Total pending investments
        $pending_investment = $this->reportRepository->fetch_pending_investment_count();

        // Total requests
        $all_request = $this->reportRepository->fetch_all_request_count();

        // Total pending requests
        $pending_request = $this->reportRepository->fetch_pending_request_count();

        // List of requests
        $request_application  = $this->reportRepository->fetch_request_application();

        // List of career applications
        $career_application  = $this->reportRepository->fetch_career_applications();

        $data = [
            'all_users' => $all_users->all_users,
            'unverified_users' => $unverified_users->unverified_users,
            'all_investment' => $all_investment->all_investment,
            'pending_investment' => $pending_investment->pending_investment,
            'all_request' => $all_request->all_request,
            'pending_request' => $pending_request->pending_request,
            'career_application' => $career_application,
            'request_application' => $request_application
        ];

        $success['StatusCode'] = 200;
        $success['Message'] = 'Data was successfully fetched';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function userDashboard($request)
    {
        $investment = $this->investmentRepository->where('id', $request['investment_id'])->get();

        if(count($investment) > 0)
        {
            $investment_user = $this->userInvestmentRepository->get_investment_of_user($request['user_id']);
            if(count($investment_user) > 0)
            {
                if(($investment[0]['num_of_pools_taken'] != $investment[0]['max_num_of_slots']) || $investment[0]['is_investment_started'] != 1)
                {
                    $error = [
                        'StatusCode' => 401,
                        'Message' => 'Report can not be generated because investment is yet to start.'
                    ];

                    return response()->json(['error' => $error], 401);
                }
                else
                {
                    // Total invested amount
                    $invested_amount = $this->reportRepository->fetch_sum_of_invested_amount($request['investment_id'],
                        $request['user_id']);

                    // Number of user's pools
                    $pools = $this->reportRepository->fetch_sum_of_pools($request['investment_id'], $request['user_id']);

                    $report = $this->reportRepository->where('investment_id', $request['investment_id'])
                        ->get();

                    $data['number_of_pools'] = $pools;
                    $data['investment_return'] = $this->investmentBreakdown($report, $invested_amount, $pools,
                        $investment[0]['num_of_pools_taken']);
                    $data['investment_report'] = $report;

                    $success['StatusCode'] = 200;
                    $success['Message'] = 'Data was successfully fetched';
                    $success['Data'] = $data;

                    return response()->json(['success' => $success], 200);
                }
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'User has no slot in this investment.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Investment does not exist.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    public function investmentBreakdown($report, $invested_amount, $user_pools, $num_of_pools_taken)
    {
        $sa = [];
        $newInvestment_amount = $invested_amount;
        for($i = 0; $i < count($report); $i++)
        {
            $payment_type = $report[$i]['payment_type'];
            $returned_amount = $report[$i]['returned_amount'];
            $shared_amount = ($user_pools / $num_of_pools_taken) * $returned_amount;
            if($payment_type == "Credit")
                $newInvestment_amount = $newInvestment_amount + $shared_amount;
            else if($payment_type == "Debit")
                $newInvestment_amount = $newInvestment_amount - $shared_amount;

            $data = [
                'investment_amount' => $invested_amount,
                'yielded_investment_amount' => $newInvestment_amount,
                'yielded_date' => $report[$i]['created_at'],
                'returned_amount' => $report[$i]['returned_amount'],
                'yielded_type' => $payment_type,
                'yielded_amount' => $shared_amount,
            ];

            array_push($sa, $data);
        }
        return $sa;
    }
}
