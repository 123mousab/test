<?php


namespace App\Services;


use App\Http\Resources\CuisineResource;
use App\Models\Cuisine;

class CuisineService extends BaseService
{

    static protected $model = Cuisine::class;
    static protected $resource = CuisineResource::class;
}
