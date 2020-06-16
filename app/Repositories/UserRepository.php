<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

     public function track_user_activity($user_id,$activity,$time,$ip,$type)
    {
        return DB::table('user_activities')->insert([
            ['user_id' => $user_id, 'activity' => $activity,'ip_address'=>$ip,'created_at'=>now(),'updated_at'=>now(),'type'=>$type],
        ]);
    }

    public function investment_views($id,$ip)
    {
        return DB::table('investment_views')->insert([
            ['investment_id' => $id,'ip'=>$ip,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function get_investment_views()
    {
        return DB::table('investment_views as CA')
        ->rightJoin('investments as C', "CA.investment_id", "=", "C.id")
        ->select('*',DB::raw('count(investment_id) as no_of_views'),'ip')
        ->groupBy('investment_id')->get();
    }
    

 	public function get_user_groups()
    {
        return DB::table('users')
        ->select(DB::raw('count(user_category) as no_of_users'),'user_category')
        ->groupBy('user_category')->get();
    }

     public function get_all_users(){
        return DB::table('users as CA')
        ->leftjoin('user_investments as C', "CA.email", "=", "C.user_id")
        ->select('*',DB::raw('count(number_of_pools) as no_of_slots'),DB::raw('sum(amount_paid) as total_amount_invested'),DB::raw('count(C.id) as no_of_investments'))
        ->groupBy('CA.id')
        ->get();
    }

}
