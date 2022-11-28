<?php


namespace App\Services;

use App\Http\Resources\DriverResource;
use App\Models\Driver;

class DriverService extends BaseService
{

    static protected $model = Driver::class;
    static protected $resource = DriverResource::class;
}
