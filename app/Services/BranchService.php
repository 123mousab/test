<?php


namespace App\Services;


use App\Http\Resources\BranchResource;
use App\Models\Branch;

class BranchService extends BaseService
{

    static protected $model = Branch::class;
    static protected $resource = BranchResource::class;
}
