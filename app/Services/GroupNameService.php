<?php


namespace App\Services;


use App\Http\Resources\GroupNameResource;
use App\Models\GroupName;

class GroupNameService extends BaseService
{

    static protected $model = GroupName::class;
    static protected $resource = GroupNameResource::class;
}
