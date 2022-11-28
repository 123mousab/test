<?php


namespace App\Services;


use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryService extends BaseService
{

    static protected $model = Country::class;
    static protected $resource = CountryResource::class;
}
