<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestInvestment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'user_id',
        'approved',
        'approved_date'
    ];
}
