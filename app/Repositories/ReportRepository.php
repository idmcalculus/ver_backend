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

    public function fetch_total_amount_invested()
    {
        return DB::table('user_investments')
            ->sum('amount_paid');
    }

    public function fetch_users_with_investment()
    {
        return DB::table('user_investments')
            ->select('user_id',DB::raw('count(*) as no_of_pools_invested'))
            ->groupBy('user_id')
            ->get();
    }

    public function fetch_activities()
    {
        return DB::table('user_activities as CA')
            ->rightJoin('users as C', "CA.user_id", "=", "C.email")
            ->select('*','CA.created_at')
 	    ->orderBy('CA.created_at', 'desc')  
            ->get();
    }
  
 	 public function fetch_user_investments()
    {
        return DB::table('user_investments as CA')
	    ->rightJoin('investments as C', "CA.investment_id", "=", "C.id")
            ->select('CA.created_at as date_added','CA.user_id','C.is_investment_started','CA.investment_id','C.category_id','CA.amount_paid','CA.number_of_pools','CA.id')
	    ->orderBy('CA.created_at', 'desc')           
            ->get();
    }

    public function fetch_users_address()
    {
        return DB::table('user_investments as CA')
            ->leftJoin('users as C', "CA.user_id", "=", "C.email")
            ->select('C.home_address', 'C.country',"CA.amount_paid")
            ->get();
    }

    public function fetch_investment_categories_count()
    {
        return DB::table('user_investments as CA')
            ->leftJoin('investments as C', "CA.investment_id", "=", "C.id")
            ->leftJoin('categories as E', "C.category_id", "=", "E.id")
            ->select('category_id',DB::raw('count(*) as no_of_pools_invested'))
            ->groupBy('category_id',)
            ->get();
    }

    public function track_user_activity($user_id,$activity,$time,$ip, $type)
    {
        return DB::table('user_activities')->insert([
            ['user_id' => $user_id, 'activity' => $activity,'ip_address'=>$ip,'created_at'=>now(),'updated_at'=>now(),'type'=>$type],
        ]);
    }

    public function fetch_sum_of_pools($investment_id, $user_id)
    {
        return DB::table('user_investments')
            ->where('investment_id', $investment_id)
            ->where('user_id', $user_id)
            ->sum('number_of_pools');
    }
}
