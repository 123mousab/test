<?php


namespace App\Services;

use App\Http\Resources\GroupResource;
use App\Models\Group;

class GroupService extends BaseService
{

    static protected $model = Group::class;
    static protected $resource = GroupResource::class;
}
