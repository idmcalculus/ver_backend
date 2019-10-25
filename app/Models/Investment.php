<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'created_by',
        'max_num_of_slots',
        'num_of_pools_taken',
        'duration',
        'investment_close_date',
        'investment_amount',
        'expected_return_period',
        'expected_return_amount',
        'is_investment_started',
        'investment_started_date',
        'investment_image',
        'estimated_percentage_profit',
        'show_publicly',
        'is_investment_ended',
        'investment_ended_date'
    ];
}
