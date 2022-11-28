<?php


namespace App\Services;


use App\Http\Resources\DivisionResource;
use App\Models\Division;

class DivisionService extends BaseService
{

    static protected $model = Division::class;
    static protected $resource = DivisionResource::class;
}
