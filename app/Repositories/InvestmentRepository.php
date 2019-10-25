<?php

namespace App\Repositories;

use App\Models\Investment;
use Carbon\Carbon;

class InvestmentRepository extends BaseRepository
{
    public function model()
    {
        return Investment::class;
    }

    public function create_update_number_of_pools_taken($investment_id, $num_of_pools)
    {
        /*******
         * CHECK IF INVESTMENT SHOULD START
         */

        Investment::where('id', $investment_id)->increment('num_of_pools_taken', $num_of_pools);

        $investment = Investment::where('id', $investment_id)->get();

//        if($investment[0]['max_num_of_slots'] === $investment[0]['num_of_pools_taken'])
//        {
//            $startDate = Carbon::now()->toDateString();
//            $closeDate = Carbon::now()->addMonth($investment[0]['duration'])->toDateString();
//
//            $data = [
//                'is_investment_started' => 1,
//                'investment_close_date' => $closeDate,
//                'investment_started_date' => $startDate
//            ];
//
//            $investment = Investment::where('id', $investment_id)->update($data);
//        }

        return $investment;
    }

    public function leave_update_number_of_pools_taken($investment_id, $num_of_pools)
    {
        return Investment::where('id', $investment_id)->decrement('num_of_pools_taken', $num_of_pools);
    }

    public function profit_estimate($investmentAmount, $investmentDuration, $expectedReturn, $expectedReturnType)
    {
        if($expectedReturnType == "Weekly")
        {
            $investmentDuration = $investmentDuration * 4;
        }

        /**
         * Total money after duration
         */
        $return = $expectedReturn * $investmentDuration;

        /**
         * Total Profit
         */
        $profit = $return - $investmentAmount;

        $percentageProfit = ($profit / $investmentAmount) * 100;

        $sub_percentageProfit = $percentageProfit - 10;
        $add_percentageProfit = $percentageProfit + 10;

        $sub_percentageProfit = ceil($sub_percentageProfit / 10) * 10;
        $add_percentageProfit = ceil($add_percentageProfit / 10) * 10;

        return $sub_percentageProfit . " - " . $add_percentageProfit . "%";
    }
}
