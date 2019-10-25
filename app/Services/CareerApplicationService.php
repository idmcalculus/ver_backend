<?php

namespace App\Services;

use App\Repositories\CareerApplicationRepository;
use App\Repositories\CareerRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CareerApplicationService extends SmsService
{
    protected $careerApplicationRepository, $careerRepository, $userRepository;

    /**
     * UserService constructor.
     * @param CareerApplicationRepository $careerApplicationRepository
     * @param CareerRepository $careerRepository
     * @param UserRepository $userRepository
     */
    public function __construct(CareerApplicationRepository $careerApplicationRepository,
                                CareerRepository $careerRepository,
                                UserRepository $userRepository)
    {
        $this->careerApplicationRepository = $careerApplicationRepository;
        $this->careerRepository = $careerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to view career applications.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $career = $this->careerApplicationRepository->orderBy('id', 'desc')->get();

            $success['StatusCode'] = 200;
            $success['Message'] = 'Application list was successfully fetched';
            $success['Data'] = $career;

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $career_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_career_applications($career_id)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to view career applications.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $career = $this->careerApplicationRepository->where('career_id', $career_id)
                                                        ->orderBy('id', 'desc')
                                                        ->get();

            $success['StatusCode'] = 200;
            $success['Message'] = 'Application list was successfully fetched';
            $success['Data'] = $career;

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $career_application_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($career_application_id)
    {
        $career = $this->careerApplicationRepository->getById($career_application_id);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Application was successfully fetched';
        $success['Data'] = $career;

        return response()->json(['success' => $success], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($request)
    {
        $data = $this->careerRepository->where('id', $request['career_id'])->get();

        if(count($data) > 0)
        {
            $requestData = [
                'career_id' => $request['career_id'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'career_brief' => $request['career_brief'],
                'curriculum_vitae' => $request['curriculum_vitae']
            ];

            $this->careerApplicationRepository->create($requestData);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Application was successfully created';

            return response()->json(['success' => $success], 200);
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Career opportunity does not exist'
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
                'Message' => 'You are not authorized to delete career application.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $data = $this->careerApplicationRepository->where('id', $request['career_application_id'])->get();

            if(count($data) > 0)
            {
                $this->careerApplicationRepository->deleteById($request['career_application_id']);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Application was successfully deleted';

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
}
