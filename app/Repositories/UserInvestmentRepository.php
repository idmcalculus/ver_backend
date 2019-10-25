<?php

namespace App\Repositories;

use App\Models\UserInvestment;

class UserInvestmentRepository extends BaseRepository
{
    public function model()
    {
        return UserInvestment::class;
    }

    public function get_investment_of_user($user_id)
    {
        return UserInvestment::where('user_id', $user_id)->groupBy('investment_id')->get();
    }
}
