<?php

namespace App\Repositories;

use App\Models\Otp;

class OtpRepository extends BaseRepository
{
    public function model()
    {
        return Otp::class;
    }
}
