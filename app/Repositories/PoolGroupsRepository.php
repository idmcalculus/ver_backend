<?php

namespace App\Repositories;

use App\Models\PoolGroups;
use Illuminate\Support\Facades\DB;

class PoolGroupsRepository extends BaseRepository
{
    public function model()
    {
        return PoolGroups::class;
    }

    public function addGroup($group_name)
    {
        return DB::table('pool_groups')->insert([
            ['group_name' => $group_name,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

}
