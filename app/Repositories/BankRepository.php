<?php

namespace App\Repositories;

use App\Models\Bank;

class BankRepository extends BaseRepository
{
    public function model()
    {
        return Bank::class;
    }
}
