<?php

namespace App\Repositories;

use App\Models\CareerApplication;

class CareerApplicationRepository extends BaseRepository
{
    public function model()
    {
        return CareerApplication::class;
    }
}
