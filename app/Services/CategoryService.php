<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CategoryService extends SmsService
{
    protected $categoryRepository, $userRepository;

    /**
     * UserService constructor.
     * @param CategoryRepository $categoryRepository
     * @param UserRepository $userRepository
     */
    public function __construct(CategoryRepository $categoryRepository,
                                UserRepository $userRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $category = $this->categoryRepository->orderBy('id', 'desc')->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Category list was successfully fetched';
        $success['Data'] = $category;

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
                'Message' => 'You are not authorized to create category.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $requestData = [
                'category_name' => $request['category_name']
            ];

            $this->categoryRepository->create($requestData);
            $success['StatusCode'] = 200;
            $success['Message'] = 'Category was successfully created';

            return response()->json(['success' => $success], 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($request)
    {
        $data = $this->categoryRepository->where('id', $request['category_id'])->get();

        if(count($data) > 0)
        {
            $currentUser = Auth::user();
            if(($currentUser['user_category'] == "Admin") || ($currentUser['user_category'] == "SuperAdmin"))
            {
                $requestData = [
                    'category_name' => $request['category_name']
                ];

                $this->categoryRepository->updateById($request['category_id'], $requestData);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Category was successfully updated';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'You are not authorized to update category.'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
        else
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'Category does not exist'
            ];

            return response()->json(['error' => $error], 401);
        }
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($categoryId)
    {
        $user = Auth::user();
        if(($user['user_category'] != "Admin") && ($user['user_category'] != "SuperAdmin"))
        {
            $error = [
                'StatusCode' => 401,
                'Message' => 'You are not authorized to delete category.'
            ];

            return response()->json(['error' => $error], 401);
        }
        else
        {
            $data = $this->categoryRepository->where('id', $categoryId)->get();

            if(count($data) > 0)
            {
                $this->categoryRepository->deleteById($categoryId);

                $success['StatusCode'] = 200;
                $success['Message'] = 'Category was successfully deleted';

                return response()->json(['success' => $success], 200);
            }
            else
            {
                $error = [
                    'StatusCode' => 401,
                    'Message' => 'Category does not exist'
                ];

                return response()->json(['error' => $error], 401);
            }
        }
    }
}
