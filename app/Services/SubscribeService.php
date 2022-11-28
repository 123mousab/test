<?php


namespace App\Services;


use App\Http\Controllers\Admin\Actions\Subscribe;
use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Http\Resources\SubscribeResource;
use App\Models\Subscription;

class SubscribeService extends BaseService
{
    static protected $model = Subscription::class;
    static protected $resource = SubscribeResource::class;

    public static function subscribe(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscribe = new Subscribe();
        return $subscribe->handle($subscribeDataTransfer);
    }

    public static function updateSubscribe($id, SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscribe = new Subscribe();
        return $subscribe->handleUpdate($id, $subscribeDataTransfer);
    }
}
