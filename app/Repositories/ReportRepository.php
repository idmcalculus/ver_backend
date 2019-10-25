<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportRepository extends BaseRepository
{
    public function model()
    {
        return Report::class;
    }

    public function fetch_all_users_count()
    {
        return DB::table('users')
            ->select(DB::raw('count(*) as all_users'))
            ->first();
    }

    public function fetch_unverified_users_count()
    {
        return DB::table('users')
            ->select(DB::raw('count(*) as unverified_users'))
            ->where('email_is_verified', 0)
            ->first();
    }

    public function fetch_all_investment_count()
    {
        return DB::table('investments')
            ->select(DB::raw('count(*) as all_investment'))
            ->first();
    }

    public function fetch_pending_investment_count()
    {
        return DB::table('investments')
            ->select(DB::raw('count(*) as pending_investment'))
            ->where('is_investment_started', 0)
            ->first();
    }

    public function fetch_all_request_count()
    {
        return DB::table('request_investments')
            ->select(DB::raw('count(*) as all_request'))
            ->first();
    }

    public function fetch_pending_request_count()
    {
        return DB::table('request_investments')
            ->select(DB::raw('count(*) as pending_request'))
            ->where('approved', 0)
            ->first();
    }

    public function fetch_career_applications()
    {
        return DB::table('career_applications as CA')
            ->leftJoin('careers as C', "CA.career_id", "=", "C.id")
            ->select('C.id', 'C.career_title', DB::raw('count(CA.career_id) as no_of_applications'))
            ->groupBy('CA.career_id')
            ->orderBy('C.id', 'desc')
            ->get();
    }

    public function fetch_request_application()
    {
        return DB::table('request_investments')
            ->select(DB::raw('*'))
            ->where('approved', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function fetch_sum_of_invested_amount($investment_id, $user_id)
    {
        return DB::table('user_investments')
            ->where('investment_id', $investment_id)
            ->where('user_id', $user_id)
            ->sum('amount_paid');
    }

    public function fetch_sum_of_pools($investment_id, $user_id)
    {
        return DB::table('user_investments')
            ->where('investment_id', $investment_id)
            ->where('user_id', $user_id)
            ->sum('number_of_pools');
    }
}
