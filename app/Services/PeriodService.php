<?php


namespace App\Services;


use App\Http\Resources\PeriodResource;
use App\Models\Period;

class PeriodService extends BaseService
{

    static protected $model = Period::class;
    static protected $resource = PeriodResource::class;
}
