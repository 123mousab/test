<?php


namespace App\Services;


use App\Http\Resources\CompanyResource;
use App\Models\Company;

class CompanyService extends BaseService
{

    static protected $model = Company::class;
    static protected $resource = CompanyResource::class;
}
