<?php


namespace App\Services;


use App\Http\Resources\KitchenResource;
use App\Models\Kitchen;
use App\Models\KitchenDetail;

class KitchenService extends BaseService
{

    static protected $model = Kitchen::class;
    static protected $resource = KitchenResource::class;

    public function save($data)
    {
        $kitchen = $this->create($data);

        $kitchenDetailsData = collect(request('groups'));

        return $kitchen->kithenDetails()->createMany($kitchenDetailsData);
    }

    public function update($kitchen_id, $data)
    {
        KitchenDetail::query()->where('kitchen_id', $kitchen_id)->delete();

       Kitchen::query()->where('id', $kitchen_id)->update([
          'cooking_date' => $data['cooking_date']
       ]);

        $kitchen = Kitchen::query()->find($kitchen_id);

        $kitchenDetailsData = collect(request('groups'));
        return $kitchen->kithenDetails()->createMany($kitchenDetailsData);
    }
}
