<?php


namespace App\Services;


use App\Http\Resources\NutriotionFactResource;
use App\Models\NutriotionFact;

class NutriotionFactService extends BaseService
{

    static protected $model = NutriotionFact::class;
    static protected $resource = NutriotionFactResource::class;
}
