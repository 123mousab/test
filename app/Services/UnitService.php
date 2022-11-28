<?php


namespace App\Services;


use App\Http\Resources\UnitResource;
use App\Models\Unit;

class UnitService extends BaseService
{

    static protected $model = Unit::class;
    static protected $resource = UnitResource::class;
}
