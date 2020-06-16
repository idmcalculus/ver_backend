<?php

namespace App\Repositories;

use App\Models\UserInvestment;
use Illuminate\Support\Facades\DB;

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


    public function get_investment_per_day()
    {
        return DB::table('user_investments as CA')
	->rightJoin('investments as C', "CA.investment_id", "=", "C.id")
	->select('*','CA.id',DB::raw("DATE(CA.created_at) as date"),DB::raw('count(number_of_pools) as no_of_slots'),DB::raw('sum(amount_paid) as total_amount_invested'),DB::raw('sum(number_of_pools) as no_of_slots'),DB::raw('count(CA.id) as no_of_investments'))
        ->groupBy(DB::raw("DATE(created_at)"))
	->get();
    }
}
