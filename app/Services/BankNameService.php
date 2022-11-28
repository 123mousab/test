<?php


namespace App\Services;


use App\Http\Resources\BankNameResource;
use App\Models\BankName;

class BankNameService extends BaseService
{

    static protected $model = BankName::class;
    static protected $resource = BankNameResource::class;
}
