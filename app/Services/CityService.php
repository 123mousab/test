<?php


namespace App\Services;


use App\Http\Resources\CityResource;
use App\Models\City;

class CityService extends BaseService
{

    static protected $model = City::class;
    static protected $resource = CityResource::class;
}
