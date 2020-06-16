<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolGroups extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_name',
    ];
}
