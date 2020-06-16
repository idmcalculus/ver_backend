<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentGroups extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_name',
        'investment_id',
    ];
}
