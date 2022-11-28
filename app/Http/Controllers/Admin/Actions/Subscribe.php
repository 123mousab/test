<?php


namespace App\Http\Controllers\Admin\Actions;



use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;

class Subscribe
{
    public function handle(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscribe = new CreateSubscribe();
        return $subscribe->process($subscribeDataTransfer);
    }

    public function handleUpdate($id, SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscribe = new UpdateSubscribe($id);
        return $subscribe->process($subscribeDataTransfer);
    }
}
