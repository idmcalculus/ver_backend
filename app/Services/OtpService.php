<?php

namespace App\Services;

use App\Repositories\CareerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\OtpRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class OtpService extends SmsService
{
    protected $otpRepository, $userRepository;

    /**
     * UserService constructor.
     * @param OtpRepository $otpRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OtpRepository $otpRepository,
                                UserRepository $userRepository)
    {
        $this->otpRepository = $otpRepository;
        $this->userRepository = $userRepository;
    }

    public function create($request)
    {
        $data = $this->otpRepository->where('otp_type', $request['otp_type'])
                                    ->where('user_id', $request['user_id'])
                                    ->get();
        if(count($data) > 0)
        {
            return $this->otpRepository->updateById($data[0]['id'], $request);
        }
        else
        {
            return $this->otpRepository->create($request);
        }
    }
}
