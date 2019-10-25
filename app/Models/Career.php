<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career_title',
        'career_description',
        'deadline',
        'position_type',
        'number_of_application',
        'career_responsibilities',
    ];
}
