<?php

namespace App\Services;

use App\Repositories\AuthenticationRepository;
use App\Repositories\InvestmentRepository;
use App\Repositories\OtpRepository;
use App\Repositories\UserInvestmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\ReportRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class UserService extends SmsService
{
    protected $authenticationRepository,$reportRepository, $userRepository, $userInvestmentRepository, $investmentRepository, $otpService,
                $otpRepository;

    /**
     * UserService constructor.
     * @param ReportRepository $reportRepository
     * @param UserRepository $userRepository
     * @param UserInvestmentRepository $userInvestmentRepository
     * @param InvestmentRepository $investmentRepository
     * @param OtpService $otpService
     * @param OtpRepository $otpRepository
     */
    public function __construct(UserRepository $userRepository,
                                UserInvestmentRepository $userInvestmentRepository,
                                InvestmentRepository $investmentRepository,
                                OtpService $otpService,
                                ReportRepository $reportRepository,
                                OtpRepository $otpRepository)
    {
        $this->userRepository = $userRepository;
        $this->userInvestmentRepository = $userInvestmentRepository;
        $this->investmentRepository = $investmentRepository;
        $this->otpService = $otpService;
        $this->otpRepository = $otpRepository;
        $this->reportRepository = $reportRepository;
    }

    public function list()
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to view user list.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $userList = $this->userRepository->get_all_users();
            $success = [
                'StatusCode' => 200,
                'Message' => 'User successfully created',
                'Data' => $userList,
            ];

            return response()->json(['success'=>$success], 200);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($data)
    {
        $emailData = $this->userRepository->where('email', $data['email'])->get();

        if(count($emailData) > 0)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'User already exist'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
		'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'user_category' => $data['user_category'],
                'authentication_type' => $data['authentication_type'],
            ];

            if($data['authentication_type'] != "E")
            {
                $userData['password'] = bcrypt(getenv('DEFAULT_USER_PASSWORD'));

                $userData['email_is_verified'] = 1;
                $userData['email_verified_at'] = new Carbon();
            }
            else
            {
                $userData['password'] = bcrypt($data['password']);
            }

            DB::transaction(function () use ($userData, $data, $emailData) {

                // Create User
                $user = $this->userRepository->create($userData);

                if($data['authentication_type'] == "E")
                {
                    // Send Welcome SMS
//                $this->sendSms($data['phone_number'], 'Dear ' . $data['first_name'] . ', we are pleased you showed interest in IMS. Kindly check your mail to verify your account');

                    // Send Welcome Email
                    $encodedMail = base64_encode($data['email']);
                    $mailData = [
                        'name' => $data['first_name'],
                        'email' => $data['email'],
                        'encodedEmail' => $encodedMail,
                        'subject' => 'Welcome to IMS! Confirm Your Email',
                        'mailTo' => $data['email'],
                        'view' => 'welcome',
                        'webpage' => getenv('WEBPAGE'),
                    ];

                    $this->sendMail($mailData);
                }

                // Create Token
                $user->createToken($data['email'])->accessToken;

            });

            $success = [
                'StatusCode' => 200,
                'Message' => 'User successfully created',
            ];

            return response()->json(['success'=>$success], 200);
        }
    }

    /**
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($email)
    {
        $userData = $this->userRepository->where('email', $email)->get();
        $this->sendSms($userData[0]['phone_number'], 'Congratulations!!! ' . $userData[0]['first_name'] . ', your account has been verified successfully.');

        if(count($userData) > 0)
        {
            $id = $userData[0]['id'];
            $data = [
                'email_is_verified' => 1,
                'email_verified_at' => new Carbon()
            ];

            $result = DB::transaction(function () use ($id, $data, $userData) {
                $result = $this->userRepository->updateById($id, $data);

                // Send Welcome SMS
//                $this->sendSms($userData[0]['phone_number'], 'Congratulations!!! ' . $userData[0]['first_name'] . ', your account has been verified successfully.');

                // Send Welcome Email
                $mailData = [
                    'name' => $userData[0]['first_name'],
                    'email' => $userData[0]['email'],
                    'subject' => 'Congratulations!!! Account Verified',
                    'mailTo' => $userData[0]['email'],
                    'view' => 'accountverify',
                    'webpage' => getenv('WEBPAGE'),
                ];

                $this->sendMail($mailData);

                $success = [
                    'StatusCode' => 200,
                    'Message' => 'User account successfully verified',
                    'Data' => $result
                ];

                return $success;

            });

            return response()->json(['success' => $result], 200);
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'User does not exist.'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser($data)
    {
        $authData = Auth::user();

        if($authData['email_is_verified'] == 0)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Account is not verified'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $success = [];

            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'user_category' => $data['user_category'],
                'authentication_type' => $data['authentication_type'],
                'phone_number' => $data['phone_number'],
                'gender' => $data['gender'],
                'home_address' => $data['home_address'],
                'country' => $data['country'],
                'profile_picture' => $data['profile_picture'],
                'month_of_birth' => $data['month_of_birth'],
                'year_of_birth' => $data['year_of_birth'],
                'day_of_birth' => $data['day_of_birth'],
                'where_you_work' => $data['where_you_work'],
                'average_monthly_income' => $data['average_monthly_income'],
            ];

            $this->userRepository->updateById($authData['id'], $userData);

            $success['StatusCode'] = 200;
            $success['Message'] = 'Data successfully updated';

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdateUser($data,Request $request)
    {
        $authData = Auth::user();
        if(($authData['user_category'] != "Admin") && ($authData['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to update user data.'
            ];

            return response()->json(['error' => $error], 401);

        }else{
            
            $success = [];

            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'user_category' => $data['user_category'],
                'authentication_type' => $data['authentication_type'],
                'phone_number' => $data['phone_number'],
                'gender' => $data['gender'],
                'home_address' => $data['home_address'],
                'country' => $data['country'],
                'profile_picture' => $data['profile_picture'],
                'month_of_birth' => $data['month_of_birth'],
                'year_of_birth' => $data['year_of_birth'],
                'day_of_birth' => $data['day_of_birth'],
                'where_you_work' => $data['where_you_work'],
                'average_monthly_income' => $data['average_monthly_income'],
            ];

            $this->userRepository->updateById($data['id'], $userData);
            $timestamp = now();
            $ip = \Request::ip();
            $this->userRepository->track_user_activity($authData['email'],$authData['name'].' updated '.$data['first_name'].'`s profile'.$request,$timestamp,$ip);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Data successfully updated';
            $success['Data'] = $response;

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordViewStats($request)
    {
        $ip = \Request::ip();
        $this->userRepository->investment_views($request['investment_id'],$ip);
        $success['StatusCode'] = 200;
        return response()->json(['success' => $success], 200);
    }

     /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getViewStats($request)
    {
        $ip = \Request::ip();
        $response = $this->userRepository->get_investment_views();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Data successfully recorded';
        $success['Data'] = $response;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword($request)
    {
        $authData = Auth::user();

        if($authData['email_is_verified'] == 0)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Account is not verified'
            ];

            return response()->json(['error' => $error], 401);
        }
        else if($authData['authentication_type'] != "E")
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You cannot change your password because you did not register with email'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $passData = [
                'password' => bcrypt($request['password'])
            ];

            $updatepassword = $this->userRepository->updateById($authData['id'], $passData);

            $token = $updatepassword->createToken($authData['email'])->accessToken;

            $data = $this->userRepository->where('email', $authData['email'])->get();

            // Send Welcome SMS
//            $this->sendSms($data[0]['phone_number'], 'Dear ' . $data[0]['first_name'] . ', your password was successfully reset. Login with your new password.');

            // Send Welcome Email
            $mailData = [
                'name' => $data[0]['first_name'],
                'email' => $data[0]['email'],
                'subject' => 'Congratulations!!! Password Reset Successful',
                'mailTo' => $data[0]['email'],
                'view' => 'reset_password_success',
                'webpage' => getenv('WEBPAGE'),
            ];

            $this->sendMail($mailData);
            $timestamp = now();
            $ip = \Request::ip();
            $this->userRepository->track_user_activity($authData['email'],$authData['first_name'].' successfully changed '.'password',$timestamp,$ip,2);

            $success = [
                'StatusCode' => 200,
                'Message' => 'User password was successfully reset',
                'Token' => $token,
            ];

            return response()->json(['success'=>$success], 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordRequest($request)
    {
        $authData = $this->userRepository
            ->where('email', $request['email'])
            ->get();

        if(count($authData) > 0)
        {
            if($authData[0]['email_is_verified'] == 0)
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Account is not verified'
                ];

                return response()->json(['error' => $error], 401);
            }
            else
            {

                $data = $this->userRepository->where('email', $request['email'])->get();
                $encodedMail = base64_encode($request['email']);

                // Send Welcome Email
                $mailData = [
                    'name' => $data[0]['first_name'],
                    'email' => $data[0]['email'],
                    'encodedEmail' => $encodedMail,
                    'subject' => 'Password Reset Request',
                    'mailTo' => $data[0]['email'],
                    'view' => 'resetpassword',
                    'webpage' => getenv('WEBPAGE'),
                ];

                $this->sendMail($mailData);

                $success = [
                    'StatusCode' => 200,
                    'Message' => 'Password reset request was successfully. Check your mail to reset password.',
                ];

                return response()->json(['success'=>$success], 200);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'User does not exist'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    public function resetPassword($request)
    {
        $email = base64_decode($request['token']);
        $authData = $this->userRepository->where('email', $email)->get();

        if($authData['email_is_verified'] == 0)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Account is not verified'
            ];

            return response()->json(['error' => $error], 401);
        }
        else if($authData['authentication_type'] != "E")
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You cannot change your password because you did not register with email'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $passData = [
                'password' => bcrypt($request['password'])
            ];

            $updatepassword = $this->userRepository->updateById($authData['id'], $passData);

            $token = $updatepassword->createToken($authData['email'])->accessToken;

            $data = $this->userRepository->where('email', $authData['email'])->get();

            // Send Welcome SMS
//            $this->sendSms($data[0]['phone_number'], 'Dear ' . $data[0]['first_name'] . ', your password was successfully reset. Login with your new password.');

            // Send Welcome Email
            $mailData = [
                'name' => $data[0]['first_name'],
                'email' => $data[0]['email'],
                'subject' => 'Congratulations!!! Password Reset Successful',
                'mailTo' => $data[0]['email'],
                'view' => 'reset_password_success',
                'webpage' => getenv('WEBPAGE'),
            ];

            $this->sendMail($mailData);

            $success = [
                'StatusCode' => 200,
                'Message' => 'User password was successfully reset',
                'Token' => $token,
            ];

            return response()->json(['success'=>$success], 200);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($data){
        $userData = $this->userRepository->where('email', $data['email'])->get();

        if(count($userData) < 1)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Email does not exist'
            ];

            return response()->json(['error' => $error], 401);
        }
        else if($userData[0]['email_is_verified'] == 0)
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Account not verified'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            if($userData[0]['authentication_type'] != "E")
            {
                $password = getenv('DEFAULT_USER_PASSWORD');
            }
            else
            {
                $password = $data['password'];
            }

            if(Auth::attempt(['email' => $userData[0]['email'], 'password' => $password])){
                $authentication = Auth::user();
                $authData = Auth::user();

//                if($authentication['authentication_type'] === "E")
//                {
                    $otp = $this->generateOTPNumber();

                    $otpData = [
                        'user_id' => $authentication['email'],
                        'otp' => $otp,
                        'otp_type' => 'Login',
                    ];

                    $this->otpService->create($otpData);

                    $mailData = [
                        'name' => $authentication['first_name'],
                        'email' => $authentication['email'],
                        'user_category' => strtolower($authentication['user_category']),
                        'subject' => 'OTP for Login',
                        'mailTo' => $authentication['email'],
                        'OTP' => $otp,
                        'view' => 'otp_success',
                        'webpage' => getenv('WEBPAGE'),
                    ];

                    $this->sendMail($mailData);
                    $timestamp = now();
                    $ip = \Request::ip();
                    $this->userRepository->track_user_activity($authData['email'],$authentication['first_name'].' logged in',$timestamp,$ip,3);

                    $success['statusCode'] = 200;
                    $success['message'] =  'OTP sent to your email address';
                    $success['token'] =  $authentication->createToken($data['email'])-> accessToken;
//                    $success['data'] =  $authentication;

                    return response()->json(['success' => $success], $success['statusCode']);
//                }
//                else
//                {
//                    $mailData = [
//                        'name' => $authentication['first_name'],
//                        'email' => $authentication['email'],
//                        'user_category' => strtolower($authentication['user_category']),
//                        'subject' => 'OTP for Login',
//                        'mailTo' => $authentication['email'],
//                        'view' => 'login_success',
//                        'webpage' => getenv('WEBPAGE'),
//                    ];
//
//                    $this->sendMail($mailData);
//
//                    $success['statusCode'] = 200;
//                    $success['message'] =  'Login successful';
//                    $success['token'] =  $authentication->createToken($authentication['email'])-> accessToken;
//                    $success['data'] =  $authentication;
//
//                    return response()->json(['success' => $success], $success['statusCode']);
//                }
            }
            else {
                $error['message'] =  'Invalid password';
                $error['statusCode'] = 401;

                return response()->json(['error'=> $error], 401);
            }
        }
    }

    function generateOTPNumber() {
        return mt_rand(100000, 999999);
    }

    public function validateOTP($request)
    {
        $authData = Auth::user();
        $data = $this->otpRepository->where('user_id', $authData['email'])
                            ->where('otp', $request['otp'])
                            ->get();
        if(count($data) > 0)
        {
            $this->otpRepository->where('otp', $request['otp'])->delete();
            $mailData = [
                'name' => $authData['first_name'],
                'email' => $authData['email'],
                'user_category' => strtolower($authData['user_category']),
                'subject' => 'Login Successful',
                'mailTo' => $authData['email'],
                'view' => 'login_success',
                'webpage' => getenv('WEBPAGE'),
            ];

            $this->sendMail($mailData);

            $success['statusCode'] = 200;
            $success['message'] =  'Login successful';
//            $success['token'] =  $authData->createToken($authData['email'])-> accessToken;
            $success['data'] =  $authData;

            return response()->json(['success' => $success], $success['statusCode']);
        }
        else
        {
            $error['message'] =  'Login failed due to invalid OTP';
            $error['statusCode'] = 401;

            return response()->json(['error'=> $error], 401);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreference($request)
    {
        $currentUser = Auth::user();

        $requestData = [
            'updates_on_new_plans' => $request['updates_on_new_plans'],
            'email_updates_on_investment_process' => $request['email_updates_on_investment_process']
        ];

        $pref = $this->userRepository->updateById($currentUser['id'], $requestData);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Preference was successfully updated';
        $success['Data'] = $pref;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdatePreference($request)
    {
        $currentUser = Auth::user();

        $requestData = [
            'updates_on_new_plans' => $request['updates_on_new_plans'],
            'email_updates_on_investment_process' => $request['email_updates_on_investment_process']
        ];

        $pref = $this->userRepository->updateById($request['user_id'], $requestData);
        $user = $this->userRepository->getById($request['user_id']);
        $timestamp = now();
        $ip = \Request::ip();
        $this->reportRepository ->track_user_activity($currentUser['email'],$currentUser['first_name'].' updated '.$user['first_name'].'`s preferences',$timestamp,$ip,2);
        $success['StatusCode'] = 200;
        $success['Message'] = 'Preference was successfully updated';
        $success['Data'] = $pref;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAccountDetail($request)
    {
        $currentUser = Auth::user();

        $requestData = [
            'account_name' => $request['account_name'],
            'account_number' => $request['account_number'],
            'bank_name' => $request['bank_name'],
            'bank_code' => $request['bank_code']
        ];

        $acc = $this->userRepository->updateById($currentUser['id'], $requestData);

        $mailData = [
            'name' => $currentUser['first_name'],
            'email' => $currentUser['email'],
            'subject' => 'Account details update',
            'mailTo' => $currentUser['email'],
            'view' => 'account_update_success',
            'webpage' => getenv('WEBPAGE'),
        ];

        $this->sendMail($mailData);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Account Detail was successfully updated';
        $success['Data'] = $acc;


        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdateAccountDetail($request)
    {
        $ip = \Request::ip();
        $authData = Auth::user();
        if(($authData['user_category'] != "Admin") && ($authData['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to update this user data.'
            ];

            return response()->json(['error' => $error], 401);

        }else{
        $currentUser = Auth::user();

        $requestData = [
            'account_name' => $request['account_name'],
            'account_number' => $request['account_number'],
            'bank_name' => $request['bank_name'],
            'bank_code' => $request['bank_code']
        ];

        $acc = $this->userRepository->updateById($request['user_id'], $requestData);

        $mailData = [
            'name' => $request['first_name'],
            'email' => $request['email'],
            'subject' => 'Account details update',
            'mailTo' => $request['email'],
            'view' => 'account_update_success',
            'webpage' => getenv('WEBPAGE'),
        ];

        $user = $this->userRepository->getById($request['user_id']);
        $response = $this->userRepository->get_user_groups();
        $this->sendMail($mailData);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Account Detail was successfully updated';
        $success['Data'] = $acc;
        $success['datas'] = $response;
        $success['categories'] = $categories;
        $timestamp = now();
        $ip = \Request::ip();
        $this->reportRepository->track_user_activity($authData['email'],$authData['first_name'].' updated '.$acc['first_name'].'`s profile ',$timestamp,$ip,2);
        return response()->json(['success' => $success], 200);
        }
    }

       /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackUserActivity($request)
    {
        $ip = \Request::ip();
        $authData = Auth::user();
        $ip = \Request::ip();
        $timestamp = now();
        $this->reportRepository->track_user_activity($authData['email'],$request['message'],$timestamp,$ip,$request['type']);

        $success['StatusCode'] = 200;
        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchProfile($request)
    {
        $user = $this->userRepository->where('email', $request['email'])->get();

        $d = [];
        $total = 0;
        $slots = 0;
        $number = 0;
        $investment_user = $this->userInvestmentRepository->get_investment_of_user($request['email']);

        for($i = 0; $i < count($investment_user); $i++)
        {
            $investment = $this->investmentRepository->orderBy('id', 'desc')
                                                    ->getById($investment_user[$i]['investment_id']);
            $total += $investment_user[$i]['amount_paid'];
            $slots += $investment_user[$i]['number_of_pools'];
            array_push($d, $investment);
        }

        $data['user'] = $user;
        $data['investment'] = $d;
        $data['user_investment']= $investment_user;
        $data['number_of_investments']= count($investment_user);
        $data['total'] = $total;
        $data['total_slots'] = $slots;
        $success['StatusCode'] = 200;
        $success['Message'] = 'Profile was successfully fetched';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function activateUser($request)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to activate this user.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $userData = $this->userRepository->where('email', $request['email'])->get();

            if(count($userData) > 0)
            {
                $id = $userData[0]['id'];

                $data = [
                    'email_is_verified' => 1,
                    'email_verified_at' => new Carbon()
                ];

                $result = $this->userRepository->updateById($id, $data);

                $success = [
                    'StatusCode' => 200,
                    'Message' => 'User account successfully activated',
                    'Data' => $result
                ];

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'User does not exist.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

    public function deactivateUser($request)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to deactivate this user.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $userData = $this->userRepository->where('email', $request['email'])->get();

            if(count($userData) > 0)
            {
                $id = $userData[0]['id'];

                $data = [
                    'email_is_verified' => 0,
                ];

                $result = $this->userRepository->updateById($id, $data);

                $success = [
                    'StatusCode' => 200,
                    'Message' => 'User account successfully deactivated',
                    'Data' => $result
                ];
                $timestamp = now();
                $ip = \Request::ip();
                $this->reportRepository ->track_user_activity($authData['email'],' deactivated '.${$userData[0]['first_name']}.'`s account',$timestamp,$ip,3);
                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'User does not exist.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function adminUserdelete($request)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to delete this user.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            
            $userData = $this->userRepository->where('email', $request['email'])->get();

            if(count($userData) > 0)
            {
                $id = $userData[0]['id'];
                $result = $this->userRepository->deleteById($id);

                $success['StatusCode'] = 200;
                $success['Message'] = 'User was successfully deleted';
                $timestamp = now();
                $ip = \Request::ip();
                $this->reportRepository ->track_user_activity($authData['email'],' deleted '.$userData[0]['first_name'].'`s account',$timestamp,$ip);
                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'An error occured deleting this user'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

      /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function adminGetUserCategories()
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to view this user.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            
            $response = $this->userRepository->get_user_groups();
            $categories = ['User','Admin','SuperAdmin'];

            if($response)
            {
                $success['Data'] = $response;
                $success['Categories'] = $categories;
                $success['StatusCode'] = 200;
                $success['Message'] = 'Categories succesfully fetched';
                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'An error occured getting the user categories'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }

     /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdateUserCategory($data)
    {
        $authData = Auth::user();
        if(($authData['user_category'] != "Admin") && ($authData['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to update user data.'
            ];

            return response()->json(['error' => $error], 401);

        }else{
            
            $success = [];

            $userData = [
                'user_category' => $data['user_category'],
            ];
            $userda = $this->userRepository->where('id', $data['id'])->get();

            $this->userRepository->updateById($data['id'], $userData);
            $timestamp = now();
            $ip = \Request::ip();
            $this->userRepository->track_user_activity($authData['email'],$authData['first_name'].' updated '.$userda['first_name'].'`s category',$timestamp,$ip,3);
            $success['StatusCode'] = 200;
            $success['Message'] = 'User Category successfully updated';

            return response()->json(['success' => $success], 200);
        }
    }

}
