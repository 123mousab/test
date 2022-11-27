<?php

namespace Src\Trader\Domain\Services;

use App\Models\Trader;
use Src\Trader\Contracts\TraderContract;

class TraderService implements TraderContract
{

    public static $model = Trader::class;

    public function index()
    {
        dd('list trader');
    }

    public function store(array $data)
    {
        dd('save trader');
    }

    public function update($id, array $data)
    {
        dd('update trader');
    }
}
