<?php

namespace App\Repositories;

use App\Models\investmentGroups;
use Illuminate\Support\Facades\DB;

class InvestmentGroupsRepository extends BaseRepository
{
    public function model()
    {
        return investmentGroups::class;
    }

    public function addInvestment($group_name,$investment_id)
    {
        return DB::table('investment_groups')->insert([
            ['group_name' => $group_name,'investment_id'=>$investment_id,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function get_investment_group()
    {
        return DB::table('investment_groups as CA')
            ->select('group_name',DB::raw('count(*) as no_of_investments'))
            ->groupBy('group_name')
            ->get();
    }

    public function get_group($id)
    {
        return DB::table('investment_groups as CA')
            ->leftJoin('investments as C', "CA.investment_id", "=", "C.id")
            ->select('*')
            ->where('group_name',$id)
            ->get();
    }
}
