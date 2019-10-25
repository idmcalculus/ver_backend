<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\InvestmentRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserInvestmentRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvestmentService extends SmsService
{
    protected $investmentRepository, $userInvestmentRepository, $userRepository, $reportRepository, $reportService, $categoryRepository;

    /**
     * UserService constructor.
     * @param InvestmentRepository $investmentRepository
     * @param UserInvestmentRepository $userInvestmentRepository
     * @param UserRepository $userRepository
     * @param ReportRepository $reportRepository
     * @param ReportService $reportService
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(InvestmentRepository $investmentRepository,
                                UserInvestmentRepository $userInvestmentRepository,
                                UserRepository $userRepository,
                                ReportRepository $reportRepository,
                                ReportService $reportService,
                                CategoryRepository $categoryRepository)
    {
        $this->investmentRepository = $investmentRepository;
        $this->userInvestmentRepository = $userInvestmentRepository;
        $this->userRepository = $userRepository;
        $this->reportRepository = $reportRepository;
        $this->reportService = $reportService;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($request)
    {
        if($request['is_frontend'] == "true")
        {
            $investment = $this->investmentRepository->where('is_investment_started', '0')
                ->where('is_investment_ended', '0')
                ->where('show_publicly', '1')
                ->orderBy('id', 'desc')
                ->get();
        }
        else
        {
            $investment = $this->investmentRepository->orderBy('id', 'desc')->get();
        }

        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment list was successfully fetched';
        $success['Data'] = $investment;

        return response()->json(['success' => $success], 200);
    }

    public function listByCategory($request)
    {
        if($request['category_id'])
        {
            if($request['is_frontend'] == true)
            {
                $investment = $this->investmentRepository->where('category_id', $request['category_id'])
                    ->where('is_investment_started', '0')
                    ->where('is_investment_ended', '0')
                    ->where('show_publicly', '1')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            else
            {
                $investment = $this->investmentRepository->where('category_id', $request['category_id'])
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }
        else
        {
            if($request['is_frontend'] == true)
            {
                $investment = $this->investmentRepository
                    ->where('is_investment_started', '0')
                    ->where('is_investment_ended', '0')
                    ->where('show_publicly', '1')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            else
            {
                $investment = $this->investmentRepository->orderBy('id', 'desc')->get();
            }
        }



        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment list was successfully fetched';
        $success['Data'] = $investment;

        return response()->json(['success' => $success], 200);
    }

    public function listByCategoryName($request)
    {
        $category = $this->categoryRepository->where('category_name', $request['category_name'])->first();
        $category_id = $category['id'];
        $investment = $this->investmentRepository->where('category_id', $category_id)
            ->where('is_investment_started', '0')
            ->where('is_investment_ended', '0')
            ->where('show_publicly', '1')
            ->orderBy('id', 'desc')
            ->get();
        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment list was successfully fetched';
        $success['Data'] = $investment;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($request)
    {
        $data = [];
        $userData = [];
        $investment = $this->investmentRepository->getById($request['investment_id']);
        $investment_user = $this->userInvestmentRepository->where('investment_id', $request['investment_id'])
                                                            ->orderBy('id', 'desc')
                                                            ->get();

        for($i = 0; $i < count($investment_user); $i++)
        {
            $user = [];
            $userInfo = $this->userRepository->where('email', $investment_user[$i]['user_id'])
                                            ->orderBy('id', 'desc')
                                            ->get();
            $user['user_info'] = $userInfo;
            $user['user_investment_info'] = $investment_user[$i];

            array_push($userData, $user);
        }

        // fetch reports
        $report = $this->reportRepository->where('investment_id', $request['investment_id'])
                                        ->orderBy('id', 'desc')
                                        ->get();

        $data['investment'] = $investment;
        $data['investment_user'] = $userData;
        $data['report'] = $report;

        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment was successfully fetched';
        $success['Data'] = $data;

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
                'Message' => 'You are not authorized to buy investment slot.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $estimated_percentage_profit = $this->investmentRepository->profit_estimate($request['investment_amount'],
                $request['duration'], $request['expected_return_amount'], $request['expected_return_period']);

            $requestData = [
                'title' => $request['title'],
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'created_by' => $user['email'],
                'max_num_of_slots' => $request['max_num_of_slots'],
                'duration' => $request['duration'],
                'investment_image' => $request['investment_image'],
                'investment_amount' => $request['investment_amount'],
                'expected_return_period' => $request['expected_return_period'],
                'expected_return_amount' => $request['expected_return_amount'],
                'estimated_percentage_profit' => $estimated_percentage_profit,
                'show_publicly' => $request['show_publicly'],
            ];

            $this->investmentRepository->create($requestData);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Investment was successfully created';

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
            $data = $this->investmentRepository->where('id', $request['investment_id'])->get();

            if(count($data) > 0)
            {
                $estimated_percentage_profit = $this->investmentRepository->profit_estimate($request['investment_amount'],
                    $request['duration'], $request['expected_return_amount'], $request['expected_return_period']);

                $requestData = [
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'category_id' => $request['category_id'],
                    'max_num_of_slots' => $request['max_num_of_slots'],
                    'duration' => $request['duration'],
                    'investment_image' => $request['investment_image'],
                    'investment_amount' => $request['investment_amount'],
                    'expected_return_period' => $request['expected_return_period'],
                    'expected_return_amount' => $request['expected_return_amount'],
                    'estimated_percentage_profit' => $estimated_percentage_profit,
                    'show_publicly' => $request['show_publicly'],
                ];

                $this->investmentRepository->updateById($request['investment_id'], $requestData);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Investment was successfully updated';

//                $userInvestment = $this->userInvestmentRepository->where('investment_id', $request['investment_id'])->get();
//
//                if(count($userInvestment) > 0)
//                {
//                    for($i = 0; $i < count($userInvestment); $i++)
//                    {
//                        $user = $this->userRepository->where('email', $userInvestment[$i]['email'])->get();
//
//                        $mailData = [
//                            'name' => $user[0]['first_name'],
//                            'email' => $user[0]['email'],
////                            'encodedEmail' => $encodedMail,
//                            'subject' => 'Updates on Investment',
//                            'mailTo' => $user[0]['email'],
//                            'view' => 'resetpassword',
//                            'webpage' => getenv('WEBPAGE'),
//                        ];
//
//                        $this->sendMail($mailData);
//                    }
//                }

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Investment does not exist'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to update investment.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    public function startInvestment($investment_id)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to buy investment slot.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $investment = $this->investmentRepository->where('id', $investment_id)->get();

            if($investment[0]['max_num_of_slots'] === $investment[0]['num_of_pools_taken'])
            {
                $startDate = Carbon::now()->toDateString();
                $closeDate = Carbon::now()->addMonth($investment[0]['duration'])->toDateString();

                $data = [
                    'is_investment_started' => 1,
                    'investment_close_date' => $closeDate,
                    'investment_started_date' => $startDate
                ];

                $this->investmentRepository->updateById($investment_id, $data);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Investment was successfully started';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Investment can not be started because some slots are yet to be bought.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

    /**
     * @param $investment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function endInvestment($investment_id)
    {
        $currentUser = Auth::user();
        if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
        {
            $data = $this->investmentRepository->where('id', $investment_id)->get();

            if(count($data) > 0)
            {
                $data = [
                    'is_investment_ended' => 1,
                    'investment_ended_date' => new Carbon()
                ];

                $this->investmentRepository->updateById($investment_id, $data);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Investment was successfully ended';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Investment does not exist'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to end investment.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    public function investmentPayoutUsers($investment_id)
    {
        $currentUser = Auth::user();
        if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
        {
            $data = $this->investmentRepository->where('id', $investment_id)->first();
            if($data['is_investment_started'] == 0)
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Payment cannot be made because investment has not started.'
                ];

                return response()->json(['error' => $error], 401);
            }
            else if($data['is_investment_ended'] == 0)
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Payment cannot be made because investment has not ended.'
                ];

                return response()->json(['error' => $error], 401);
            }
            else
            {
                $payData = [];
                $investment_user = $this->userInvestmentRepository->where('investment_id', $investment_id)->get()->unique();
                for ($i = 0; $i < count($investment_user); $i++)
                {
                    $user = $this->userRepository->where('email', $investment_user[$i]['user_id'])->first();
                    $d = [
                        'investment_id' => $investment_id,
                        'user_id' => $investment_user[$i]['user_id']
                    ];

                    $report = $this->getYieldedAmount($d);

                    $result['user'] = $user;
                    $result['report'] = $report;

                    array_push($payData, $result);
                }

                $success['StatusCode'] = 200;
                $success['Message'] = 'Investment was successfully ended';
                $success['Data'] = $payData;

                return response()->json(['success' => $success], 200);

            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to perform this action.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    public function getYieldedAmount($request)
    {
        $investment = $this->investmentRepository->where('id', $request['investment_id'])->first();
        // Total invested amount
        $invested_amount = $this->reportRepository->fetch_sum_of_invested_amount($request['investment_id'],
            $request['user_id']);

        // Number of user's pools
        $pools = $this->reportRepository->fetch_sum_of_pools($request['investment_id'], $request['user_id']);

        $report = $this->reportRepository->where('investment_id', $request['investment_id'])
            ->get();

        $data = $this->reportService->investmentBreakdown($report, $invested_amount, $pools,
            $investment['num_of_pools_taken']);

        return end($data);
    }

    public function createTransferRecipient($name, $description, $account_number, $bank_code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transferrecipient?account_number=".$account_number."&bank_code=".$bank_code."&type=nuban&name=".$name."&currency=NGN&description=".$description,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . getenv("PAYSTACK_SECRETE_KEY"),
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
