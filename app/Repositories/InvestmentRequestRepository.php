<?php

namespace App\Repositories;

use App\Models\RequestInvestment;

class InvestmentRequestRepository extends BaseRepository
{
    public function model()
    {
        return RequestInvestment::class;
    }
}
