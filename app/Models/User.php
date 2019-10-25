<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'gender',
        'home_address',
        'profile_picture',
        'country',
        'user_category',
        'account_name',
        'account_number',
        'bank_name',
        'updates_on_new_plans',
        'email_updates_on_investment_process',
        'email_is_verified',
        'email_verified_at',
        'authentication_type',
        'password',
        'month_of_birth',
        'year_of_birth',
        'day_of_birth',
        'where_you_work',
        'average_monthly_income',
        'bank_code',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
