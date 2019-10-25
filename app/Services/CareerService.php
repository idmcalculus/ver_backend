<?php

namespace App\Services;

use App\Repositories\CareerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CareerService extends SmsService
{
    protected $careerRepository, $userRepository;

    /**
     * UserService constructor.
     * @param CareerRepository $careerRepository
     * @param UserRepository $userRepository
     */
    public function __construct(CareerRepository $careerRepository,
                                UserRepository $userRepository)
    {
        $this->careerRepository = $careerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $career = $this->careerRepository->orderBy('id', 'desc')->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Career opportunity list was successfully fetched';
        $success['Data'] = $career;

        return response()->json(['success' => $success], 200);
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
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to create career opportunities.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $requestData = [
                'career_title' => $request['career_title'],
                'career_description' => $request['career_description'],
                'deadline' => $request['deadline'],
                'position_type' => $request['position_type'],
                'number_of_application' => $request['number_of_application'],
                'career_responsibilities' => $request['career_responsibilities'],
            ];

            $this->careerRepository->create($requestData);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Career opportunity was successfully created';

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($request)
    {
        $data = $this->careerRepository->where('id', $request['career_id'])->get();

        if(count($data) > 0)
        {
            $currentUser = Auth::user();
            if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
            {
                $requestData = [
                    'career_title' => $request['career_title'],
                    'career_description' => $request['career_description'],
                    'deadline' => $request['deadline'],
                    'position_type' => $request['position_type'],
                    'number_of_application' => $request['number_of_application'],
                    'career_responsibilities' => $request['career_responsibilities'],
                ];

                $this->careerRepository->updateById($request['career_id'], $requestData);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Career opportunity was successfully updated';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'You are not authorized to update career opportunity.'
                ];

                return response()->json(['error' => $error], 401);
            }
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
        $data = $this->careerRepository->where('id', $request['career_id'])->get();

        if(count($data) > 0)
        {
            $currentUser = Auth::user();
            if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
            {
                $this->careerRepository->deleteById($request['career_id']);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Career opportunity was successfully deleted';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'You are not authorized to delete career opportunity.'
                ];

                return response()->json(['error' => $error], 401);
            }
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
}
