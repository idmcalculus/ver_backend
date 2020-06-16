<?php

namespace App\Services;

use App\Repositories\CareerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\InvestmentRepository;
use App\Repositories\ReportRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\UserInvestmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\PoolGroupsRepository;
use App\Repositories\InvestmentGroups;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserInvestmentService extends SmsService
{
    protected $userInvestmentRepository,$PoolGroupsRepository, $investmentRepository, $userRepository, $reportRepository;

    /**
     * UserService constructor.
     * @param UserInvestmentRepository $userInvestmentRepository
     * @param InvestmentRepository $investmentRepository
     * @param UserRepository $userRepository
     * @param ReportRepository $reportRepository
     * @param PoolGroupsRepository $PoolGroupsRepository
     */
    public function __construct(UserInvestmentRepository $userInvestmentRepository,
                                InvestmentRepository $investmentRepository,
                                UserRepository $userRepository,
                                ReportRepository $reportRepository,
                                PoolGroupsRepository $PoolGroupsRepository)
    {
        $this->userInvestmentRepository = $userInvestmentRepository;
        $this->investmentRepository = $investmentRepository;
        $this->userRepository = $userRepository;
        $this->reportRepository = $reportRepository;
        $this->PoolGroupsRepository = $PoolGroupsRepository;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInvestmentUser($request)
    {
        $data = [];
        $investment_user = $this->userInvestmentRepository
                            ->where('investment_id', $request['investment_id'])
                            ->orderBy('id', 'desc')
                            ->get();
        $investment = $this->investmentRepository->getById($request['investment_id']);

        // fetch report
//        $report = $this->reportRepository->where('investment_id', $request['investment_id'])->get();

        $data['investment'] = $investment;
        $data['investment_user'] = $investment_user;
//        $data['report'] = $report;

        $success['StatusCode'] = 200;
        $success['Message'] = 'Record was successfully fetched';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function getInvestmentPerday($request)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to view user list.'
            ];

            return response()->json(['error' => $error], 401);
        }else{
            $data = [];
            $investment_count = $this->userInvestmentRepository->get_investment_per_day();
            if($investment_count){
            $success['StatusCode'] = 200;
            $success['Message'] = 'Data was successfully fetched';
            $success['Data'] = $investment_count;
    
            return response()->json(['success' => $success], 200);
            }else{
 
                    $error = [
                        'StatusCode' => 401,
                        'Message' => 'Error occured fetrching data,try gain later.'
                    ];
    
                    return response()->json(['error' => $error], 401);
            }

        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInvestmentOfUser($userId)
    {
        $data = [];
	$inv = [];
        $investment_user = $this->userInvestmentRepository->get_investment_of_user($userId);

        for($i = 0; $i < count($investment_user); $i++)
        {
            $investment = $this->investmentRepository->orderBy('id', 'desc')->getById($investment_user[$i]['investment_id']);
            array_push($data, $investment);
	    array_push($inv, $investment_user);
        }

        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment was successfully fetched';
        $success['Data'] = $data;
	$success['Inv']=$investment_user;

        return response()->json(['success' => $success], 200);
    }

    public function pullOutOfInvestment($request)
    {
        $user = Auth::user();
        $investment = $this->investmentRepository->getById($request['investment_id']);
        if($investment['is_investment_started'] === 1)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You cannot leave this investment slot because it has started.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $d = $this->userInvestmentRepository
                ->where('user_id', $user['email'])
                ->where('investment_id', $request['investment_id'])->get();

            if($d)
            {
                for ($i = 0; $i < count($d); $i++)
                {
                    /**
                     * CREATE REPORT
                     */

                    $title = "User pulled out";
                    $description = $user['last_name'] . " have pulled out of investment.";
                    $paymentType = "Debit";

                    $requestData = [
                        'title' => $title,
                        'description' => $description,
                        'investment_id' => $request['investment_id'],
                        'user_id' => $user['email'],
                        'returned_amount' => $d[$i]['amount_paid'],
                        'payment_type' => $paymentType,
                    ];

//                    $this->reportRepository->create($requestData);
                }

                /**
                 * PULL OUT
                 */

                $this->investmentRepository
                    ->leave_update_number_of_pools_taken($request['investment_id'], $investment['num_of_pools_taken']);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Request was successful';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Error leaving this slot.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

    /**
     * @param $career_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($career_id)
    {
        $career = $this->careerRepository->getById($career_id);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Career opportunity was successfully fetched';
        $success['Data'] = $career;

        return response()->json(['success' => $success], 200);
    }

   

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($request)
    {
        $user = Auth::user();

        $investment = $this->investmentRepository->getById($request['investment_id']);
        $max_num_of_slots = $investment['max_num_of_slots'];
        $num_of_pools_taken = $investment['num_of_pools_taken'];

        $num_of_pools_left = $max_num_of_slots - $num_of_pools_taken;
        if(($max_num_of_slots < $request['number_of_pools']) || ($num_of_pools_left < $request['number_of_pools']))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Number of slot selected is more than the available slots.'
            ];

            return response()->json(['error' => $error], 401);
        }

        if($user['user_category'] == "Admin"){
            $requestData = [
                'investment_id' => $request['investment_id'],
                'amount_paid' => $request['amount_paid'],
                'payment_reference' => $request['payment_reference'],
                'number_of_pools' => $request['number_of_pools'],
                'user_id' => $request['user_email'],
              ];
        }else{
               $requestData = [
                'investment_id' => $request['investment_id'],
                'amount_paid' => $request['amount_paid'],
                'payment_reference' => $request['payment_reference'],
                'number_of_pools' => $request['number_of_pools'],
                'user_id' => $user['email'],
              ];
        }
    
        $this->userInvestmentRepository->create($requestData);

        // update $num_of_pools_taken
        $this->investmentRepository
                    ->create_update_number_of_pools_taken($request['investment_id'], $request['number_of_pools']);

        $title = "New user bought slot(s)";
        $description = $user['last_name'] . " have successfully bought " . $request['number_of_pools'] . " slots.";
        $paymentType = "Credit";

        $requestData = [
            'title' => $title,
            'description' => $description,
            'investment_id' => $request['investment_id'],
            'user_id' => $user['email'],
            'returned_amount' => $request['amount_paid'],
            'payment_type' => $paymentType,
        ];

//        $this->reportRepository->create($requestData);

        $mailData = [
            'name' => $user['first_name'],
            'email' => $user['email'],
            'user_category' => strtolower($user['user_category']),
            'subject' => 'Payment Confirmation',
            'mailTo' => $user['email'],
            'view' => 'payment_receipt',
            'webpage' => getenv('WEBPAGE'),
        ];

        $this->sendMail($mailData);

        $timestamp = now();
        $ip = \Request::ip();

        $this->reportRepository ->track_user_activity($user['email'],$user['email'].' was added to investment '.$request['investment_id'],$timestamp,$ip,3);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Investment pool was successful';

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw($request)
    {
        $mailData = [
            'name' => 'first_name',
            'email' => $request['email'],
            'subject' => 'Withdrawal Notification',
            'mailTo' => 'ibraheemkabir9@gmail.com',
            'view' => 'withdrawRequest',
            'webpage' => getenv('WEBPAGE'),
        ];

        $this->sendMail($mailData);
        $success['StatusCode'] = 200;
        $success['Message'] = 'withdraw request was successfully sent';

        return response()->json(['success' => $success], 200);
    }


}
