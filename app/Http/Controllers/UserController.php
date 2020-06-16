<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\LoginUserRequest;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Http\Requests\Users\UpdateAccountDetailRequest;
use App\Http\Requests\Users\AdminUpdateAccountDetailRequest;
use App\Http\Requests\Users\AdminUpdatePreferenceRequest;
use App\Http\Requests\Users\AdminDeleteUserRequest;
use App\Http\Requests\Users\UpdatePreferenceRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\UpdateCategoryRequest;
use App\Http\Requests\Users\TrackUserRequest;
use App\Http\Requests\Users\RecordViewStatsRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

     public function recordViewStats(RecordViewStatsRequest $request)
    {
        return $this->userService->list();
    }

    public function list()
    {
        return $this->userService->list();
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(CreateUserRequest $request)
    {
        return $this->userService->register($request);
    }

    /**
     * @param UpdateAccountDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminGetUserCategories()
    {
        return $this->userService-> adminGetUserCategories();
    }

     /**
     * @param TrackUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackUserActivity(TrackUserRequest $request)
    {
        return $this->userService->trackUserActivity($request);
    }

   

    /**
     * @param UpdateCategoryRequest $request
     * @return mixed
     */
    public function updateUserCategory(UpdateCategoryRequest $request)
    {
        return $this->userService->adminUpdateUserCategory($request);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($data)
    {
        return $this->userService->verifyUser(base64_decode($data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(UpdateUserRequest $request)
    {
        return $this->userService->updateUser($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdateUser(UpdateUserRequest $request)
    {
        return $this->userService->adminUpdateUser($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->userService->changePassword($request);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordRequest(ResetPasswordRequest $request)
    {
        return $this->userService->resetPasswordRequest($request);
    }

    public function resetPassword(Request $request)
    {
        $body = json_decode($request->getContent());
        return $this->userService->resetPassword($body);
    }

    /**
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        return $this->userService->login($request);
    }

    public function validateOTP(Request $request)
    {
        return $this->userService->validateOTP($request);
    }

    /**
     * @param UpdatePreferenceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreference(UpdatePreferenceRequest $request)
    {
        return $this->userService->updatePreference($request);
    }


    /**
     * @param AdminUpdatePreferenceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdatePreference(AdminUpdatePreferenceRequest $request)
    {
        return $this->userService->adminUpdatePreference($request);
    }

    
    /**
     * @param AdminUpdatePreferenceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUserdelete(adminDeleteUserRequest $request)
    {
        return $this->userService->adminUserdelete($request);
    }

    /**
     * @param UpdateAccountDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAccountDetail(UpdateAccountDetailRequest $request)
    {
        return $this->userService->updateAccountDetail($request);
    }

    /**
     * @param AdminUpdateAccountDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdateAccountDetail(AdminUpdateAccountDetailRequest $request)
    {
        return $this->userService->adminUpdateAccountDetail($request);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchProfile(ResetPasswordRequest $request)
    {
        return $this->userService->fetchProfile($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function activateUser(Request $request)
    {
        return $this->userService->activateUser($request);
    }

    public function deactivateUser(Request $request)
    {
        return $this->userService->deactivateUser($request);
    }

    public function deactivateUsers(Request $request)
    {
        return $this->userService->recordViewStats($request);
    }

    public function getViewStats(Request $request)
    {
        return $this->userService->getViewStats($request);
    }
}
