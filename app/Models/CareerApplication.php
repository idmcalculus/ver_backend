<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'career_brief',
        'curriculum_vitae'
    ];
}
