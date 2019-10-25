<?php

namespace App\Repositories;

use App\Models\Career;

class CareerRepository extends BaseRepository
{
    public function model()
    {
        return Career::class;
    }
}
