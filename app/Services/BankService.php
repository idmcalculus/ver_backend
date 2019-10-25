<?php

namespace App\Services;

use App\Repositories\BankRepository;

class BankService extends SmsService
{
    protected $bankRepository;

    /**
     * UserService constructor.
     * @param BankRepository $bankRepository
     */
    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $career = $this->bankRepository->orderBy('id', 'desc')->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Bank list was successfully fetched';
        $success['Data'] = $career;

        return response()->json(['success' => $success], 200);
    }
}
