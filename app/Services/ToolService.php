<?php


namespace App\Services;


use App\Http\Resources\ToolResource;
use App\Models\Tool;

class ToolService extends BaseService
{

    static protected $model = Tool::class;
    static protected $resource = ToolResource::class;
}
